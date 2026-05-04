import { NextRequest, NextResponse } from 'next/server'
import { getSession } from '../../../../lib/auth'
import { getDb } from '../../../../lib/db'

export async function GET() {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const db = getDb()
  return NextResponse.json(db.prepare('SELECT * FROM settings ORDER BY key').all())
}

export async function PATCH(req: NextRequest) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const updates = await req.json() as Record<string, string>
  const db = getDb()
  const stmt = db.prepare('UPDATE settings SET value = ? WHERE key = ? AND editable = 1')
  const updateAll = db.transaction(() => {
    for (const [key, value] of Object.entries(updates)) {
      stmt.run(String(value), key)
    }
  })
  updateAll()
  return NextResponse.json(db.prepare('SELECT * FROM settings ORDER BY key').all())
}
