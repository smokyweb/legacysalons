import { NextRequest, NextResponse } from 'next/server'
import { getSession } from '../../../../../lib/auth'
import { getDb } from '../../../../../lib/db'

export async function PATCH(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const body = await req.json()
  const db = getDb()
  const fields = ['suite','first_name','last_name','tenant_name','weekly_rent','start_date','end_date','status','phone','email','license_exp','license_status','contract_status','notes']
  const updates = fields.filter(f => f in body).map(f => `${f} = ?`).join(', ')
  const values = fields.filter(f => f in body).map(f => body[f])
  if (!updates) return NextResponse.json({ error: 'No fields' }, { status: 400 })
  db.prepare(`UPDATE tenants SET ${updates}, updated_at = ? WHERE tenant_id = ?`).run(...values, Date.now(), params.id)
  return NextResponse.json(db.prepare('SELECT * FROM tenants WHERE tenant_id = ?').get(params.id))
}

export async function DELETE(_req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const db = getDb()
  db.prepare('DELETE FROM rent_payments WHERE tenant_id = ?').run(params.id)
  db.prepare('DELETE FROM free_weeks WHERE tenant_id = ?').run(params.id)
  db.prepare('DELETE FROM rent_schedule WHERE tenant_id = ?').run(params.id)
  db.prepare('DELETE FROM tenants WHERE tenant_id = ?').run(params.id)
  return NextResponse.json({ success: true })
}
