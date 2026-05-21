'use client'
import { useState, useEffect, useCallback } from 'react'

type Tenant = { tenant_id: string; suite: string; tenant_name: string; weekly_rent: number; status: string; phone: string | null; location: string }

type Appointment = {
  id: string
  tenant_id: string
  tenant_name: string
  suite: string
  phone: string
  date: string
  time: string
  type: string
  notes: string
  status: 'scheduled' | 'confirmed' | 'cancelled' | 'completed'
  created_at: number
}

const APPT_TYPES = [
  { key: 'suite_tour', label: 'Suite Tour', icon: '🏠', color: 'bg-blue-600' },
  { key: 'lease_signing', label: 'Lease Signing', icon: '📝', color: 'bg-green-600' },
  { key: 'maintenance', label: 'Maintenance Visit', icon: '🔧', color: 'bg-orange-600' },
  { key: 'move_in', label: 'Move-In', icon: '📦', color: 'bg-purple-600' },
  { key: 'move_out', label: 'Move-Out', icon: '🚪', color: 'bg-red-600' },
  { key: 'general', label: 'General Meeting', icon: '📅', color: 'bg-slate-500' },
]

const STATUS_STYLES: Record<string, string> = {
  scheduled: 'bg-blue-900/40 text-blue-300 border-blue-700/50',
  confirmed: 'bg-green-900/40 text-green-300 border-green-700/50',
  cancelled: 'bg-red-900/40 text-red-300 border-red-700/50',
  completed: 'bg-slate-700/40 text-slate-300 border-slate-600/50',
}

function getTodayStr() {
  return new Date().toISOString().split('T')[0]
}

function fmtDateDisplay(d: string) {
  if (!d) return ''
  const dt = new Date(d + 'T00:00:00')
  return dt.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' })
}

export default function TenantSchedulingVoiceTab() {
  const [tenants, setTenants] = useState<Tenant[]>([])
  const [appointments, setAppointments] = useState<Appointment[]>([])
  const [showForm, setShowForm] = useState(false)
  const [callLaunching, setCallLaunching] = useState<string | null>(null)
  const [callStatus, setCallStatus] = useState<Record<string, string>>({})
  const [filterDate, setFilterDate] = useState('')
  const [filterStatus, setFilterStatus] = useState('all')
  const [search, setSearch] = useState('')

  // New appointment form state
  const [form, setForm] = useState({
    tenant_id: '',
    date: getTodayStr(),
    time: '10:00',
    type: 'suite_tour',
    notes: '',
    notify_voice: true,
  })
  const [formSubmitting, setFormSubmitting] = useState(false)
  const [formStatus, setFormStatus] = useState('')

  const loadData = useCallback(async () => {
    const [t, a] = await Promise.all([
      fetch('/api/rent/tenants').then(r => r.ok ? r.json() : []),
      fetch('/api/scheduling/appointments').then(r => r.ok ? r.json() : []).catch(() => []),
    ])
    setTenants(t.filter((ten: Tenant) => ten.status === 'Active'))
    setAppointments(a)
  }, [])

  useEffect(() => { loadData() }, [loadData])

  const filtered = appointments.filter(a => {
    const matchDate = !filterDate || a.date === filterDate
    const matchStatus = filterStatus === 'all' || a.status === filterStatus
    const matchSearch = !search ||
      a.tenant_name?.toLowerCase().includes(search.toLowerCase()) ||
      a.suite?.toLowerCase().includes(search.toLowerCase())
    return matchDate && matchStatus && matchSearch
  })

  const today = getTodayStr()
  const upcoming = appointments.filter(a => a.date >= today && a.status !== 'cancelled').slice(0, 5)

  async function submitForm() {
    if (!form.tenant_id || !form.date || !form.time) {
      setFormStatus('Please fill in all required fields.')
      return
    }
    setFormSubmitting(true)
    setFormStatus('')
    const tenant = tenants.find(t => t.tenant_id === form.tenant_id)
    if (!tenant) { setFormStatus('Tenant not found.'); setFormSubmitting(false); return }
    try {
      const res = await fetch('/api/scheduling/appointments', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          tenant_id: form.tenant_id,
          tenant_name: tenant.tenant_name,
          suite: tenant.suite,
          phone: tenant.phone || '',
          date: form.date,
          time: form.time,
          type: form.type,
          notes: form.notes,
          notify_voice: form.notify_voice,
        })
      })
      const data = await res.json()
      if (res.ok) {
        setFormStatus('✅ Appointment scheduled' + (form.notify_voice && tenant.phone ? ' · Voice notification queued' : ''))
        setForm({ tenant_id: '', date: getTodayStr(), time: '10:00', type: 'suite_tour', notes: '', notify_voice: true })
        loadData()
      } else {
        setFormStatus(`❌ ${data.error || 'Failed to schedule'}`)
      }
    } catch {
      setFormStatus('❌ Connection error')
    }
    setFormSubmitting(false)
  }

  async function sendVoiceReminder(appt: Appointment) {
    setCallLaunching(appt.id)
    try {
      const res = await fetch('/api/voice/launch', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          tenants: [{ tenant_id: appt.tenant_id, tenant_name: appt.tenant_name, phone: appt.phone, suite: appt.suite }],
          call_type: 'tour_schedule',
          appointment: { date: appt.date, time: appt.time, type: appt.type },
        })
      })
      const data = await res.json()
      setCallStatus(prev => ({ ...prev, [appt.id]: res.ok ? `✅ Call initiated` : `❌ ${data.error || 'Failed'}` }))
    } catch {
      setCallStatus(prev => ({ ...prev, [appt.id]: '❌ Connection error' }))
    }
    setCallLaunching(null)
  }

  async function updateStatus(apptId: string, status: Appointment['status']) {
    await fetch(`/api/scheduling/appointments/${apptId}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ status })
    }).catch(() => null)
    loadData()
  }

  const typeMap = Object.fromEntries(APPT_TYPES.map(t => [t.key, t]))

  return (
    <div className="max-w-7xl mx-auto px-6 py-6 space-y-6">
      {/* Page Header */}
      <div className="flex items-start justify-between flex-wrap gap-4">
        <div>
          <h2 className="text-2xl font-bold text-white">Tenant Scheduling with Voice Agent</h2>
          <p className="text-slate-400 mt-1 text-sm">Schedule appointments and send AI voice call reminders to tenants</p>
        </div>
        <div className="flex items-center gap-3">
          <div className="bg-slate-800 rounded-xl border border-slate-700 px-4 py-3 text-center">
            <p className="text-slate-400 text-xs uppercase tracking-wider">Upcoming</p>
            <p className="text-2xl font-bold text-white mt-0.5">{upcoming.length}</p>
          </div>
          <button
            onClick={() => setShowForm(v => !v)}
            className="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl transition-colors shadow-lg"
          >
            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
            </svg>
            New Appointment
          </button>
        </div>
      </div>

      {/* New Appointment Form */}
      {showForm && (
        <div className="bg-slate-800 rounded-2xl border border-slate-700 p-6">
          <h3 className="text-lg font-bold text-white mb-5">Schedule New Appointment</h3>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {/* Tenant */}
            <div>
              <label className="block text-xs font-semibold text-slate-400 mb-1.5">Tenant *</label>
              <select
                value={form.tenant_id}
                onChange={e => setForm(f => ({ ...f, tenant_id: e.target.value }))}
                className="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="">Select tenant...</option>
                {tenants.map(t => (
                  <option key={t.tenant_id} value={t.tenant_id}>{t.tenant_name} — Suite {t.suite}</option>
                ))}
              </select>
            </div>

            {/* Date */}
            <div>
              <label className="block text-xs font-semibold text-slate-400 mb-1.5">Date *</label>
              <input
                type="date"
                value={form.date}
                min={getTodayStr()}
                onChange={e => setForm(f => ({ ...f, date: e.target.value }))}
                className="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            {/* Time */}
            <div>
              <label className="block text-xs font-semibold text-slate-400 mb-1.5">Time *</label>
              <input
                type="time"
                value={form.time}
                onChange={e => setForm(f => ({ ...f, time: e.target.value }))}
                className="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            {/* Appointment Type */}
            <div>
              <label className="block text-xs font-semibold text-slate-400 mb-1.5">Type</label>
              <select
                value={form.type}
                onChange={e => setForm(f => ({ ...f, type: e.target.value }))}
                className="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                {APPT_TYPES.map(t => (
                  <option key={t.key} value={t.key}>{t.icon} {t.label}</option>
                ))}
              </select>
            </div>

            {/* Notes */}
            <div className="md:col-span-2">
              <label className="block text-xs font-semibold text-slate-400 mb-1.5">Notes</label>
              <input
                type="text"
                value={form.notes}
                onChange={e => setForm(f => ({ ...f, notes: e.target.value }))}
                placeholder="Optional notes..."
                className="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-xl text-white text-sm placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>

          {/* Voice Notify Toggle */}
          <div className="flex items-center gap-3 mt-4">
            <button
              onClick={() => setForm(f => ({ ...f, notify_voice: !f.notify_voice }))}
              className={`relative w-11 h-6 rounded-full transition-colors ${form.notify_voice ? 'bg-blue-600' : 'bg-slate-600'}`}
            >
              <span className={`absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-all ${form.notify_voice ? 'left-6' : 'left-1'}`} />
            </button>
            <div>
              <p className="text-white text-sm font-medium">Send Voice Reminder</p>
              <p className="text-slate-400 text-xs">AI voice call will notify the tenant of this appointment</p>
            </div>
          </div>

          <div className="flex items-center gap-3 mt-5">
            <button
              onClick={submitForm}
              disabled={formSubmitting}
              className="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 disabled:bg-slate-600 text-white font-bold text-sm rounded-xl transition-colors"
            >
              {formSubmitting ? 'Scheduling...' : 'Schedule Appointment'}
            </button>
            <button
              onClick={() => { setShowForm(false); setFormStatus('') }}
              className="px-6 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded-xl transition-colors"
            >
              Cancel
            </button>
            {formStatus && (
              <p className={`text-sm ${formStatus.startsWith('✅') ? 'text-green-400' : formStatus.startsWith('❌') ? 'text-red-400' : 'text-amber-400'}`}>
                {formStatus}
              </p>
            )}
          </div>
        </div>
      )}

      {/* Upcoming appointments strip */}
      {upcoming.length > 0 && (
        <div>
          <h3 className="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-3">Upcoming</h3>
          <div className="flex gap-3 overflow-x-auto pb-2">
            {upcoming.map(a => {
              const t = typeMap[a.type] || typeMap['general']
              return (
                <div key={a.id} className="flex-shrink-0 bg-slate-800 rounded-xl border border-slate-700 p-4 w-52">
                  <div className="flex items-center gap-2 mb-2">
                    <span className="text-lg">{t.icon}</span>
                    <span className="text-xs font-semibold text-slate-300">{t.label}</span>
                  </div>
                  <p className="text-white font-bold text-sm truncate">{a.tenant_name}</p>
                  <p className="text-slate-400 text-xs">Suite {a.suite}</p>
                  <p className="text-blue-300 text-xs font-medium mt-1">{fmtDateDisplay(a.date)} · {a.time}</p>
                  <span className={`inline-block mt-2 px-2 py-0.5 rounded border text-xs font-medium capitalize ${STATUS_STYLES[a.status]}`}>
                    {a.status}
                  </span>
                </div>
              )
            })}
          </div>
        </div>
      )}

      {/* Appointments Table */}
      <div className="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
        {/* Filters */}
        <div className="px-5 py-4 border-b border-slate-700 flex items-center gap-3 flex-wrap">
          <input
            value={search}
            onChange={e => setSearch(e.target.value)}
            placeholder="Search tenant or suite..."
            className="flex-1 min-w-40 px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <input
            type="date"
            value={filterDate}
            onChange={e => setFilterDate(e.target.value)}
            className="px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <select
            value={filterStatus}
            onChange={e => setFilterStatus(e.target.value)}
            className="px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="all">All Statuses</option>
            <option value="scheduled">Scheduled</option>
            <option value="confirmed">Confirmed</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
          {(filterDate || filterStatus !== 'all' || search) && (
            <button
              onClick={() => { setFilterDate(''); setFilterStatus('all'); setSearch('') }}
              className="px-3 py-2 bg-slate-600 hover:bg-slate-500 text-slate-300 text-xs font-semibold rounded-lg"
            >
              Clear
            </button>
          )}
        </div>

        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-slate-700/60">
              <tr>
                <th className="text-left px-5 py-3 text-xs text-slate-400 uppercase tracking-wider">Tenant / Suite</th>
                <th className="text-left px-5 py-3 text-xs text-slate-400 uppercase tracking-wider">Date & Time</th>
                <th className="text-left px-5 py-3 text-xs text-slate-400 uppercase tracking-wider">Type</th>
                <th className="text-left px-5 py-3 text-xs text-slate-400 uppercase tracking-wider hidden md:table-cell">Notes</th>
                <th className="text-left px-5 py-3 text-xs text-slate-400 uppercase tracking-wider">Status</th>
                <th className="text-left px-5 py-3 text-xs text-slate-400 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-700/50">
              {filtered.length === 0 ? (
                <tr>
                  <td colSpan={6} className="px-5 py-12 text-center">
                    <p className="text-slate-400 text-sm">No appointments found</p>
                    <button
                      onClick={() => setShowForm(true)}
                      className="mt-3 text-blue-400 hover:text-blue-300 text-sm font-semibold"
                    >
                      + Schedule one now
                    </button>
                  </td>
                </tr>
              ) : filtered.map(a => {
                const t = typeMap[a.type] || typeMap['general']
                return (
                  <tr key={a.id} className="hover:bg-slate-700/30 transition-colors">
                    <td className="px-5 py-4">
                      <p className="text-white font-semibold text-sm">{a.tenant_name}</p>
                      <p className="text-slate-400 text-xs">Suite {a.suite}</p>
                      {a.phone && <a href={`tel:${a.phone}`} className="text-green-400 text-xs hover:text-green-300">{a.phone}</a>}
                    </td>
                    <td className="px-5 py-4">
                      <p className="text-white text-sm font-medium">{fmtDateDisplay(a.date)}</p>
                      <p className="text-slate-400 text-xs">{a.time}</p>
                    </td>
                    <td className="px-5 py-4">
                      <span className="flex items-center gap-1.5 text-sm text-slate-300">
                        <span>{t.icon}</span>
                        <span className="hidden sm:inline">{t.label}</span>
                      </span>
                    </td>
                    <td className="px-5 py-4 hidden md:table-cell">
                      <p className="text-slate-400 text-xs max-w-[160px] truncate">{a.notes || '—'}</p>
                    </td>
                    <td className="px-5 py-4">
                      <select
                        value={a.status}
                        onChange={e => updateStatus(a.id, e.target.value as Appointment['status'])}
                        className={`px-2 py-1 rounded border text-xs font-medium bg-transparent focus:outline-none capitalize cursor-pointer ${STATUS_STYLES[a.status]}`}
                      >
                        <option value="scheduled">Scheduled</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                      </select>
                    </td>
                    <td className="px-5 py-4">
                      {a.phone && a.status !== 'cancelled' && a.status !== 'completed' ? (
                        <div className="flex flex-col gap-1">
                          <button
                            onClick={() => sendVoiceReminder(a)}
                            disabled={callLaunching === a.id}
                            className="flex items-center gap-1.5 px-3 py-1.5 bg-blue-700/50 hover:bg-blue-600/70 disabled:bg-slate-700 text-blue-300 hover:text-white text-xs font-semibold rounded-lg border border-blue-700/40 transition-colors"
                          >
                            {callLaunching === a.id ? (
                              <svg className="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24">
                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                              </svg>
                            ) : (
                              <svg className="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                              </svg>
                            )}
                            Voice Remind
                          </button>
                          {callStatus[a.id] && (
                            <span className={`text-xs ${callStatus[a.id].startsWith('✅') ? 'text-green-400' : 'text-red-400'}`}>
                              {callStatus[a.id]}
                            </span>
                          )}
                        </div>
                      ) : (
                        <span className="text-slate-600 text-xs">—</span>
                      )}
                    </td>
                  </tr>
                )
              })}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  )
}
