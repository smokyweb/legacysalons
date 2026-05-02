import { NextRequest, NextResponse } from 'next/server'
import { getSession } from '@/lib/auth'
import { getDb } from '@/lib/db'

export async function GET(_req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })

  const db = getDb()
  const run = db.prepare('SELECT * FROM runs WHERE id = ?').get(params.id)
  if (!run) return NextResponse.json({ error: 'Not found' }, { status: 404 })

  const payments = db.prepare('SELECT * FROM payments WHERE run_id = ? ORDER BY id ASC').all(params.id)
  return NextResponse.json({ run, payments })
}
