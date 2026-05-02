import { NextResponse } from 'next/server'
import { getSession } from '../../../lib/auth'
import { getDb } from '../../../lib/db'

export async function GET() {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })

  const db = getDb()
  const runs = db.prepare('SELECT * FROM runs ORDER BY created_at DESC').all()
  return NextResponse.json(runs)
}
