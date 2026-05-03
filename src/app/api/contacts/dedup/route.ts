import { NextResponse } from 'next/server'
import { getSession } from '../../../../lib/auth'
import { getDb } from '../../../../lib/db'

export async function POST() {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })

  const db = getDb()
  
  // Find duplicates by email (keep the oldest/first entry)
  const allContacts = db.prepare('SELECT * FROM contacts ORDER BY created_at ASC').all() as Array<Record<string, unknown>>
  
  const seenEmails = new Map<string, number>()
  const seenNames = new Map<string, number>()
  const toDelete: number[] = []

  for (const c of allContacts) {
    const email = ((c.email as string) || '').toLowerCase().trim()
    const name = `${c.first_name || ''} ${c.last_name || ''}`.toLowerCase().trim()
    
    if (email && seenEmails.has(email)) {
      toDelete.push(c.id as number)
      continue
    }
    if (email) seenEmails.set(email, c.id as number)

    // Also dedupe by full name if no email
    if (!email && name && name !== ' ' && seenNames.has(name)) {
      toDelete.push(c.id as number)
      continue
    }
    if (!email && name && name !== ' ') seenNames.set(name, c.id as number)
  }

  // Delete duplicates
  const deleteStmt = db.prepare('DELETE FROM contacts WHERE id = ?')
  const deleteActivity = db.prepare('DELETE FROM contact_activity WHERE contact_id = ?')
  
  const deleteAll = db.transaction(() => {
    for (const id of toDelete) {
      deleteActivity.run(id)
      deleteStmt.run(id)
    }
  })
  deleteAll()

  return NextResponse.json({ 
    success: true, 
    removed: toDelete.length,
    remaining: allContacts.length - toDelete.length
  })
}
