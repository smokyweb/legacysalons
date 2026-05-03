import { NextResponse } from 'next/server'
import { getSession } from '../../../lib/auth'

export async function POST() {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })

  const webhookUrl = process.env.N8N_LEADS_WEBHOOK_URL
  if (!webhookUrl || webhookUrl.includes('placeholder')) {
    return NextResponse.json({ error: 'Gmail import webhook not configured' }, { status: 400 })
  }

  const controller = new AbortController()
  const timeoutId = setTimeout(() => controller.abort(), 5000)

  fetch(webhookUrl, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ trigger: 'manual', timestamp: Date.now() }),
    signal: controller.signal,
  })
    .then(() => clearTimeout(timeoutId))
    .catch(err => {
      clearTimeout(timeoutId)
      if (err?.name !== 'AbortError') console.error('Lead import webhook error:', err?.message)
    })

  return NextResponse.json({ status: 'triggered' })
}
