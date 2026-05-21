import Image from 'next/image';
import { Star } from 'lucide-react';
import { Review } from '@/lib/mockData';

export default function ReviewCard({ review }: { review: Review }) {
  return (
    <div className="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
      <div className="flex items-start gap-3 mb-3">
        <div className="w-10 h-10 rounded-full overflow-hidden relative flex-shrink-0">
          <Image src={review.avatar} alt={review.author} fill className="object-cover" />
        </div>
        <div className="flex-1 min-w-0">
          <div className="flex items-center justify-between gap-2">
            <p className="font-semibold text-slate-900 text-sm">{review.author}</p>
            <span className="text-xs text-slate-400 flex-shrink-0">{review.date}</span>
          </div>
          <div className="flex items-center gap-0.5 mt-0.5">
            {[...Array(5)].map((_, i) => (
              <Star
                key={i}
                className="w-3.5 h-3.5"
                fill={i < review.rating ? '#F59E0B' : 'none'}
                stroke={i < review.rating ? '#F59E0B' : '#D1D5DB'}
              />
            ))}
          </div>
        </div>
      </div>
      <p className="text-sm text-slate-600 leading-relaxed">{review.comment}</p>
    </div>
  );
}
