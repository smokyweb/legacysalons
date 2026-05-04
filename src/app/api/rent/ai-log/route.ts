import { NextRequest, NextResponse } from 'next/server'
import { getSession } from '../../../../lib/auth'
import { getDb } from '../../../../lib/db'
import { v4 as uuidv4 } from 'uuid'

export async function GET() {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const db = getDb()
  return NextResponse.json(db.prepare('SELECT * FROM ai_automation_log ORDER BY created_at DESC LIMIT 100').all())
}

export async function POST(req: NextRequest) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const body = await req.json()
  const db = getDb()
  const entries = Array.isArray(body) ? body : [body]
  const insert = db.prepare(`INSERT INTO ai_automation_log (import_id, email_date, from_address, subject, extracted_tenant, extracted_amount, extracted_payment_type, extracted_payment_date, confidence, review_status, raw_snippet, ai_notes)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`)
  const insertAll = db.transaction(() => {
    for (const e of entries) {
      insert.run(e.import_id || ('IMP-' + uuidv4().slice(0,8).toUpperCase()), e.email_date||null, e.from_address||null, e.subject||null, e.extracted_tenant||null, e.extracted_amount||null, e.extracted_payment_type||null, e.extracted_payment_date||null, e.confidence||null, e.review_status||'Needs Review', e.raw_snippet||null, e.ai_notes||null)
    }
  })
  insertAll()
  return NextResponse.json({ success: true, inserted: entries.length })
}
