import { LucideIcon } from 'lucide-react';

interface StatsCardProps {
  title: string;
  value: string | number;
  change?: string;
  changePositive?: boolean;
  icon: LucideIcon;
  gradient?: boolean;
}

export default function StatsCard({ title, value, change, changePositive = true, icon: Icon, gradient = false }: StatsCardProps) {
  return (
    <div className={`rounded-2xl p-5 shadow-sm border ${gradient ? 'bg-gradient-to-br from-blue-600 to-pink-600 border-transparent text-white' : 'bg-white border-gray-100'}`}>
      <div className="flex items-start justify-between mb-3">
        <div className={`w-10 h-10 rounded-xl flex items-center justify-center ${gradient ? 'bg-white/20' : 'bg-gradient-to-br from-blue-50 to-pink-50'}`}>
          <Icon className={`w-5 h-5 ${gradient ? 'text-white' : 'text-blue-600'}`} />
        </div>
        {change && (
          <span className={`text-xs font-medium px-2 py-0.5 rounded-full ${
            gradient
              ? 'bg-white/20 text-white'
              : changePositive
                ? 'bg-green-100 text-green-700'
                : 'bg-red-100 text-red-700'
          }`}>
            {changePositive ? '↑' : '↓'} {change}
          </span>
        )}
      </div>
      <div className={`text-2xl font-bold mb-0.5 ${gradient ? 'text-white' : 'text-slate-900'}`}>{value}</div>
      <div className={`text-sm ${gradient ? 'text-white/80' : 'text-slate-500'}`}>{title}</div>
    </div>
  );
}
