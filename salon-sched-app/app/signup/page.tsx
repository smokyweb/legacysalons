'use client';
import { useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { Calendar, Scissors, ArrowRight, Eye, EyeOff, Sparkles, Check } from 'lucide-react';

type Role = 'customer' | 'professional' | null;

export default function SignupPage() {
  const [role, setRole] = useState<Role>(null);
  const [showPassword, setShowPassword] = useState(false);
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (role === 'professional') {
      window.location.href = '/dashboard';
    } else {
      window.location.href = '/discover';
    }
  };

  return (
    <div className="min-h-screen flex">
      {/* Left panel */}
      <div className="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-slate-900 to-blue-900 flex-col items-center justify-center p-12 overflow-hidden">
        <div className="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-600/20 rounded-full blur-3xl animate-pulse" />
        <div className="absolute bottom-1/4 right-1/4 w-80 h-80 bg-pink-600/15 rounded-full blur-3xl animate-pulse" style={{ animationDelay: '2s' }} />

        <div className="relative z-10 text-center">
          <div className="flex items-center justify-center gap-3 mb-8">
            <div className="w-12 h-12 relative">
              <Image src="/logo.png" alt="GlowBook" fill className="object-contain" />
            </div>
            <span className="text-3xl font-black bg-gradient-to-r from-blue-400 to-pink-400 bg-clip-text text-transparent">GlowBook</span>
          </div>
          <h2 className="text-4xl font-black text-white mb-4 leading-tight">
            Your beauty business,<br />
            <span className="bg-gradient-to-r from-blue-400 to-pink-400 bg-clip-text text-transparent">powered by AI.</span>
          </h2>
          <p className="text-slate-400 text-lg max-w-sm mx-auto leading-relaxed">
            Whether you&apos;re booking a treatment or running a salon, GlowBook has everything you need.
          </p>

          <div className="mt-10 space-y-3 text-left max-w-sm mx-auto">
            {[
              'AI Voice Receptionist — never miss a booking',
              'Smart scheduling and calendar sync',
              '1M+ clients on the marketplace',
              'Payments, CRM, and AI marketing tools',
            ].map(item => (
              <div key={item} className="flex items-center gap-3">
                <div className="w-5 h-5 rounded-full bg-gradient-to-r from-blue-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                  <Check className="w-3 h-3 text-white" />
                </div>
                <span className="text-slate-300 text-sm">{item}</span>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Right panel */}
      <div className="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white overflow-y-auto">
        <div className="w-full max-w-md">
          {/* Mobile logo */}
          <div className="flex items-center gap-2 mb-8 lg:hidden">
            <div className="w-8 h-8 relative">
              <Image src="/logo.png" alt="GlowBook" fill className="object-contain" />
            </div>
            <span className="text-xl font-black bg-gradient-to-r from-blue-600 to-pink-600 bg-clip-text text-transparent">GlowBook</span>
          </div>

          {!role ? (
            <>
              <div className="mb-8">
                <h1 className="text-3xl font-black text-slate-900 mb-2">Join GlowBook</h1>
                <p className="text-slate-500">How would you like to use GlowBook?</p>
              </div>

              <div className="space-y-4">
                <button
                  onClick={() => setRole('customer')}
                  className="w-full p-6 rounded-2xl border-2 border-gray-200 hover:border-blue-400 transition-all group text-left relative overflow-hidden hover:shadow-md"
                >
                  <div className="absolute inset-0 bg-gradient-to-br from-blue-50 to-pink-50 opacity-0 group-hover:opacity-100 transition-opacity" />
                  <div className="relative flex items-start gap-4">
                    <div className="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-100 to-pink-100 group-hover:from-blue-200 group-hover:to-pink-200 flex items-center justify-center flex-shrink-0 transition-colors">
                      <Calendar className="w-6 h-6 text-blue-600" />
                    </div>
                    <div className="flex-1">
                      <h3 className="text-lg font-bold text-slate-900 mb-1 group-hover:text-blue-700 transition-colors">
                        I want to book an appointment
                      </h3>
                      <p className="text-slate-500 text-sm leading-relaxed">
                        Find and book beauty professionals near you. Discover salons, spas, and stylists in seconds.
                      </p>
                    </div>
                    <ArrowRight className="w-5 h-5 text-slate-300 group-hover:text-blue-500 transition-colors flex-shrink-0 mt-1" />
                  </div>
                </button>

                <button
                  onClick={() => setRole('professional')}
                  className="w-full p-6 rounded-2xl border-2 border-gray-200 hover:border-pink-400 transition-all group text-left relative overflow-hidden hover:shadow-md"
                >
                  <div className="absolute inset-0 bg-gradient-to-br from-pink-50 to-purple-50 opacity-0 group-hover:opacity-100 transition-opacity" />
                  <div className="relative flex items-start gap-4">
                    <div className="w-12 h-12 rounded-2xl bg-gradient-to-br from-pink-100 to-purple-100 group-hover:from-pink-200 group-hover:to-purple-200 flex items-center justify-center flex-shrink-0 transition-colors">
                      <Scissors className="w-6 h-6 text-pink-600" />
                    </div>
                    <div className="flex-1">
                      <h3 className="text-lg font-bold text-slate-900 mb-1 group-hover:text-pink-700 transition-colors">
                        I&apos;m a beauty professional
                      </h3>
                      <p className="text-slate-500 text-sm leading-relaxed">
                        Manage bookings, grow your business, and automate with AI. Everything you need in one place.
                      </p>
                    </div>
                    <ArrowRight className="w-5 h-5 text-slate-300 group-hover:text-pink-500 transition-colors flex-shrink-0 mt-1" />
                  </div>
                </button>
              </div>

              <div className="mt-6 text-center">
                <p className="text-slate-500 text-sm">
                  Already have an account?{' '}
                  <Link href="/login" className="text-blue-600 font-semibold hover:text-blue-700">
                    Sign in
                  </Link>
                </p>
              </div>
            </>
          ) : (
            <>
              <button
                onClick={() => setRole(null)}
                className="flex items-center gap-2 text-slate-500 hover:text-slate-700 text-sm mb-6 transition-colors"
              >
                ← Back
              </button>

              <div className="mb-6">
                <div className={`inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-3 ${role === 'professional' ? 'bg-pink-100 text-pink-700' : 'bg-blue-100 text-blue-700'}`}>
                  {role === 'professional' ? <Scissors className="w-3 h-3" /> : <Calendar className="w-3 h-3" />}
                  {role === 'professional' ? 'Beauty Professional' : 'Customer'}
                </div>
                <h1 className="text-3xl font-black text-slate-900 mb-2">Create your account</h1>
                <p className="text-slate-500">Start free — no credit card required.</p>
              </div>

              <form onSubmit={handleSubmit} className="space-y-4">
                <div>
                  <label className="block text-sm font-semibold text-slate-700 mb-1.5">Full name</label>
                  <input
                    type="text"
                    value={name}
                    onChange={e => setName(e.target.value)}
                    placeholder="Maya Johnson"
                    className="w-full px-4 py-3 rounded-xl border border-gray-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                    required
                  />
                </div>

                <div>
                  <label className="block text-sm font-semibold text-slate-700 mb-1.5">Email address</label>
                  <input
                    type="email"
                    value={email}
                    onChange={e => setEmail(e.target.value)}
                    placeholder="you@example.com"
                    className="w-full px-4 py-3 rounded-xl border border-gray-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                    required
                  />
                </div>

                <div>
                  <label className="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                  <div className="relative">
                    <input
                      type={showPassword ? 'text' : 'password'}
                      value={password}
                      onChange={e => setPassword(e.target.value)}
                      placeholder="Min. 8 characters"
                      className="w-full px-4 py-3 rounded-xl border border-gray-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm pr-12"
                      required
                      minLength={8}
                    />
                    <button
                      type="button"
                      onClick={() => setShowPassword(!showPassword)}
                      className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                    >
                      {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
                    </button>
                  </div>
                </div>

                <p className="text-xs text-slate-500">
                  By creating an account you agree to our{' '}
                  <Link href="/terms" className="text-blue-600 hover:underline">Terms of Service</Link>
                  {' '}and{' '}
                  <Link href="/privacy" className="text-blue-600 hover:underline">Privacy Policy</Link>.
                </p>

                <button
                  type="submit"
                  className="w-full py-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white font-bold text-sm hover:from-blue-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2"
                >
                  <Sparkles className="w-4 h-4" />
                  Create Account
                </button>
              </form>

              <div className="mt-4 text-center">
                <p className="text-slate-500 text-sm">
                  Already have an account?{' '}
                  <Link href="/login" className="text-blue-600 font-semibold hover:text-blue-700">
                    Sign in
                  </Link>
                </p>
              </div>
            </>
          )}
        </div>
      </div>
    </div>
  );
}
