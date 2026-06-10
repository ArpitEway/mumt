import { API_URL } from '../api/client.js';

/**
 * Admin service - centralises admin‑related API calls.
 * This mirrors the backend AdminService used in PHP.
 */
export const adminService = {
  /**
   * Fetch dashboard data (menu headings + menus) for the logged‑in admin.
   * Returns an object { menu_headings: [], menus: [] }.
   */
  getDashboardData: async () => {
    try {
      const response = await fetch(`${API_URL}/admin/dashboard`, {
        credentials: 'include',
        headers: {
          'Content-Type': 'application/json',
        },
      });
      if (!response.ok) {
        const err = await response.text();
        throw new Error(err || 'Failed to load admin dashboard data');
      }
      return await response.json();
    } catch (e) {
      console.error('adminService.getDashboardData error:', e);
      throw e;
    }
  },
};
