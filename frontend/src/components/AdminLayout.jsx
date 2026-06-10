import React from 'react';
import { Link } from 'react-router-dom';

export default function AdminLayout({ children }) {
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

      {/* Centered Card */}
      <div className="min-h-screen bg-gradient-to-r from-primary-100 to-primary-50 flex items-center justify-center">
        <div className="w-full max-w-md bg-white/90 rounded-xl shadow-lg p-8">
          {children}
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
