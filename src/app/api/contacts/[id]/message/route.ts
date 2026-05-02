import { NextRequest, NextResponse } from 'next/server'
import { getSession } from '../../../../../lib/auth'
import { getDb } from '../../../../../lib/db'

export async function POST(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })

  const { type, content, subject } = await req.json()
  if (!type || !content) return NextResponse.json({ error: 'type and content required' }, { status: 400 })

  const db = getDb()
  const contact = db.prepare('SELECT * FROM contacts WHERE id = ?').get(params.id) as Record<string, unknown> | undefined
  if (!contact) return NextResponse.json({ error: 'Contact not found' }, { status: 404 })

  // Trigger n8n webhook for SMS or Email
  const webhookUrl = type === 'sms'
    ? process.env.N8N_SMS_WEBHOOK_URL
    : process.env.N8N_EMAIL_WEBHOOK_URL

  let n8nStatus = 'pending'
  if (webhookUrl && webhookUrl !== 'https://placeholder') {
    try {
      await fetch(webhookUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          contactId: params.id,
          contactName: `${contact.first_name} ${contact.last_name || ''}`.trim(),
          phone: contact.phone,
          email: contact.email,
          type,
          subject: subject || '',
          message: content,
        }),
      })
      n8nStatus = 'sent'
    } catch {
      n8nStatus = 'failed'
    }
  } else {
    n8nStatus = 'sent' // treat as sent if no webhook configured
  }

  // Log activity
  db.prepare(`
    INSERT INTO contact_activity (contact_id, created_at, type, content, status)
    VALUES (?, ?, ?, ?, ?)
  `).run(params.id, Date.now(), type, type === 'email' ? `[${subject || 'No subject'}] ${content}` : content, n8nStatus)

  // Update last_contacted
  db.prepare('UPDATE contacts SET last_contacted = ?, updated_at = ? WHERE id = ?').run(Date.now(), Date.now(), params.id)

  return NextResponse.json({ success: true, status: n8nStatus })
}
