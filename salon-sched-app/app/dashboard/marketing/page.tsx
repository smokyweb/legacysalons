'use client';
import { useState } from 'react';
import { Sparkles, Copy, Check, Mail, MessageSquare, ChevronDown } from 'lucide-react';

const contentTypes = [
  'Service Description',
  'Instagram Caption',
  'Email Campaign',
  'SMS Blast',
];

const mockGenerated: Record<string, string> = {
  'Service Description': "Transform your look with our signature Balayage service — a sun-kissed, natural color technique that grows out beautifully. Our expert colorists use premium products to create a dimensional, radiant finish tailored exclusively to your features. Book today and discover why our clients call it life-changing.",
  'Instagram Caption': "✨ Glow up season is officially HERE. 🌸 Whether you\'re going for that effortless balayage or a bold new cut, our chairs are ready for you. Limited spots this week — DM to book or tap the link in bio. 💛 #GlowBook #HairGoals #SalonLife #BeautyPro",
  'Email Campaign': "Subject: Your next appointment is waiting 💅\n\nHi [First Name],\n\nWe miss you! It's been a while since your last visit, and we'd love to see you back in the chair.\n\nThis week only, enjoy 15% off any service when you book via GlowBook. Use code: GLOWBACK\n\n→ Book Now: glowbook.com/maya\n\nWith love,\nMaya @ Glow Studio",
  'SMS Blast': "Hi [Name]! Maya here 💇‍♀️ Quick reminder: your hair is due for some love! Book this week and get a complimentary deep condition. Reply BOOK or call (310) 555-0142. See you soon! ✨",
};

const emailCampaigns = [
  { id: 1, name: 'Win-Back Campaign', status: 'Sent', sent: 142, opens: '68%', date: 'May 15, 2026' },
  { id: 2, name: 'Summer Specials', status: 'Draft', sent: 0, opens: '—', date: 'May 18, 2026' },
  { id: 3, name: 'New Service Launch', status: 'Scheduled', sent: 0, opens: '—', date: 'May 25, 2026' },
  { id: 4, name: 'VIP Loyalty Rewards', status: 'Sent', sent: 89, opens: '74%', date: 'May 10, 2026' },
];

const smsCampaigns = [
  { id: 1, name: 'Appointment Reminders', status: 'Active', delivered: 234, date: 'Ongoing' },
  { id: 2, name: 'Flash Sale Alert', status: 'Sent', delivered: 178, date: 'May 12, 2026' },
  { id: 3, name: 'Review Request', status: 'Sent', delivered: 95, date: 'May 8, 2026' },
];

const statusColors: Record<string, string> = {
  Sent: 'bg-green-100 text-green-700',
  Draft: 'bg-gray-100 text-gray-600',
  Scheduled: 'bg-blue-100 text-blue-700',
  Active: 'bg-emerald-100 text-emerald-700',
};

export default function MarketingPage() {
  const [topic, setTopic] = useState('');
  const [contentType, setContentType] = useState('Instagram Caption');
  const [generating, setGenerating] = useState(false);
  const [generated, setGenerated] = useState('');
  const [copied, setCopied] = useState(false);

  const handleGenerate = () => {
    if (!topic.trim()) return;
    setGenerating(true);
    setGenerated('');
    setTimeout(() => {
      setGenerated(mockGenerated[contentType] || mockGenerated['Instagram Caption']);
      setGenerating(false);
    }, 2000);
  };

  const handleCopy = () => {
    navigator.clipboard.writeText(generated);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <div className="p-6 lg:p-8 max-w-7xl mx-auto">
      <div className="mb-8">
        <h1 className="text-2xl font-black text-slate-900 mb-1">AI Marketing Hub</h1>
        <p className="text-slate-500 text-sm">Generate content, manage campaigns, and grow your audience with AI</p>
      </div>

      {/* AI Generator */}
      <div className="bg-gradient-to-br from-slate-900 to-blue-900 rounded-2xl p-6 mb-6 relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-pink-600/10" />
        <div className="absolute top-0 right-0 w-48 h-48 bg-blue-400/10 rounded-full blur-3xl" />
        <div className="relative">
          <div className="flex items-center gap-2 mb-4">
            <Sparkles className="w-5 h-5 text-pink-400" />
            <h2 className="text-white font-bold">AI Content Generator</h2>
          </div>
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <div className="lg:col-span-2">
              <label className="block text-slate-400 text-xs font-medium mb-1.5">Topic or service to write about</label>
              <textarea
                value={topic}
                onChange={e => setTopic(e.target.value)}
                placeholder="e.g. My new balayage service, summer specials, win-back lapsed clients..."
                rows={3}
                className="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"
              />
            </div>
            <div>
              <label className="block text-slate-400 text-xs font-medium mb-1.5">Content type</label>
              <div className="relative">
                <select
                  value={contentType}
                  onChange={e => setContentType(e.target.value)}
                  className="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 appearance-none cursor-pointer"
                >
                  {contentTypes.map(t => (
                    <option key={t} value={t} className="text-slate-900 bg-white">{t}</option>
                  ))}
                </select>
                <ChevronDown className="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60 pointer-events-none" />
              </div>
              <button
                onClick={handleGenerate}
                disabled={generating || !topic.trim()}
                className="mt-3 w-full py-3 rounded-xl bg-gradient-to-r from-blue-500 to-pink-500 text-white text-sm font-bold hover:from-blue-600 hover:to-pink-600 transition-all disabled:opacity-50 flex items-center justify-center gap-2 shadow-lg"
              >
                {generating ? (
                  <>
                    <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                    Generating...
                  </>
                ) : (
                  <>
                    <Sparkles className="w-4 h-4" />
                    Generate with AI ✨
                  </>
                )}
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* Generated content */}
      {(generated || generating) && (
        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
          <div className="flex items-center justify-between mb-3">
            <div className="flex items-center gap-2">
              <Sparkles className="w-4 h-4 text-pink-500" />
              <span className="text-sm font-bold text-slate-900">Generated {contentType}</span>
            </div>
            {generated && (
              <div className="flex items-center gap-2">
                <button
                  onClick={handleCopy}
                  className="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-slate-600 text-xs font-medium hover:bg-gray-50 transition-all"
                >
                  {copied ? <Check className="w-3.5 h-3.5 text-green-500" /> : <Copy className="w-3.5 h-3.5" />}
                  {copied ? 'Copied!' : 'Copy'}
                </button>
                <button className="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gradient-to-r from-blue-600 to-pink-600 text-white text-xs font-semibold hover:from-blue-700 hover:to-pink-700 transition-all">
                  Use This ✓
                </button>
              </div>
            )}
          </div>
          {generating ? (
            <div className="space-y-2">
              {[100, 90, 95, 80, 70].map((w, i) => (
                <div key={i} className={`h-4 bg-gradient-to-r from-slate-200 via-blue-100 to-slate-200 rounded animate-pulse`} style={{ width: `${w}%` }} />
              ))}
            </div>
          ) : (
            <div className="bg-gradient-to-br from-blue-50 to-pink-50 rounded-xl p-4">
              <p className="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{generated}</p>
            </div>
          )}
        </div>
      )}

      {/* Campaigns */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Email campaigns */}
        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <div className="flex items-center justify-between mb-4">
            <div className="flex items-center gap-2">
              <Mail className="w-4 h-4 text-blue-600" />
              <h3 className="font-bold text-slate-900">Email Campaigns</h3>
            </div>
            <button className="text-xs text-blue-600 font-semibold hover:text-blue-700">+ New</button>
          </div>
          <div className="space-y-3">
            {emailCampaigns.map(campaign => (
              <div key={campaign.id} className="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                <div>
                  <p className="text-sm font-semibold text-slate-900">{campaign.name}</p>
                  <p className="text-xs text-slate-400">{campaign.date} · {campaign.sent > 0 ? `${campaign.sent} sent · ${campaign.opens} opens` : 'Not sent yet'}</p>
                </div>
                <span className={`px-2.5 py-1 rounded-full text-xs font-semibold ${statusColors[campaign.status]}`}>{campaign.status}</span>
              </div>
            ))}
          </div>
        </div>

        {/* SMS campaigns */}
        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <div className="flex items-center justify-between mb-4">
            <div className="flex items-center gap-2">
              <MessageSquare className="w-4 h-4 text-pink-600" />
              <h3 className="font-bold text-slate-900">SMS Campaigns</h3>
            </div>
            <button className="text-xs text-pink-600 font-semibold hover:text-pink-700">+ New</button>
          </div>
          <div className="space-y-3">
            {smsCampaigns.map(campaign => (
              <div key={campaign.id} className="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                <div>
                  <p className="text-sm font-semibold text-slate-900">{campaign.name}</p>
                  <p className="text-xs text-slate-400">{campaign.date} · {campaign.delivered} delivered</p>
                </div>
                <span className={`px-2.5 py-1 rounded-full text-xs font-semibold ${statusColors[campaign.status]}`}>{campaign.status}</span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
