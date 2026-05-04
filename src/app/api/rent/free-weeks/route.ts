import { NextRequest, NextResponse } from 'next/server'
import { getSession } from '../../../../lib/auth'
import { getDb } from '../../../../lib/db'
import { v4 as uuidv4 } from 'uuid'

export async function GET() {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const db = getDb()
  return NextResponse.json(db.prepare('SELECT fw.*, t.tenant_name, t.suite FROM free_weeks fw LEFT JOIN tenants t ON fw.tenant_id = t.tenant_id ORDER BY fw.created_at DESC').all())
}

export async function POST(req: NextRequest) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const body = await req.json()
  const db = getDb()
  const { tenant_id, type, weeks_granted, date_granted, apply_to_week_start, approval_status, approved_by, notes } = body
  if (!tenant_id) return NextResponse.json({ error: 'tenant_id required' }, { status: 400 })
  const id = 'FW-' + uuidv4().slice(0, 8).toUpperCase()
  db.prepare(`INSERT INTO free_weeks (free_week_id, tenant_id, type, weeks_granted, date_granted, apply_to_week_start, weeks_used, approval_status, approved_by, notes)
    VALUES (?, ?, ?, ?, ?, ?, 0, ?, ?, ?)`)
    .run(id, tenant_id, type||null, weeks_granted||1, date_granted||new Date().toISOString().slice(0,10), apply_to_week_start||null, approval_status||'Approved', approved_by||null, notes||null)
  return NextResponse.json(db.prepare('SELECT * FROM free_weeks WHERE free_week_id = ?').get(id), { status: 201 })
}
