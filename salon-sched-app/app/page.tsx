import Link from 'next/link';
import Image from 'next/image';
import {
  Phone, Calendar, Map, CreditCard, Sparkles, MessageSquare,
  Star, Check, ArrowRight, Mic, Users, Clock, TrendingUp
} from 'lucide-react';
import Navbar from '@/components/Navbar';
import Footer from '@/components/Footer';

const features = [
  { icon: Mic, title: 'AI Voice Booking', desc: 'Customers call and book 24/7 via conversational AI — no human needed. Never miss a booking again.', gradient: 'from-blue-500 to-blue-600' },
  { icon: Calendar, title: 'Smart Calendar', desc: 'Drag-drop scheduling with conflict detection, recurring appointments, and real-time availability.', gradient: 'from-violet-500 to-purple-600' },
  { icon: Map, title: 'Marketplace Discovery', desc: 'Get found by 1M+ customers searching by location, service, rating, and price in real time.', gradient: 'from-pink-500 to-rose-600' },
  { icon: CreditCard, title: 'Payments & POS', desc: 'Accept cards, tips, gift cards, and memberships. Full POS built in, zero extra hardware needed.', gradient: 'from-green-500 to-emerald-600' },
  { icon: Sparkles, title: 'AI Content Tools', desc: 'Generate service descriptions, Instagram captions, and email campaigns with one click.', gradient: 'from-amber-500 to-orange-600' },
  { icon: MessageSquare, title: 'Connect Messaging', desc: '2-way SMS, AI auto-replies, business number masking, and blast campaigns from one inbox.', gradient: 'from-cyan-500 to-teal-600' },
];

const steps = [
  { step: '01', title: 'Search & Discover', desc: 'Find beauty professionals near you by service, rating, price, or next availability.', icon: Map },
  { step: '02', title: 'Book Instantly', desc: 'Select your service, pick a time, and confirm in under 60 seconds — or call our AI.', icon: Calendar },
  { step: '03', title: 'Show Up & Glow', desc: 'Get automated reminders, arrive and enjoy. No-show protection included.', icon: Star },
];

const testimonials = [
  { name: 'Maya Johnson', title: 'Master Colorist, Beverly Hills', quote: 'GlowBook doubled my bookings in 3 months. The AI voice receptionist handles 60% of my calls while I focus on clients. It paid for itself in the first week.', rating: 5, avatar: 'https://picsum.photos/seed/maya_t/80/80' },
  { name: 'Carlos Reyes', title: 'Barber, Miami', quote: "My shop stays fully booked now. The AI answers calls at 2am and schedules for me. My clients love it and I sleep better knowing nothing slips through.", rating: 5, avatar: 'https://picsum.photos/seed/carlos_t/80/80' },
  { name: 'Aisha Okonkwo', title: 'Spa Owner, New York', quote: 'The CRM and marketing tools are incredible. I grew my client base 40% in 60 days. The automated reminders alone cut no-shows by 80%.', rating: 5, avatar: 'https://picsum.photos/seed/aisha_t/80/80' },
];

const pricingPlans = [
  {
    name: 'Free',
    price: 0,
    desc: 'For new professionals just getting started',
    features: ['1 business listing', 'Up to 20 bookings/mo', 'Basic calendar', 'Client messaging', 'GlowBook marketplace'],
    cta: 'Start Free',
    popular: false,
  },
  {
    name: 'Pro',
    price: 29,
    desc: 'For growing beauty professionals',
    features: ['Everything in Free', 'Unlimited bookings', 'AI Voice Receptionist', 'Smart reminders & SMS', 'Analytics dashboard', 'Payment processing', 'AI content tools', 'Priority support'],
    cta: 'Start Free Trial',
    popular: true,
  },
  {
    name: 'Enterprise',
    price: 99,
    desc: 'For salons, studios, and multi-location businesses',
    features: ['Everything in Pro', 'Multi-staff & locations', 'Custom branding', 'Advanced CRM & segments', 'API access', 'Dedicated success manager', 'White-label option', 'SLA guarantee'],
    cta: 'Contact Sales',
    popular: false,
  },
];

export default function HomePage() {
  return (
    <div className="min-h-screen bg-white">
      <Navbar />

      {/* Hero */}
      <section className="relative pt-24 pb-20 overflow-hidden">
        {/* Background blobs */}
        <div className="absolute top-0 right-0 w-[600px] h-[600px] bg-blue-50 rounded-full blur-3xl opacity-40 -translate-y-1/3 translate-x-1/3" />
        <div className="absolute bottom-0 left-0 w-[400px] h-[400px] bg-pink-50 rounded-full blur-3xl opacity-50" />

        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
              {/* Badge */}
              <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-700 text-sm font-medium mb-6 border border-blue-100">
                <Mic className="w-4 h-4" />
                <span>Introducing AI Voice Booking</span>
                <ArrowRight className="w-3.5 h-3.5" />
              </div>

              <h1 className="text-5xl lg:text-6xl font-black text-slate-900 leading-tight mb-6">
                Book Beauty,{' '}
                <span className="gradient-text">Your Way.</span>
                <br />
                <span className="text-4xl lg:text-5xl font-bold text-slate-700">Powered by AI.</span>
              </h1>

              <p className="text-xl text-slate-500 leading-relaxed mb-8 max-w-xl">
                24/7 AI voice scheduling, instant booking, and smart business tools for 300,000+ beauty professionals and 1M+ clients worldwide.
              </p>

              <div className="flex flex-col sm:flex-row gap-4 mb-10">
                <Link href="/discover" className="inline-flex items-center justify-center gap-2 px-7 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-pink-600 text-white font-bold text-base hover:from-blue-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                  <Map className="w-5 h-5" />
                  Find a Salon
                </Link>
                <Link href="/signup" className="inline-flex items-center justify-center gap-2 px-7 py-4 rounded-2xl border-2 border-slate-200 text-slate-900 font-bold text-base hover:border-blue-300 hover:bg-blue-50 transition-all">
                  List Your Business
                  <ArrowRight className="w-5 h-5" />
                </Link>
              </div>

              {/* Trust badges */}
              <div className="flex items-center gap-4 text-sm text-slate-400">
                <div className="flex -space-x-2">
                  {['a', 'b', 'c', 'd'].map(s => (
                    <div key={s} className="w-8 h-8 rounded-full border-2 border-white overflow-hidden relative">
                      <Image src={`https://picsum.photos/seed/${s}_av/32/32`} alt="user" fill className="object-cover" />
                    </div>
                  ))}
                </div>
                <span><strong className="text-slate-700">162M+</strong> appointments booked</span>
                <span className="flex items-center gap-0.5"><Star className="w-4 h-4 fill-yellow-400 text-yellow-400" /><strong className="text-slate-700">4.9</strong></span>
              </div>
            </div>

            {/* Hero Visual */}
            <div className="hidden lg:block">
              <div className="relative">
                {/* Floating card */}
                <div className="float-anim absolute -top-4 -left-8 bg-white rounded-2xl shadow-xl p-4 border border-gray-100 w-64 z-10">
                  <div className="flex items-center gap-3 mb-3">
                    <div className="w-10 h-10 rounded-full overflow-hidden relative">
                      <Image src="https://picsum.photos/seed/maya/40/40" alt="Maya" fill className="object-cover" />
                    </div>
                    <div>
                      <p className="font-semibold text-slate-900 text-sm">Maya Johnson</p>
                      <p className="text-xs text-slate-500">Master Colorist</p>
                    </div>
                  </div>
                  <div className="bg-gradient-to-r from-blue-50 to-pink-50 rounded-xl p-3">
                    <p className="text-xs font-semibold text-slate-700 mb-1">Next Available</p>
                    <p className="text-sm font-bold text-blue-600">Today 3:00 PM</p>
                  </div>
                </div>

                {/* Main image */}
                <div className="rounded-3xl overflow-hidden shadow-2xl">
                  <Image
                    src="https://picsum.photos/seed/hero_salon/600/500"
                    alt="Beauty professional at work"
                    width={600}
                    height={500}
                    className="object-cover"
                  />
                </div>

                {/* AI Voice card */}
                <div className="float-anim absolute -bottom-4 -right-8 bg-slate-900 rounded-2xl shadow-xl p-4 w-56 z-10" style={{ animationDelay: '2s' }}>
                  <div className="flex items-center gap-2 mb-2">
                    <div className="w-6 h-6 rounded-full bg-gradient-to-r from-blue-500 to-pink-500 flex items-center justify-center">
                      <Mic className="w-3 h-3 text-white" />
                    </div>
                    <span className="text-white text-xs font-semibold">AI Receptionist</span>
                    <div className="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse ml-auto" />
                  </div>
                  <div className="flex items-end gap-0.5 h-6 justify-center">
                    {[3, 6, 9, 5, 8, 4, 7, 5, 9, 3, 6].map((h, i) => (
                      <div key={i} className="w-1 bg-gradient-to-t from-blue-500 to-pink-400 rounded-full waveform-bar" style={{ height: `${h * 2 + 4}px`, animationDelay: `${i * 0.1}s` }} />
                    ))}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Stats */}
      <section className="py-12 border-y border-gray-100 bg-gradient-to-r from-slate-50 to-blue-50/30">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            {[
              { value: '300K+', label: 'Beauty Professionals', icon: Users },
              { value: '162M+', label: 'Appointments Booked', icon: Calendar },
              { value: '750+', label: 'Cities Covered', icon: Map },
              { value: '4.9★', label: 'Average Rating', icon: Star },
            ].map(({ value, label, icon: Icon }) => (
              <div key={label} className="text-center">
                <div className="text-3xl font-black gradient-text mb-1">{value}</div>
                <div className="text-sm text-slate-500 font-medium">{label}</div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Features */}
      <section id="features" className="py-24">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 text-blue-700 text-sm font-medium mb-4">
              <Sparkles className="w-4 h-4" />
              Everything you need
            </div>
            <h2 className="text-4xl font-black text-slate-900 mb-4">The Complete Beauty OS</h2>
            <p className="text-xl text-slate-500 max-w-2xl mx-auto">Every tool a beauty professional needs to run, grow, and automate their business — in one platform.</p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {features.map(({ icon: Icon, title, desc, gradient }) => (
              <div key={title} className="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all group">
                <div className={`w-12 h-12 rounded-2xl bg-gradient-to-br ${gradient} flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-md`}>
                  <Icon className="w-6 h-6 text-white" />
                </div>
                <h3 className="text-lg font-bold text-slate-900 mb-2">{title}</h3>
                <p className="text-slate-500 text-sm leading-relaxed">{desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* How It Works */}
      <section className="py-24 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-black text-slate-900 mb-4">Book in 3 Simple Steps</h2>
            <p className="text-xl text-slate-500">The fastest booking experience in beauty.</p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {steps.map(({ step, title, desc, icon: Icon }, i) => (
              <div key={step} className="relative text-center">
                {i < steps.length - 1 && (
                  <div className="hidden md:block absolute top-8 left-[60%] w-[80%] h-0.5 bg-gradient-to-r from-blue-200 to-pink-200" />
                )}
                <div className="relative z-10 inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-pink-600 shadow-lg mb-6">
                  <Icon className="w-7 h-7 text-white" />
                  <div className="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-slate-900 text-white text-xs font-black flex items-center justify-center">{step}</div>
                </div>
                <h3 className="text-xl font-bold text-slate-900 mb-3">{title}</h3>
                <p className="text-slate-500 text-sm leading-relaxed max-w-sm mx-auto">{desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* AI Voice Spotlight */}
      <section className="py-24 bg-slate-900 relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-blue-900/30 to-pink-900/30" />
        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-3xl" />

        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
              <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/20 text-blue-300 text-sm font-medium mb-6 border border-blue-500/30">
                <Mic className="w-4 h-4" />
                The AI Voice Receptionist
              </div>
              <h2 className="text-4xl lg:text-5xl font-black text-white mb-6 leading-tight">
                Never Miss a<br />
                <span className="gradient-text">Booking Again.</span>
              </h2>
              <p className="text-xl text-slate-300 mb-8 leading-relaxed">
                Your AI receptionist picks up every call 24/7, understands natural conversation, and books appointments — even while you sleep. Outbound reminders included.
              </p>
              <div className="space-y-4 mb-10">
                {[
                  'Answers calls in your voice and style',
                  'Handles bookings, reschedules, and cancellations',
                  'Outbound reminder calls before each appointment',
                  'Works with any existing phone number (Twilio)',
                  'Full call log with intent and outcome tracking',
                ].map(item => (
                  <div key={item} className="flex items-center gap-3">
                    <div className="w-5 h-5 rounded-full bg-gradient-to-r from-blue-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                      <Check className="w-3 h-3 text-white" />
                    </div>
                    <span className="text-slate-300 text-sm">{item}</span>
                  </div>
                ))}
              </div>
              <Link href="/signup" className="inline-flex items-center gap-2 px-7 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-pink-600 text-white font-bold text-base hover:from-blue-700 hover:to-pink-700 transition-all shadow-lg">
                <Phone className="w-5 h-5" />
                Get Your AI Number
              </Link>
            </div>

            {/* Phone mockup */}
            <div className="flex justify-center">
              <div className="relative w-64">
                <div className="bg-slate-800 rounded-[2.5rem] p-4 shadow-2xl border border-slate-700">
                  <div className="bg-slate-900 rounded-[2rem] p-5 min-h-[400px] flex flex-col">
                    <div className="flex items-center justify-between mb-4">
                      <span className="text-slate-400 text-xs">9:41 AM</span>
                      <div className="flex items-center gap-1">
                        <div className="w-1 h-3 bg-white/60 rounded-full" />
                        <div className="w-1 h-4 bg-white/80 rounded-full" />
                        <div className="w-1 h-5 bg-white rounded-full" />
                      </div>
                    </div>
                    <div className="flex-1 flex flex-col items-center justify-center">
                      <div className="w-16 h-16 rounded-full bg-gradient-to-r from-blue-600 to-pink-600 flex items-center justify-center mb-4 pulse-glow">
                        <Phone className="w-7 h-7 text-white" />
                      </div>
                      <p className="text-white font-semibold text-sm mb-1">GlowBook AI</p>
                      <p className="text-green-400 text-xs mb-6">Active Call — 0:42</p>
                      <div className="flex items-end gap-1 justify-center h-10 mb-4">
                        {[3, 6, 9, 5, 8, 4, 7, 5, 9, 3, 6, 8].map((h, i) => (
                          <div key={i} className="w-1 bg-gradient-to-t from-blue-500 to-pink-400 rounded-full waveform-bar" style={{ height: `${h * 3 + 6}px`, animationDelay: `${i * 0.08}s` }} />
                        ))}
                      </div>
                      <div className="bg-slate-800 rounded-2xl p-3 w-full">
                        <p className="text-slate-300 text-xs text-center leading-relaxed">
                          &quot;I&apos;d like to book a balayage appointment for Saturday if possible...&quot;
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Testimonials */}
      <section className="py-24">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-black text-slate-900 mb-4">Loved by 300K+ Pros</h2>
            <p className="text-xl text-slate-500">Real results from real beauty professionals.</p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {testimonials.map(t => (
              <div key={t.name} className="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all">
                <div className="flex items-center gap-0.5 mb-4">
                  {[...Array(5)].map((_, i) => <Star key={i} className="w-4 h-4 fill-yellow-400 text-yellow-400" />)}
                </div>
                <p className="text-slate-600 text-sm leading-relaxed mb-5 italic">&quot;{t.quote}&quot;</p>
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-full overflow-hidden relative">
                    <Image src={t.avatar} alt={t.name} fill className="object-cover" />
                  </div>
                  <div>
                    <p className="font-semibold text-slate-900 text-sm">{t.name}</p>
                    <p className="text-xs text-slate-500">{t.title}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Pricing */}
      <section id="pricing" className="py-24 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-4xl font-black text-slate-900 mb-4">Simple, Transparent Pricing</h2>
            <p className="text-xl text-slate-500">Start free. Upgrade when you are ready to grow.</p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            {pricingPlans.map(plan => (
              <div key={plan.name} className={`bg-white rounded-2xl p-7 border shadow-sm relative flex flex-col ${plan.popular ? 'border-blue-300 ring-2 ring-blue-100 shadow-lg' : 'border-gray-100'}`}>
                {plan.popular && (
                  <div className="absolute -top-3 left-1/2 -translate-x-1/2">
                    <span className="px-4 py-1 rounded-full bg-gradient-to-r from-blue-600 to-pink-600 text-white text-xs font-bold shadow-md">Most Popular</span>
                  </div>
                )}
                <div className="mb-6">
                  <h3 className="text-xl font-bold text-slate-900 mb-1">{plan.name}</h3>
                  <p className="text-sm text-slate-500 mb-4">{plan.desc}</p>
                  <div className="flex items-baseline gap-1">
                    <span className="text-4xl font-black text-slate-900">${plan.price}</span>
                    {plan.price > 0 && <span className="text-slate-400">/mo</span>}
                  </div>
                </div>
                <ul className="space-y-2.5 mb-7 flex-1">
                  {plan.features.map(f => (
                    <li key={f} className="flex items-center gap-2.5 text-sm text-slate-600">
                      <div className="w-4 h-4 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <Check className="w-2.5 h-2.5 text-blue-600" />
                      </div>
                      {f}
                    </li>
                  ))}
                </ul>
                <Link
                  href="/signup"
                  className={`block text-center py-3 rounded-2xl font-semibold text-sm transition-all ${plan.popular ? 'bg-gradient-to-r from-blue-600 to-pink-600 text-white hover:from-blue-700 hover:to-pink-700 shadow-md hover:shadow-lg' : 'border-2 border-slate-200 text-slate-900 hover:border-blue-300 hover:bg-blue-50'}`}
                >
                  {plan.cta}
                </Link>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Banner */}
      <section className="py-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <div className="bg-gradient-to-r from-blue-600 to-pink-600 rounded-3xl p-12 shadow-2xl">
            <h2 className="text-4xl font-black text-white mb-4">Ready to Glow Up Your Business?</h2>
            <p className="text-xl text-white/80 mb-8">Join 300,000+ beauty professionals already using GlowBook.</p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link href="/signup" className="px-8 py-4 rounded-2xl bg-white text-blue-600 font-bold text-base hover:bg-blue-50 transition-all shadow-md">
                Get Started Free
              </Link>
              <Link href="/discover" className="px-8 py-4 rounded-2xl border-2 border-white/40 text-white font-bold text-base hover:bg-white/10 transition-all">
                Explore Professionals
              </Link>
            </div>
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
}
