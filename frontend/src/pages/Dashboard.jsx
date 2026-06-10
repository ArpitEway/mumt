import React, { useEffect, useMemo, useState } from 'react';
import { useAuth } from '../state/AuthContext.jsx';
import { api } from '../api/client.js';
import { useNavigate } from 'react-router-dom';
import StudentDashboard from './StudentDashboard.jsx';
import PageWrapper from '../components/PageWrapper.jsx';

export default function Dashboard() {
  const { user } = useAuth();
  const navigate = useNavigate();
  const [summary, setSummary] = useState(null);
  const [menu, setMenu] = useState({ headings: [], menus: [] });
  const [error, setError] = useState('');
  const [activeTabId, setActiveTabId] = useState(null);

  useEffect(() => {
    if (user?.role === 'student') {
      return;
    }
    Promise.all([api('/admin/summary'), api('/admin/menu')])
      .then(([summaryData, menuData]) => {
        setSummary(summaryData);
        setMenu(menuData);
        if (menuData.headings && menuData.headings.length > 0) {
          const sorted = [...menuData.headings].sort((a, b) => {
            const orderA = a.heading_order ?? 0;
            const orderB = b.heading_order ?? 0;
            if (orderA !== orderB) return orderA - orderB;
            return a.id - b.id;
          });
          setActiveTabId(sorted[0].id);
        }
      })
      .catch((err) => setError(err.message));
  }, [user?.role]);

  const groupedMenu = useMemo(() => {
    return menu.headings
      .map((heading) => ({
        ...heading,
        label: heading.label || heading.heading || heading.heading_name || '',
        items: menu.menus
          .filter((item) => Number(item.heading_id) === Number(heading.id))
          .map((item) => ({
            ...item,
            label: item.label || item.option || '',
            table: item.table || item.url || '',
          })),
      }))
      .sort((a, b) => {
        const orderA = a.heading_order ?? 0;
        const orderB = b.heading_order ?? 0;
        if (orderA !== orderB) return orderA - orderB;
        return a.id - b.id;
      });
  }, [menu]);

  const activeGroup = useMemo(
    () => groupedMenu.find((group) => group.id === activeTabId),
    [groupedMenu, activeTabId]
  );

  if (error) return <div className="alert">{error}</div>;
  if (user?.role === 'student') return <StudentDashboard />;

  return (
    <PageWrapper>
      {user?.role === 'admin' ? (
        <div className="dashboard-card">
          <h2 className="dashboard-title">Admin Section</h2>
          <div className="dashboard-split">
            <div className="dashboard-sidebar-tabs">
              {groupedMenu.map((group) => (
                <button
                  key={group.id}
                  className={`dashboard-tab-btn ${activeTabId === group.id ? 'active' : ''}`}
                  onClick={() => setActiveTabId(group.id)}
                >
                  <span>{group.label}</span>
                  <span className="chevron">›</span>
                </button>
              ))}
            </div>
            <div className="dashboard-content-panel">
              {activeGroup ? (
                <div>
                  <div className="actions-grid">
                    {activeGroup.items.map((item) => (
                      <div
                        key={item.id}
                        className="action-btn-card"
                        onClick={() => {
                          if (item.link) {
                            navigate(item.link);
                          } else if (item.table) {
                            navigate(`/resources/${item.table}`);
                          }
                        }}
                      >
                        {item.label}
                      </div>
                    ))}
                  </div>
                  {activeGroup.items.length === 0 && (
                    <div className="empty-state">No actions available in this category.</div>
                  )}
                </div>
              ) : (
                <div className="empty-state">Please select a category from the menu.</div>
              )}
            </div>
          </div>
          {summary?.cards && summary.cards.length > 0 && (
            <div className="dashboard-summary-section">
              <h3 className="dashboard-summary-title">Database Summary</h3>
              <section className="stats-grid">
                {summary.cards.map((card) => (
                  <article className="stat-card" key={card.label}>
                    <span>{card.label}</span>
                    <strong>{card.value}</strong>
                  </article>
                ))}
              </section>
            </div>
          )}
        </div>
      ) : (
        <>
          {summary?.cards && summary.cards.length > 0 && (
            <section className="stats-grid">
              {summary.cards.map((card) => (
                <article className="stat-card" key={card.label}>
                  <span>{card.label}</span>
                  <strong>{card.value}</strong>
                </article>
              ))}
            </section>
          )}
          <div className="module-grid">
            {groupedMenu.map((group) => (
              <article className="module-card" key={group.id}>
                <h3>{group.label}</h3>
                <ul>
                  {group.items.map((item) => (
                    <li key={item.id}>
                      <button
                        className="text-button"
                        onClick={() => {
                          if (item.link) {
                            navigate(item.link);
                          } else if (item.table) {
                            navigate(`/resources/${item.table}`);
                          }
                        }}
                      >
                        {item.label}
                      </button>
                    </li>
                  ))}
                </ul>
              </article>
            ))}
          </div>
        </>
      )}
    </PageWrapper>
  );
}
