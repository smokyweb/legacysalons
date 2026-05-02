'use client'
import { useState, useEffect, useCallback } from 'react'
import { useRouter } from 'next/navigation'

type Payment = { name: string; amount: number; date: string; payment_app: string }
type Run = { id: string; created_at: number; status: string; payment_count: number; total_amount: number; week_start: string; week_end: string }

function getCurrentWeek() {
  const now = new Date()
  const day = now.getDay()
  const daysSinceTuesday = (day - 2 + 7) % 7
  const start = new Date(now)
  start.setDate(now.getDate() - daysSinceTuesday)
  start.setHours(0,0,0,0)
  const end = new Date(start)
  end.setDate(start.getDate() + 6)
  const fmt = (d: Date) => d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
  return `${fmt(start)} – ${fmt(end)}`
}

function fmtDate(ts: number) {
  return new Date(ts).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' })
}

export default function DashboardClient() {
  const router = useRouter()
  const [status, setStatus] = useState<'idle'|'running'|'success'|'error'>('idle')
  const [runs, setRuns] = useState<Run[]>([])
  const [activeRun, setActiveRun] = useState<{ run: Run; payments: Payment[] } | null>(null)
  const [errorMsg, setErrorMsg] = useState('')
  const [currentRunId, setCurrentRunId] = useState<string | null>(null)
  const weekLabel = getCurrentWeek()

  const loadRuns = useCallback(async () => {
    try {
      const res = await fetch('/api/runs')
      if (res.ok) setRuns(await res.json())
    } catch {}
  }, [])

  useEffect(() => { loadRuns() }, [loadRuns])

  // Poll for results while running
  useEffect(() => {
    if (status !== 'running' || !currentRunId) return
    const interval = setInterval(async () => {
      try {
        const res = await fetch(`/api/runs/${currentRunId}`)
        if (res.ok) {
          const data = await res.json()
          if (data.run.status === 'completed') {
            setActiveRun(data)
            setStatus('success')
            setCurrentRunId(null)
            loadRuns()
            clearInterval(interval)
          } else if (data.run.status === 'error') {
            setStatus('error')
            setErrorMsg('Workflow returned an error. Check n8n for details.')
            setCurrentRunId(null)
            clearInterval(interval)
          }
        }
      } catch {}
    }, 3000)
    return () => clearInterval(interval)
  }, [status, currentRunId, loadRuns])

  async function handleRun() {
    setStatus('running')
    setErrorMsg('')
    setActiveRun(null)
    try {
      const res = await fetch('/api/run-workflow', { method: 'POST' })
      if (res.ok) {
        const data = await res.json()
        setCurrentRunId(data.runId)
      } else {
        throw new Error('Failed to trigger workflow')
      }
    } catch (e: unknown) {
      setStatus('error')
      setErrorMsg(e instanceof Error ? e.message : 'Failed to trigger workflow')
    }
  }

  async function handleViewRun(id: string) {
    try {
      const res = await fetch(`/api/runs/${id}`)
      if (res.ok) setActiveRun(await res.json())
    } catch {}
  }

  async function handleLogout() {
    await fetch('/api/auth/logout', { method: 'POST' })
    router.push('/login')
  }

  const statusColors = {
    idle: 'bg-slate-600 text-slate-300',
    running: 'bg-yellow-600 text-yellow-100',
    success: 'bg-green-700 text-green-100',
    error: 'bg-red-700 text-red-100',
  }
  const statusLabels = { idle: 'Ready', running: 'Running...', success: 'Completed', error: 'Error' }

  return (
    <div className="min-h-screen bg-slate-900">
      {/* Header */}
      <header className="bg-slate-800 border-b border-slate-700 px-6 py-4">
        <div className="max-w-6xl mx-auto flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="w-9 h-9 rounded-xl bg-green-600 flex items-center justify-center">
              <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h1 className="text-lg font-bold text-white leading-none">Legacy Salons</h1>
              <p className="text-xs text-slate-400">Payment Dashboard</p>
            </div>
          </div>
          <button onClick={handleLogout} className="text-slate-400 hover:text-white text-sm flex items-center gap-1.5 transition-colors">
            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Sign Out
          </button>
        </div>
      </header>

      <main className="max-w-6xl mx-auto px-6 py-8 space-y-8">
        {/* Run Workflow Card */}
        <div className="bg-slate-800 rounded-2xl border border-slate-700 p-8">
          <div className="flex items-start justify-between flex-wrap gap-4">
            <div>
              <h2 className="text-xl font-bold text-white">Run Weekly Payment Report</h2>
              <p className="text-slate-400 mt-1 text-sm flex items-center gap-1.5">
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Current week: <span className="text-white font-medium">{weekLabel}</span>
              </p>
            </div>
            <span className={`px-3 py-1 rounded-full text-xs font-semibold ${statusColors[status]}`}>
              {statusLabels[status]}
            </span>
          </div>

          <div className="mt-6">
            <button
              onClick={handleRun}
              disabled={status === 'running'}
              className="inline-flex items-center gap-2.5 px-8 py-4 bg-green-600 hover:bg-green-500 disabled:bg-slate-600 disabled:cursor-not-allowed text-white font-bold text-lg rounded-xl transition-all duration-200 shadow-lg hover:shadow-green-900/40"
            >
              {status === 'running' ? (
                <>
                  <svg className="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                  </svg>
                  Processing...
                </>
              ) : (
                <>
                  <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                  </svg>
                  Run Report
                </>
              )}
            </button>
          </div>

          {status === 'running' && (
            <div className="mt-4 text-sm text-yellow-400 flex items-center gap-2">
              <svg className="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
              </svg>
              Fetching payments from Gmail via n8n... This may take 30–60 seconds.
            </div>
          )}

          {status === 'error' && (
            <div className="mt-4 flex items-center gap-2 text-red-400 text-sm bg-red-900/30 border border-red-800 rounded-lg px-4 py-3">
              <svg className="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
              </svg>
              {errorMsg}
            </div>
          )}
        </div>

        {/* Results Table */}
        {activeRun && (
          <div className="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
            <div className="px-6 py-4 border-b border-slate-700 flex items-center justify-between">
              <div>
                <h3 className="text-lg font-bold text-white">Payment Results</h3>
                <p className="text-slate-400 text-sm">Run on {fmtDate(activeRun.run.created_at)} · {activeRun.payments.length} payment{activeRun.payments.length !== 1 ? 's' : ''}</p>
              </div>
              <span className="bg-green-900/50 text-green-300 px-3 py-1 rounded-full text-sm font-semibold border border-green-800">
                Total: ${activeRun.run.total_amount.toFixed(2)}
              </span>
            </div>
            {activeRun.payments.length === 0 ? (
              <div className="px-6 py-12 text-center text-slate-400">
                <svg className="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p>No payments found for this week.</p>
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full">
                  <thead>
                    <tr className="bg-slate-700/50">
                      <th className="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Name</th>
                      <th className="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Amount</th>
                      <th className="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Date</th>
                      <th className="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">App</th>
                    </tr>
                  </thead>
                  <tbody>
                    {activeRun.payments.map((p, i) => (
                      <tr key={i} className={i % 2 === 0 ? 'bg-slate-800' : 'bg-slate-750'}>
                        <td className="px-6 py-3.5 text-white font-medium">{p.name || '—'}</td>
                        <td className="px-6 py-3.5 text-green-400 font-bold">${Number(p.amount || 0).toFixed(2)}</td>
                        <td className="px-6 py-3.5 text-slate-300">{p.date || '—'}</td>
                        <td className="px-6 py-3.5">
                          <span className={`px-2.5 py-1 rounded-full text-xs font-semibold ${
                            p.payment_app === 'Cash App' ? 'bg-emerald-900/60 text-emerald-300 border border-emerald-800' :
                            p.payment_app === 'Zelle' ? 'bg-purple-900/60 text-purple-300 border border-purple-800' :
                            'bg-slate-700 text-slate-400'
                          }`}>
                            {p.payment_app || 'Unknown'}
                          </span>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                  <tfoot>
                    <tr className="bg-slate-700/60 border-t border-slate-700">
                      <td className="px-6 py-3.5 text-slate-300 font-semibold">Total</td>
                      <td className="px-6 py-3.5 text-green-400 font-bold text-lg">
                        ${activeRun.payments.reduce((s, p) => s + Number(p.amount || 0), 0).toFixed(2)}
                      </td>
                      <td colSpan={2}></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            )}
          </div>
        )}

        {/* Run History */}
        <div className="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
          <div className="px-6 py-4 border-b border-slate-700">
            <h3 className="text-lg font-bold text-white">Run History</h3>
            <p className="text-slate-400 text-sm">Click any run to view its payments</p>
          </div>
          {runs.length === 0 ? (
            <div className="px-6 py-12 text-center text-slate-400">
              <svg className="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p>No runs yet. Click &quot;Run Report&quot; to get started.</p>
            </div>
          ) : (
            <div className="divide-y divide-slate-700">
              {runs.map(run => (
                <button
                  key={run.id}
                  onClick={() => handleViewRun(run.id)}
                  className="w-full text-left px-6 py-4 hover:bg-slate-700/50 transition-colors flex items-center justify-between group"
                >
                  <div>
                    <p className="text-white font-medium group-hover:text-green-400 transition-colors">
                      {fmtDate(run.created_at)}
                    </p>
                    <p className="text-slate-400 text-sm mt-0.5">
                      {run.payment_count} payment{run.payment_count !== 1 ? 's' : ''}
                      {run.week_start && ` · Week of ${run.week_start}`}
                    </p>
                  </div>
                  <div className="flex items-center gap-3">
                    <span className="text-green-400 font-bold">${Number(run.total_amount).toFixed(2)}</span>
                    <span className={`px-2.5 py-1 rounded-full text-xs font-semibold ${
                      run.status === 'completed' ? 'bg-green-900/50 text-green-300 border border-green-800' :
                      run.status === 'running' ? 'bg-yellow-900/50 text-yellow-300 border border-yellow-800' :
                      'bg-red-900/50 text-red-300 border border-red-800'
                    }`}>
                      {run.status}
                    </span>
                    <svg className="w-4 h-4 text-slate-500 group-hover:text-slate-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                    </svg>
                  </div>
                </button>
              ))}
            </div>
          )}
        </div>
      </main>
    </div>
  )
}
