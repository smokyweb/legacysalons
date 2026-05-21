'use client';
import { useState } from 'react';
import { X, ChevronRight, Check, Calendar, Clock, User, Phone } from 'lucide-react';
import { Service, Pro } from '@/lib/mockData';

interface BookingModalProps {
  pro: Pro;
  initialService?: Service | null;
  onClose: () => void;
}

const timeSlots = {
  morning: ['9:00 AM', '9:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM'],
  afternoon: ['12:00 PM', '12:30 PM', '1:00 PM', '2:00 PM', '3:00 PM', '3:30 PM'],
  evening: ['4:00 PM', '5:00 PM', '5:30 PM', '6:00 PM', '6:30 PM', '7:00 PM'],
};

const calendarDays = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
const availableDates = [21, 22, 23, 24, 25, 26, 27, 28, 29, 30];

export default function BookingModal({ pro, initialService, onClose }: BookingModalProps) {
  const [step, setStep] = useState(initialService ? 2 : 1);
  const [selectedService, setSelectedService] = useState<Service | null>(initialService || null);
  const [selectedDate, setSelectedDate] = useState<number | null>(null);
  const [selectedTime, setSelectedTime] = useState<string | null>(null);
  const [form, setForm] = useState({ name: '', phone: '', notes: '' });
  const [confirmed, setConfirmed] = useState(false);
  const bookingRef = `GB-${Math.random().toString(36).substr(2, 8).toUpperCase()}`;

  const handleConfirm = () => {
    if (!form.name || !form.phone) return;
    setConfirmed(true);
  };

  if (confirmed) {
    return (
      <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div className="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl text-center">
          <div className="w-16 h-16 rounded-full bg-gradient-to-r from-blue-600 to-pink-600 flex items-center justify-center mx-auto mb-6">
            <Check className="w-8 h-8 text-white" />
          </div>
          <h2 className="text-2xl font-bold text-slate-900 mb-2">Booking Confirmed!</h2>
          <p className="text-slate-500 mb-6">Your appointment has been scheduled. You will receive a confirmation SMS shortly.</p>
          <div className="bg-slate-50 rounded-2xl p-4 mb-6 text-left space-y-3">
            <div className="flex justify-between text-sm">
              <span className="text-slate-500">Booking Ref</span>
              <span className="font-bold text-blue-600">{bookingRef}</span>
            </div>
            <div className="flex justify-between text-sm">
              <span className="text-slate-500">Service</span>
              <span className="font-semibold text-slate-900">{selectedService?.name}</span>
            </div>
            <div className="flex justify-between text-sm">
              <span className="text-slate-500">Date & Time</span>
              <span className="font-semibold text-slate-900">May {selectedDate}, {selectedTime}</span>
            </div>
            <div className="flex justify-between text-sm">
              <span className="text-slate-500">With</span>
              <span className="font-semibold text-slate-900">{pro.name}</span>
            </div>
            <div className="flex justify-between text-sm">
              <span className="text-slate-500">Total</span>
              <span className="font-bold text-slate-900">${selectedService?.price}</span>
            </div>
          </div>
          <button onClick={onClose} className="w-full py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-pink-600 text-white font-semibold hover:from-blue-700 hover:to-pink-700 transition-all">
            Done
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
      <div className="bg-white rounded-3xl max-w-lg w-full shadow-2xl max-h-[90vh] overflow-y-auto">
        {/* Header */}
        <div className="flex items-center justify-between p-6 border-b border-gray-100">
          <div>
            <h2 className="text-lg font-bold text-slate-900">
              {step === 1 ? 'Select a Service' : step === 2 ? 'Pick a Date & Time' : 'Your Details'}
            </h2>
            <div className="flex items-center gap-1 mt-1">
              {[1, 2, 3].map(s => (
                <div key={s} className={`h-1 rounded-full transition-all ${s === step ? 'w-8 bg-gradient-to-r from-blue-600 to-pink-600' : s < step ? 'w-4 bg-blue-300' : 'w-4 bg-gray-200'}`} />
              ))}
            </div>
          </div>
          <button onClick={onClose} className="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
            <X className="w-4 h-4 text-gray-600" />
          </button>
        </div>

        <div className="p-6">
          {/* Step 1: Select Service */}
          {step === 1 && (
            <div className="space-y-3">
              {pro.services.map(service => (
                <button
                  key={service.id}
                  onClick={() => { setSelectedService(service); setStep(2); }}
                  className="w-full text-left p-4 rounded-2xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50/50 transition-all group"
                >
                  <div className="flex items-center justify-between">
                    <div>
                      <p className="font-semibold text-slate-900 group-hover:text-blue-600">{service.name}</p>
                      <p className="text-sm text-slate-500 mt-0.5">{service.duration} • <span className="font-semibold">${service.price}</span></p>
                    </div>
                    <ChevronRight className="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors" />
                  </div>
                </button>
              ))}
            </div>
          )}

          {/* Step 2: Date & Time */}
          {step === 2 && (
            <div className="space-y-5">
              <div className="bg-blue-50 rounded-2xl p-3 flex items-center gap-3">
                <div className="w-8 h-8 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 flex items-center justify-center flex-shrink-0">
                  <Calendar className="w-4 h-4 text-white" />
                </div>
                <div>
                  <p className="text-xs text-slate-500">Selected service</p>
                  <p className="text-sm font-semibold text-slate-900">{selectedService?.name} — ${selectedService?.price}</p>
                </div>
              </div>

              <div>
                <p className="text-sm font-semibold text-slate-700 mb-3">May 2026</p>
                <div className="grid grid-cols-7 gap-1 mb-2">
                  {calendarDays.map(d => <div key={d} className="text-center text-xs text-slate-400 font-medium py-1">{d}</div>)}
                </div>
                <div className="grid grid-cols-7 gap-1">
                  {[...Array(20)].map((_, i) => i < 20 ? (
                    <div key={i} className={`aspect-square rounded-xl text-xs flex items-center justify-center ${i < 20 && availableDates.includes(i + 1) ? 'cursor-pointer font-medium transition-all ' + (selectedDate === i + 1 ? 'bg-gradient-to-r from-blue-600 to-pink-600 text-white shadow-md' : 'hover:bg-blue-50 text-slate-700') : 'text-slate-300'}`}
                      onClick={() => availableDates.includes(i + 1) && setSelectedDate(i + 1)}>
                      {i + 1}
                    </div>
                  ) : null)}
                </div>
              </div>

              {selectedDate && (
                <div className="space-y-3">
                  {Object.entries(timeSlots).map(([period, slots]) => (
                    <div key={period}>
                      <p className="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2 capitalize">{period}</p>
                      <div className="grid grid-cols-3 gap-2">
                        {slots.map(time => (
                          <button
                            key={time}
                            onClick={() => setSelectedTime(time)}
                            className={`py-2 rounded-xl text-xs font-medium transition-all ${selectedTime === time ? 'bg-gradient-to-r from-blue-600 to-pink-600 text-white shadow-md' : 'bg-gray-50 hover:bg-blue-50 text-slate-700 border border-gray-100'}`}
                          >
                            {time}
                          </button>
                        ))}
                      </div>
                    </div>
                  ))}
                </div>
              )}

              {selectedDate && selectedTime && (
                <button onClick={() => setStep(3)} className="w-full py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-pink-600 text-white font-semibold hover:from-blue-700 hover:to-pink-700 transition-all flex items-center justify-center gap-2">
                  Continue <ChevronRight className="w-4 h-4" />
                </button>
              )}
            </div>
          )}

          {/* Step 3: Details */}
          {step === 3 && (
            <div className="space-y-4">
              <div className="bg-blue-50 rounded-2xl p-3 space-y-1.5">
                <div className="flex justify-between text-sm">
                  <span className="text-slate-500">Service</span>
                  <span className="font-semibold text-slate-900">{selectedService?.name}</span>
                </div>
                <div className="flex justify-between text-sm">
                  <span className="text-slate-500">Date & Time</span>
                  <span className="font-semibold text-slate-900">May {selectedDate}, {selectedTime}</span>
                </div>
                <div className="flex justify-between text-sm">
                  <span className="text-slate-500">Total</span>
                  <span className="font-bold text-blue-600">${selectedService?.price}</span>
                </div>
              </div>

              <div className="space-y-3">
                <div>
                  <label className="text-sm font-medium text-slate-700 mb-1.5 block flex items-center gap-1.5">
                    <User className="w-3.5 h-3.5" /> Your Name
                  </label>
                  <input
                    type="text"
                    placeholder="Full name"
                    value={form.name}
                    onChange={e => setForm({ ...form, name: e.target.value })}
                    className="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition-all"
                  />
                </div>
                <div>
                  <label className="text-sm font-medium text-slate-700 mb-1.5 block flex items-center gap-1.5">
                    <Phone className="w-3.5 h-3.5" /> Phone Number
                  </label>
                  <input
                    type="tel"
                    placeholder="(555) 000-0000"
                    value={form.phone}
                    onChange={e => setForm({ ...form, phone: e.target.value })}
                    className="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition-all"
                  />
                </div>
                <div>
                  <label className="text-sm font-medium text-slate-700 mb-1.5 block">Notes (optional)</label>
                  <textarea
                    placeholder="Any special requests or notes..."
                    value={form.notes}
                    onChange={e => setForm({ ...form, notes: e.target.value })}
                    rows={2}
                    className="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition-all resize-none"
                  />
                </div>
              </div>

              <button
                onClick={handleConfirm}
                disabled={!form.name || !form.phone}
                className="w-full py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-pink-600 text-white font-semibold hover:from-blue-700 hover:to-pink-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Confirm Booking
              </button>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
