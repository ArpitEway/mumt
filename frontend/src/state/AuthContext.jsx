import React, { createContext, useContext, useMemo, useState } from 'react';
import { api } from '../api/client.js';

const AuthContext = createContext(null);
export const useAuth = () => useContext(AuthContext);

function initialUser() {
  const raw = localStorage.getItem('mumt_user') || localStorage.getItem('user');
  if (!raw) return null;
  try {
    return JSON.parse(raw);
  } catch {
    return null;
  }
}

function initialToken() {
  return localStorage.getItem('mumt_token') || localStorage.getItem('token');
}

export function AuthProvider({ children }) {
  const [user, setUserState] = useState(initialUser);
  const [token, setTokenState] = useState(initialToken);

  async function login(payload) {
    const result = await api('/auth/login', { method: 'POST', body: payload });
    localStorage.setItem('mumt_token', result.token);
    localStorage.setItem('token', result.token);
    localStorage.setItem('mumt_user', JSON.stringify(result.user));
    setTokenState(result.token);
    setUserState(result.user);
    return result.user;
  }

  function setUser(userPayload) {
    if (userPayload) {
      localStorage.setItem('mumt_user', JSON.stringify(userPayload));
      setUserState(userPayload);
      const storedToken = initialToken();
      if (storedToken) setTokenState(storedToken);
    } else {
      localStorage.removeItem('mumt_user');
      setUserState(null);
    }
  }

  function logout() {
    localStorage.removeItem('mumt_token');
    localStorage.removeItem('token');
    localStorage.removeItem('mumt_user');
    setTokenState(null);
    setUserState(null);
  }

  const value = useMemo(
    () => ({ user, token, login, logout, setUser, isAuthenticated: Boolean(token) }),
    [user, token]
  );

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function isAdmin() {
  const { user } = useAuth();
  return user?.role === 'admin';
}

