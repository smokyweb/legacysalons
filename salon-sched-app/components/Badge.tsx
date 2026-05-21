interface BadgeProps {
  status: string;
  size?: 'sm' | 'md';
}

const statusConfig: Record<string, { label: string; classes: string }> = {
  upcoming: { label: 'Upcoming', classes: 'bg-blue-100 text-blue-700' },
  completed: { label: 'Completed', classes: 'bg-green-100 text-green-700' },
  cancelled: { label: 'Cancelled', classes: 'bg-red-100 text-red-700' },
  'no-show': { label: 'No Show', classes: 'bg-orange-100 text-orange-700' },
  booked: { label: 'Booked', classes: 'bg-green-100 text-green-700' },
  info: { label: 'Info Given', classes: 'bg-blue-100 text-blue-700' },
  transferred: { label: 'Transferred', classes: 'bg-purple-100 text-purple-700' },
  missed: { label: 'Missed', classes: 'bg-gray-100 text-gray-600' },
  VIP: { label: 'VIP', classes: 'bg-yellow-100 text-yellow-700' },
  Regular: { label: 'Regular', classes: 'bg-blue-100 text-blue-600' },
  New: { label: 'New', classes: 'bg-green-100 text-green-700' },
  Loyal: { label: 'Loyal', classes: 'bg-purple-100 text-purple-700' },
  Ambassador: { label: 'Ambassador', classes: 'bg-pink-100 text-pink-700' },
  Bridal: { label: 'Bridal', classes: 'bg-rose-100 text-rose-700' },
};

export default function Badge({ status, size = 'sm' }: BadgeProps) {
  const config = statusConfig[status] || { label: status, classes: 'bg-gray-100 text-gray-700' };
  const sizeClass = size === 'sm' ? 'px-2 py-0.5 text-xs' : 'px-3 py-1 text-sm';
  return (
    <span className={`inline-flex items-center rounded-full font-medium ${config.classes} ${sizeClass}`}>
      {config.label}
    </span>
  );
}
