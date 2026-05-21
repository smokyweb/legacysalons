import {
  Calendar, DollarSign, Users, Clock, ArrowRight, Phone, Plus, MessageSquare, Star, TrendingUp
} from 'lucide-react';
import Link from 'next/link';
import Image from 'next/image';
import StatsCard from '@/components/StatsCard';
import Badge from '@/components/Badge';
import { dashboardStats, bookings, clients, voiceCalls } from '@/lib/mockData';

export default function DashboardPage() {
  const today = new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
  const upcomingToday = bookings.filter(b => b.status === 'upcoming').slice(0, 4);

  return (
    <div className="p-6 lg:p-8 max-w-7xl mx-auto">
      {/* Header */}
      <div className="flex items-start justify-between mb-8">
        <div>
          <h1 className="text-2xl font-black text-slate-900 mb-1">Good morning, Maya ✨</h1>
          <p className="text-slate-500 text-sm">{today}</p>
        </div>
        <div className="flex items-center gap-3">
          <Link href="/dashboard/bookings" className="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-slate-700 text-sm font-medium hover:border-blue-300 hover:bg-blue-50 transition-all">
            <Calendar className="w-4 h-4" />
            New Booking
          </Link>
          <Link href="/dashboard/messages" className="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 text-slate-700 text-sm font-medium hover:border-blue-300 hover:bg-blue-50 transition-all">
            <MessageSquare className="w-4 h-4" />
            Message
          </Link>
          <Link href="/dashboard/calendar" className="flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-pink-700 transition-all shadow-sm">
            <Plus className="w-4 h-4" />
            Quick Add
          </Link>
        </div>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <StatsCard title="Today's Appointments" value={dashboardStats.todayAppointments} change="2 more than avg" changePositive={true} icon={Calendar} />
        <StatsCard title="Revenue This Week" value={`$${dashboardStats.weekRevenue.toLocaleString()}`} change="12%" changePositive={true} icon={DollarSign} />
        <StatsCard title="New Clients This Month" value={dashboardStats.newClientsMonth} change="5 from last mo" changePositive={true} icon={Users} />
        <StatsCard title="Active Bookings" value={dashboardStats.activeBookings} icon={Clock} gradient />
      </div>

      {/* Two column layout */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {/* Today's schedule */}
        <div className="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <div className="flex items-center justify-between mb-5">
            <h2 className="font-bold text-slate-900">Today&apos;s Schedule</h2>
            <Link href="/dashboard/calendar" className="text-sm text-blue-600 font-medium hover:text-blue-700 flex items-center gap-1">
              View Calendar <ArrowRight className="w-3.5 h-3.5" />
            </Link>
          </div>
          <div className="space-y-3">
            {upcomingToday.map(booking => (
              <div key={booking.id} className="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 transition-colors group">
                <div className="w-12 text-center flex-shrink-0">
                  <p className="text-xs text-slate-400 font-medium">{booking.time.split(' ')[1]}</p>
                  <p className="text-sm font-bold text-slate-900">{booking.time.split(':')[0]}{booking.time.includes('AM') ? 'AM' : 'PM'}</p>
                </div>
                <div className="w-px h-10 bg-gradient-to-b from-blue-500 to-pink-500 rounded-full flex-shrink-0" />
                <div className="flex-1 min-w-0">
                  <p className="font-semibold text-slate-900 truncate">{booking.clientName}</p>
                  <p className="text-sm text-slate-500 truncate">{booking.service}</p>
                </div>
                <div className="flex items-center gap-3 flex-shrink-0">
                  <span className="text-sm font-bold text-slate-900">${booking.amount}</span>
                  <Badge status={booking.status} />
                </div>
              </div>
            ))}
          </div>
          <div className="mt-4 pt-4 border-t border-gray-100">
            <Link href="/dashboard/bookings" className="text-sm text-blue-600 font-medium hover:text-blue-700 flex items-center gap-1 justify-center">
              View all {bookings.length} bookings <ArrowRight className="w-3.5 h-3.5" />
            </Link>
          </div>
        </div>

        {/* AI Voice card */}
        <div className="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-5 relative overflow-hidden">
          <div className="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-pink-600/10" />
          <div className="relative">
            <div className="flex items-center gap-3 mb-4">
              <div className="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 flex items-center justify-center pulse-glow">
                <Phone className="w-5 h-5 text-white" />
              </div>
              <div>
                <p className="font-semibold text-white text-sm">AI Receptionist</p>
                <div className="flex items-center gap-1">
                  <div className="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse" />
                  <span className="text-xs text-green-400">Active</span>
                </div>
              </div>
            </div>
            <div className="grid grid-cols-3 gap-3 mb-4">
              <div className="bg-white/10 rounded-xl p-2.5 text-center">
                <p className="text-2xl font-black text-white">{dashboardStats.voiceCallsToday}</p>
                <p className="text-xs text-slate-400">Calls Today</p>
              </div>
              <div className="bg-white/10 rounded-xl p-2.5 text-center">
                <p className="text-2xl font-black text-white">{dashboardStats.voiceBookingsToday}</p>
                <p className="text-xs text-slate-400">Booked</p>
              </div>
              <div className="bg-white/10 rounded-xl p-2.5 text-center">
                <p className="text-2xl font-black text-white">100%</p>
                <p className="text-xs text-slate-400">Uptime</p>
              </div>
            </div>
            <div className="flex items-end gap-0.5 justify-center h-8 mb-4">
              {[3, 6, 9, 5, 8, 4, 7, 5, 9, 3, 6, 8, 4].map((h, i) => (
                <div key={i} className="w-1 bg-gradient-to-t from-blue-500 to-pink-400 rounded-full waveform-bar" style={{ height: `${h * 2 + 6}px`, animationDelay: `${i * 0.08}s` }} />
              ))}
            </div>
            <Link href="/dashboard/voice-agent" className="block text-center py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-medium transition-all">
              Manage AI Agent
            </Link>
          </div>
        </div>
      </div>

      {/* Bottom row */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Recent bookings table */}
        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <div className="flex items-center justify-between mb-5">
            <h2 className="font-bold text-slate-900">Recent Bookings</h2>
            <Link href="/dashboard/bookings" className="text-sm text-blue-600 font-medium hover:text-blue-700 flex items-center gap-1">
              All <ArrowRight className="w-3.5 h-3.5" />
            </Link>
          </div>
          <div className="space-y-2">
            {bookings.slice(0, 5).map(booking => (
              <div key={booking.id} className="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div className="flex items-center gap-3">
                  <div className="w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-pink-100 flex items-center justify-center text-xs font-bold text-blue-600 flex-shrink-0">
                    {booking.clientName[0]}
                  </div>
                  <div>
                    <p className="text-sm font-semibold text-slate-900">{booking.clientName}</p>
                    <p className="text-xs text-slate-400">{booking.service}</p>
                  </div>
                </div>
                <div className="flex items-center gap-3">
                  <span className="text-sm font-bold text-slate-900">${booking.amount}</span>
                  <Badge status={booking.status} />
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Top clients */}
        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <div className="flex items-center justify-between mb-5">
            <h2 className="font-bold text-slate-900">Top Clients</h2>
            <Link href="/dashboard/clients" className="text-sm text-blue-600 font-medium hover:text-blue-700 flex items-center gap-1">
              All clients <ArrowRight className="w-3.5 h-3.5" />
            </Link>
          </div>
          <div className="space-y-3">
            {clients.slice(0, 5).map(client => (
              <div key={client.id} className="flex items-center gap-3">
                <div className="w-9 h-9 rounded-full overflow-hidden relative flex-shrink-0">
                  <Image src={client.avatar} alt={client.name} fill className="object-cover" />
                </div>
                <div className="flex-1 min-w-0">
                  <p className="text-sm font-semibold text-slate-900 truncate">{client.name}</p>
                  <p className="text-xs text-slate-400">{client.visitCount} visits</p>
                </div>
                <div className="text-right flex-shrink-0">
                  <p className="text-sm font-bold text-slate-900">${client.totalSpent.toLocaleString()}</p>
                  <div className="flex gap-1 justify-end">
                    {client.tags.slice(0, 1).map(tag => (
                      <Badge key={tag} status={tag} />
                    ))}
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
