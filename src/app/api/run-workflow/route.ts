import { NextResponse } from 'next/server'
import { getSession } from '@/lib/auth'
import { getDb } from '@/lib/db'
import { v4 as uuidv4 } from 'uuid'

function getCurrentWeekBounds() {
  const now = new Date()
  const day = now.getDay()
  const daysSinceTuesday = (day - 2 + 7) % 7
  const start = new Date(now)
  start.setDate(now.getDate() - daysSinceTuesday)
  start.setHours(0, 0, 0, 0)
  const end = new Date(start)
  end.setDate(start.getDate() + 6)
  const fmt = (d: Date) => d.toISOString().slice(0, 10)
  return { week_start: fmt(start), week_end: fmt(end) }
}

export async function POST() {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })

  const runId = uuidv4()
  const { week_start, week_end } = getCurrentWeekBounds()
  const db = getDb()

  db.prepare(`
    INSERT INTO runs (id, created_at, status, payment_count, total_amount, week_start, week_end)
    VALUES (?, ?, 'running', 0, 0, ?, ?)
  `).run(runId, Date.now(), week_start, week_end)

  const webhookUrl = process.env.N8N_WEBHOOK_URL
  const callbackUrl = `${process.env.NEXT_PUBLIC_APP_URL}/api/webhook/results`

  try {
    await fetch(webhookUrl!, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ runId, callbackUrl, week_start, week_end }),
    })
  } catch (e) {
    console.error('Failed to trigger n8n webhook:', e)
    db.prepare(`UPDATE runs SET status = 'error' WHERE id = ?`).run(runId)
    return NextResponse.json({ error: 'Failed to trigger workflow' }, { status: 500 })
  }

  return NextResponse.json({ status: 'triggered', runId })
}
