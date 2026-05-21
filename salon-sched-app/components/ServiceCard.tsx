'use client';
import { Clock } from 'lucide-react';
import { Service } from '@/lib/mockData';

interface ServiceCardProps {
  service: Service;
  onBook?: (service: Service) => void;
}

export default function ServiceCard({ service, onBook }: ServiceCardProps) {
  return (
    <div className="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all">
      <div className="flex items-start justify-between gap-4">
        <div className="flex-1">
          <div className="flex items-center gap-2 mb-1">
            <span className="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">{service.category}</span>
          </div>
          <h3 className="font-semibold text-slate-900 mb-1">{service.name}</h3>
          <p className="text-sm text-slate-500 mb-3">{service.description}</p>
          <div className="flex items-center gap-4">
            <span className="flex items-center gap-1 text-sm text-slate-500">
              <Clock className="w-3.5 h-3.5" />
              {service.duration}
            </span>
            <span className="text-sm font-bold text-slate-900">${service.price}</span>
          </div>
        </div>
        {onBook && (
          <button
            onClick={() => onBook(service)}
            className="flex-shrink-0 px-4 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-pink-700 transition-all shadow-sm"
          >
            Book
          </button>
        )}
      </div>
    </div>
  );
}
