import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import AdminLayout from '../components/AdminLayout.jsx';
import { authAPI } from '../api/client.js';
import { adminLoginSchema } from '@mmyvv/shared';
import { useAuthStore } from '../store/index.js';

export default function AdminLogin() {
  const navigate = useNavigate();
  const { setUser } = useAuthStore();
  const [form, setForm] = useState({ username: '', password: '' });
  const [error, setError] = useState('');
  const [fieldErrors, setFieldErrors] = useState({});

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({ ...prev, [name]: value }));
    if (fieldErrors[name]) setFieldErrors((prev) => ({ ...prev, [name]: undefined }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setFieldErrors({});
    const payload = { ...form, role: 'admin' };
    const result = adminLoginSchema.safeParse(payload);
    if (!result.success) {
      const formatted = {};
      result.error.errors.forEach((err) => {
        const path = err.path.join('.');
        if (!formatted[path]) formatted[path] = [];
        formatted[path].push(err.message);
      });
      setFieldErrors(formatted);
      return;
    }
    try {
      const resp = await authAPI.adminLogin(payload);
      if (resp.success) {
        localStorage.setItem('token', resp.data.token);
        localStorage.setItem('mumt_token', resp.data.token);
        setUser(resp.data.user);
        navigate('/admin/dashboard');
      } else {
        setError(resp.error || 'Login failed');
      }
    } catch (err) {
      setError(err.message || 'Login failed');
    }
  };

  return (
    <>
      {/* Header */}
      <header className="w-full bg-gradient-to-r from-primary-300 to-primary-400 text-white px-4 py-2 mb-0 shadow-md">
        <div className="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
          <div className="text-sm text-white flex items-center gap-4 font-medium">
            <span>Maharishi Road, Mangla Bilaspur - 495001 Chhattisgarh</span>
            <span className="hidden sm:inline">|</span>
            <a href="mailto:utdpaniin@gmail.com" className="underline text-white">mumt.registrar1@gmail.com</a>
          </div>
          <Link to="/" className="inline-flex items-center justify-center rounded-lg border-2 border-slate-800 bg-white px-5 py-1 text-sm font-semibold text-primary-300 transition hover:bg-white/60">
            Back to Home
          </Link>
        </div>
      </header>
       <div className="w-full bg-white/60 border-b-2 border-primary-100 px-4 py-6">
        <div className="max-w-7xl mx-auto flex items-center justify-between">
          <div className="flex items-center gap-4">
            <div className="w-12 h-12 rounded-full bg-primary-50 flex items-center justify-center text-xl"> <img className="site-brand-logo" src="/assets/logo.png" alt="University logo" /></div>
            <h2 className="text-2xl font-bold text-primary-300">Maharishi University of Management and Technology Bilaspur</h2>
          </div>
          {/* <div className="w-16 h-16 rounded-full bg-primary-50 flex items-center justify-center text-4xl hidden sm:flex">🧑‍🎓</div> */}
        </div>
      </div>

      {/* Main Content */}
      <div className="min-h-screen bg-gradient-to-r from-primary-100 to-primary-50 flex items-center justify-center">
        <div className="w-full max-w-md bg-white/90 rounded-xl shadow-lg p-8">
          <h2 className="text-2xl font-bold mb-4 text-center">Administrator Login</h2>
          {error && (
            <div className="bg-red-100 border border-red-300 text-red-600 p-2 mb-4 rounded">
              {error}
            </div>
          )}
          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-sm font-medium mb-1">Username</label>
              <input
                type="text"
                name="username"
                value={form.username}
                onChange={handleChange}
                className="input-field w-full"
              />
              {fieldErrors.username && (
                <p className="text-red-500 text-sm mt-1">{fieldErrors.username.join(', ')}</p>
              )}
            </div>
            <div>
              <label className="block text-sm font-medium mb-1">Password</label>
              <input
                type="password"
                name="password"
                value={form.password}
                onChange={handleChange}
                className="input-field w-full"
              />
              {fieldErrors.password && (
                <p className="text-red-500 text-sm mt-1">{fieldErrors.password.join(', ')}</p>
              )}
            </div>
            <button type="submit" className="w-full btn-primary py-2">
              Sign In
            </button>
          </form>
        </div>
      </div>

      {/* Footer */}
      <footer className="w-full bg-gradient-to-r from-primary-300 to-primary-400 text-white mt-12 shadow-md">
        <div className="max-w-7xl mx-auto px-4 py-4 text-center">
          MUMT © 2026. All rights reserved.
        </div>
      </footer>
    </>
  );
}
