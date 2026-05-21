'use client';
import { useState } from 'react';
import Image from 'next/image';
import { Search, Plus, X, Phone, Mail, Calendar } from 'lucide-react';
import Badge from '@/components/Badge';
import { clients, Client } from '@/lib/mockData';

export default function ClientsPage() {
  const [search, setSearch] = useState('');
  const [selectedClient, setSelectedClient] = useState<Client | null>(null);

  const filtered = clients.filter(c =>
    !search ||
    c.name.toLowerCase().includes(search.toLowerCase()) ||
    c.email.toLowerCase().includes(search.toLowerCase()) ||
    c.phone.includes(search)
  );

  return (
    <div className="p-6 lg:p-8">
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-2xl font-black text-slate-900">Clients & CRM</h1>
          <p className="text-sm text-slate-500 mt-0.5">{clients.length} clients in your book</p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-pink-700 transition-all shadow-sm">
          <Plus className="w-4 h-4" />
          Add Client
        </button>
      </div>

      <div className="flex flex-col lg:flex-row gap-5">
        {/* Client list */}
        <div className="flex-1">
          <div className="bg-white rounded-2xl border border-gray-100 shadow-sm mb-4 p-3">
            <div className="relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
              <input
                type="text"
                placeholder="Search clients..."
                value={search}
                onChange={e => setSearch(e.target.value)}
                className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none text-sm"
              />
            </div>
          </div>

          <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            {filtered.map((client, i) => (
              <button
                key={client.id}
                onClick={() => setSelectedClient(client)}
                className={`w-full flex items-center gap-4 px-5 py-4 hover:bg-slate-50 transition-colors text-left ${i < filtered.length - 1 ? 'border-b border-gray-50' : ''} ${selectedClient?.id === client.id ? 'bg-blue-50/50 border-l-4 border-blue-500' : ''}`}
              >
                <div className="w-11 h-11 rounded-full overflow-hidden relative flex-shrink-0">
                  <Image src={client.avatar} alt={client.name} fill className="object-cover" />
                </div>
                <div className="flex-1 min-w-0">
                  <div className="flex items-center gap-2 flex-wrap">
                    <p className="font-semibold text-slate-900">{client.name}</p>
                    <div className="flex gap-1">
                      {client.tags.slice(0, 2).map(tag => <Badge key={tag} status={tag} />)}
                    </div>
                  </div>
                  <p className="text-xs text-slate-400 mt-0.5">Last visit: {client.lastVisit} · {client.visitCount} visits</p>
                </div>
                <div className="text-right flex-shrink-0">
                  <p className="text-sm font-bold text-slate-900">${client.totalSpent.toLocaleString()}</p>
                  <p className="text-xs text-slate-400">total spent</p>
                </div>
              </button>
            ))}
          </div>
        </div>

        {/* Client profile panel */}
        {selectedClient && (
          <div className="lg:w-80 bg-white rounded-2xl border border-gray-100 shadow-sm p-5 h-fit sticky top-4">
            <div className="flex items-center justify-between mb-5">
              <h3 className="font-bold text-slate-900">Client Profile</h3>
              <button onClick={() => setSelectedClient(null)} className="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                <X className="w-4 h-4 text-gray-500" />
              </button>
            </div>
            <div className="text-center mb-5">
              <div className="w-16 h-16 rounded-full overflow-hidden relative mx-auto mb-3">
                <Image src={selectedClient.avatar} alt={selectedClient.name} fill className="object-cover" />
              </div>
              <h4 className="font-bold text-slate-900">{selectedClient.name}</h4>
              <div className="flex items-center justify-center gap-1 mt-1">
                {selectedClient.tags.map(tag => <Badge key={tag} status={tag} />)}
              </div>
            </div>

            <div className="space-y-3 mb-5">
              <div className="flex items-center gap-2 text-sm text-slate-600">
                <Phone className="w-4 h-4 text-slate-400 flex-shrink-0" />
                {selectedClient.phone}
              </div>
              <div className="flex items-center gap-2 text-sm text-slate-600">
                <Mail className="w-4 h-4 text-slate-400 flex-shrink-0" />
                {selectedClient.email}
              </div>
              <div className="flex items-center gap-2 text-sm text-slate-600">
                <Calendar className="w-4 h-4 text-slate-400 flex-shrink-0" />
                Last visit: {selectedClient.lastVisit}
              </div>
            </div>

            <div className="grid grid-cols-3 gap-2 mb-5">
              <div className="bg-slate-50 rounded-xl p-3 text-center">
                <p className="text-lg font-black text-slate-900">{selectedClient.visitCount}</p>
                <p className="text-xs text-slate-400">Visits</p>
              </div>
              <div className="bg-slate-50 rounded-xl p-3 text-center">
                <p className="text-lg font-black text-blue-600">${selectedClient.totalSpent.toLocaleString()}</p>
                <p className="text-xs text-slate-400">Spent</p>
              </div>
              <div className="bg-slate-50 rounded-xl p-3 text-center">
                <p className="text-lg font-black text-green-600">A+</p>
                <p className="text-xs text-slate-400">Rating</p>
              </div>
            </div>

            {selectedClient.notes && (
              <div className="bg-amber-50 rounded-xl p-3 mb-4">
                <p className="text-xs font-semibold text-amber-700 mb-1">Notes</p>
                <p className="text-xs text-amber-600">{selectedClient.notes}</p>
              </div>
            )}

            <div className="flex gap-2">
              <button className="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-xs font-semibold hover:from-blue-700 hover:to-pink-700 transition-all">
                Book Appointment
              </button>
              <button className="flex-1 py-2.5 rounded-xl border border-gray-200 text-slate-700 text-xs font-semibold hover:border-blue-300 hover:bg-blue-50 transition-all">
                Send Message
              </button>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
