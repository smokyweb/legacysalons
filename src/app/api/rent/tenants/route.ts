import { NextRequest, NextResponse } from 'next/server'
import { getSession } from '../../../../lib/auth'
import { getDb } from '../../../../lib/db'

export async function GET() {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const db = getDb()
  const tenants = db.prepare('SELECT * FROM tenants ORDER BY suite ASC').all()
  return NextResponse.json(tenants)
}

export async function POST(req: NextRequest) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const body = await req.json()
  const db = getDb()
  const now = Date.now()
  const { tenant_id, suite, first_name, last_name, tenant_name, weekly_rent, start_date, end_date, status, phone, email, license_exp, license_status, contract_status, notes } = body
  if (!tenant_id || !suite) return NextResponse.json({ error: 'tenant_id and suite required' }, { status: 400 })
  db.prepare(`INSERT OR REPLACE INTO tenants (tenant_id, suite, first_name, last_name, tenant_name, weekly_rent, start_date, end_date, status, phone, email, license_exp, license_status, contract_status, notes, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`)
    .run(tenant_id, suite, first_name||null, last_name||null, tenant_name||null, weekly_rent||0, start_date||null, end_date||null, status||'Vacant', phone||null, email||null, license_exp||null, license_status||null, contract_status||null, notes||null, now, now)
  return NextResponse.json(db.prepare('SELECT * FROM tenants WHERE tenant_id = ?').get(tenant_id), { status: 201 })
}
