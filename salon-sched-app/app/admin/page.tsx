import { Users, DollarSign, Zap, Activity, LayoutDashboard, Settings, CreditCard, TrendingUp } from 'lucide-react';

const tenants = [
  { id: 1, name: 'Maya Glow Studio', plan: 'Pro', status: 'Active', joined: 'Jan 12, 2026', revenue: '$29/mo' },
  { id: 2, name: "Carlos Cuts & Fades", plan: 'Enterprise', status: 'Active', joined: 'Feb 5, 2026', revenue: '$99/mo' },
  { id: 3, name: 'Aisha Wellness Spa', plan: 'Pro', status: 'Active', joined: 'Mar 22, 2026', revenue: '$29/mo' },
  { id: 4, name: "Nina's Nail Studio", plan: 'Free', status: 'Active', joined: 'Apr 1, 2026', revenue: '$0/mo' },
  { id: 5, name: 'James Wellness Chicago', plan: 'Pro', status: 'Trial', joined: 'May 10, 2026', revenue: '$29/mo' },
  { id: 6, name: 'Sofia Makeup Artist', plan: 'Enterprise', status: 'Active', joined: 'Jan 30, 2026', revenue: '$99/mo' },
  { id: 7, name: 'Dr. Kim Skin Clinic', plan: 'Pro', status: 'Active', joined: 'Feb 14, 2026', revenue: '$29/mo' },
  { id: 8, name: "Layla's Lash Studio", plan: 'Free', status: 'Inactive', joined: 'Mar 5, 2026', revenue: '$0/mo' },
];

const stats = [
  { title: 'Total Tenants', value: '1,284', change: '+48 this month', icon: Users, gradient: 'from-blue-500 to-blue-600' },
  { title: 'Monthly Revenue', value: '$38,420', change: '+12% vs last mo', icon: DollarSign, gradient: 'from-green-500 to-emerald-600' },
  { title: 'Active AI Calls Today', value: '2,841', change: 'Across all tenants', icon: Zap, gradient: 'from-violet-500 to-purple-600' },
  { title: 'Platform Uptime', value: '99.98%', change: 'Last 30 days', icon: Activity, gradient: 'from-pink-500 to-rose-600' },
];

const navItems = [
  { icon: LayoutDashboard, label: 'Dashboard', active: true },
  { icon: Users, label: 'Tenants' },
  { icon: CreditCard, label: 'Subscriptions' },
  { icon: Zap, label: 'AI Usage' },
  { icon: TrendingUp, label: 'Analytics' },
  { icon: Settings, label: 'Settings' },
];

const statusColors: Record<string, string> = {
  Active: 'bg-green-100 text-green-700',
  Trial: 'bg-amber-100 text-amber-700',
  Inactive: 'bg-slate-100 text-slate-500',
};

const planColors: Record<string, string> = {
  Pro: 'bg-blue-100 text-blue-700',
  Enterprise: 'bg-purple-100 text-purple-700',
  Free: 'bg-gray-100 text-gray-600',
};

export default function AdminPage() {
  return (
    <div className="min-h-screen flex bg-slate-50">
      {/* Sidebar */}
      <div className="w-64 min-h-screen bg-slate-900 flex flex-col border-r border-slate-800">
        <div className="p-6 border-b border-slate-800">
          <div className="flex items-center gap-2">
            <div className="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-600 to-pink-600 flex items-center justify-center">
              <Zap className="w-4 h-4 text-white" />
            </div>
            <div>
              <span className="text-white font-bold text-sm">GlowBook</span>
              <p className="text-slate-500 text-xs">Admin Console</p>
            </div>
          </div>
        </div>
        <nav className="flex-1 p-4 space-y-1">
          {navItems.map(({ icon: Icon, label, active }) => (
            <div
              key={label}
              className={`flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium cursor-pointer transition-all ${
                active ? 'bg-gradient-to-r from-blue-600/20 to-pink-600/20 text-white border border-blue-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800'
              }`}
            >
              <Icon style={{ width: '18px', height: '18px' }} className={`flex-shrink-0 ${active ? 'text-blue-400' : ''}`} />
              {label}
            </div>
          ))}
        </nav>
        <div className="p-4 border-t border-slate-800">
          <div className="flex items-center gap-3 px-3 py-2">
            <div className="w-8 h-8 rounded-full bg-gradient-to-r from-blue-600 to-pink-600 flex items-center justify-center text-white text-xs font-bold">A</div>
            <div>
              <p className="text-white text-xs font-semibold">Admin User</p>
              <p className="text-slate-500 text-xs">admin@glowbook.io</p>
            </div>
          </div>
        </div>
      </div>

      {/* Main */}
      <div className="flex-1 p-8">
        <div className="mb-8">
          <h1 className="text-2xl font-black text-slate-900 mb-1">Admin Dashboard</h1>
          <p className="text-slate-500 text-sm">Platform overview and tenant management</p>
        </div>

        {/* Stats */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
          {stats.map(({ title, value, change, icon: Icon, gradient }) => (
            <div key={title} className="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
              <div className="flex items-start justify-between mb-3">
                <div className={`w-10 h-10 rounded-xl bg-gradient-to-br ${gradient} flex items-center justify-center shadow-md`}>
                  <Icon className="w-5 h-5 text-white" />
                </div>
              </div>
              <p className="text-2xl font-black text-slate-900 mb-0.5">{value}</p>
              <p className="text-xs text-slate-500 font-medium mb-1">{title}</p>
              <p className="text-xs text-green-600">{change}</p>
            </div>
          ))}
        </div>

        {/* Tenants table */}
        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm">
          <div className="flex items-center justify-between p-5 border-b border-gray-100">
            <h2 className="font-bold text-slate-900">Tenants</h2>
            <button className="px-4 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-pink-700 transition-all shadow-sm">
              + Invite Tenant
            </button>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr className="border-b border-gray-100">
                  <th className="text-left px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Business</th>
                  <th className="text-left px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Plan</th>
                  <th className="text-left px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                  <th className="text-left px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Joined</th>
                  <th className="text-left px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Revenue</th>
                  <th className="text-left px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody>
                {tenants.map((tenant, i) => (
                  <tr key={tenant.id} className={`border-b border-gray-50 hover:bg-slate-50 transition-colors ${i === tenants.length - 1 ? 'border-0' : ''}`}>
                    <td className="px-5 py-3.5">
                      <div className="flex items-center gap-3">
                        <div className="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-100 to-pink-100 flex items-center justify-center text-xs font-bold text-blue-600">
                          {tenant.name[0]}
                        </div>
                        <span className="text-sm font-semibold text-slate-900">{tenant.name}</span>
                      </div>
                    </td>
                    <td className="px-5 py-3.5">
                      <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-semibold ${planColors[tenant.plan]}`}>{tenant.plan}</span>
                    </td>
                    <td className="px-5 py-3.5">
                      <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-semibold ${statusColors[tenant.status]}`}>{tenant.status}</span>
                    </td>
                    <td className="px-5 py-3.5 text-sm text-slate-500">{tenant.joined}</td>
                    <td className="px-5 py-3.5 text-sm font-semibold text-slate-900">{tenant.revenue}</td>
                    <td className="px-5 py-3.5">
                      <button className="text-xs text-blue-600 hover:text-blue-700 font-medium">View →</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  );
}
