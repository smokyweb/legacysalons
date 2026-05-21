'use client';
import Link from 'next/link';
import Image from 'next/image';
import { usePathname } from 'next/navigation';
import {
  LayoutDashboard, Calendar, BookOpen, Users, Scissors,
  MessageSquare, Phone, Sparkles, CreditCard, Settings, LogOut
} from 'lucide-react';

const navItems = [
  { href: '/dashboard', icon: LayoutDashboard, label: 'Overview' },
  { href: '/dashboard/calendar', icon: Calendar, label: 'Calendar' },
  { href: '/dashboard/bookings', icon: BookOpen, label: 'Bookings' },
  { href: '/dashboard/clients', icon: Users, label: 'Clients / CRM' },
  { href: '/dashboard/services', icon: Scissors, label: 'Services' },
  { href: '/dashboard/messages', icon: MessageSquare, label: 'Messages' },
  { href: '/dashboard/voice-agent', icon: Phone, label: 'AI Voice Agent' },
  { href: '/dashboard/marketing', icon: Sparkles, label: 'Marketing & AI' },
  { href: '/dashboard/payments', icon: CreditCard, label: 'Payments' },
  { href: '/dashboard/settings', icon: Settings, label: 'Settings' },
];

export default function DashboardSidebar() {
  const pathname = usePathname();

  return (
    <div className="w-64 min-h-screen bg-slate-900 flex flex-col border-r border-slate-800">
      {/* Logo */}
      <div className="p-6 border-b border-slate-800">
        <Link href="/" className="flex items-center gap-2">
          <div className="w-8 h-8 relative">
            <Image src="/logo.png" alt="GlowBook" fill className="object-contain" />
          </div>
          <span className="text-xl font-bold gradient-text">GlowBook</span>
        </Link>
        <div className="mt-4 flex items-center gap-3">
          <div className="w-9 h-9 rounded-full bg-gradient-to-r from-blue-600 to-pink-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
            M
          </div>
          <div className="min-w-0">
            <p className="text-sm font-semibold text-white truncate">Maya Johnson</p>
            <p className="text-xs text-slate-400 truncate">maya@glowstudio.com</p>
          </div>
        </div>
      </div>

      {/* Nav */}
      <nav className="flex-1 p-4 space-y-1">
        {navItems.map(({ href, icon: Icon, label }) => {
          const isActive = pathname === href;
          return (
            <Link
              key={href}
              href={href}
              className={`flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all ${
                isActive
                  ? 'bg-gradient-to-r from-blue-600/20 to-pink-600/20 text-white border border-blue-500/30'
                  : 'text-slate-400 hover:text-white hover:bg-slate-800'
              }`}
            >
              <Icon className={`w-4.5 h-4.5 flex-shrink-0 ${isActive ? 'text-blue-400' : ''}`} style={{ width: '18px', height: '18px' }} />
              {label}
              {label === 'AI Voice Agent' && (
                <span className="ml-auto flex items-center gap-1">
                  <span className="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse" />
                </span>
              )}
            </Link>
          );
        })}
      </nav>

      {/* Bottom */}
      <div className="p-4 border-t border-slate-800">
        <Link href="/" className="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-all">
          <LogOut style={{ width: '18px', height: '18px' }} className="flex-shrink-0" />
          Sign Out
        </Link>
      </div>
    </div>
  );
}
