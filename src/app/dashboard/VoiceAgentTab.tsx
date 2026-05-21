'use client'
import { useState, useEffect, useCallback } from 'react'

type Tenant = { tenant_id: string; suite: string; tenant_name: string; weekly_rent: number; status: string; phone: string | null; location: string }
type CallLog = { id: string; tenant_id: string; tenant_name: string; phone: string; call_type: string; status: string; notes: string; created_at: number }

function fmtDate(ts: number) {
  return new Date(ts).toLocaleString('en-US', { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' })
}

export default function VoiceAgentTab() {
  const [tenants, setTenants] = useState<Tenant[]>([])
  const [callLogs, setCallLogs] = useState<CallLog[]>([])
  const [selectedTenants, setSelectedTenants] = useState<Set<string>>(new Set())
  const [callType, setCallType] = useState<'rent_reminder' | 'tour_schedule' | 'welcome' | 'custom'>('rent_reminder')
  const [customMessage, setCustomMessage] = useState('')
  const [filterLocation, setFilterLocation] = useState('all')
  const [filterStatus, setFilterStatus] = useState('unpaid')
  const [launching, setLaunching] = useState(false)
  const [launchStatus, setLaunchStatus] = useState('')
  const [search, setSearch] = useState('')

  const loadData = useCallback(async () => {
    const [t] = await Promise.all([
      fetch('/api/rent/tenants').then(r => r.ok ? r.json() : []),
    ])
    setTenants(t.filter((ten: Tenant) => ten.status === 'Active' && ten.phone))
  }, [])

  useEffect(() => { loadData() }, [loadData])

  // Filter tenants
  const filtered = tenants.filter(t => {
    const matchLoc = filterLocation === 'all' || t.location === filterLocation
    const matchSearch = !search || t.tenant_name?.toLowerCase().includes(search.toLowerCase()) || t.suite?.includes(search)
    return matchLoc && matchSearch
  })

  function toggleTenant(id: string) {
    setSelectedTenants(prev => {
      const next = new Set(prev)
      if (next.has(id)) next.delete(id); else next.add(id)
      return next
    })
  }

  function selectAll() { setSelectedTenants(new Set(filtered.map(t => t.tenant_id))) }
  function clearAll() { setSelectedTenants(new Set()) }

  const callTypeLabels: Record<string, { label: string; desc: string; color: string }> = {
    rent_reminder: { label: 'Rent Reminder', desc: 'Remind tenant that rent is due or past due', color: 'bg-amber-600' },
    tour_schedule: { label: 'Schedule Tour', desc: 'Invite prospect to schedule a suite tour', color: 'bg-blue-600' },
    welcome: { label: 'Welcome Call', desc: 'Welcome new tenant to the salon', color: 'bg-green-600' },
    custom: { label: 'Custom Message', desc: 'Send a custom voice message', color: 'bg-purple-600' },
  }

  async function launchCalls() {
    if (selectedTenants.size === 0) { setLaunchStatus('Please select at least one tenant'); return }
    setLaunching(true)
    setLaunchStatus('')
    try {
      const selectedList = tenants.filter(t => selectedTenants.has(t.tenant_id))
      const res = await fetch('/api/voice/launch', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          tenants: selectedList,
          call_type: callType,
          custom_message: callType === 'custom' ? customMessage : null,
        })
      })
      const data = await res.json()
      if (res.ok) {
        setLaunchStatus(`✅ ${data.launched} calls initiated${data.queued ? ` (${data.queued} queued)` : ''}`)
        setSelectedTenants(new Set())
      } else {
        setLaunchStatus(`❌ ${data.error || 'Failed to launch calls'}`)
      }
    } catch {
      setLaunchStatus('❌ Connection error')
    }
    setLaunching(false)
  }

  return (
    <div className="max-w-7xl mx-auto px-6 py-6 space-y-6">
      {/* Header */}
      <div className="flex items-start justify-between flex-wrap gap-4">
        <div>
          <h2 className="text-2xl font-bold text-white">Tenant Scheduling with Voice Agent</h2>
          <p className="text-slate-400 mt-1 text-sm">Select tenants and launch AI voice calls for reminders, scheduling, and outreach</p>
        </div>
        <div className="bg-slate-800 rounded-xl border border-slate-700 px-4 py-3 text-center">
          <p className="text-slate-400 text-xs uppercase tracking-wider">Tenants with Phone</p>
          <p className="text-2xl font-bold text-white mt-0.5">{tenants.length}</p>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Left — Call Configuration */}
        <div className="space-y-4">
          <div className="bg-slate-800 rounded-2xl border border-slate-700 p-5">
            <h3 className="text-base font-bold text-white mb-4">Call Type</h3>
            <div className="space-y-2">
              {Object.entries(callTypeLabels).map(([key, val]) => (
                <button key={key} onClick={() => setCallType(key as typeof callType)}
                  className={`w-full text-left p-3 rounded-xl border transition-all ${callType === key ? 'border-blue-500 bg-blue-900/20' : 'border-slate-600 hover:border-slate-500'}`}>
                  <div className="flex items-center gap-2">
                    <span className={`w-2 h-2 rounded-full ${val.color}`}></span>
                    <span className="text-white font-semibold text-sm">{val.label}</span>
                  </div>
                  <p className="text-slate-400 text-xs mt-1 ml-4">{val.desc}</p>
                </button>
              ))}
            </div>
            {callType === 'custom' && (
              <div className="mt-4">
                <label className="block text-xs font-semibold text-slate-400 mb-2">Custom Message</label>
                <textarea value={customMessage} onChange={e => setCustomMessage(e.target.value)} rows={3}
                  placeholder="Enter the message for the voice agent to deliver..."
                  className="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-xl text-white text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" />
              </div>
            )}
          </div>

          {/* Launch Button */}
          <div className="bg-slate-800 rounded-2xl border border-slate-700 p-5">
            <div className="flex items-center justify-between mb-3">
              <h3 className="text-base font-bold text-white">Ready to Launch</h3>
              <span className="text-2xl font-bold text-blue-400">{selectedTenants.size}</span>
            </div>
            <p className="text-slate-400 text-sm mb-4">{selectedTenants.size} tenant{selectedTenants.size !== 1 ? 's' : ''} selected for <span className="text-white font-medium">{callTypeLabels[callType].label}</span></p>
            <button onClick={launchCalls} disabled={launching || selectedTenants.size === 0}
              className="w-full py-3 bg-blue-600 hover:bg-blue-500 disabled:bg-slate-600 disabled:cursor-not-allowed text-white font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
              {launching ? (
                <><svg className="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"/><path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>Launching...</>
              ) : (
                <><svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>Launch Voice Calls</>
              )}
            </button>
            {launchStatus && <p className={`text-sm mt-3 text-center ${launchStatus.startsWith('✅') ? 'text-green-400' : 'text-red-400'}`}>{launchStatus}</p>}
            <p className="text-slate-500 text-xs mt-3 text-center">Voice agent requires configuration — see setup below</p>
          </div>

          {/* Setup Notice */}
          <div className="bg-amber-900/20 rounded-2xl border border-amber-700/40 p-4">
            <div className="flex items-start gap-2">
              <svg className="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <div>
                <p className="text-amber-300 font-semibold text-sm">Voice Agent Setup Required</p>
                <p className="text-slate-400 text-xs mt-1">Connect a voice AI provider (Twilio + AI, Bland.ai, Vapi.ai) to activate calling. Contact your admin to configure.</p>
              </div>
            </div>
          </div>
        </div>

        {/* Right — Tenant Selection */}
        <div className="lg:col-span-2">
          <div className="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
            <div className="px-5 py-4 border-b border-slate-700 flex items-center gap-3 flex-wrap">
              <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Search tenants..."
                className="flex-1 min-w-40 px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
              <select value={filterLocation} onChange={e => setFilterLocation(e.target.value)}
                className="px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all">All Locations</option>
                <option value="Cooper">Cooper</option>
                <option value="Village">Village</option>
              </select>
              <button onClick={selectAll} className="px-3 py-2 bg-slate-600 hover:bg-slate-500 text-white text-xs font-semibold rounded-lg">Select All</button>
              <button onClick={clearAll} className="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-300 text-xs font-semibold rounded-lg border border-slate-600">Clear</button>
            </div>
            <div className="overflow-y-auto" style={{maxHeight: '500px'}}>
              <table className="w-full">
                <thead className="sticky top-0 bg-slate-700/90 backdrop-blur-sm"><tr>
                  <th className="w-10 px-4 py-3"></th>
                  <th className="text-left px-4 py-3 text-xs text-slate-400 uppercase">Suite</th>
                  <th className="text-left px-4 py-3 text-xs text-slate-400 uppercase">Tenant</th>
                  <th className="text-left px-4 py-3 text-xs text-slate-400 uppercase hidden md:table-cell">Location</th>
                  <th className="text-left px-4 py-3 text-xs text-slate-400 uppercase">Phone</th>
                </tr></thead>
                <tbody className="divide-y divide-slate-700/50">
                  {filtered.length === 0 ? (
                    <tr><td colSpan={5} className="px-4 py-12 text-center text-slate-400">No tenants with phone numbers found.</td></tr>
                  ) : filtered.map(t => (
                    <tr key={t.tenant_id} onClick={() => toggleTenant(t.tenant_id)}
                      className={`cursor-pointer transition-colors ${selectedTenants.has(t.tenant_id) ? 'bg-blue-900/20 hover:bg-blue-900/30' : 'hover:bg-slate-700/30'}`}>
                      <td className="px-4 py-3">
                        <input type="checkbox" checked={selectedTenants.has(t.tenant_id)} onChange={() => toggleTenant(t.tenant_id)}
                          className="w-4 h-4 rounded border-slate-600 bg-slate-700 text-blue-500 focus:ring-blue-500 cursor-pointer" onClick={e => e.stopPropagation()} />
                      </td>
                      <td className="px-4 py-3 text-white font-bold text-sm">{t.suite}</td>
                      <td className="px-4 py-3">
                        <p className="text-white font-medium text-sm">{t.tenant_name}</p>
                        <p className="text-slate-500 text-xs">${Number(t.weekly_rent||0).toFixed(0)}/week</p>
                      </td>
                      <td className="px-4 py-3 hidden md:table-cell">
                        <span className={`px-2 py-0.5 rounded text-xs font-semibold ${t.location === 'Cooper' ? 'bg-blue-900/50 text-blue-300' : 'bg-purple-900/50 text-purple-300'}`}>{t.location}</span>
                      </td>
                      <td className="px-4 py-3">
                        <a href={`tel:${t.phone}`} onClick={e => e.stopPropagation()} className="text-green-400 text-sm hover:text-green-300">{t.phone}</a>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
