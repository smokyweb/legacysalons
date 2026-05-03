import { NextRequest, NextResponse } from 'next/server'
import { getDb } from '../../../../lib/db'

// Called by n8n to import leads from Gmail
export async function POST(req: NextRequest) {
  const secret = req.headers.get('x-webhook-secret')
  if (secret !== process.env.WEBHOOK_SECRET) {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  }

  const body = await req.json()
  const leads: Array<{
    first_name: string; last_name?: string; email?: string; phone?: string;
    company?: string; notes?: string; speciality?: string; budget?: string;
    likely_move_date?: string; lead_source?: string; lead_date?: string;
  }> = Array.isArray(body) ? body : body.leads || []

  if (!leads.length) return NextResponse.json({ imported: 0 })

  const db = getDb()
  const insert = db.prepare(`
    INSERT INTO contacts (created_at, updated_at, first_name, last_name, email, phone, company, stage, notes, speciality, budget, likely_move_date, lead_source, lead_date)
    VALUES (?, ?, ?, ?, ?, ?, ?, 'New Lead', ?, ?, ?, ?, ?, ?)
  `)

  let imported = 0
  let skipped = 0
  const insertAll = db.transaction(() => {
    for (const lead of leads) {
      if (!lead.first_name) { skipped++; continue }
      // Skip if email already exists
      if (lead.email) {
        const existing = db.prepare('SELECT id FROM contacts WHERE email = ?').get(lead.email)
        if (existing) { skipped++; continue }
      }
      const now = Date.now()
      insert.run(now, now, lead.first_name, lead.last_name || null, lead.email || null, lead.phone || null, lead.company || null, lead.notes || null, lead.speciality || null, lead.budget || null, lead.likely_move_date || null, lead.lead_source || 'Gmail', lead.lead_date || null)
      imported++
    }
  })
  insertAll()

  return NextResponse.json({ success: true, imported, skipped })
}
