'use client';
import { Phone, Mic } from 'lucide-react';

export default function AIVoiceWidget({ compact = false }: { compact?: boolean }) {
  const bars = [3, 6, 9, 5, 8, 4, 7, 5, 9, 3, 6, 8, 4, 7, 5];

  if (compact) {
    return (
      <div className="flex items-center gap-2 px-3 py-2 bg-blue-50 rounded-xl border border-blue-100">
        <div className="w-6 h-6 rounded-full bg-gradient-to-r from-blue-600 to-pink-600 flex items-center justify-center flex-shrink-0">
          <Phone className="w-3 h-3 text-white" />
        </div>
        <div className="flex items-end gap-0.5 h-4">
          {bars.slice(0, 8).map((h, i) => (
            <div key={i} className="w-0.5 bg-gradient-to-t from-blue-600 to-pink-500 rounded-full waveform-bar" style={{ height: `${h * 3 + 4}px`, animationDelay: `${i * 0.1}s` }} />
          ))}
        </div>
        <span className="text-xs font-semibold text-blue-700">Book via AI Voice</span>
      </div>
    );
  }

  return (
    <div className="relative bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 overflow-hidden">
      <div className="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-pink-600/10" />
      <div className="relative">
        <div className="flex items-center gap-3 mb-4">
          <div className="w-12 h-12 rounded-2xl bg-gradient-to-r from-blue-600 to-pink-600 flex items-center justify-center shadow-lg pulse-glow">
            <Mic className="w-6 h-6 text-white" />
          </div>
          <div>
            <div className="text-white font-semibold">AI Receptionist</div>
            <div className="flex items-center gap-1.5">
              <div className="w-2 h-2 rounded-full bg-green-400 animate-pulse" />
              <span className="text-xs text-green-400 font-medium">Active 24/7</span>
            </div>
          </div>
        </div>
        <div className="flex items-end justify-center gap-1 h-12 mb-4">
          {bars.map((h, i) => (
            <div key={i} className="w-1.5 bg-gradient-to-t from-blue-500 to-pink-400 rounded-full waveform-bar" style={{ height: `${h * 4 + 8}px`, animationDelay: `${i * 0.08}s` }} />
          ))}
        </div>
        <p className="text-slate-300 text-sm text-center">Listening for your call...</p>
      </div>
    </div>
  );
}
