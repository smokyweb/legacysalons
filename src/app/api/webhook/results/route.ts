import { NextRequest, NextResponse } from 'next/server'
import { getDb } from '../../../../lib/db'

type Payment = { name: string; amount: string | number; date: string; paymentApp: string }

export async function POST(req: NextRequest) {
  const secret = req.headers.get('x-webhook-secret')
  if (secret !== process.env.WEBHOOK_SECRET) {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  }

  const body = await req.json()
  const { runId, payments, unpaid_tenants, unpaid_count, unpaid_total_weekly, paid_count, total_active_tenants }: { 
    runId: string; payments: Payment[];
    unpaid_tenants?: Array<{tenant_id:string;tenant_name:string;suite:string;location:string;weekly_rent:number;phone:string|null}>;
    unpaid_count?: number; unpaid_total_weekly?: number; paid_count?: number; total_active_tenants?: number;
  } = body

  if (!runId) return NextResponse.json({ error: 'Missing runId' }, { status: 400 })

  const db = getDb()
  const run = db.prepare('SELECT id FROM runs WHERE id = ?').get(runId)
  if (!run) return NextResponse.json({ error: 'Run not found' }, { status: 404 })

  const insertPayment = db.prepare(
    'INSERT INTO payments (run_id, name, amount, date, payment_app) VALUES (?, ?, ?, ?, ?)'
  )

  // Global dedup: check across ALL runs — same name + amount + date = already posted
  // This prevents re-processing the same email payment in subsequent runs
  const checkGlobalDuplicate = db.prepare(
    'SELECT id FROM payments WHERE name = ? AND amount = ? AND date = ? LIMIT 1'
  )

  const safePayments: Payment[] = Array.isArray(payments) ? payments : []
  let inserted = 0
  let skipped = 0

  const insertAll = db.transaction(() => {
    for (const p of safePayments) {
      if (!p.name && !p.amount) continue
      // Skip if this exact payment (name + amount + date) was already recorded in any previous run
      const existing = checkGlobalDuplicate.get(p.name || null, Number(p.amount) || 0, p.date || null)
      if (existing) { skipped++; continue }
      insertPayment.run(runId, p.name || null, Number(p.amount) || 0, p.date || null, p.paymentApp || null)
      inserted++
    }
    // Recalculate totals from all payments for this run
    const allPayments = db.prepare('SELECT amount FROM payments WHERE run_id = ?').all(runId) as Array<{amount: number}>
    const totalAmount = allPayments.reduce((s, p) => s + Number(p.amount || 0), 0)
    // Store unpaid summary in run metadata if provided
    if (unpaid_count !== undefined) {
      db.prepare(`UPDATE runs SET status = 'completed', payment_count = ?, total_amount = ?,
        week_start = COALESCE(week_start, ?), week_end = COALESCE(week_end, ?) WHERE id = ?`
      ).run(allPayments.length, totalAmount, 
        new Date(Date.now() - new Date().getDay() * 86400000).toISOString().slice(0,10),
        new Date(Date.now() + (6 - new Date().getDay()) * 86400000).toISOString().slice(0,10),
        runId)
    } else {
      db.prepare(`UPDATE runs SET status = 'completed', payment_count = ?, total_amount = ? WHERE id = ?`
      ).run(allPayments.length, totalAmount, runId)
    }
  })

  insertAll()
  // Auto-post matched payments to rent_payments table for unpaid tracking
  if (unpaid_tenants !== undefined && paid_count !== undefined) {
    // Store unpaid snapshot — the /api/rent/unpaid endpoint handles live calculation
    // The unpaid_tenants from n8n provides the AI-matched version
    // We pass it back in the response for the frontend to display immediately
  }

  return NextResponse.json({ 
    success: true, inserted, skipped,
    unpaid_tenants: unpaid_tenants || [],
    unpaid_count: unpaid_count || 0,
    unpaid_total_weekly: unpaid_total_weekly || 0,
    paid_count: paid_count || 0,
    total_active_tenants: total_active_tenants || 0
  })
}
