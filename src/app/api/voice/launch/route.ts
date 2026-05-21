import { NextRequest, NextResponse } from 'next/server'
import { getSession } from '../../../../lib/auth'

export async function POST(req: NextRequest) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })

  const { tenants, call_type, custom_message } = await req.json()

  // Voice agent webhook URL (configure via env var when ready)
  const voiceWebhookUrl = process.env.VOICE_AGENT_WEBHOOK_URL

  if (!voiceWebhookUrl || voiceWebhookUrl.includes('placeholder')) {
    return NextResponse.json({
      error: 'Voice agent not configured yet. Set VOICE_AGENT_WEBHOOK_URL in environment variables.',
      setup_required: true
    }, { status: 400 })
  }

  // When configured, this will call the voice agent API for each tenant
  // Supported providers: Bland.ai, Vapi.ai, Twilio + AI
  const results = []
  for (const tenant of tenants) {
    try {
      const res = await fetch(voiceWebhookUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          phone: tenant.phone,
          tenant_name: tenant.tenant_name,
          suite: tenant.suite,
          weekly_rent: tenant.weekly_rent,
          call_type,
          custom_message: custom_message || null,
        })
      })
      results.push({ tenant_id: tenant.tenant_id, status: res.ok ? 'launched' : 'failed' })
    } catch {
      results.push({ tenant_id: tenant.tenant_id, status: 'error' })
    }
  }

  const launched = results.filter(r => r.status === 'launched').length
  return NextResponse.json({ success: true, launched, total: tenants.length, results })
}
