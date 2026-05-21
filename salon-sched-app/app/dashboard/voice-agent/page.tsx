'use client';
import { useState } from 'react';
import { Phone, Mic, PhoneCall, Settings, Save } from 'lucide-react';
import Badge from '@/components/Badge';
import { voiceCalls, dashboardStats } from '@/lib/mockData';

const bars = [3, 6, 9, 5, 8, 4, 7, 5, 9, 3, 6, 8, 4, 7, 5, 9, 5, 7, 4, 8];

export default function VoiceAgentPage() {
  const [isActive, setIsActive] = useState(true);
  const [greeting, setGreeting] = useState("Hi! You've reached Maya's studio. I'm your AI assistant — I can help you book, reschedule, or answer questions about our services. How can I help you today?");

  return (
    <div className="p-6 lg:p-8">
      <div className="flex items-start justify-between mb-6">
        <div>
          <h1 className="text-2xl font-black text-slate-900">AI Voice Agent</h1>
          <p className="text-sm text-slate-500 mt-0.5">Your 24/7 AI receptionist — never misses a call</p>
        </div>
        <div className="flex items-center gap-3">
          <div className={`flex items-center gap-2 px-4 py-2 rounded-xl border-2 ${isActive ? 'border-green-300 bg-green-50' : 'border-gray-200 bg-gray-50'}`}>
            <div className={`w-2.5 h-2.5 rounded-full ${isActive ? 'bg-green-500 animate-pulse' : 'bg-gray-400'}`} />
            <span className={`text-sm font-semibold ${isActive ? 'text-green-700' : 'text-gray-600'}`}>{isActive ? 'Active' : 'Inactive'}</span>
          </div>
          <button
            onClick={() => setIsActive(!isActive)}
            className={`px-4 py-2 rounded-xl text-sm font-semibold transition-all ${isActive ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-gradient-to-r from-blue-600 to-pink-600 text-white hover:from-blue-700 hover:to-pink-700 shadow-sm'}`}
          >
            {isActive ? 'Pause Agent' : 'Activate Agent'}
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {/* Stats */}
        <div className="lg:col-span-2 grid grid-cols-3 gap-4">
          <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
            <PhoneCall className="w-6 h-6 text-blue-500 mx-auto mb-2" />
            <p className="text-3xl font-black text-slate-900">{dashboardStats.voiceCallsToday}</p>
            <p className="text-sm text-slate-500">Calls Today</p>
          </div>
          <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
            <Phone className="w-6 h-6 text-green-500 mx-auto mb-2" />
            <p className="text-3xl font-black text-slate-900">{dashboardStats.voiceBookingsToday}</p>
            <p className="text-sm text-slate-500">Bookings Made</p>
          </div>
          <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
            <Mic className="w-6 h-6 text-pink-500 mx-auto mb-2" />
            <p className="text-3xl font-black text-slate-900">63%</p>
            <p className="text-sm text-slate-500">Booking Rate</p>
          </div>
        </div>

        {/* Waveform widget */}
        <div className="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-5 relative overflow-hidden">
          <div className="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-pink-600/10" />
          <div className="relative">
            <div className="flex items-center gap-2 mb-3">
              <div className="w-8 h-8 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 flex items-center justify-center">
                <Mic className="w-4 h-4 text-white" />
              </div>
              <div>
                <p className="text-white text-sm font-semibold">Live Waveform</p>
                <p className="text-slate-400 text-xs">{isActive ? 'Listening...' : 'Offline'}</p>
              </div>
            </div>
            <div className="flex items-end justify-center gap-0.5 h-10 mb-3">
              {bars.map((h, i) => (
                <div
                  key={i}
                  className={`w-1 rounded-full ${isActive ? 'bg-gradient-to-t from-blue-500 to-pink-400 waveform-bar' : 'bg-slate-700'}`}
                  style={{ height: `${isActive ? h * 2 + 4 : 4}px`, animationDelay: `${i * 0.08}s` }}
                />
              ))}
            </div>
            <div className="text-center">
              <p className="text-slate-400 text-xs">Twilio: +1 (855) 456-7890</p>
            </div>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Call log */}
        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <h3 className="font-bold text-slate-900 mb-4">Today&apos;s Call Log</h3>
          <div className="space-y-2">
            {voiceCalls.map(call => (
              <div key={call.id} className="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 transition-colors">
                <div className={`w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 ${
                  call.outcome === 'booked' ? 'bg-green-100' :
                  call.outcome === 'transferred' ? 'bg-purple-100' :
                  call.outcome === 'missed' ? 'bg-gray-100' : 'bg-blue-100'
                }`}>
                  <Phone className={`w-3.5 h-3.5 ${
                    call.outcome === 'booked' ? 'text-green-600' :
                    call.outcome === 'transferred' ? 'text-purple-600' :
                    call.outcome === 'missed' ? 'text-gray-500' : 'text-blue-600'
                  }`} />
                </div>
                <div className="flex-1 min-w-0">
                  <div className="flex items-center gap-2">
                    <p className="text-sm font-medium text-slate-900 truncate">{call.caller}</p>
                    <span className="text-xs text-slate-400">{call.time}</span>
                  </div>
                  <p className="text-xs text-slate-500 truncate">{call.intent}</p>
                </div>
                <div className="flex items-center gap-2 flex-shrink-0">
                  <span className="text-xs text-slate-400">{call.duration}</span>
                  <Badge status={call.outcome} />
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* AI Config */}
        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <div className="flex items-center gap-2 mb-5">
            <Settings className="w-5 h-5 text-slate-600" />
            <h3 className="font-bold text-slate-900">AI Configuration</h3>
          </div>
          <div className="space-y-4">
            <div>
              <label className="text-sm font-medium text-slate-700 mb-2 block">Custom Greeting</label>
              <textarea
                value={greeting}
                onChange={e => setGreeting(e.target.value)}
                rows={3}
                className="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none text-sm resize-none"
              />
            </div>
            <div>
              <label className="text-sm font-medium text-slate-700 mb-2 block">Business Hours</label>
              <div className="grid grid-cols-2 gap-2">
                <input type="time" defaultValue="09:00" className="w-full px-3 py-2 rounded-xl border border-gray-200 focus:border-blue-400 outline-none text-sm" />
                <input type="time" defaultValue="19:00" className="w-full px-3 py-2 rounded-xl border border-gray-200 focus:border-blue-400 outline-none text-sm" />
              </div>
            </div>
            <div>
              <label className="text-sm font-medium text-slate-700 mb-2 block">Services to Offer</label>
              <div className="flex flex-wrap gap-2">
                {['Haircut', 'Balayage', 'Highlights', 'Deep Condition', 'Blowout'].map(s => (
                  <span key={s} className="px-3 py-1 rounded-xl text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 cursor-pointer hover:bg-blue-100 transition-colors">{s}</span>
                ))}
              </div>
            </div>
            <button className="w-full py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-pink-700 transition-all flex items-center justify-center gap-2 shadow-sm">
              <Save className="w-4 h-4" />
              Save Configuration
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
