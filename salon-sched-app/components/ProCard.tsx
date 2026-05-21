import Image from 'next/image';
import Link from 'next/link';
import { Star, MapPin, Clock } from 'lucide-react';
import { Pro } from '@/lib/mockData';

export default function ProCard({ pro }: { pro: Pro }) {
  return (
    <Link href={`/pro/${pro.slug}`} className="block h-full">
      <div className={`bg-white rounded-2xl overflow-hidden border shadow-sm hover:shadow-lg transition-all duration-300 group cursor-pointer h-full flex flex-col ${pro.featured ? 'border-blue-200 ring-2 ring-blue-100' : 'border-gray-100'}`}>
        <div className="relative h-48 overflow-hidden">
          <Image src={pro.image} alt={pro.name} fill className="object-cover group-hover:scale-105 transition-transform duration-500" />
          <div className="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent" />
          {pro.badge && (
            <div className="absolute top-3 left-3">
              <span className="px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-600 to-pink-600 text-white shadow-md">{pro.badge}</span>
            </div>
          )}
          <div className="absolute top-3 right-3">
            <span className="px-2 py-1 rounded-lg text-xs font-semibold bg-black/40 text-white backdrop-blur-sm">{pro.priceRange}</span>
          </div>
          <div className="absolute bottom-3 left-3 flex items-center gap-1">
            <Star className="w-3.5 h-3.5 fill-yellow-400 text-yellow-400" />
            <span className="text-sm font-bold text-white">{pro.rating}</span>
            <span className="text-xs text-white/80">({pro.reviewCount})</span>
          </div>
        </div>
        <div className="p-4 flex flex-col flex-1">
          <h3 className="font-bold text-slate-900 mb-0.5 group-hover:text-blue-600 transition-colors">{pro.name}</h3>
          <p className="text-sm text-slate-500 mb-3">{pro.specialty}</p>
          <div className="flex items-center gap-1 text-slate-400 text-xs mb-4">
            <MapPin className="w-3.5 h-3.5 flex-shrink-0" />
            <span className="truncate">{pro.location}</span>
          </div>
          <div className="mt-auto flex items-center justify-between gap-2">
            <div className="flex items-center gap-1 text-xs text-green-600 bg-green-50 px-2 py-1 rounded-lg">
              <Clock className="w-3 h-3" />
              <span>{pro.nextAvailable}</span>
            </div>
            <span className="px-3 py-1.5 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-xs font-semibold hover:from-blue-700 hover:to-pink-700 transition-all">
              Book Now
            </span>
          </div>
        </div>
      </div>
    </Link>
  );
}
