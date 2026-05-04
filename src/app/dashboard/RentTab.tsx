'use client'
import { useState, useEffect, useCallback } from 'react'

type Tenant = { tenant_id: string; suite: string; tenant_name: string; first_name: string; last_name: string; weekly_rent: number; status: string; phone: string; email: string; start_date: string; license_status: string; contract_status: string }
type Balance = { tenant_id: string; tenant_name: string; suite: string; weekly_rent: number; status: string; current_balance: number; days_late_max: number; balance_status: string; next_due_date: string; total_payments: number; late_fees: number }
type RentDashboard = { active_tenants: number; vacant_suites: number; total_suites: number; total_rent_charged: number; total_payments: number; total_late_fees: number; total_outstanding: number; pending_ai_review: number; recent_payments: Array<Record<string,unknown>> }

const BALANCE_COLORS: Record<string, string> = {
  Current: 'bg-green-900/50 text-green-300 border-green-800',
  Due: 'bg-yellow-900/50 text-yellow-300 border-yellow-800',
  Late: 'bg-red-900/50 text-red-300 border-red-800',
  Inactive: 'bg-slate-700 text-slate-400 border-slate-600',
}

function fmt$(n: number) { return '$' + Number(n || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') }

type SortDir = 'asc' | 'desc'
function smartCompare(av: unknown, bv: unknown): number {
  // Both null/undefined — equal
  if (av == null && bv == null) return 0
  if (av == null) return 1
  if (bv == null) return -1
  // Numeric comparison — handles suite numbers like '1','2','10','26'
  const an = Number(av); const bn = Number(bv)
  if (!isNaN(an) && !isNaN(bn)) return an - bn
  // Dollar strings like '$200.00' or '$1,234.56'
  const as = String(av).replace(/[$,]/g, ''); const bs = String(bv).replace(/[$,]/g, '')
  const af = parseFloat(as); const bf = parseFloat(bs)
  if (!isNaN(af) && !isNaN(bf)) return af - bf
  // Date strings YYYY-MM-DD
  if (/^\d{4}-\d{2}-\d{2}/.test(String(av)) && /^\d{4}-\d{2}-\d{2}/.test(String(bv))) {
    return String(av).localeCompare(String(bv))
  }
  // Alphabetical (case-insensitive)
  return String(av).toLowerCase().localeCompare(String(bv).toLowerCase())
}

function useSortable<T>(data: T[], defaultKey: keyof T, defaultDir: SortDir = 'asc') {
  const [sortKey, setSortKey] = useState<keyof T>(defaultKey)
  const [sortDir, setSortDir] = useState<SortDir>(defaultDir)
  const sorted = [...data].sort((a, b) => {
    const cmp = smartCompare(a[sortKey], b[sortKey])
    return sortDir === 'asc' ? cmp : -cmp
  })
  function toggle(key: keyof T) {
    if (sortKey === key) setSortDir(d => d === 'asc' ? 'desc' : 'asc')
    else { setSortKey(key); setSortDir('asc') }
  }
  function SortIcon({ col }: { col: keyof T }) {
    if (sortKey !== col) return <span className="ml-1 text-slate-600">⇅</span>
    return <span className="ml-1 text-blue-400">{sortDir === 'asc' ? '↑' : '↓'}</span>
  }
  return { sorted, toggle, SortIcon, sortKey, sortDir }
}

export default function RentTab({ role }: { role: string }) {
  const [view, setView] = useState<'dashboard' | 'tenants' | 'balances' | 'ailog'>('dashboard')
  const [dashboard, setDashboard] = useState<RentDashboard | null>(null)
  const [tenants, setTenants] = useState<Tenant[]>([])
  const [balances, setBalances] = useState<Balance[]>([])
  const [aiLog, setAiLog] = useState<Array<Record<string,unknown>>>([])
  const [loading, setLoading] = useState(false)
  const [showAddTenant, setShowAddTenant] = useState(false)
  const [showAddPayment, setShowAddPayment] = useState<string | null>(null)
  const [newTenant, setNewTenant] = useState({ tenant_id: '', suite: '', first_name: '', last_name: '', tenant_name: '', weekly_rent: '', start_date: '', phone: '', email: '', status: 'Active' })
  const [newPayment, setNewPayment] = useState({ payment_date: new Date().toISOString().slice(0,10), amount: '', payment_type: 'Cash App', reference: '', rent_week_start: '', notes: '' })
  const [saving, setSaving] = useState(false)

  const loadDashboard = useCallback(async () => {
    const [d, t, b] = await Promise.all([
      fetch('/api/rent/dashboard').then(r => r.ok ? r.json() : null),
      fetch('/api/rent/tenants').then(r => r.ok ? r.json() : []),
      fetch('/api/rent/balances').then(r => r.ok ? r.json() : []),
    ])
    if (d) setDashboard(d)
    setTenants(t)
    setBalances(b)
  }, [])

  const loadAiLog = useCallback(async () => {
    const data = await fetch('/api/rent/ai-log').then(r => r.ok ? r.json() : [])
    setAiLog(data)
  }, [])

  useEffect(() => { loadDashboard() }, [loadDashboard])
  useEffect(() => { if (view === 'ailog') loadAiLog() }, [view, loadAiLog])

  async function saveTenant() {
    if (!newTenant.tenant_id || !newTenant.suite) return
    setSaving(true)
    await fetch('/api/rent/tenants', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ ...newTenant, weekly_rent: parseFloat(newTenant.weekly_rent) || 0 }) })
    setShowAddTenant(false)
    setNewTenant({ tenant_id: '', suite: '', first_name: '', last_name: '', tenant_name: '', weekly_rent: '', start_date: '', phone: '', email: '', status: 'Active' })
    loadDashboard()
    setSaving(false)
  }

  async function savePayment() {
    if (!showAddPayment || !newPayment.amount) return
    setSaving(true)
    await fetch('/api/rent/payments', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ ...newPayment, tenant_id: showAddPayment, amount: parseFloat(newPayment.amount) }) })
    setShowAddPayment(null)
    setNewPayment({ payment_date: new Date().toISOString().slice(0,10), amount: '', payment_type: 'Cash App', reference: '', rent_week_start: '', notes: '' })
    loadDashboard()
    setSaving(false)
  }

  const tenantSort = useSortable(tenants, 'suite' as keyof Tenant)
  const balanceSort = useSortable(balances.filter(b => b.status === 'Active'), 'current_balance' as keyof Balance, 'desc')
  const lateBalances = balances.filter(b => b.balance_status === 'Late').sort((a,b) => b.current_balance - a.current_balance)
  const activeBalances = balanceSort.sorted
  const [recentSort, setRecentSort] = useState<string>('payment_date')
  const [recentDir, setRecentDir] = useState<'asc'|'desc'>('desc')
  function toggleRecent(key: string) { if (recentSort === key) setRecentDir(d => d === 'asc' ? 'desc' : 'asc'); else { setRecentSort(key); setRecentDir('asc') } }
  function RecentIcon({ col }: { col: string }) { if (recentSort !== col) return <span className="ml-1 text-slate-600">⇅</span>; return <span className="ml-1 text-blue-400">{recentDir === 'asc' ? '↑' : '↓'}</span> }
  const sortedRecent = dashboard ? [...(dashboard.recent_payments || [])].sort((a, b) => {
    const cmp = smartCompare(a[recentSort], b[recentSort])
    return recentDir === 'asc' ? cmp : -cmp
  }) : []

  return (
    <div className="max-w-7xl mx-auto px-6 py-6">
      {/* Sub-nav */}
      <div className="flex items-center gap-1 bg-slate-700/50 rounded-xl p-1 mb-6 w-fit">
        {[['dashboard','Dashboard'],['tenants','Tenants'],['balances','Balances'],['ailog','AI Log']].map(([v,l]) => (
          <button key={v} onClick={() => setView(v as typeof view)} className={`px-4 py-2 rounded-lg text-sm font-semibold transition-all ${view === v ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white'}`}>{l}</button>
        ))}
      </div>

      {/* DASHBOARD VIEW */}
      {view === 'dashboard' && dashboard && (
        <div className="space-y-6">
          {/* KPI Cards */}
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            {[
              { label: 'Active Tenants', value: dashboard.active_tenants, color: 'text-green-400', sub: `${dashboard.vacant_suites} vacant` },
              { label: 'Total Collected', value: fmt$(dashboard.total_payments), color: 'text-blue-400', sub: `${fmt$(dashboard.total_rent_charged)} charged` },
              { label: 'Outstanding', value: fmt$(dashboard.total_outstanding), color: dashboard.total_outstanding > 0 ? 'text-red-400' : 'text-green-400', sub: `${fmt$(dashboard.total_late_fees)} in late fees` },
              { label: 'AI Pending Review', value: dashboard.pending_ai_review, color: dashboard.pending_ai_review > 0 ? 'text-yellow-400' : 'text-slate-400', sub: 'unposted payments' },
            ].map(s => (
              <div key={s.label} className="bg-slate-800 rounded-xl border border-slate-700 px-5 py-4">
                <p className="text-slate-400 text-xs font-medium uppercase tracking-wider">{s.label}</p>
                <p className={`text-2xl font-bold mt-1 ${s.color}`}>{s.value}</p>
                <p className="text-slate-500 text-xs mt-1">{s.sub}</p>
              </div>
            ))}
          </div>

          {/* Late Tenants Watchlist */}
          {lateBalances.length > 0 && (
            <div className="bg-slate-800 rounded-2xl border border-red-800/40 overflow-hidden">
              <div className="px-6 py-4 border-b border-slate-700 flex items-center gap-2">
                <svg className="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <h3 className="text-lg font-bold text-white">Late Payments Watchlist</h3>
                <span className="ml-auto px-2.5 py-1 bg-red-900/50 text-red-300 text-xs font-bold rounded-full border border-red-800">{lateBalances.length} tenants</span>
              </div>
              <div className="overflow-x-auto">
                <table className="w-full">
                  <thead><tr className="bg-slate-700/50"><th className="text-left px-6 py-3 text-xs text-slate-400 uppercase">Tenant</th><th className="text-left px-6 py-3 text-xs text-slate-400 uppercase">Suite</th><th className="text-left px-6 py-3 text-xs text-slate-400 uppercase">Balance</th><th className="text-left px-6 py-3 text-xs text-slate-400 uppercase">Days Late</th><th className="px-6 py-3"></th></tr></thead>
                  <tbody className="divide-y divide-slate-700/50">
                    {lateBalances.map(b => (
                      <tr key={b.tenant_id} className="hover:bg-slate-700/30">
                        <td className="px-6 py-3 text-white font-medium">{b.tenant_name || b.tenant_id}</td>
                        <td className="px-6 py-3 text-slate-300">{b.suite}</td>
                        <td className="px-6 py-3 text-red-400 font-bold">{fmt$(b.current_balance)}</td>
                        <td className="px-6 py-3 text-red-300">{b.days_late_max} days</td>
                        <td className="px-6 py-3"><button onClick={() => setShowAddPayment(b.tenant_id)} className="px-3 py-1 bg-green-700 hover:bg-green-600 text-white text-xs font-semibold rounded-lg">Post Payment</button></td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {/* Recent Payments */}
          <div className="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
            <div className="px-6 py-4 border-b border-slate-700"><h3 className="text-lg font-bold text-white">Recent Payments</h3></div>
            <div className="overflow-x-auto">
              <table className="w-full">
                <thead><tr className="bg-slate-700/50">
                  {[['tenant_name','Tenant'],['suite','Suite'],['payment_date','Date'],['net_amount','Amount'],['payment_type','Type']].map(([k,l]) => (
                    <th key={k} onClick={() => toggleRecent(k)} className="text-left px-6 py-3 text-xs text-slate-400 uppercase cursor-pointer hover:text-white select-none">{l}<RecentIcon col={k} /></th>
                  ))}
                </tr></thead>
                <tbody className="divide-y divide-slate-700/50">
                  {sortedRecent.length === 0 ? (
                    <tr><td colSpan={5} className="px-6 py-8 text-center text-slate-400">No payments recorded yet.</td></tr>
                  ) : sortedRecent.map((p, i) => (
                    <tr key={i} className="hover:bg-slate-700/30">
                      <td className="px-6 py-3 text-white">{p.tenant_name as string || p.tenant_id as string}</td>
                      <td className="px-6 py-3 text-slate-300">{p.suite as string}</td>
                      <td className="px-6 py-3 text-slate-300">{p.payment_date as string}</td>
                      <td className="px-6 py-3 text-green-400 font-bold">{fmt$(p.net_amount as number)}</td>
                      <td className="px-6 py-3"><span className="px-2 py-0.5 bg-slate-700 text-slate-300 text-xs rounded">{p.payment_type as string}</span></td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {/* TENANTS VIEW */}
      {view === 'tenants' && (
        <div>
          <div className="flex items-center justify-between mb-4">
            <h3 className="text-lg font-bold text-white">Tenants ({tenants.length})</h3>
            <button onClick={() => setShowAddTenant(true)} className="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-500 text-white font-semibold text-sm rounded-xl">
              <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" /></svg>Add Tenant
            </button>
          </div>
          <div className="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
            <table className="w-full">
              <thead><tr className="bg-slate-700/50 border-b border-slate-700">
                {([['suite','Suite'],['tenant_name','Tenant'],['phone','Phone','hidden md:table-cell'],['weekly_rent','Weekly Rent'],['status','Status'],['start_date','Start Date','hidden lg:table-cell']] as [keyof Tenant, string, string?][]).map(([k,l,cls]) => (
                  <th key={String(k)} onClick={() => tenantSort.toggle(k)} className={`text-left px-6 py-3 text-xs text-slate-400 uppercase cursor-pointer hover:text-white select-none ${cls||''}`}>{l}<tenantSort.SortIcon col={k} /></th>
                ))}
                <th className="px-6 py-3"></th>
              </tr></thead>
              <tbody className="divide-y divide-slate-700/50">
                {tenantSort.sorted.length === 0 ? (
                  <tr><td colSpan={7} className="px-6 py-12 text-center text-slate-400">No tenants yet. Add one to get started.</td></tr>
                ) : tenantSort.sorted.map(t => (
                  <tr key={t.tenant_id} className="hover:bg-slate-700/30">
                    <td className="px-6 py-4 text-white font-bold">{t.suite}</td>
                    <td className="px-6 py-4">
                      <p className="text-white font-medium">{t.tenant_name || `${t.first_name||''} ${t.last_name||''}`.trim() || '—'}</p>
                      <p className="text-slate-400 text-xs">{t.tenant_id}</p>
                    </td>
                    <td className="px-6 py-4 text-slate-300 text-sm hidden md:table-cell">{t.phone || '—'}</td>
                    <td className="px-6 py-4 text-green-400 font-bold">{t.weekly_rent ? fmt$(t.weekly_rent) : '—'}</td>
                    <td className="px-6 py-4"><span className={`px-2.5 py-1 rounded-full text-xs font-semibold border ${t.status === 'Active' ? 'bg-green-900/50 text-green-300 border-green-800' : 'bg-slate-700 text-slate-400 border-slate-600'}`}>{t.status}</span></td>
                    <td className="px-6 py-4 text-slate-400 text-sm hidden lg:table-cell">{t.start_date || '—'}</td>
                    <td className="px-6 py-4"><button onClick={() => setShowAddPayment(t.tenant_id)} className="px-3 py-1 bg-blue-700 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg">+ Payment</button></td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* BALANCES VIEW */}
      {view === 'balances' && (
        <div>
          <h3 className="text-lg font-bold text-white mb-4">Tenant Balances</h3>
          <div className="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
            <table className="w-full">
              <thead><tr className="bg-slate-700/50 border-b border-slate-700">
                {([['tenant_name','Tenant'],['suite','Suite'],['weekly_rent','Weekly Rent'],['total_payments','Paid'],['current_balance','Balance'],['balance_status','Status'],['next_due_date','Next Due','hidden lg:table-cell']] as [keyof Balance, string, string?][]).map(([k,l,cls]) => (
                  <th key={String(k)} onClick={() => balanceSort.toggle(k)} className={`text-left px-6 py-3 text-xs uppercase cursor-pointer hover:text-white select-none ${k === 'current_balance' ? 'text-amber-400' : 'text-slate-400'} ${cls||''}`}>{l}<balanceSort.SortIcon col={k} /></th>
                ))}
                <th className="px-6 py-3"></th>
              </tr></thead>
              <tbody className="divide-y divide-slate-700/50">
                {activeBalances.length === 0 ? (
                  <tr><td colSpan={8} className="px-6 py-12 text-center text-slate-400">No active tenants.</td></tr>
                ) : activeBalances.map(b => (
                  <tr key={b.tenant_id} className="hover:bg-slate-700/30">
                    <td className="px-6 py-4 text-white font-medium">{b.tenant_name || b.tenant_id}</td>
                    <td className="px-6 py-4 text-slate-300">{b.suite}</td>
                    <td className="px-6 py-4 text-slate-300">{fmt$(b.weekly_rent)}</td>
                    <td className="px-6 py-4 text-green-400">{fmt$(b.total_payments)}</td>
                    <td className="px-6 py-4"><span className={`font-bold ${b.current_balance > 0 ? 'text-red-400' : 'text-green-400'}`}>{fmt$(b.current_balance)}</span></td>
                    <td className="px-6 py-4"><span className={`px-2.5 py-1 rounded-full text-xs font-semibold border ${BALANCE_COLORS[b.balance_status] || BALANCE_COLORS.Inactive}`}>{b.balance_status}</span></td>
                    <td className="px-6 py-4 text-slate-400 text-sm hidden lg:table-cell">{b.next_due_date}</td>
                    <td className="px-6 py-4"><button onClick={() => setShowAddPayment(b.tenant_id)} className="px-3 py-1 bg-green-700 hover:bg-green-600 text-white text-xs font-semibold rounded-lg">+ Payment</button></td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* AI LOG VIEW */}
      {view === 'ailog' && (
        <div>
          <h3 className="text-lg font-bold text-white mb-4">AI Automation Log — Pending Review</h3>
          <div className="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
            <table className="w-full">
              <thead><tr className="bg-slate-700/50 border-b border-slate-700">
                <th className="text-left px-6 py-3 text-xs text-slate-400 uppercase">Date</th>
                <th className="text-left px-6 py-3 text-xs text-slate-400 uppercase">Tenant</th>
                <th className="text-left px-6 py-3 text-xs text-slate-400 uppercase">Amount</th>
                <th className="text-left px-6 py-3 text-xs text-slate-400 uppercase">Type</th>
                <th className="text-left px-6 py-3 text-xs text-slate-400 uppercase">Status</th>
                <th className="text-left px-6 py-3 text-xs text-slate-400 uppercase hidden lg:table-cell">AI Notes</th>
              </tr></thead>
              <tbody className="divide-y divide-slate-700/50">
                {aiLog.length === 0 ? (
                  <tr><td colSpan={6} className="px-6 py-12 text-center text-slate-400">No AI log entries yet. Payments extracted by AI will appear here for review.</td></tr>
                ) : aiLog.map((e, i) => (
                  <tr key={i} className="hover:bg-slate-700/30">
                    <td className="px-6 py-3 text-slate-300 text-sm">{e.email_date as string || '—'}</td>
                    <td className="px-6 py-3 text-white">{e.extracted_tenant as string || '—'}</td>
                    <td className="px-6 py-3 text-green-400 font-bold">{e.extracted_amount ? fmt$(e.extracted_amount as number) : '—'}</td>
                    <td className="px-6 py-3 text-slate-300 text-sm">{e.extracted_payment_type as string || '—'}</td>
                    <td className="px-6 py-3"><span className={`px-2.5 py-1 rounded-full text-xs font-semibold border ${e.review_status === 'Posted' ? 'bg-green-900/50 text-green-300 border-green-800' : 'bg-yellow-900/50 text-yellow-300 border-yellow-800'}`}>{e.review_status as string}</span></td>
                    <td className="px-6 py-3 text-slate-400 text-xs hidden lg:table-cell">{e.ai_notes as string || '—'}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* Add Tenant Modal */}
      {showAddTenant && (
        <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" onClick={() => setShowAddTenant(false)}>
          <div className="bg-slate-800 rounded-2xl border border-slate-700 w-full max-w-lg p-6" onClick={e => e.stopPropagation()}>
            <h3 className="text-lg font-bold text-white mb-5">Add Tenant</h3>
            <div className="grid grid-cols-2 gap-4">
              {[{label:'Tenant ID *',key:'tenant_id',type:'text'},{label:'Suite *',key:'suite',type:'text'},{label:'First Name',key:'first_name',type:'text'},{label:'Last Name',key:'last_name',type:'text'},{label:'Business Name',key:'tenant_name',type:'text'},{label:'Weekly Rent ($)',key:'weekly_rent',type:'number'},{label:'Start Date',key:'start_date',type:'date'},{label:'Phone',key:'phone',type:'tel'},{label:'Email',key:'email',type:'email'}].map(f => (
                <div key={f.key}>
                  <label className="block text-xs font-semibold text-slate-400 mb-1">{f.label}</label>
                  <input type={f.type} value={(newTenant as Record<string,string>)[f.key]} onChange={e => setNewTenant(n => ({...n,[f.key]:e.target.value}))} className="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
              ))}
              <div>
                <label className="block text-xs font-semibold text-slate-400 mb-1">Status</label>
                <select value={newTenant.status} onChange={e => setNewTenant(n => ({...n,status:e.target.value}))} className="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                  <option>Active</option><option>Vacant</option><option>Inactive</option>
                </select>
              </div>
            </div>
            <div className="flex gap-3 mt-6">
              <button onClick={saveTenant} disabled={!newTenant.tenant_id || !newTenant.suite || saving} className="flex-1 py-3 bg-green-600 hover:bg-green-500 disabled:bg-slate-600 text-white font-bold rounded-xl">{saving ? 'Saving...' : 'Add Tenant'}</button>
              <button onClick={() => setShowAddTenant(false)} className="px-6 py-3 border border-slate-600 text-slate-300 hover:text-white rounded-xl">Cancel</button>
            </div>
          </div>
        </div>
      )}

      {/* Post Payment Modal */}
      {showAddPayment && (
        <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" onClick={() => setShowAddPayment(null)}>
          <div className="bg-slate-800 rounded-2xl border border-slate-700 w-full max-w-md p-6" onClick={e => e.stopPropagation()}>
            <h3 className="text-lg font-bold text-white mb-5">Post Payment</h3>
            <p className="text-slate-400 text-sm mb-4">Tenant: <span className="text-white font-medium">{tenants.find(t => t.tenant_id === showAddPayment)?.tenant_name || showAddPayment}</span></p>
            <div className="space-y-4">
              {[{label:'Payment Date',key:'payment_date',type:'date'},{label:'Amount ($)',key:'amount',type:'number'},{label:'Reference / Check #',key:'reference',type:'text'},{label:'Rent Week Start (Sunday)',key:'rent_week_start',type:'date'}].map(f => (
                <div key={f.key}>
                  <label className="block text-xs font-semibold text-slate-400 mb-1">{f.label}</label>
                  <input type={f.type} value={(newPayment as Record<string,string>)[f.key]} onChange={e => setNewPayment(p => ({...p,[f.key]:e.target.value}))} className="w-full px-3 py-2.5 bg-slate-700 border border-slate-600 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
              ))}
              <div>
                <label className="block text-xs font-semibold text-slate-400 mb-1">Payment Type</label>
                <select value={newPayment.payment_type} onChange={e => setNewPayment(p => ({...p,payment_type:e.target.value}))} className="w-full px-3 py-2.5 bg-slate-700 border border-slate-600 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                  <option>Cash App</option><option>Zelle</option><option>Cash</option><option>Card</option><option>Check</option>
                </select>
              </div>
            </div>
            <div className="flex gap-3 mt-6">
              <button onClick={savePayment} disabled={!newPayment.amount || saving} className="flex-1 py-3 bg-green-600 hover:bg-green-500 disabled:bg-slate-600 text-white font-bold rounded-xl">{saving ? 'Saving...' : 'Post Payment'}</button>
              <button onClick={() => setShowAddPayment(null)} className="px-6 py-3 border border-slate-600 text-slate-300 hover:text-white rounded-xl">Cancel</button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
