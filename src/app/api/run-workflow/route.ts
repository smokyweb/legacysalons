import { NextResponse } from 'next/server'
import { getSession } from '../../../lib/auth'
import { getDb } from '../../../lib/db'
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
  const callbackUrl = `${process.env.NEXT_PUBLIC_APP_URL || 'https://legacy.bluesapps.com'}/api/webhook/results`

  // Fire-and-forget — don't await n8n's full response (it runs for 30-60s)
  // Use AbortController with short timeout just to send the request
  const controller = new AbortController()
  const timeoutId = setTimeout(() => controller.abort(), 5000) // 5s to initiate connection

  fetch(webhookUrl!, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ runId, callbackUrl, week_start, week_end }),
    signal: controller.signal,
  })
    .then(() => clearTimeout(timeoutId))
    .catch(err => {
      clearTimeout(timeoutId)
      // AbortError is expected — n8n accepted the request but is still processing
      if (err?.name !== 'AbortError') {
        console.error('n8n webhook error:', err?.message)
        db.prepare(`UPDATE runs SET status = 'error' WHERE id = ?`).run(runId)
      }
    })

  // Return immediately — the site polls /api/runs/:id for completion
  return NextResponse.json({ status: 'triggered', runId })
}
