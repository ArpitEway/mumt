import React, { useEffect, useState } from 'react';
import { adminService } from '../services/adminService.js';
import AdminLayout from '../components/AdminLayout.jsx';
import AdminMenu from '../components/AdminMenu.jsx';
import { Spinner } from '../components/PageWrapper.jsx'; // Assuming a spinner component exists, otherwise fallback to simple text.

/**
 * AdminDashboard
 *
 * Renders the admin dashboard page. It loads menu headings and menu items from the
 * backend via the shared AdminService. The UI mirrors the original PHP view – a
 * list of headings on the left and the corresponding menu tiles on the right.
 */
const AdminDashboard = () => {
  const [headings, setHeadings] = useState([]);
  const [menus, setMenus] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const data = await adminService.getDashboardData();
        setHeadings(data.menu_headings || []);
        setMenus(data.menus || []);
      } catch (e) {
        setError(e.message ?? 'Failed to load dashboard data');
      } finally {
        setLoading(false);
      }
    };
    fetchData();
  }, []);

  if (loading) {
    return (
      <AdminLayout>
        <div className="flex justify-center items-center h-full">
          <Spinner />
        </div>
      </AdminLayout>
    );
  }

  if (error) {
    return (
      <AdminLayout>
        <div className="p-8 text-red-600">{error}</div>
      </AdminLayout>
    );
  }

  return (
    <AdminLayout>
      <div className="grid grid-cols-12 gap-4 p-4">
        {/* Left column – headings list */}
        <div className="col-span-3">
          <ul className="space-y-2">
            {headings.map((h) => (
              <li
                key={h.id}
                className="px-4 py-2 bg-primary-200 rounded cursor-pointer hover:bg-primary-300"
                // Clicking a heading is handled inside AdminMenu via its own state
              >
                {h.heading_name}
              </li>
            ))}
          </ul>
        </div>
        {/* Right column – menus for selected heading */}
        <div className="col-span-9">
          <AdminMenu headings={headings} menus={menus} />
        </div>
      </div>
    </AdminLayout>
  );
};

export default AdminDashboard;
