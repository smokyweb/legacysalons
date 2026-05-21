'use client';
import { useState } from 'react';
import { Search, Filter, Download, Plus } from 'lucide-react';
import Badge from '@/components/Badge';
import { bookings } from '@/lib/mockData';

const statusFilters = ['All', 'upcoming', 'completed', 'cancelled', 'no-show'];

export default function BookingsPage() {
  const [search, setSearch] = useState('');
  const [activeFilter, setActiveFilter] = useState('All');

  const filtered = bookings.filter(b => {
    const matchesSearch = !search ||
      b.clientName.toLowerCase().includes(search.toLowerCase()) ||
      b.service.toLowerCase().includes(search.toLowerCase());
    const matchesFilter = activeFilter === 'All' || b.status === activeFilter;
    return matchesSearch && matchesFilter;
  });

  return (
    <div className="p-6 lg:p-8">
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-2xl font-black text-slate-900">Bookings</h1>
          <p className="text-sm text-slate-500 mt-0.5">{bookings.length} total appointments</p>
        </div>
        <div className="flex items-center gap-2">
          <button className="flex items-center gap-2 px-3 py-2 rounded-xl border border-gray-200 text-slate-600 text-sm font-medium hover:border-blue-300 transition-all">
            <Download className="w-4 h-4" />
            Export
          </button>
          <button className="flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-pink-700 transition-all shadow-sm">
            <Plus className="w-4 h-4" />
            New Booking
          </button>
        </div>
      </div>

      {/* Filters */}
      <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
        <div className="flex flex-col sm:flex-row gap-3">
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            <input
              type="text"
              placeholder="Search clients or services..."
              value={search}
              onChange={e => setSearch(e.target.value)}
              className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none text-sm"
            />
          </div>
          <div className="flex items-center gap-2 flex-wrap">
            {statusFilters.map(f => (
              <button
                key={f}
                onClick={() => setActiveFilter(f)}
                className={`px-3 py-2 rounded-xl text-xs font-medium transition-all capitalize ${activeFilter === f ? 'bg-gradient-to-r from-blue-600 to-pink-600 text-white shadow-sm' : 'bg-gray-100 text-slate-600 hover:bg-gray-200'}`}
              >
                {f}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Table */}
      <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="bg-slate-50 border-b border-gray-100">
                <th className="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">Client</th>
                <th className="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">Service</th>
                <th className="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden sm:table-cell">Date & Time</th>
                <th className="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                <th className="text-right px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">Amount</th>
                <th className="text-right px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide hidden md:table-cell">Actions</th>
              </tr>
            </thead>
            <tbody>
              {filtered.map((booking, i) => (
                <tr key={booking.id} className={`border-b border-gray-50 hover:bg-slate-50/50 transition-colors ${i % 2 === 0 ? 'bg-white' : 'bg-white'}`}>
                  <td className="px-5 py-4">
                    <div className="flex items-center gap-3">
                      <div className="w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-pink-100 flex items-center justify-center text-xs font-bold text-blue-600 flex-shrink-0">
                        {booking.clientName[0]}
                      </div>
                      <div>
                        <p className="font-semibold text-slate-900 text-sm">{booking.clientName}</p>
                        <p className="text-xs text-slate-400">{booking.phone}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-5 py-4">
                    <p className="text-sm font-medium text-slate-700">{booking.service}</p>
                  </td>
                  <td className="px-5 py-4 hidden sm:table-cell">
                    <p className="text-sm text-slate-700">{booking.date}</p>
                    <p className="text-xs text-slate-400">{booking.time}</p>
                  </td>
                  <td className="px-5 py-4">
                    <Badge status={booking.status} />
                  </td>
                  <td className="px-5 py-4 text-right">
                    <span className="text-sm font-bold text-slate-900">${booking.amount}</span>
                  </td>
                  <td className="px-5 py-4 text-right hidden md:table-cell">
                    <div className="flex items-center justify-end gap-1">
                      <button className="px-2.5 py-1 rounded-lg text-xs font-medium text-blue-600 hover:bg-blue-50 transition-colors">View</button>
                      <button className="px-2.5 py-1 rounded-lg text-xs font-medium text-slate-500 hover:bg-slate-100 transition-colors">Edit</button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
          {filtered.length === 0 && (
            <div className="text-center py-12">
              <p className="text-slate-400">No bookings match your filter.</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
