import { NextRequest, NextResponse } from 'next/server'
import { getSession } from '../../../../lib/auth'
import { getDb } from '../../../../lib/db'

export async function GET(req: NextRequest) {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })

  const db = getDb()
  const url = new URL(req.url)
  const weekStart = url.searchParams.get('week_start') || (() => {
    // Default to current week's Sunday
    const d = new Date()
    d.setHours(0,0,0,0)
    d.setDate(d.getDate() - d.getDay())
    return d.toISOString().slice(0,10)
  })()

  // Get all tenants who paid this week
  const paid = db.prepare(`
    SELECT DISTINCT tenant_id FROM rent_payments 
    WHERE rent_week_start = ?
  `).all(weekStart) as Array<{tenant_id: string}>
  const paidIds = new Set(paid.map(p => p.tenant_id))

  // Get all active tenants who have NOT paid
  const tenants = db.prepare(`SELECT * FROM tenants WHERE status = 'Active' ORDER BY location ASC, suite ASC`).all() as Array<Record<string,unknown>>
  const unpaid = tenants.filter(t => !paidIds.has(t.tenant_id as string))

  const totalUnpaid = unpaid.reduce((s, t) => s + Number(t.weekly_rent || 0), 0)

  return NextResponse.json({
    week_start: weekStart,
    total_unpaid_amount: totalUnpaid,
    unpaid_count: unpaid.length,
    total_active: tenants.length,
    paid_count: paidIds.size,
    unpaid_tenants: unpaid.map(t => ({
      tenant_id: t.tenant_id,
      tenant_name: t.tenant_name,
      suite: t.suite,
      location: t.location,
      weekly_rent: t.weekly_rent,
      phone: t.phone,
      email: t.email,
    }))
  })
}
