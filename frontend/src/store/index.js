import { useState } from 'react';
import { useAuth } from '../state/AuthContext.jsx';

export function useAuthStore() {
  const { setUser } = useAuth();
  const [error, setError] = useState('');

  return {
    setUser,
    error,
    setError,
  };
}
