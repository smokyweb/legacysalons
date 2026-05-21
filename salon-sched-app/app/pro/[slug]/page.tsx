'use client';
import { useState } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { Star, MapPin, Phone, ChevronLeft, Share2 } from 'lucide-react';
import Navbar from '@/components/Navbar';
import Footer from '@/components/Footer';
import ServiceCard from '@/components/ServiceCard';
import ReviewCard from '@/components/ReviewCard';
import BookingModal from '@/components/BookingModal';
import AIVoiceWidget from '@/components/AIVoiceWidget';
import { pros } from '@/lib/mockData';
import { Service } from '@/lib/mockData';

type Params = { slug: string };

export default function ProProfilePage({ params }: { params: Params }) {
  const pro = pros.find(p => p.slug === params.slug) || pros[0];
  const [activeTab, setActiveTab] = useState<'services' | 'gallery' | 'reviews' | 'about'>('services');
  const [showBooking, setShowBooking] = useState(false);
  const [selectedService, setSelectedService] = useState<Service | null>(null);

  const handleBookService = (service: Service) => {
    setSelectedService(service);
    setShowBooking(true);
  };

  const tabs = [
    { id: 'services', label: 'Services' },
    { id: 'gallery', label: 'Gallery' },
    { id: 'reviews', label: `Reviews (${pro.reviews.length})` },
    { id: 'about', label: 'About' },
  ] as const;

  return (
    <div className="min-h-screen bg-slate-50">
      <Navbar />

      {/* Hero cover */}
      <div className="relative h-64 md:h-80 mt-16">
        <Image src={pro.coverImage} alt={pro.name} fill className="object-cover" priority />
        <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
        <div className="absolute top-4 left-4">
          <Link href="/discover" className="flex items-center gap-1.5 text-white/80 hover:text-white text-sm font-medium transition-colors bg-black/30 rounded-xl px-3 py-1.5 backdrop-blur-sm">
            <ChevronLeft className="w-4 h-4" />
            Back to Discover
          </Link>
        </div>
        <button className="absolute top-4 right-4 w-9 h-9 rounded-full bg-black/30 backdrop-blur-sm flex items-center justify-center text-white hover:bg-black/50 transition-colors">
          <Share2 className="w-4 h-4" />
        </button>
      </div>

      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Profile info */}
        <div className="relative -mt-16 mb-6">
          <div className="bg-white rounded-3xl shadow-lg p-6">
            <div className="flex flex-col sm:flex-row gap-5">
              <div className="w-24 h-24 rounded-2xl overflow-hidden border-4 border-white shadow-lg flex-shrink-0 relative">
                <Image src={pro.image} alt={pro.name} fill className="object-cover" />
              </div>
              <div className="flex-1 min-w-0">
                <div className="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                  <div>
                    <div className="flex items-center gap-2 flex-wrap mb-1">
                      <h1 className="text-2xl font-black text-slate-900">{pro.name}</h1>
                      {pro.badge && (
                        <span className="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-600 to-pink-600 text-white">{pro.badge}</span>
                      )}
                    </div>
                    <p className="text-slate-500 mb-2">{pro.specialty}</p>
                    <div className="flex items-center gap-4 flex-wrap text-sm">
                      <span className="flex items-center gap-1 text-slate-500">
                        <MapPin className="w-4 h-4" />
                        {pro.location}
                      </span>
                      <span className="flex items-center gap-1 text-slate-500">
                        <Phone className="w-4 h-4" />
                        {pro.phone}
                      </span>
                      <div className="flex items-center gap-1">
                        <Star className="w-4 h-4 fill-yellow-400 text-yellow-400" />
                        <span className="font-bold text-slate-900">{pro.rating}</span>
                        <span className="text-slate-400">({pro.reviewCount} reviews)</span>
                      </div>
                    </div>
                  </div>
                  <div className="flex flex-col gap-2 sm:items-end flex-shrink-0">
                    <button
                      onClick={() => setShowBooking(true)}
                      className="px-6 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-pink-600 text-white font-bold text-sm hover:from-blue-700 hover:to-pink-700 transition-all shadow-md hover:shadow-lg whitespace-nowrap"
                    >
                      Book Now
                    </button>
                    <AIVoiceWidget compact />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Tabs */}
        <div className="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
          <div className="flex border-b border-gray-100">
            {tabs.map(tab => (
              <button
                key={tab.id}
                onClick={() => setActiveTab(tab.id)}
                className={`flex-1 py-3.5 text-sm font-semibold transition-all ${
                  activeTab === tab.id
                    ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50/50'
                    : 'text-slate-500 hover:text-slate-700'
                }`}
              >
                {tab.label}
              </button>
            ))}
          </div>

          <div className="p-6">
            {/* Services */}
            {activeTab === 'services' && (
              <div className="space-y-3">
                {pro.services.map(service => (
                  <ServiceCard key={service.id} service={service} onBook={handleBookService} />
                ))}
              </div>
            )}

            {/* Gallery */}
            {activeTab === 'gallery' && (
              <div className="columns-2 md:columns-3 gap-3 space-y-3">
                {pro.gallery.map((img, i) => (
                  <div key={i} className="break-inside-avoid rounded-xl overflow-hidden">
                    <Image
                      src={img}
                      alt={`Gallery ${i + 1}`}
                      width={400}
                      height={i % 2 === 0 ? 350 : 450}
                      className="w-full object-cover hover:scale-105 transition-transform duration-300"
                    />
                  </div>
                ))}
              </div>
            )}

            {/* Reviews */}
            {activeTab === 'reviews' && (
              <div>
                <div className="flex items-center gap-4 mb-6 p-4 bg-slate-50 rounded-2xl">
                  <div className="text-center">
                    <div className="text-4xl font-black text-slate-900 mb-0.5">{pro.rating}</div>
                    <div className="flex items-center gap-0.5 justify-center mb-1">
                      {[...Array(5)].map((_, i) => (
                        <Star key={i} className="w-4 h-4 fill-yellow-400 text-yellow-400" />
                      ))}
                    </div>
                    <p className="text-xs text-slate-500">{pro.reviewCount} reviews</p>
                  </div>
                  <div className="flex-1 space-y-1.5">
                    {[5, 4, 3, 2, 1].map(star => (
                      <div key={star} className="flex items-center gap-2">
                        <span className="text-xs text-slate-500 w-4">{star}</span>
                        <div className="flex-1 bg-gray-100 rounded-full h-2 overflow-hidden">
                          <div
                            className="h-full bg-gradient-to-r from-blue-500 to-pink-500 rounded-full"
                            style={{ width: star === 5 ? '80%' : star === 4 ? '15%' : '5%' }}
                          />
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
                <div className="space-y-4">
                  {pro.reviews.map(review => (
                    <ReviewCard key={review.id} review={review} />
                  ))}
                </div>
              </div>
            )}

            {/* About */}
            {activeTab === 'about' && (
              <div className="max-w-2xl">
                <h3 className="text-lg font-bold text-slate-900 mb-3">About {pro.name}</h3>
                <p className="text-slate-600 leading-relaxed mb-6">{pro.bio}</p>
                <div className="grid grid-cols-2 gap-4">
                  <div className="bg-slate-50 rounded-2xl p-4">
                    <p className="text-sm text-slate-500 mb-1">Specialty</p>
                    <p className="font-semibold text-slate-900">{pro.specialty}</p>
                  </div>
                  <div className="bg-slate-50 rounded-2xl p-4">
                    <p className="text-sm text-slate-500 mb-1">Location</p>
                    <p className="font-semibold text-slate-900">{pro.location}</p>
                  </div>
                  <div className="bg-slate-50 rounded-2xl p-4">
                    <p className="text-sm text-slate-500 mb-1">Price Range</p>
                    <p className="font-semibold text-slate-900">{pro.priceRange}</p>
                  </div>
                  <div className="bg-slate-50 rounded-2xl p-4">
                    <p className="text-sm text-slate-500 mb-1">Next Available</p>
                    <p className="font-semibold text-green-600">{pro.nextAvailable}</p>
                  </div>
                </div>
              </div>
            )}
          </div>
        </div>

        {/* Sticky Book Button (mobile) */}
        <div className="fixed bottom-6 left-4 right-4 sm:hidden z-40">
          <button
            onClick={() => setShowBooking(true)}
            className="w-full py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-pink-600 text-white font-bold text-base shadow-2xl hover:from-blue-700 hover:to-pink-700 transition-all"
          >
            Book an Appointment
          </button>
        </div>
      </div>

      <Footer />

      {showBooking && (
        <BookingModal
          pro={pro}
          initialService={selectedService}
          onClose={() => { setShowBooking(false); setSelectedService(null); }}
        />
      )}
    </div>
  );
}
