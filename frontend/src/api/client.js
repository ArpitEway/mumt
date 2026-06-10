const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:5000/api';

function getToken() {
  return localStorage.getItem('mumt_token');
}

export async function api(path, options = {}) {
  console.log('API request', { url: `${API_URL}${path}`, options });
  const headers = {
    'Content-Type': 'application/json',
    ...(options.headers || {})
  };
  const token = getToken();
  if (token) headers.Authorization = `Bearer ${token}`;

  const response = await fetch(`${API_URL}${path}`, {
    ...options,
    headers,
    body: options.body && typeof options.body !== 'string' ? JSON.stringify(options.body) : options.body
  });

  console.log('Response status', response.status);
  const contentType = response.headers.get('content-type') || '';
  const data = contentType.includes('application/json') ? await response.json() : await response.text();

  if (!response.ok) {
    const message = typeof data === 'string' ? data : data.message || 'Request failed';
    console.error('API error', message);
    const error = new Error(message);
    if (data.details) error.details = data.details;
    throw error;
  }

  return data;
}

export { API_URL };

export const authAPI = {
  login: async (payload) => {
    try {
      const result = await api('/auth/login', { method: 'POST', body: payload });
      return { success: true, data: { token: result.token, user: result.user } };
    } catch (error) {
      return { success: false, error: error.message || 'Login failed' };
    }
  },
  adminLogin: async (payload) => {
    try {
      const result = await api('/auth/login', { method: 'POST', body: payload });
      // Assuming backend returns token and user with role admin
      return { success: true, data: { token: result.token, user: result.user } };
    } catch (error) {
      return { success: false, error: error.message || 'Admin login failed' };
    }
  },
};
