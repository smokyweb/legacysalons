'use client';
import { useState } from 'react';
import { ChevronLeft, ChevronRight, Plus, Clock } from 'lucide-react';

const hours = Array.from({ length: 12 }, (_, i) => i + 8); // 8am-7pm
const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
const dates = [19, 20, 21, 22, 23, 24, 25];

const appointments = [
  { id: 1, day: 0, start: 9, duration: 2, client: 'Sarah M.', service: 'Balayage', color: 'from-blue-500 to-blue-600' },
  { id: 2, day: 0, start: 13, duration: 1, client: 'Jennifer A.', service: 'Haircut', color: 'from-pink-500 to-pink-600' },
  { id: 3, day: 1, start: 10, duration: 1.5, client: 'Emma D.', service: 'Gel Mani', color: 'from-purple-500 to-purple-600' },
  { id: 4, day: 2, start: 9, duration: 1, client: 'Lisa C.', service: 'Facial', color: 'from-green-500 to-green-600' },
  { id: 5, day: 2, start: 15, duration: 2, client: 'Nina W.', service: 'Bridal MU', color: 'from-rose-500 to-rose-600' },
  { id: 6, day: 3, start: 11, duration: 1, client: 'Alex T.', service: 'Massage', color: 'from-teal-500 to-teal-600' },
  { id: 7, day: 4, start: 14, duration: 1, client: 'Rachel G.', service: 'Lash Set', color: 'from-amber-500 to-amber-600' },
  { id: 8, day: 5, start: 10, duration: 2, client: 'Marcus J.', service: 'Fade', color: 'from-indigo-500 to-indigo-600' },
];

export default function CalendarPage() {
  const [currentDay] = useState(2); // today = Wed

  return (
    <div className="p-6 lg:p-8 h-full">
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-2xl font-black text-slate-900">Calendar</h1>
          <p className="text-sm text-slate-500">Week of May 19 – 25, 2026</p>
        </div>
        <div className="flex items-center gap-2">
          <div className="flex items-center border border-gray-200 rounded-xl overflow-hidden">
            <button className="p-2 hover:bg-slate-50 transition-colors"><ChevronLeft className="w-4 h-4 text-slate-500" /></button>
            <span className="px-3 text-sm font-semibold text-slate-700">This Week</span>
            <button className="p-2 hover:bg-slate-50 transition-colors"><ChevronRight className="w-4 h-4 text-slate-500" /></button>
          </div>
          <button className="flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-pink-700 transition-all shadow-sm">
            <Plus className="w-4 h-4" />
            New Appointment
          </button>
        </div>
      </div>

      <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {/* Day headers */}
        <div className="grid grid-cols-8 border-b border-gray-100">
          <div className="p-3 text-xs text-slate-400 text-center" />
          {days.map((day, i) => (
            <div key={day} className={`p-3 text-center border-l border-gray-100 ${i === currentDay ? 'bg-blue-50' : ''}`}>
              <p className="text-xs text-slate-500 font-medium">{day}</p>
              <p className={`text-lg font-black ${i === currentDay ? 'text-blue-600' : 'text-slate-900'}`}>{dates[i]}</p>
              {i === currentDay && <div className="w-1.5 h-1.5 rounded-full bg-blue-600 mx-auto mt-0.5" />}
            </div>
          ))}
        </div>

        {/* Time grid */}
        <div className="overflow-y-auto max-h-[60vh]">
          <div className="grid grid-cols-8 relative">
            {/* Hours column */}
            <div>
              {hours.map(h => (
                <div key={h} className="h-16 flex items-start justify-end pr-3 pt-2">
                  <span className="text-xs text-slate-400 font-medium">{h > 12 ? `${h - 12}pm` : h === 12 ? '12pm' : `${h}am`}</span>
                </div>
              ))}
            </div>

            {/* Day columns */}
            {days.map((day, dayIdx) => (
              <div key={day} className={`border-l border-gray-100 relative ${dayIdx === currentDay ? 'bg-blue-50/30' : ''}`}>
                {hours.map(h => (
                  <div key={h} className="h-16 border-b border-gray-50" />
                ))}
                {/* Appointments */}
                {appointments.filter(a => a.day === dayIdx).map(apt => (
                  <div
                    key={apt.id}
                    className={`absolute left-1 right-1 bg-gradient-to-b ${apt.color} rounded-xl px-2 py-1.5 text-white shadow-sm cursor-pointer hover:shadow-md transition-shadow overflow-hidden`}
                    style={{
                      top: `${(apt.start - 8) * 64 + 2}px`,
                      height: `${apt.duration * 64 - 4}px`,
                    }}
                  >
                    <p className="text-xs font-bold truncate">{apt.client}</p>
                    <p className="text-xs opacity-80 truncate">{apt.service}</p>
                    <p className="text-xs opacity-70 flex items-center gap-0.5 mt-0.5">
                      <Clock className="w-2.5 h-2.5" />
                      {apt.start}:00{apt.start >= 12 ? 'pm' : 'am'}
                    </p>
                  </div>
                ))}
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
