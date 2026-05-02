import { NextResponse } from 'next/server'
import { getSession } from '../../../lib/auth'
import { getDb } from '../../../lib/db'

export async function GET() {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })

  const db = getDb()
  const runs = db.prepare('SELECT * FROM runs ORDER BY created_at DESC').all()
  // Recalculate total_amount from actual payments for accuracy
  const runsWithCorrectTotals = (runs as Array<Record<string, unknown>>).map((run) => {
    const payments = db.prepare('SELECT amount FROM payments WHERE run_id = ?').all(run.id as string) as Array<{amount: number}>
    const total = payments.reduce((s, p) => s + Number(p.amount || 0), 0)
    const count = payments.length
    return { ...run, total_amount: total, payment_count: count }
  })
  return NextResponse.json(runsWithCorrectTotals)
}
