'use client';
import { useState } from 'react';
import { User, Building, Phone, Bell, CreditCard, Save, Upload } from 'lucide-react';
import type { ElementType } from 'react';

type TabId = 'profile' | 'business' | 'aivoice' | 'notifications' | 'billing';

const tabs: { id: TabId; label: string; icon: ElementType }[] = [
  { id: 'profile', label: 'Profile', icon: User },
  { id: 'business', label: 'Business', icon: Building },
  { id: 'aivoice', label: 'AI Voice', icon: Phone },
  { id: 'notifications', label: 'Notifications', icon: Bell },
  { id: 'billing', label: 'Billing', icon: CreditCard },
];

function ToggleSwitch({ enabled, onChange }: { enabled: boolean; onChange: (v: boolean) => void }) {
  return (
    <button
      type="button"
      onClick={() => onChange(!enabled)}
      className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors ${enabled ? 'bg-gradient-to-r from-blue-600 to-pink-500' : 'bg-gray-200'}`}
    >
      <span className={`inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform ${enabled ? 'translate-x-6' : 'translate-x-1'}`} />
    </button>
  );
}

export default function SettingsPage() {
  const [activeTab, setActiveTab] = useState<TabId>('profile');
  const [aiEnabled, setAiEnabled] = useState(true);
  const [notifications, setNotifications] = useState({
    email: true, sms: true, newBooking: true, cancellation: true, reminder: false, marketing: false,
  });

  return (
    <div className="p-6 lg:p-8 max-w-4xl mx-auto">
      <div className="mb-8">
        <h1 className="text-2xl font-black text-slate-900 mb-1">Settings</h1>
        <p className="text-slate-500 text-sm">Manage your profile, business, AI, and billing settings</p>
      </div>

      <div className="flex gap-6">
        <div className="w-48 flex-shrink-0">
          <nav className="space-y-1">
            {tabs.map(({ id, label, icon: Icon }) => (
              <button
                key={id}
                onClick={() => setActiveTab(id)}
                className={`w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all text-left ${
                  activeTab === id
                    ? 'bg-gradient-to-r from-blue-600/10 to-pink-600/10 text-blue-700 border border-blue-200'
                    : 'text-slate-600 hover:bg-slate-50'
                }`}
              >
                <Icon style={{ width: '16px', height: '16px' }} />
                {label}
              </button>
            ))}
          </nav>
        </div>

        <div className="flex-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
          {activeTab === 'profile' && (
            <div>
              <h2 className="text-lg font-bold text-slate-900 mb-6">Profile</h2>
              <div className="flex items-center gap-5 mb-6 pb-6 border-b border-gray-100">
                <div className="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-600 to-pink-600 flex items-center justify-center text-white text-2xl font-black flex-shrink-0">
                  M
                </div>
                <div>
                  <p className="font-semibold text-slate-900 mb-1">Profile Photo</p>
                  <p className="text-xs text-slate-500 mb-2">JPG, PNG or GIF. Max 2MB.</p>
                  <button className="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-gray-200 text-slate-700 text-xs font-medium hover:bg-gray-50 transition-all">
                    <Upload className="w-3 h-3" />
                    Upload Photo
                  </button>
                </div>
              </div>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-600 mb-1.5">Full Name</label>
                  <input defaultValue="Maya Johnson" className="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-600 mb-1.5">Display Name</label>
                  <input defaultValue="Maya" className="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-600 mb-1.5">Email</label>
                  <input defaultValue="maya@glowstudio.com" type="email" className="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-600 mb-1.5">Phone</label>
                  <input defaultValue="(310) 555-0142" type="tel" className="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
              </div>
              <button className="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-pink-700 transition-all shadow-sm">
                <Save className="w-4 h-4" />
                Save Changes
              </button>
            </div>
          )}

          {activeTab === 'business' && (
            <div>
              <h2 className="text-lg font-bold text-slate-900 mb-6">Business Information</h2>
              <div className="space-y-4 mb-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-600 mb-1.5">Business Name</label>
                  <input defaultValue="Maya Glow Studio" className="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-600 mb-1.5">Business Address</label>
                  <input defaultValue="9821 Wilshire Blvd, Beverly Hills, CA 90210" className="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-600 mb-1.5">Business Hours</label>
                  <div className="space-y-2">
                    {['Monday – Friday', 'Saturday', 'Sunday'].map(day => (
                      <div key={day} className="flex items-center justify-between p-3 rounded-xl border border-gray-200">
                        <span className="text-xs font-medium text-slate-700">{day}</span>
                        <span className="text-xs text-slate-500">{day === 'Sunday' ? 'Closed' : '9:00 AM – 7:00 PM'}</span>
                      </div>
                    ))}
                  </div>
                </div>
              </div>
              <button className="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-pink-700 transition-all shadow-sm">
                <Save className="w-4 h-4" />
                Save Changes
              </button>
            </div>
          )}

          {activeTab === 'aivoice' && (
            <div>
              <h2 className="text-lg font-bold text-slate-900 mb-6">AI Voice Agent</h2>
              <div className="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-gray-200 mb-4">
                <div>
                  <p className="font-semibold text-slate-900 text-sm">AI Receptionist</p>
                  <p className="text-xs text-slate-500">Automatically answers calls and books appointments 24/7</p>
                </div>
                <ToggleSwitch enabled={aiEnabled} onChange={setAiEnabled} />
              </div>
              {aiEnabled && (
                <div className="bg-green-50 border border-green-200 rounded-xl p-3 mb-4 flex items-center gap-2">
                  <div className="w-2 h-2 rounded-full bg-green-400 animate-pulse flex-shrink-0" />
                  <span className="text-green-700 text-xs font-semibold">AI Receptionist is active and answering calls</span>
                </div>
              )}
              <div className="space-y-4 mb-4">
                <div>
                  <label className="block text-xs font-semibold text-slate-600 mb-1.5">Twilio Phone Number</label>
                  <div className="flex items-center gap-3">
                    <input readOnly value="+1 (310) 888-4201" className="flex-1 px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-slate-900 bg-gray-50 cursor-not-allowed" />
                    <span className="px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Active</span>
                  </div>
                </div>
                <div>
                  <label className="block text-xs font-semibold text-slate-600 mb-1.5">Custom Greeting</label>
                  <textarea
                    defaultValue="Hi, you've reached Maya Glow Studio! I'm Maya's AI assistant. I can help you book an appointment, check availability, or answer questions. How can I help you today?"
                    rows={3}
                    className="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                  />
                </div>
              </div>
              <button className="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-pink-700 transition-all shadow-sm">
                <Save className="w-4 h-4" />
                Save Changes
              </button>
            </div>
          )}

          {activeTab === 'notifications' && (
            <div>
              <h2 className="text-lg font-bold text-slate-900 mb-6">Notifications</h2>
              <div className="space-y-4">
                {[
                  { key: 'email' as const, label: 'Email Notifications', desc: 'Receive updates via email' },
                  { key: 'sms' as const, label: 'SMS Notifications', desc: 'Receive updates via text message' },
                  { key: 'newBooking' as const, label: 'New Booking Alerts', desc: 'Get notified when a client books' },
                  { key: 'cancellation' as const, label: 'Cancellations', desc: 'Alert when a booking is cancelled' },
                  { key: 'reminder' as const, label: 'Appointment Reminders', desc: 'Reminder 24h before each appointment' },
                  { key: 'marketing' as const, label: 'Marketing Updates', desc: 'Tips, features, and product news' },
                ].map(({ key, label, desc }) => (
                  <div key={key} className="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div>
                      <p className="text-sm font-semibold text-slate-900">{label}</p>
                      <p className="text-xs text-slate-500">{desc}</p>
                    </div>
                    <ToggleSwitch enabled={notifications[key]} onChange={v => setNotifications(prev => ({ ...prev, [key]: v }))} />
                  </div>
                ))}
              </div>
            </div>
          )}

          {activeTab === 'billing' && (
            <div>
              <h2 className="text-lg font-bold text-slate-900 mb-6">Billing & Plan</h2>
              <div className="bg-gradient-to-br from-blue-600 to-pink-600 rounded-2xl p-5 text-white mb-5">
                <div className="flex items-start justify-between mb-3">
                  <div>
                    <span className="text-white/70 text-xs uppercase tracking-wide font-semibold">Current Plan</span>
                    <h3 className="text-2xl font-black mt-0.5">Pro</h3>
                  </div>
                  <span className="px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold">Active</span>
                </div>
                <p className="text-white/80 text-sm mb-4">$29/month · Renews June 15, 2026</p>
                <div className="grid grid-cols-2 gap-2 text-xs text-white/70">
                  {['Unlimited bookings', 'AI Voice Receptionist', 'AI Content Tools', 'Priority Support'].map(f => (
                    <div key={f} className="flex items-center gap-1.5">
                      <div className="w-1.5 h-1.5 rounded-full bg-white/60" />
                      {f}
                    </div>
                  ))}
                </div>
              </div>
              <div className="flex items-center gap-3 mb-5">
                <button className="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-pink-700 transition-all shadow-sm">
                  Upgrade to Enterprise
                </button>
                <button className="px-4 py-2.5 rounded-xl border border-gray-200 text-slate-700 text-sm font-medium hover:bg-gray-50 transition-all">
                  Cancel Plan
                </button>
              </div>
              <div className="bg-slate-50 rounded-xl p-4">
                <p className="text-xs font-semibold text-slate-700 mb-2">Payment Method</p>
                <div className="flex items-center gap-3">
                  <div className="w-8 h-6 rounded bg-blue-600 flex items-center justify-center">
                    <span className="text-white text-xs font-bold">V</span>
                  </div>
                  <span className="text-sm text-slate-900">Visa ending in 4821</span>
                  <button className="ml-auto text-xs text-blue-600 hover:text-blue-700 font-medium">Change</button>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
