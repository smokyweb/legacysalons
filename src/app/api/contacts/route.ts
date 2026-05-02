import { NextRequest, NextResponse } from 'next/server'
import { getSession } from '../../../lib/auth'
import { getDb } from '../../../lib/db'

export async function GET() {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const db = getDb()
  const contacts = db.prepare('SELECT * FROM contacts ORDER BY updated_at DESC').all()
  return NextResponse.json(contacts)
}

export async function POST(req: NextRequest) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const body = await req.json()
  const { first_name, last_name, email, phone, company, stage, notes, assigned_to, deal_value } = body
  if (!first_name) return NextResponse.json({ error: 'first_name is required' }, { status: 400 })
  const db = getDb()
  const now = Date.now()
  const result = db.prepare(`
    INSERT INTO contacts (created_at, updated_at, first_name, last_name, email, phone, company, stage, notes, assigned_to, deal_value)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  `).run(now, now, first_name, last_name || null, email || null, phone || null, company || null, stage || 'New Lead', notes || null, assigned_to || null, deal_value || 0)
  const contact = db.prepare('SELECT * FROM contacts WHERE id = ?').get(result.lastInsertRowid)
  return NextResponse.json(contact, { status: 201 })
}
