import { NextRequest, NextResponse } from 'next/server'
import { getSession } from '../../../../lib/auth'
import { getDb } from '../../../../lib/db'
import { v4 as uuidv4 } from 'uuid'

export async function GET(req: NextRequest) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const db = getDb()
  const tenantId = new URL(req.url).searchParams.get('tenant_id')
  const query = tenantId
    ? 'SELECT p.*, t.tenant_name, t.suite FROM rent_payments p LEFT JOIN tenants t ON p.tenant_id = t.tenant_id WHERE p.tenant_id = ? ORDER BY p.payment_date DESC'
    : 'SELECT p.*, t.tenant_name, t.suite FROM rent_payments p LEFT JOIN tenants t ON p.tenant_id = t.tenant_id ORDER BY p.payment_date DESC'
  const rows = tenantId ? db.prepare(query).all(tenantId) : db.prepare(query).all()
  return NextResponse.json(rows)
}

export async function POST(req: NextRequest) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const body = await req.json()
  const { tenant_id, payment_date, amount, payment_type, reference, rent_week_start, posted_by, confidence, notes } = body
  if (!tenant_id || !payment_date || !amount) return NextResponse.json({ error: 'tenant_id, payment_date, amount required' }, { status: 400 })
  const db = getDb()
  const settings = Object.fromEntries((db.prepare('SELECT key, value FROM settings').all() as Array<{key:string,value:string}>).map(s => [s.key, s.value]))
  const cardFeePct = payment_type === 'Card' ? Number(settings.card_fee_pct || 5) / 100 : 0
  const fee = Number(amount) * cardFeePct
  const netAmount = Number(amount) - fee
  const paymentId = 'PAY-' + uuidv4().slice(0, 8).toUpperCase()
  db.prepare(`INSERT INTO rent_payments (payment_id, tenant_id, payment_date, amount, payment_type, fee, net_amount, reference, rent_week_start, posted_by, confidence, notes)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`)
    .run(paymentId, tenant_id, payment_date, Number(amount), payment_type||'Cash App', fee, netAmount, reference||null, rent_week_start||null, posted_by||'Admin', confidence||'High', notes||null)
  return NextResponse.json(db.prepare('SELECT * FROM rent_payments WHERE payment_id = ?').get(paymentId), { status: 201 })
}
