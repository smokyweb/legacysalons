'use client';
import { useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { Menu, X, Sparkles } from 'lucide-react';

export default function Navbar() {
  const [mobileOpen, setMobileOpen] = useState(false);

  return (
    <nav className="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          {/* Logo */}
          <Link href="/" className="flex items-center gap-2 group">
            <div className="w-8 h-8 relative">
              <Image src="/logo.png" alt="GlowBook" fill className="object-contain" />
            </div>
            <span className="text-xl font-bold gradient-text">GlowBook</span>
          </Link>

          {/* Desktop Nav */}
          <div className="hidden md:flex items-center gap-8">
            <Link href="/#features" className="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">Features</Link>
            <Link href="/#pricing" className="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">Pricing</Link>
            <Link href="/discover" className="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">Discover</Link>
            <Link href="/login" className="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">Sign In</Link>
            <Link
              href="/signup"
              className="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-pink-700 transition-all shadow-sm hover:shadow-md"
            >
              <Sparkles className="w-3.5 h-3.5" />
              Get Started Free
            </Link>
          </div>

          {/* Mobile menu toggle */}
          <button
            className="md:hidden p-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors"
            onClick={() => setMobileOpen(!mobileOpen)}
          >
            {mobileOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
          </button>
        </div>
      </div>

      {/* Mobile Menu */}
      {mobileOpen && (
        <div className="md:hidden bg-white border-t border-gray-100 px-4 py-4 flex flex-col gap-3">
          <Link href="/#features" className="text-sm font-medium text-gray-700 py-2" onClick={() => setMobileOpen(false)}>Features</Link>
          <Link href="/#pricing" className="text-sm font-medium text-gray-700 py-2" onClick={() => setMobileOpen(false)}>Pricing</Link>
          <Link href="/discover" className="text-sm font-medium text-gray-700 py-2" onClick={() => setMobileOpen(false)}>Discover</Link>
          <Link href="/login" className="text-sm font-medium text-gray-700 py-2" onClick={() => setMobileOpen(false)}>Sign In</Link>
          <Link
            href="/signup"
            className="w-full text-center px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold"
            onClick={() => setMobileOpen(false)}
          >
            Get Started Free
          </Link>
        </div>
      )}
    </nav>
  );
}
