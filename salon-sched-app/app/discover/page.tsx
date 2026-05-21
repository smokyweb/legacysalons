'use client';
import { useState } from 'react';
import { Search, MapPin, SlidersHorizontal, Star } from 'lucide-react';
import Navbar from '@/components/Navbar';
import Footer from '@/components/Footer';
import ProCard from '@/components/ProCard';
import { pros } from '@/lib/mockData';

const categories = ['All', 'Hair', 'Nails', 'Spa', 'Barber', 'Wellness', 'Makeup', 'Lashes'];
const sortOptions = ['Nearest', 'Highest Rated', 'Next Available', 'Price: Low-High'];

export default function DiscoverPage() {
  const [search, setSearch] = useState('');
  const [activeCategory, setActiveCategory] = useState('All');
  const [activeSort, setActiveSort] = useState('Highest Rated');

  const categoryMap: Record<string, string[]> = {
    Hair: ['Hair', 'Colorist', 'Stylist', 'Barber', 'Textured'],
    Nails: ['Nail', 'Gel', 'Manicure'],
    Spa: ['Spa', 'Massage', 'Wellness', 'Holistic'],
    Barber: ['Barber', 'Fade'],
    Wellness: ['Wellness', 'Reiki', 'Holistic'],
    Makeup: ['Makeup', 'MUA', 'Bridal'],
    Lashes: ['Lash', 'Brow'],
  };

  const filtered = pros.filter(pro => {
    const matchesSearch = !search ||
      pro.name.toLowerCase().includes(search.toLowerCase()) ||
      pro.specialty.toLowerCase().includes(search.toLowerCase()) ||
      pro.location.toLowerCase().includes(search.toLowerCase());

    const matchesCategory = activeCategory === 'All' ||
      (categoryMap[activeCategory] || []).some(kw =>
        pro.specialty.toLowerCase().includes(kw.toLowerCase())
      );

    return matchesSearch && matchesCategory;
  });

  const sorted = [...filtered].sort((a, b) => {
    if (activeSort === 'Highest Rated') return b.rating - a.rating;
    if (activeSort === 'Price: Low-High') return a.services[0]?.price - b.services[0]?.price;
    return 0;
  });

  return (
    <div className="min-h-screen bg-slate-50">
      <Navbar />

      {/* Hero search */}
      <section className="pt-24 pb-10 bg-gradient-to-br from-blue-600 to-pink-600">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-4xl font-black text-white mb-3">Find Your Perfect Pro</h1>
          <p className="text-white/80 mb-8">Discover beauty professionals near you. Book instantly, 24/7.</p>
          <div className="bg-white rounded-2xl p-2 flex flex-col sm:flex-row gap-2 shadow-xl">
            <div className="flex items-center gap-3 flex-1 px-4">
              <MapPin className="w-5 h-5 text-slate-400 flex-shrink-0" />
              <input
                type="text"
                placeholder="City, zip, or neighborhood"
                className="flex-1 outline-none text-slate-700 placeholder-slate-400 text-sm"
              />
            </div>
            <div className="flex items-center gap-3 flex-1 px-4 border-t sm:border-t-0 sm:border-l border-gray-100 pt-2 sm:pt-0">
              <Search className="w-5 h-5 text-slate-400 flex-shrink-0" />
              <input
                type="text"
                value={search}
                onChange={e => setSearch(e.target.value)}
                placeholder="Service type (hair, nails, spa...)"
                className="flex-1 outline-none text-slate-700 placeholder-slate-400 text-sm"
              />
            </div>
            <button className="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white font-semibold text-sm hover:from-blue-700 hover:to-pink-700 transition-all">
              Search
            </button>
          </div>
        </div>
      </section>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Filters */}
        <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
          <div className="flex items-center gap-2 flex-wrap">
            {categories.map(cat => (
              <button
                key={cat}
                onClick={() => setActiveCategory(cat)}
                className={`px-4 py-2 rounded-xl text-sm font-medium transition-all ${
                  activeCategory === cat
                    ? 'bg-gradient-to-r from-blue-600 to-pink-600 text-white shadow-md'
                    : 'bg-white text-slate-600 border border-gray-200 hover:border-blue-300 hover:text-blue-600'
                }`}
              >
                {cat}
              </button>
            ))}
          </div>
          <div className="flex items-center gap-3">
            <SlidersHorizontal className="w-4 h-4 text-slate-400" />
            <select
              value={activeSort}
              onChange={e => setActiveSort(e.target.value)}
              className="text-sm font-medium text-slate-700 border border-gray-200 rounded-xl px-3 py-2 outline-none focus:border-blue-400 bg-white"
            >
              {sortOptions.map(opt => <option key={opt}>{opt}</option>)}
            </select>
          </div>
        </div>

        {/* Results count */}
        <div className="flex items-center justify-between mb-6">
          <p className="text-sm text-slate-500">
            Showing <strong className="text-slate-900">{sorted.length}</strong> professionals
          </p>
          <div className="flex items-center gap-1 text-sm text-slate-500">
            <Star className="w-4 h-4 fill-yellow-400 text-yellow-400" />
            <span>All verified & rated</span>
          </div>
        </div>

        {/* Featured sponsor */}
        <div className="mb-6 bg-gradient-to-r from-blue-600/5 to-pink-600/5 rounded-2xl border-2 border-blue-100 p-4">
          <div className="flex items-center gap-2 mb-3">
            <span className="text-xs font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full">FEATURED</span>
            <span className="text-xs text-slate-400">Sponsored listing</span>
          </div>
          <div className="grid grid-cols-1">
            <ProCard pro={{ ...pros[0], featured: true }} />
          </div>
        </div>

        {/* Pro grid */}
        {sorted.length > 0 ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            {sorted.slice(1).map(pro => (
              <ProCard key={pro.id} pro={pro} />
            ))}
          </div>
        ) : (
          <div className="text-center py-16">
            <Search className="w-12 h-12 text-slate-300 mx-auto mb-4" />
            <h3 className="text-lg font-semibold text-slate-600 mb-2">No results found</h3>
            <p className="text-slate-400 text-sm">Try a different search or category filter.</p>
          </div>
        )}
      </div>

      <Footer />
    </div>
  );
}
