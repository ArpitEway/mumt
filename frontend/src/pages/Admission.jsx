import React, { useState } from 'react';
import PageWrapper from '../components/PageWrapper.jsx';
import { api } from '../api/client.js';

export default function Admission() {
  const [form, setForm] = useState({
    fullName: '',
    email: '',
    mobile: '',
    program: ''
  });
  const [error, setError] = useState('');
  const [success, setSuccess] = useState(false);
  const [saving, setSaving] = useState(false);

  const updateField = (field, value) => {
    setForm(prev => ({ ...prev, [field]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!form.fullName || !form.email || !form.mobile || !form.program) {
      setError('Please fill all fields');
      return;
    }
    setError('');
    setSaving(true);
    try {
      await api('/admission', { method: 'POST', body: form });
      setSuccess(true);
      setForm({ fullName: '', email: '', mobile: '', program: '' });
    } catch (err) {
      setError(err.message || 'Submission failed');
    } finally {
      setSaving(false);
    }
  };

  return (
    <PageWrapper>
      <div className="admission-page max-w-xl mx-auto p-4 bg-white rounded shadow">
        <h2 className="text-2xl font-bold mb-4">Admission Form</h2>
        {error && <div className="alert mb-4 text-red-600">{error}</div>}
        {success && <div className="alert mb-4 text-green-600">Submitted successfully!</div>}
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium mb-1">Full Name</label>
            <input
              type="text"
              value={form.fullName}
              onChange={e => updateField('fullName', e.target.value)}
              className="w-full border rounded px-3 py-2"
              required
            />
          </div>
          <div>
            <label className="block text-sm font-medium mb-1">Email</label>
            <input
              type="email"
              value={form.email}
              onChange={e => updateField('email', e.target.value)}
              className="w-full border rounded px-3 py-2"
              required
            />
          </div>
          <div>
            <label className="block text-sm font-medium mb-1">Mobile</label>
            <input
              type="tel"
              value={form.mobile}
              onChange={e => updateField('mobile', e.target.value.replace(/\D/g, '').slice(0,10))}
              className="w-full border rounded px-3 py-2"
              placeholder="10‑digit number"
              required
            />
          </div>
          <div>
            <label className="block text-sm font-medium mb-1">Program of Interest</label>
            <input
              type="text"
              value={form.program}
              onChange={e => updateField('program', e.target.value)}
              className="w-full border rounded px-3 py-2"
              required
            />
          </div>
          <div className="flex justify-end">
            <button
              type="submit"
              disabled={saving}
              className="primary-button px-4 py-2"
            >
              {saving ? 'Submitting…' : 'Submit'}
            </button>
          </div>
        </form>
      </div>
    </PageWrapper>
  );
}
