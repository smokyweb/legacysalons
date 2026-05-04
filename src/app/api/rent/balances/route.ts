import { NextResponse } from 'next/server'
import { getSession } from '../../../../lib/auth'
import { getDb } from '../../../../lib/db'

export async function GET() {
  const session = await getSession()
  if (!session) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 })
  const db = getDb()

  const settings = Object.fromEntries((db.prepare('SELECT key, value FROM settings').all() as Array<{key:string,value:string}>).map(s => [s.key, s.value]))
  const lateFeePerDay = Number(settings.late_fee_per_day || 20)
  const balanceThreshold = Number(settings.balance_threshold || 0.01)
  const today = new Date()

  const tenants = db.prepare('SELECT * FROM tenants').all() as Array<Record<string,unknown>>

  const balances = tenants.map(t => {
    const tid = t.tenant_id as string
    // Total rent charges from rent_schedule
    const chargesRow = db.prepare('SELECT COALESCE(SUM(rent_charge),0) as total FROM rent_schedule WHERE tenant_id = ?').get(tid) as {total: number}
    const totalRentCharges = chargesRow.total

    // Total payments
    const paymentsRow = db.prepare('SELECT COALESCE(SUM(net_amount),0) as total FROM rent_payments WHERE tenant_id = ?').get(tid) as {total: number}
    const totalPayments = paymentsRow.total

    // Free week credits
    const freeRow = db.prepare("SELECT COALESCE(SUM(weeks_granted),0) as total FROM free_weeks WHERE tenant_id = ? AND approval_status = 'Approved'").get(tid) as {total: number}
    const freeWeekCredits = freeRow.total * Number(t.weekly_rent || 0)

    // Late fees - calculate based on overdue rent_schedule rows
    let lateFees = 0
    const overdueRows = db.prepare("SELECT * FROM rent_schedule WHERE tenant_id = ? AND balance_before_late_fee > ? AND status != 'Paid'").all(tid, balanceThreshold) as Array<Record<string,unknown>>
    for (const row of overdueRows) {
      const dueDate = new Date(row.rent_due_date as string)
      if (today > dueDate) {
        const daysLate = Math.max(0, Math.floor((today.getTime() - dueDate.getTime()) / (1000 * 60 * 60 * 24)))
        lateFees += daysLate * lateFeePerDay
      }
    }

    const currentBalance = totalRentCharges - totalPayments - freeWeekCredits + lateFees

    // Next due date - next upcoming Saturday
    const nextDue = new Date(today)
    const daysUntilSat = (6 - today.getDay() + 7) % 7 || 7
    nextDue.setDate(today.getDate() + daysUntilSat)

    // Days late max
    const daysLateMax = overdueRows.reduce((max, row) => {
      const dueDate = new Date(row.rent_due_date as string)
      if (today > dueDate) {
        const days = Math.floor((today.getTime() - dueDate.getTime()) / (1000 * 60 * 60 * 24))
        return Math.max(max, days)
      }
      return max
    }, 0)

    let balanceStatus = 'Inactive'
    if (t.status === 'Active') {
      if (currentBalance <= 0) balanceStatus = 'Current'
      else if (daysLateMax === 0) balanceStatus = 'Due'
      else balanceStatus = 'Late'
    }

    return {
      tenant_id: tid,
      tenant_name: t.tenant_name || `${t.first_name || ''} ${t.last_name || ''}`.trim(),
      suite: t.suite,
      weekly_rent: t.weekly_rent,
      status: t.status,
      total_rent_charges: totalRentCharges,
      total_payments: totalPayments,
      free_week_credits: freeWeekCredits,
      late_fees: lateFees,
      current_balance: currentBalance,
      days_late_max: daysLateMax,
      balance_status: balanceStatus,
      license_status: t.license_status,
      contract_status: t.contract_status,
      next_due_date: nextDue.toISOString().slice(0, 10)
    }
  })

  return NextResponse.json(balances)
}
