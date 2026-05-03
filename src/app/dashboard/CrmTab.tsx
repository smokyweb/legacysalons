'use client'
import { useState, useEffect, useCallback } from 'react'

type Contact = {
  id: number; created_at: number; updated_at: number; first_name: string; last_name: string | null;
  email: string | null; phone: string | null; company: string | null; stage: string;
  notes: string | null; assigned_to: string | null; deal_value: number; last_contacted: number | null;
  likely_move_date: string | null; budget: string | null; speciality: string | null; lead_source: string | null; lead_date: string | null;
}
type Activity = { id: number; created_at: number; type: string; content: string; status: string }

const STAGES = ['New Lead', 'Contacted', 'Qualified', 'Proposal Sent', 'Active Client', 'Lost']
const STAGE_COLORS: Record<string, string> = {
  'New Lead': 'bg-blue-900/60 text-blue-300 border-blue-800',
  'Contacted': 'bg-yellow-900/60 text-yellow-300 border-yellow-800',
  'Qualified': 'bg-purple-900/60 text-purple-300 border-purple-800',
  'Proposal Sent': 'bg-orange-900/60 text-orange-300 border-orange-800',
  'Active Client': 'bg-green-900/60 text-green-300 border-green-800',
  'Lost': 'bg-slate-700 text-slate-400 border-slate-600',
}

function fmtDate(ts: number) {
  return new Date(ts).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}
function initials(c: Contact) {
  return `${c.first_name[0] || ''}${c.last_name?.[0] || ''}`.toUpperCase()
}
function fullName(c: Contact) { return `${c.first_name} ${c.last_name || ''}`.trim() }

export default function CrmTab({ role }: { role: string }) {
  const [contacts, setContacts] = useState<Contact[]>([])
  const [view, setView] = useState<'list' | 'kanban'>('list')
  const [search, setSearch] = useState('')
  const [stageFilter, setStageFilter] = useState('all')
  const [selectedContact, setSelectedContact] = useState<Contact | null>(null)
  const [activity, setActivity] = useState<Activity[]>([])
  const [showAddContact, setShowAddContact] = useState(false)
  const [showMessage, setShowMessage] = useState<{ type: 'sms' | 'email' } | null>(null)
  const [msgContent, setMsgContent] = useState('')
  const [msgSubject, setMsgSubject] = useState('')
  const [msgSending, setMsgSending] = useState(false)
  const [msgStatus, setMsgStatus] = useState('')
  const [newContact, setNewContact] = useState({ first_name: '', last_name: '', email: '', phone: '', company: '', stage: 'New Lead', notes: '', deal_value: '', likely_move_date: '', budget: '', speciality: '', lead_source: '' })
  const [saving, setSaving] = useState(false)
  const [editingContact, setEditingContact] = useState(false)
  const [editFields, setEditFields] = useState<Partial<Contact & { deal_value: string }>>({})

  const loadContacts = useCallback(async () => {
    const res = await fetch('/api/contacts')
    if (res.ok) setContacts(await res.json())
  }, [])

  useEffect(() => { loadContacts() }, [loadContacts])

  async function openContact(c: Contact) {
    setSelectedContact(c)
    const res = await fetch(`/api/contacts/${c.id}`)
    if (res.ok) {
      const data = await res.json()
      setActivity(data.activity)
      setSelectedContact(data.contact)
    }
  }

  async function updateStage(id: number, stage: string) {
    await fetch(`/api/contacts/${id}`, { method: 'PATCH', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ stage }) })
    loadContacts()
    if (selectedContact?.id === id) setSelectedContact(c => c ? { ...c, stage } : null)
  }

  async function deleteContact(id: number) {
    if (!confirm('Delete this contact?')) return
    await fetch(`/api/contacts/${id}`, { method: 'DELETE' })
    setSelectedContact(null)
    loadContacts()
  }

  async function addContact() {
    if (!newContact.first_name) return
    setSaving(true)
    const res = await fetch('/api/contacts', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ ...newContact, deal_value: parseFloat(newContact.deal_value) || 0 }) })
    if (res.ok) { setShowAddContact(false); setNewContact({ first_name: '', last_name: '', email: '', phone: '', company: '', stage: 'New Lead', notes: '', deal_value: '', likely_move_date: '', budget: '', speciality: '', lead_source: '' }); loadContacts() }
    setSaving(false)
  }

  async function sendMessage() {
    if (!selectedContact || !showMessage || !msgContent) return
    setMsgSending(true)
    setMsgStatus('')
    const res = await fetch(`/api/contacts/${selectedContact.id}/message`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ type: showMessage.type, content: msgContent, subject: msgSubject }),
    })
    const data = await res.json()
    setMsgStatus(data.success ? '✅ Sent successfully!' : '❌ Failed to send')
    if (data.success) {
      setMsgContent(''); setMsgSubject('')
      const actRes = await fetch(`/api/contacts/${selectedContact.id}`)
      if (actRes.ok) { const d = await actRes.json(); setActivity(d.activity) }
    }
    setMsgSending(false)
  }

  async function saveEdit() {
    if (!selectedContact) return
    setSaving(true)
    const res = await fetch(`/api/contacts/${selectedContact.id}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(editFields),
    })
    if (res.ok) {
      const updated = await res.json()
      setSelectedContact(updated)
      setEditingContact(false)
      setEditFields({})
      loadContacts()
    }
    setSaving(false)
  }

  const filtered = contacts.filter(c => {
    const q = search.toLowerCase()
    const matchSearch = !q || fullName(c).toLowerCase().includes(q) || c.email?.toLowerCase().includes(q) || c.phone?.includes(q) || c.company?.toLowerCase().includes(q)
    const matchStage = stageFilter === 'all' || c.stage === stageFilter
    return matchSearch && matchStage
  })

  const totalValue = filtered.reduce((s, c) => s + Number(c.deal_value || 0), 0)

  return (
    <div className="max-w-7xl mx-auto px-6 py-8">
      {/* Stats */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        {[
          { label: 'Total Contacts', value: contacts.length, color: 'text-blue-400' },
          { label: 'Active Clients', value: contacts.filter(c => c.stage === 'Active Client').length, color: 'text-green-400' },
          { label: 'New Leads', value: contacts.filter(c => c.stage === 'New Lead').length, color: 'text-yellow-400' },
          { label: 'Pipeline Value', value: `$${contacts.reduce((s,c) => s + Number(c.deal_value||0), 0).toLocaleString()}`, color: 'text-purple-400' },
        ].map(s => (
          <div key={s.label} className="bg-slate-800 rounded-xl border border-slate-700 px-5 py-4">
            <p className="text-slate-400 text-xs font-medium uppercase tracking-wider">{s.label}</p>
            <p className={`text-2xl font-bold mt-1 ${s.color}`}>{s.value}</p>
          </div>
        ))}
      </div>

      {/* Toolbar */}
      <div className="flex flex-wrap items-center gap-3 mb-6">
        <div className="relative flex-1 min-w-48">
          <svg className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
          <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Search contacts..." className="w-full pl-9 pr-4 py-2.5 bg-slate-700/60 border border-slate-600/50 rounded-xl text-white placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>
        <select value={stageFilter} onChange={e => setStageFilter(e.target.value)} className="px-3 py-2.5 bg-slate-700/60 border border-slate-600/50 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
          <option value="all">All Stages</option>
          {STAGES.map(s => <option key={s} value={s}>{s}</option>)}
        </select>
        <div className="flex items-center gap-1 bg-slate-700/50 rounded-xl p-1">
          <button onClick={() => setView('list')} className={`px-3 py-1.5 rounded-lg text-xs font-semibold transition-all ${view === 'list' ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white'}`}>List</button>
          <button onClick={() => setView('kanban')} className={`px-3 py-1.5 rounded-lg text-xs font-semibold transition-all ${view === 'kanban' ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white'}`}>Kanban</button>
        </div>
        <button onClick={() => setShowAddContact(true)} className="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-500 text-white font-semibold text-sm rounded-xl transition-colors">
          <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" /></svg>
          Add Contact
        </button>
      </div>

      {view === 'list' ? (
        /* LIST VIEW */
        <div className="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
          <table className="w-full">
            <thead><tr className="bg-slate-700/50 border-b border-slate-700">
              <th className="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Contact</th>
              <th className="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider hidden md:table-cell">Company</th>
              <th className="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Stage</th>
              <th className="text-left px-6 py-3 text-xs font-semibold text-amber-400 uppercase tracking-wider hidden md:table-cell">Budget</th>
              <th className="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider hidden lg:table-cell">Speciality</th>
              <th className="text-left px-6 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider hidden lg:table-cell">Lead Date</th>
              <th className="px-6 py-3"></th>
            </tr></thead>
            <tbody className="divide-y divide-slate-700/50">
              {filtered.length === 0 ? (
                <tr><td colSpan={6} className="px-6 py-12 text-center text-slate-400">No contacts found. Add one to get started!</td></tr>
              ) : filtered.map(c => (
                <tr key={c.id} className="hover:bg-slate-700/30 transition-colors cursor-pointer" onClick={() => openContact(c)}>
                  <td className="px-6 py-4">
                    <div className="flex items-center gap-3">
                      <div className="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">{initials(c)}</div>
                      <div>
                        <p className="text-white font-semibold text-sm">{fullName(c)}</p>
                        <p className="text-slate-400 text-xs">{c.email || c.phone || '—'}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-6 py-4 text-slate-300 text-sm hidden md:table-cell">{c.company || '—'}</td>
                  <td className="px-6 py-4">
                    <span className={`px-2.5 py-1 rounded-full text-xs font-semibold border ${STAGE_COLORS[c.stage] || 'bg-slate-700 text-slate-400'}`}>{c.stage}</span>
                  </td>
                  <td className="px-6 py-4 hidden md:table-cell">
                    {c.budget ? <span className="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-900/50 text-amber-300 border border-amber-700">{c.budget}</span> : <span className="text-slate-500 text-sm">—</span>}
                  </td>
                  <td className="px-6 py-4 text-slate-300 text-sm hidden lg:table-cell">{c.speciality || '—'}</td>
                  <td className="px-6 py-4 text-slate-400 text-sm hidden lg:table-cell">{c.lead_date || '—'}</td>
                  <td className="px-6 py-4">
                    <div className="flex items-center gap-2" onClick={e => e.stopPropagation()}>
                      {c.phone && <button onClick={() => { openContact(c); setShowMessage({ type: 'sms' }) }} title="Send SMS" className="p-1.5 text-slate-400 hover:text-green-400 hover:bg-green-900/30 rounded-lg transition-colors">
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                      </button>}
                      {c.email && <button onClick={() => { openContact(c); setShowMessage({ type: 'email' }) }} title="Send Email" className="p-1.5 text-slate-400 hover:text-blue-400 hover:bg-blue-900/30 rounded-lg transition-colors">
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                      </button>}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      ) : (
        /* KANBAN VIEW */
        <div className="flex gap-4 overflow-x-auto pb-4">
          {STAGES.map(stage => {
            const stageCols = filtered.filter(c => c.stage === stage)
            return (
              <div key={stage} className="flex-shrink-0 w-64">
                <div className="flex items-center justify-between mb-3">
                  <span className={`px-2.5 py-1 rounded-full text-xs font-semibold border ${STAGE_COLORS[stage]}`}>{stage}</span>
                  <span className="text-slate-400 text-xs">{stageCols.length}</span>
                </div>
                <div className="space-y-2">
                  {stageCols.map(c => (
                    <div key={c.id} onClick={() => openContact(c)} className="bg-slate-800 border border-slate-700 rounded-xl p-4 cursor-pointer hover:border-slate-500 transition-colors">
                      <div className="flex items-center gap-2 mb-2">
                        <div className="w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-xs font-bold text-white">{initials(c)}</div>
                        <p className="text-white font-semibold text-sm">{fullName(c)}</p>
                      </div>
                      {c.company && <p className="text-slate-400 text-xs mb-1">{c.company}</p>}
                      {c.deal_value ? <p className="text-green-400 text-xs font-semibold">${Number(c.deal_value).toLocaleString()}</p> : null}
                    </div>
                  ))}
                </div>
              </div>
            )
          })}
        </div>
      )}

      {/* Contact Detail Modal */}
      {selectedContact && (
        <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-start justify-center p-4 pt-16 overflow-y-auto" onClick={() => { setSelectedContact(null); setShowMessage(null); setMsgStatus('') }}>
          <div className="bg-slate-800 rounded-2xl border border-slate-700 w-full max-w-2xl" onClick={e => e.stopPropagation()}>
            {/* Header */}
            <div className="px-6 py-5 border-b border-slate-700 flex items-start justify-between">
              <div className="flex items-center gap-4">
                <div className="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-xl font-bold text-white">{initials(selectedContact)}</div>
                <div>
                  <h2 className="text-xl font-bold text-white">{fullName(selectedContact)}</h2>
                  {selectedContact.company && <p className="text-slate-400 text-sm">{selectedContact.company}</p>}
                </div>
              </div>
              <button onClick={() => { setSelectedContact(null); setShowMessage(null); setMsgStatus('') }} className="text-slate-400 hover:text-white p-1">
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
              </button>
            </div>

            <div className="px-6 py-5 space-y-5">
              {/* Contact Info */}
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <p className="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Email</p>
                  <p className="text-white text-sm">{selectedContact.email || '—'}</p>
                </div>
                <div>
                  <p className="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Phone</p>
                  <p className="text-white text-sm">{selectedContact.phone || '—'}</p>
                </div>
                <div>
                  <p className="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Stage</p>
                  <select value={selectedContact.stage} onChange={e => updateStage(selectedContact.id, e.target.value)}
                    className="bg-slate-700 border border-slate-600 rounded-lg text-white text-sm px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-green-500">
                    {STAGES.map(s => <option key={s} value={s}>{s}</option>)}
                  </select>
                </div>
                <div>
                  <p className="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Deal Value</p>
                  <p className="text-green-400 font-semibold text-sm">{selectedContact.deal_value ? `$${Number(selectedContact.deal_value).toLocaleString()}` : '—'}</p>
                </div>
              </div>

              {/* Extended fields grid */}
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <p className="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Likely Move Date</p>
                  <p className="text-white text-sm">{selectedContact.likely_move_date || '—'}</p>
                </div>
                <div>
                  <p className="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Budget</p>
                  {selectedContact.budget ? (
                    <span className="px-3 py-1.5 rounded-lg text-sm font-bold bg-amber-900/50 text-amber-300 border border-amber-700 inline-block">{selectedContact.budget}</span>
                  ) : <p className="text-slate-500 text-sm">Not specified</p>}
                </div>
                <div>
                  <p className="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Speciality</p>
                  <p className="text-white text-sm">{selectedContact.speciality || '—'}</p>
                </div>
                <div>
                  <p className="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Lead Source</p>
                  <p className="text-slate-400 text-sm">{selectedContact.lead_source || '—'}</p>
                </div>
                <div>
                  <p className="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Lead Date</p>
                  <p className="text-white text-sm font-medium">{selectedContact.lead_date || '—'}</p>
                </div>
              </div>
              {selectedContact.notes && (
                <div>
                  <p className="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Notes</p>
                  <p className="text-slate-300 text-sm bg-slate-700/40 rounded-lg px-3 py-2">{selectedContact.notes}</p>
                </div>
              )}

              {/* Action Buttons */}
              <div className="flex gap-3 flex-wrap">
                <button onClick={() => { setEditingContact(true); setEditFields({ budget: selectedContact.budget || '', speciality: selectedContact.speciality || '', phone: selectedContact.phone || '', likely_move_date: selectedContact.likely_move_date || '', notes: selectedContact.notes || '', lead_source: selectedContact.lead_source || '' }) }} className="flex items-center gap-2 px-4 py-2.5 bg-slate-600 hover:bg-slate-500 text-white font-semibold text-sm rounded-xl transition-colors">
                  <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                  Edit
                </button>
                {selectedContact.phone && (
                  <button onClick={() => { setShowMessage({ type: 'sms' }); setMsgStatus('') }} className="flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-500 text-white font-semibold text-sm rounded-xl transition-colors">
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                    Send SMS
                  </button>
                )}
                {selectedContact.email && (
                  <button onClick={() => { setShowMessage({ type: 'email' }); setMsgStatus('') }} className="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm rounded-xl transition-colors">
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Send Email
                  </button>
                )}
                {role === 'admin' && (
                  <button onClick={() => deleteContact(selectedContact.id)} className="ml-auto flex items-center gap-2 px-4 py-2.5 bg-red-900/40 hover:bg-red-900/60 text-red-400 font-semibold text-sm rounded-xl transition-colors border border-red-800/50">
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Delete
                  </button>
                )}
              </div>

              {/* Message Composer */}
              {showMessage && (
                <div className={`rounded-xl border p-4 space-y-3 ${showMessage.type === 'sms' ? 'bg-green-900/20 border-green-800/50' : 'bg-blue-900/20 border-blue-800/50'}`}>
                  <h4 className="font-semibold text-white text-sm">{showMessage.type === 'sms' ? '📱 Send SMS' : '📧 Send Email'} to {fullName(selectedContact)}</h4>
                  {showMessage.type === 'email' && (
                    <input value={msgSubject} onChange={e => setMsgSubject(e.target.value)} placeholder="Subject" className="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                  )}
                  <textarea value={msgContent} onChange={e => setMsgContent(e.target.value)} placeholder={showMessage.type === 'sms' ? 'Type your SMS message...' : 'Type your email message...'} rows={3} className="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 resize-none" />
                  <div className="flex items-center gap-3">
                    <button onClick={sendMessage} disabled={!msgContent || msgSending} className={`flex items-center gap-2 px-4 py-2 font-semibold text-sm rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-white ${showMessage.type === 'sms' ? 'bg-green-600 hover:bg-green-500' : 'bg-blue-600 hover:bg-blue-500'}`}>
                      {msgSending ? 'Sending...' : `Send ${showMessage.type === 'sms' ? 'SMS' : 'Email'}`}
                    </button>
                    <button onClick={() => { setShowMessage(null); setMsgContent(''); setMsgSubject(''); setMsgStatus('') }} className="text-slate-400 hover:text-white text-sm">Cancel</button>
                    {msgStatus && <span className="text-sm">{msgStatus}</span>}
                  </div>
                </div>
              )}

              {/* Inline Edit Form */}
              {editingContact && (
                <div className="bg-slate-700/40 rounded-xl border border-slate-600 p-4 space-y-3">
                  <h4 className="font-semibold text-white text-sm">Edit Contact</h4>
                  <div className="grid grid-cols-2 gap-3">
                    {[
                      { label: 'Phone', key: 'phone', type: 'tel' },
                      { label: 'Budget (e.g. $200/week)', key: 'budget', type: 'text' },
                      { label: 'Speciality', key: 'speciality', type: 'text' },
                      { label: 'Lead Source', key: 'lead_source', type: 'text' },
                      { label: 'Likely Move Date', key: 'likely_move_date', type: 'date' },
                    ].map(f => (
                      <div key={f.key}>
                        <label className="block text-xs font-semibold text-slate-400 mb-1">{f.label}</label>
                        <input type={f.type} value={(editFields as Record<string,string>)[f.key] || ''} onChange={e => setEditFields(p => ({ ...p, [f.key]: e.target.value }))}
                          className="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
                      </div>
                    ))}
                  </div>
                  <div>
                    <label className="block text-xs font-semibold text-slate-400 mb-1">Notes</label>
                    <textarea value={(editFields.notes as string) || ''} onChange={e => setEditFields(p => ({ ...p, notes: e.target.value }))} rows={3}
                      className="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none" />
                  </div>
                  <div className="flex gap-3">
                    <button onClick={saveEdit} disabled={saving} className="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-500 text-white font-semibold text-sm rounded-lg transition-colors disabled:opacity-50">
                      {saving ? 'Saving...' : 'Save Changes'}
                    </button>
                    <button onClick={() => { setEditingContact(false); setEditFields({}) }} className="text-slate-400 hover:text-white text-sm">Cancel</button>
                  </div>
                </div>
              )}

              {/* Activity Feed */}
              {activity.length > 0 && (
                <div>
                  <p className="text-xs text-slate-400 font-medium uppercase tracking-wider mb-3">Activity</p>
                  <div className="space-y-2 max-h-48 overflow-y-auto">
                    {activity.map(a => (
                      <div key={a.id} className="flex items-start gap-3 text-sm">
                        <span className={`mt-0.5 px-2 py-0.5 rounded text-xs font-medium flex-shrink-0 ${a.type === 'sms' ? 'bg-green-900/60 text-green-300' : 'bg-blue-900/60 text-blue-300'}`}>
                          {a.type === 'sms' ? 'SMS' : 'Email'}
                        </span>
                        <div className="flex-1 min-w-0">
                          <p className="text-slate-300 truncate">{a.content}</p>
                          <p className="text-slate-500 text-xs">{fmtDate(a.created_at)}</p>
                        </div>
                        <span className={`text-xs px-1.5 py-0.5 rounded flex-shrink-0 ${a.status === 'sent' ? 'text-green-400' : 'text-red-400'}`}>{a.status}</span>
                      </div>
                    ))}
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>
      )}

      {/* Add Contact Modal */}
      {showAddContact && (
        <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" onClick={() => setShowAddContact(false)}>
          <div className="bg-slate-800 rounded-2xl border border-slate-700 w-full max-w-lg p-6" onClick={e => e.stopPropagation()}>
            <h3 className="text-lg font-bold text-white mb-5">Add New Contact</h3>
            <div className="grid grid-cols-2 gap-4">
              {[
                { label: 'First Name *', key: 'first_name', type: 'text' },
                { label: 'Last Name', key: 'last_name', type: 'text' },
                { label: 'Email', key: 'email', type: 'email' },
                { label: 'Phone', key: 'phone', type: 'tel' },
                { label: 'Company / Salon', key: 'company', type: 'text' },
                { label: 'Deal Value ($)', key: 'deal_value', type: 'number' },
                { label: 'Likely Move Date', key: 'likely_move_date', type: 'date' },
                { label: 'Budget', key: 'budget', type: 'text' },
                { label: 'Speciality', key: 'speciality', type: 'text' },
                { label: 'Lead Source', key: 'lead_source', type: 'text' },
              ].map(f => (
                <div key={f.key}>
                  <label className="block text-xs font-semibold text-slate-400 mb-1.5">{f.label}</label>
                  <input type={f.type} value={(newContact as Record<string,string>)[f.key]} onChange={e => setNewContact(n => ({ ...n, [f.key]: e.target.value }))}
                    className="w-full px-3 py-2.5 bg-slate-700 border border-slate-600 rounded-xl text-white text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
              ))}
            </div>
            <div className="mt-4">
              <label className="block text-xs font-semibold text-slate-400 mb-1.5">Stage</label>
              <select value={newContact.stage} onChange={e => setNewContact(n => ({ ...n, stage: e.target.value }))} className="w-full px-3 py-2.5 bg-slate-700 border border-slate-600 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                {STAGES.map(s => <option key={s} value={s}>{s}</option>)}
              </select>
            </div>
            <div className="mt-4">
              <label className="block text-xs font-semibold text-slate-400 mb-1.5">Notes</label>
              <textarea value={newContact.notes} onChange={e => setNewContact(n => ({ ...n, notes: e.target.value }))} rows={2} className="w-full px-3 py-2.5 bg-slate-700 border border-slate-600 rounded-xl text-white text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500 resize-none" />
            </div>
            <div className="flex gap-3 mt-6">
              <button onClick={addContact} disabled={!newContact.first_name || saving} className="flex-1 py-3 bg-green-600 hover:bg-green-500 disabled:bg-slate-600 text-white font-bold rounded-xl transition-colors">
                {saving ? 'Saving...' : 'Add Contact'}
              </button>
              <button onClick={() => setShowAddContact(false)} className="px-6 py-3 border border-slate-600 text-slate-300 hover:text-white rounded-xl transition-colors">Cancel</button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
