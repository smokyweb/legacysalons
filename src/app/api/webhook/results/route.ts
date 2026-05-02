import { NextRequest, NextResponse } from 'next/server'
import { getDb } from '@/lib/db'

type Payment = { name: string; amount: string | number; date: string; paymentApp: string }

export async function POST(req: NextRequest) {
  const secret = req.headers.get('x-webhook-secret')
  if (secret !== process.env.WEBHOOK_SECRET) {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  }

  const body = await req.json()
  const { runId, payments }: { runId: string; payments: Payment[] } = body

  if (!runId) return NextResponse.json({ error: 'Missing runId' }, { status: 400 })

  const db = getDb()
  const run = db.prepare('SELECT id FROM runs WHERE id = ?').get(runId)
  if (!run) return NextResponse.json({ error: 'Run not found' }, { status: 404 })

  const insertPayment = db.prepare(
    'INSERT INTO payments (run_id, name, amount, date, payment_app) VALUES (?, ?, ?, ?, ?)'
  )

  const safePayments: Payment[] = Array.isArray(payments) ? payments : []
  const totalAmount = safePayments.reduce((s, p) => s + Number(p.amount || 0), 0)

  const insertAll = db.transaction(() => {
    for (const p of safePayments) {
      insertPayment.run(runId, p.name || null, Number(p.amount) || 0, p.date || null, p.paymentApp || null)
    }
    db.prepare(`
      UPDATE runs SET status = 'completed', payment_count = ?, total_amount = ? WHERE id = ?
    `).run(safePayments.length, totalAmount, runId)
  })

  insertAll()
  return NextResponse.json({ success: true })
}
