import React from 'react';
import { Navigate } from 'react-router-dom';
import { useAuth } from '../state/AuthContext.jsx';

export default function AdminProtected({ children }) {
  const { user, isAuthenticated } = useAuth();
  if (!isAuthenticated) {
    return <Navigate to="/admin-login" replace />;
  }
  if (!user || user.role !== 'admin') {
    // Not an admin, redirect to home or unauthorized page
    return <Navigate to="/" replace />;
  }
  return <>{children}</>;
}
