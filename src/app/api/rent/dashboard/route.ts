import { NextResponse } from 'next/server'
import { getSession } from '../../../../lib/auth'
import { getDb } from '../../../../lib/db'

export async function GET() {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const db = getDb()

  const tenants = db.prepare('SELECT * FROM tenants').all() as Array<Record<string,unknown>>
  const activeTenants = tenants.filter(t => t.status === 'Active').length
  const vacantSuites = tenants.filter(t => t.status === 'Vacant').length

  const paymentsTotal = (db.prepare('SELECT COALESCE(SUM(net_amount),0) as total FROM rent_payments').get() as {total:number}).total
  const rentChargesTotal = (db.prepare('SELECT COALESCE(SUM(rent_charge),0) as total FROM rent_schedule').get() as {total:number}).total
  const lateFeesTotal = (db.prepare('SELECT COALESCE(SUM(late_fee),0) as total FROM rent_schedule').get() as {total:number}).total
  const outstanding = Math.max(0, rentChargesTotal - paymentsTotal + lateFeesTotal)

  // Status counts (from balances logic simplified)
  const currentWeekStart = (() => {
    const d = new Date(); d.setHours(0,0,0,0)
    const day = d.getDay()
    d.setDate(d.getDate() - day) // back to Sunday
    return d.toISOString().slice(0,10)
  })()

  // Recent AI log entries needing review
  const pendingReview = (db.prepare("SELECT COUNT(*) as c FROM ai_automation_log WHERE review_status = 'Needs Review'").get() as {c:number}).c
  const recentPayments = db.prepare('SELECT p.*, t.tenant_name, t.suite FROM rent_payments p LEFT JOIN tenants t ON p.tenant_id = t.tenant_id ORDER BY p.created_at DESC LIMIT 10').all()

  return NextResponse.json({
    active_tenants: activeTenants,
    vacant_suites: vacantSuites,
    total_suites: tenants.length,
    total_rent_charged: rentChargesTotal,
    total_payments: paymentsTotal,
    total_late_fees: lateFeesTotal,
    total_outstanding: outstanding,
    pending_ai_review: pendingReview,
    recent_payments: recentPayments,
    current_week_start: currentWeekStart
  })
}
