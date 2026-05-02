import { NextRequest, NextResponse } from 'next/server'
import { getSession } from '../../../../lib/auth'
import { getDb } from '../../../../lib/db'

export async function GET(_req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const db = getDb()
  const contact = db.prepare('SELECT * FROM contacts WHERE id = ?').get(params.id)
  if (!contact) return NextResponse.json({ error: 'Not found' }, { status: 404 })
  const activity = db.prepare('SELECT * FROM contact_activity WHERE contact_id = ? ORDER BY created_at DESC').all(params.id)
  return NextResponse.json({ contact, activity })
}

export async function PATCH(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const body = await req.json()
  const db = getDb()
  const fields = ['first_name','last_name','email','phone','company','stage','notes','assigned_to','deal_value','last_contacted','likely_move_date','budget','speciality','lead_source']
  const updates = fields.filter(f => f in body).map(f => `${f} = ?`).join(', ')
  const values = fields.filter(f => f in body).map(f => body[f])
  if (!updates) return NextResponse.json({ error: 'No fields to update' }, { status: 400 })
  db.prepare(`UPDATE contacts SET ${updates}, updated_at = ? WHERE id = ?`).run(...values, Date.now(), params.id)
  const contact = db.prepare('SELECT * FROM contacts WHERE id = ?').get(params.id)
  return NextResponse.json(contact)
}

export async function DELETE(_req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const db = getDb()
  db.prepare('DELETE FROM contact_activity WHERE contact_id = ?').run(params.id)
  db.prepare('DELETE FROM contacts WHERE id = ?').run(params.id)
  return NextResponse.json({ success: true })
}
