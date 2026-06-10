import React from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth, isAdmin } from '../state/AuthContext.jsx';

export const Spinner = () => (
  <svg className="animate-spin h-8 w-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
  </svg>
);

export default function PageWrapper({ children }) {
   const { user, token, logout } = useAuth();
    const navigate = useNavigate();
  
    const handleLogout = () => {
      logout();
      navigate('/login');
    };
  const homeLink = user?.role === 'student' ? `/student-dashboard/${user.id}` : '/';

  return (
    <div className="min-h-screen flex flex-col justify-between bg-gradient-to-r from-primary-100 to-primary-50">
      {/* Header */}
      <header className="w-full bg-gradient-to-r from-primary-300 to-primary-400 text-white px-4 py-2 mb-0 shadow-md">
        <div className="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
          <div className="text-sm text-white flex items-center gap-4 font-medium">
            <span>Maharishi Road, Mangla Bilaspur - 495001
Chhattisgarh</span>
            <span className="hidden sm:inline">|</span>
            <a href="mailto:utdpaniin@gmail.com" className="underline text-white">mumt.registrar1@gmail.com</a>
          </div>
           {token && user ? (
              <button onClick={handleLogout} className="bg-white text-primary-600 px-3 py-1 rounded">
            Logout
          </button>
           ): (
            <Link to="/login">
              <button
                type="button"
                className="inline-flex items-center justify-center rounded-lg border-2 border-slate-800 bg-white px-5 py-1 text-sm font-semibold text-primary-300 transition hover:bg-white/60"
              >
                Sign In
          </button>
            </Link>
           )}
        </div>
        
      </header>
       <div className="w-full bg-white/60 border-b-2 border-primary-100 px-4 py-6 mb-8">
        <div className="max-w-7xl mx-auto flex items-center justify-between">
          <div className="flex items-center gap-4">
            <Link to={homeLink} className="flex items-center gap-4">
              <div className="w-12 h-12 rounded-full bg-primary-50 flex items-center justify-center text-xl">
                <img className="site-brand-logo" src="/assets/logo.png" alt="University logo" />
              </div>
              <h2 className="text-2xl font-bold text-primary-300">Maharishi University of Management and Technology Bilaspur</h2>
            </Link>
          </div>
          {/* <div className="w-16 h-16 rounded-full bg-primary-50 flex items-center justify-center text-4xl hidden sm:flex">🧑‍🎓</div> */}
        </div>
      </div>

      {/* Main content */}
      <main className="flex-1 flex items-center justify-center px-4 py-2">
        <div className="w-full max-w-7xl mx-auto">
          {children}
        </div>
      </main>

      {/* Footer */}
      <footer className="w-full bg-gradient-to-r from-primary-300 to-primary-400 text-white mt-12 shadow-md">
          <div className="max-w-7xl mx-auto px-4 py-4 text-center">
        MUMT © 2026. All rights reserved.        </div>
      </footer>
    </div>
  );
}
