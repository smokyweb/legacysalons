import { NextRequest, NextResponse } from 'next/server'
import { getDb } from '../../../../../lib/db'

// Public endpoint for internal use by n8n workflows
// Protected by webhook secret instead of session cookie
export async function GET(req: NextRequest) {
  const secret = req.headers.get('x-webhook-secret') || req.nextUrl.searchParams.get('secret')
  if (secret !== process.env.WEBHOOK_SECRET) {
    return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  }
  const db = getDb()
  const tenants = db.prepare("SELECT tenant_id, suite, tenant_name, weekly_rent, status, location, phone FROM tenants WHERE status = 'Active' ORDER BY location, suite").all()
  return NextResponse.json(tenants)
}
