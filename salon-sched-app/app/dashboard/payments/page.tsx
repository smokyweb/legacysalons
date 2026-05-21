import { DollarSign, TrendingUp, Clock, CreditCard } from 'lucide-react';

const transactions = [
  { id: 'TXN001', client: 'Sarah Mitchell', service: 'Balayage Full', amount: 280, date: 'May 21, 2026', status: 'Paid' },
  { id: 'TXN002', client: 'Jennifer Adams', service: 'Haircut & Style', amount: 85, date: 'May 21, 2026', status: 'Paid' },
  { id: 'TXN003', client: 'Nina Walsh', service: 'Bridal Makeup', amount: 350, date: 'May 21, 2026', status: 'Pending' },
  { id: 'TXN004', client: 'Emma Davis', service: 'Gel Manicure', amount: 55, date: 'May 20, 2026', status: 'Paid' },
  { id: 'TXN005', client: 'Lisa Chen', service: 'Hydra-Glow Facial', amount: 165, date: 'May 20, 2026', status: 'Paid' },
  { id: 'TXN006', client: 'Alex Torres', service: 'Deep Tissue Massage', amount: 140, date: 'May 19, 2026', status: 'Paid' },
  { id: 'TXN007', client: 'Tyler Brooks', service: 'Silk Press', amount: 130, date: 'May 18, 2026', status: 'Refunded' },
  { id: 'TXN008', client: 'David Park', service: 'Korean Glass Skin Facial', amount: 175, date: 'May 18, 2026', status: 'Paid' },
];

const weeklyRevenue = [
  { day: 'Mon', amount: 420 },
  { day: 'Tue', amount: 680 },
  { day: 'Wed', amount: 540 },
  { day: 'Thu', amount: 820 },
  { day: 'Fri', amount: 960 },
  { day: 'Sat', amount: 1120 },
  { day: 'Sun', amount: 340 },
];

const maxRevenue = Math.max(...weeklyRevenue.map(d => d.amount));

const statusColors: Record<string, string> = {
  Paid: 'bg-green-100 text-green-700',
  Pending: 'bg-amber-100 text-amber-700',
  Refunded: 'bg-red-100 text-red-600',
};

const stats = [
  { title: 'Revenue Today', value: '$1,380', change: '+$210 vs yesterday', icon: DollarSign, gradient: 'from-blue-500 to-blue-600' },
  { title: 'This Week', value: '$4,880', change: '+12%', icon: TrendingUp, gradient: 'from-green-500 to-emerald-600' },
  { title: 'This Month', value: '$14,240', change: '+8% vs last mo', icon: CreditCard, gradient: 'from-violet-500 to-purple-600' },
  { title: 'Pending Payouts', value: '$435', change: 'Est. 2 business days', icon: Clock, gradient: 'from-amber-500 to-orange-600' },
];

export default function PaymentsPage() {
  return (
    <div className="p-6 lg:p-8 max-w-7xl mx-auto">
      <div className="mb-8">
        <h1 className="text-2xl font-black text-slate-900 mb-1">Payments</h1>
        <p className="text-slate-500 text-sm">Track revenue, transactions, and payouts</p>
      </div>

      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {stats.map(({ title, value, change, icon: Icon, gradient }) => (
          <div key={title} className="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div className={`w-10 h-10 rounded-xl bg-gradient-to-br ${gradient} flex items-center justify-center shadow-md mb-3`}>
              <Icon className="w-5 h-5 text-white" />
            </div>
            <p className="text-2xl font-black text-slate-900 mb-0.5">{value}</p>
            <p className="text-xs text-slate-500 font-medium mb-1">{title}</p>
            <p className="text-xs text-green-600">{change}</p>
          </div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div className="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <h2 className="font-bold text-slate-900 mb-4">Revenue — Last 7 Days</h2>
          <div className="flex items-end gap-2 h-40">
            {weeklyRevenue.map(({ day, amount }) => (
              <div key={day} className="flex-1 flex flex-col items-center gap-2">
                <span className="text-xs font-bold text-slate-600">${amount >= 1000 ? `${(amount / 1000).toFixed(1)}k` : amount}</span>
                <div
                  className="w-full rounded-t-lg bg-gradient-to-t from-blue-500 to-pink-400 transition-all"
                  style={{ height: `${(amount / maxRevenue) * 100}px` }}
                />
                <span className="text-xs text-slate-400 font-medium">{day}</span>
              </div>
            ))}
          </div>
        </div>

        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <h2 className="font-bold text-slate-900 mb-4">Payout Account</h2>
          <div className="bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl p-4 mb-4">
            <div className="flex items-center justify-between mb-4">
              <span className="text-white/60 text-xs">Bank Account</span>
              <CreditCard className="w-4 h-4 text-white/40" />
            </div>
            <p className="text-white font-mono text-sm mb-1">Chase Checking</p>
            <p className="text-white/60 text-xs">••••  ••••  ••••  4821</p>
            <div className="mt-4 pt-3 border-t border-white/10">
              <p className="text-white/60 text-xs mb-1">Next payout</p>
              <p className="text-white font-bold">$435.00</p>
              <p className="text-white/50 text-xs">May 23, 2026</p>
            </div>
          </div>
          <button className="w-full py-2.5 rounded-xl border border-gray-200 text-slate-700 text-sm font-medium hover:bg-gray-50 transition-all">
            Manage Payout Settings
          </button>
        </div>
      </div>

      <div className="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div className="flex items-center justify-between p-5 border-b border-gray-100">
          <h2 className="font-bold text-slate-900">Recent Transactions</h2>
          <button className="text-sm text-blue-600 font-medium hover:text-blue-700">Export CSV</button>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="border-b border-gray-100">
                <th className="text-left px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Client</th>
                <th className="text-left px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Service</th>
                <th className="text-left px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Amount</th>
                <th className="text-left px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Date</th>
                <th className="text-left px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody>
              {transactions.map((txn, i) => (
                <tr key={txn.id} className={`border-b border-gray-50 hover:bg-slate-50 transition-colors ${i === transactions.length - 1 ? 'border-0' : ''}`}>
                  <td className="px-5 py-3.5">
                    <div className="flex items-center gap-3">
                      <div className="w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-pink-100 flex items-center justify-center text-xs font-bold text-blue-600 flex-shrink-0">
                        {txn.client[0]}
                      </div>
                      <span className="text-sm font-semibold text-slate-900">{txn.client}</span>
                    </div>
                  </td>
                  <td className="px-5 py-3.5 text-sm text-slate-500">{txn.service}</td>
                  <td className="px-5 py-3.5 text-sm font-bold text-slate-900">${txn.amount}</td>
                  <td className="px-5 py-3.5 text-sm text-slate-500">{txn.date}</td>
                  <td className="px-5 py-3.5">
                    <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-semibold ${statusColors[txn.status]}`}>{txn.status}</span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
