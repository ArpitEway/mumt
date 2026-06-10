import React, { useState } from 'react';
import { NavLink } from 'react-router-dom';

// Define the same menu groups used across the app
const menuGroups = [
  {
    label: 'Student',
    items: [
      { table: 'student', label: 'Students' },
      { table: 'student_profile', label: 'My Profile' }
    ]
  },
  {
    label: 'Center',
    items: [
      { table: 'center', label: 'Centers' },
      { table: 'course', label: 'Courses' }
    ]
  },
  {
    label: 'Examination',
    items: [
      { table: 'paper_master', label: 'Papers' },
      { table: 'online_payment_transaction', label: 'Payments' }
    ]
  }
];

export default function AdminSidebar() {
  const [openGroups, setOpenGroups] = useState({});

  const toggleGroup = (label) => {
    setOpenGroups((prev) => ({ ...prev, [label]: !prev[label] }));
  };

  return (
    <aside className="admin-sidebar">
      <div className="brand">
        <span className="brand-mark">EU</span>
        <div>
          <strong>mumt</strong>
          <small>admin</small>
        </div>
      </div>
      <nav className="admin-nav">
        {menuGroups.map((group) => (
          <div key={group.label} className="admin-menu-group">
            <div
              className="group-heading"
              onClick={() => toggleGroup(group.label)}
            >
              {group.label}
            </div>
            {openGroups[group.label] && (
              <ul className="group-items">
                {group.items.map((item) => (
                  <li key={item.table} className="admin-menu-item">
                    <NavLink to={`/resources/${item.table}`}>{item.label}</NavLink>
                  </li>
                ))}
              </ul>
            )}
          </div>
        ))}
      </nav>
    </aside>
  );
}
