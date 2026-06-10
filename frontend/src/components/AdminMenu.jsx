import React, { useState } from 'react';
import { Link } from 'react-router-dom';

export default function AdminMenu({ headings = [], menus = [] }) {
  const [activeHeading, setActiveHeading] = useState(headings[0]?.id || null);

  const filteredMenus = menus.filter((m) => m.heading_id === activeHeading);

  return (
    <div>
      {/* Headings */}
      <ul className="space-y-2 mb-4">
        {headings.map((heading) => (
          <li key={heading.id}>
            <button
              className={`w-full text-left px-3 py-2 rounded ${activeHeading === heading.id ? 'bg-primary-300 text-white' : 'bg-gray-100 text-gray-800'}`}
              onClick={() => setActiveHeading(heading.id)}
            >
              {heading.heading || heading.name || heading.title}
            </button>
          </li>
        ))}
      </ul>
      {/* Menu items */}
      <div className="grid gap-2">
        {filteredMenus.map((menu) => (
          <Link
            key={menu.id}
            to={menu.url}
            className="block border-2 border-primary-200 bg-primary-50 p-3 rounded hover:bg-primary-100 transition"
          >
            <span className="nav-text font-medium">{menu.option || menu.name}</span>
          </Link>
        ))}
      </div>
    </div>
  );
}
