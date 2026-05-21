'use client';
import { useState } from 'react';
import { Plus, Edit2, Trash2, Sparkles, Clock, DollarSign, Tag } from 'lucide-react';

interface ServiceItem {
  id: string;
  name: string;
  duration: string;
  price: number;
  category: string;
  description: string;
}

const initialServices: ServiceItem[] = [
  { id: 's1', name: 'Balayage Full', duration: '3 hrs', price: 280, category: 'Color', description: 'Full balayage with toning and blowout included.' },
  { id: 's2', name: 'Haircut & Style', duration: '1 hr', price: 85, category: 'Cut', description: 'Precision cut tailored to your face shape.' },
  { id: 's3', name: 'Highlights + Toner', duration: '2.5 hrs', price: 220, category: 'Color', description: 'Partial or full highlights with custom toner.' },
  { id: 's4', name: 'Deep Condition Treatment', duration: '45 min', price: 65, category: 'Treatment', description: 'Protein or moisture treatment for damaged hair.' },
  { id: 's5', name: 'Keratin Smoothing', duration: '2 hrs', price: 180, category: 'Treatment', description: 'Smoothing treatment for frizz-free hair lasting 3 months.' },
  { id: 's6', name: 'Blowout', duration: '45 min', price: 55, category: 'Style', description: 'Professional blowout with heat protection.' },
];

const categoryColors: Record<string, string> = {
  Color: 'bg-pink-100 text-pink-700',
  Cut: 'bg-blue-100 text-blue-700',
  Treatment: 'bg-green-100 text-green-700',
  Style: 'bg-purple-100 text-purple-700',
  Combo: 'bg-amber-100 text-amber-700',
};

export default function ServicesPage() {
  const [services, setServices] = useState<ServiceItem[]>(initialServices);
  const [showForm, setShowForm] = useState(false);
  const [aiLoading, setAiLoading] = useState<string | null>(null);
  const [formData, setFormData] = useState<Partial<ServiceItem>>({ name: '', duration: '', price: 0, category: 'Cut', description: '' });

  const handleAddService = () => {
    if (!formData.name) return;
    const newService: ServiceItem = {
      id: `s${Date.now()}`,
      name: formData.name || '',
      duration: formData.duration || '1 hr',
      price: formData.price || 0,
      category: formData.category || 'Cut',
      description: formData.description || '',
    };
    setServices([...services, newService]);
    setShowForm(false);
    setFormData({ name: '', duration: '', price: 0, category: 'Cut', description: '' });
  };

  const handleDelete = (id: string) => {
    setServices(services.filter(s => s.id !== id));
  };

  const handleAiGenerate = (id: string) => {
    setAiLoading(id);
    setTimeout(() => {
      setServices(prev => prev.map(s => s.id === id
        ? { ...s, description: 'A luxurious, expertly crafted service designed to transform your look and elevate your confidence. Using premium professional-grade products and advanced techniques for stunning, lasting results.' }
        : s
      ));
      setAiLoading(null);
    }, 1800);
  };

  return (
    <div className="p-6 lg:p-8 max-w-7xl mx-auto">
      <div className="flex items-start justify-between mb-8">
        <div>
          <h1 className="text-2xl font-black text-slate-900 mb-1">Services</h1>
          <p className="text-slate-500 text-sm">Manage your service menu and pricing</p>
        </div>
        <button
          onClick={() => setShowForm(!showForm)}
          className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-pink-700 transition-all shadow-sm"
        >
          <Plus className="w-4 h-4" />
          Add Service
        </button>
      </div>

      {showForm && (
        <div className="bg-white rounded-2xl border border-blue-100 shadow-sm p-5 mb-6">
          <h3 className="font-bold text-slate-900 mb-4">New Service</h3>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1">Service Name</label>
              <input
                type="text"
                placeholder="e.g. Balayage Full"
                value={formData.name}
                onChange={e => setFormData({ ...formData, name: e.target.value })}
                className="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1">Duration</label>
              <input
                type="text"
                placeholder="e.g. 1 hr"
                value={formData.duration}
                onChange={e => setFormData({ ...formData, duration: e.target.value })}
                className="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1">Price ($)</label>
              <input
                type="number"
                placeholder="0"
                value={formData.price || ''}
                onChange={e => setFormData({ ...formData, price: Number(e.target.value) })}
                className="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div>
              <label className="block text-xs font-semibold text-slate-600 mb-1">Category</label>
              <select
                value={formData.category}
                onChange={e => setFormData({ ...formData, category: e.target.value })}
                className="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
              >
                <option>Cut</option>
                <option>Color</option>
                <option>Treatment</option>
                <option>Style</option>
                <option>Combo</option>
              </select>
            </div>
          </div>
          <div className="mb-4">
            <label className="block text-xs font-semibold text-slate-600 mb-1">Description</label>
            <textarea
              placeholder="Describe the service..."
              value={formData.description}
              onChange={e => setFormData({ ...formData, description: e.target.value })}
              rows={2}
              className="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
            />
          </div>
          <div className="flex items-center gap-3">
            <button
              onClick={handleAddService}
              className="px-5 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-pink-700 transition-all shadow-sm"
            >
              Add Service
            </button>
            <button
              onClick={() => setShowForm(false)}
              className="px-5 py-2 rounded-xl border border-gray-200 text-slate-700 text-sm font-medium hover:bg-gray-50 transition-all"
            >
              Cancel
            </button>
          </div>
        </div>
      )}

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {services.map(service => (
          <div key={service.id} className="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all group">
            <div className="flex items-start justify-between mb-3">
              <span className={`inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold ${categoryColors[service.category] || 'bg-gray-100 text-gray-600'}`}>
                <Tag className="w-3 h-3" />
                {service.category}
              </span>
              <div className="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button className="p-1.5 rounded-lg hover:bg-blue-50 text-slate-400 hover:text-blue-600 transition-colors">
                  <Edit2 className="w-3.5 h-3.5" />
                </button>
                <button
                  onClick={() => handleDelete(service.id)}
                  className="p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-500 transition-colors"
                >
                  <Trash2 className="w-3.5 h-3.5" />
                </button>
              </div>
            </div>
            <h3 className="font-bold text-slate-900 mb-2">{service.name}</h3>
            <div className="flex items-center gap-3 mb-3">
              <span className="flex items-center gap-1 text-xs text-slate-500">
                <Clock className="w-3 h-3" />
                {service.duration}
              </span>
              <span className="flex items-center gap-1 text-sm font-bold text-slate-900">
                <DollarSign className="w-3.5 h-3.5 text-green-500" />
                {service.price}
              </span>
            </div>
            <p className="text-xs text-slate-500 leading-relaxed mb-4 min-h-[2.5rem]">
              {aiLoading === service.id ? (
                <span className="block w-full h-3 bg-gradient-to-r from-slate-200 via-blue-100 to-slate-200 rounded animate-pulse" />
              ) : (
                service.description || 'No description yet.'
              )}
            </p>
            <button
              onClick={() => handleAiGenerate(service.id)}
              disabled={!!aiLoading}
              className="flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-pink-600 transition-colors disabled:opacity-50"
            >
              <Sparkles className="w-3.5 h-3.5" />
              {aiLoading === service.id ? 'Generating...' : '✨ Generate with AI'}
            </button>
          </div>
        ))}
      </div>
    </div>
  );
}
