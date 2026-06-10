import React, { useEffect, useMemo, useState } from 'react';
import { useParams } from 'react-router-dom';
import { useNavigate, useLocation } from 'react-router-dom';
import { api } from '../api/client.js';
import { useAuth } from '../state/AuthContext.jsx';

function displayValue(value) {
  if (value === null || value === undefined || value === '') return '-';
  return String(value);
}

export default function ResourcePage() {
  const { table } = useParams();
  const { user } = useAuth();
  const [rows, setRows] = useState([]);
  const [columns, setColumns] = useState([]);
  const [pagination, setPagination] = useState({ page: 1, limit: 20, total: 0 });
  const [query, setQuery] = useState('');
  const [editing, setEditing] = useState(null);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const studentActions = [
    { label: 'All Students' },
    { label: 'Consolidate Report' },
    { label: 'Search Students', description: 'Search student records and details' },
    { label: 'Check Payment Transaction', description: 'Review student payment status' },
    { label: 'Search Student Answer-sheet status', description: 'Track answer-sheet completion' },
    { label: 'Internal Marks', description: 'Access internal marks submission' },
    { label: 'Practical Marks', description: 'Access practical marks submission' },
    { label: 'Mode Change Request', description: 'Review student mode change requests' },
    { label: 'Change Student Mode', description: 'Modify a student’s enrollment mode' },
    { label: 'Exam Form Complaint', description: 'Review exam form complaints' }
  ];

  const navigate = useNavigate();
  const location = useLocation();
  const [showAll, setShowAll] = useState(false);

  function handleActionClick(action) {
    if (action === 'all') {
      setShowAll((s) => !s);
      return;
    }
    if (action === 'consolidate') return navigate(`/resources/${table}?report=consolidate`);
    return navigate(`/resources/${table}?action=${action}`);
  }

  const searchParams = useMemo(() => new URLSearchParams(location.search), [location.search]);
  const actionParam = searchParams.get('action');
  const reportParam = searchParams.get('report');

  const visibleColumns = useMemo(() => columns.slice(0, 8), [columns]);
  const pkName = useMemo(() => {
    const pk = columns.find((c) => c.columnKey === 'PRI');
    return pk ? pk.name : 'id';
  }, [columns]);

  const editableColumns = useMemo(
    () => columns.filter((column) => column.extra !== 'auto_increment' && column.name !== pkName).slice(0, 16),
    [columns, pkName]
  );

  async function load(page = pagination.page, q = query) {
    setLoading(true);
    setError('');
    try {
      const data = await api(`/resources/${table}?page=${page}&limit=${pagination.limit}&q=${encodeURIComponent(q)}`);
      setRows(data.data);
      setColumns(data.columns);
      setPagination(data.pagination);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    setQuery('');
    setEditing(null);
    load(1, '');
  }, [table]);

  async function handleSearch(event) {
    event.preventDefault();
    await load(1, query);
  }

  async function handleSave(event) {
    event.preventDefault();
    const method = editing?.[pkName] ? 'PUT' : 'POST';
    const path = editing?.[pkName] ? `/resources/${table}/${editing[pkName]}` : `/resources/${table}`;
    await api(path, { method, body: editing });
    setEditing(null);
    await load();
  }

  async function handleDelete(row) {
    const confirmed = window.confirm(`Delete ${table} record #${row[pkName]}?`);
    if (!confirmed) return;
    await api(`/resources/${table}/${row[pkName]}`, { method: 'DELETE' });
    await load();
  }

  return (
    <div className="content-stack">
      <section className="section">
        <div className="resource-toolbar">
          <div>
            <span className="eyebrow">Database Table</span>
            <h2>{table}</h2>
          </div>
          <form className="search-form" onSubmit={handleSearch}>
            <input value={query} onChange={(event) => setQuery(event.target.value)} placeholder="Search text fields" />
            <button className="ghost-button" type="submit">Search</button>
          </form>
          {user?.role === 'admin' && (
            <button className="primary-button compact" type="button" onClick={() => setEditing({})}>
              Add
            </button>
          )}
        </div>

        {error && <div className="alert">{error}</div>}

        {table === 'student' && (
          <section className="section student-actions compact">
            <div className="section-heading">
              <span className="eyebrow">Student Actions</span>
              <h2>Quick actions</h2>
            </div>
            <div className="module-grid">
              {studentActions.map((item) => {
                const label = item.label;
                const key = String(label).toLowerCase().replace(/[^a-z0-9]+/g, '-');
                const actionKey = label === 'All Students' ? 'all' : String(label).toLowerCase().split(' ')[0];
                return (
                  <article
                    key={key}
                    className="module-card clickable-card"
                    role="button"
                    onClick={() => handleActionClick(actionKey)}
                  >
                    <h3>{label}</h3>
                  </article>
                );
              })}
            </div>
          </section>
        )}

        {(!actionParam && !reportParam) || showAll ? (
          <>
          <div className="table-wrap">
          <table>
            <thead>
              <tr>
                {visibleColumns.map((column) => (
                  <th key={column.name}>{column.name}</th>
                ))}
                {user?.role === 'admin' && <th>Actions</th>}
              </tr>
            </thead>
            <tbody>
              {rows.map((row, index) => (
                <tr key={row[pkName] || index}>
                  {visibleColumns.map((column) => (
                    <td key={column.name}>{displayValue(row[column.name])}</td>
                  ))}
                  {user?.role === 'admin' && (
                    <td className="action-cell">
                      <button className="text-button" type="button" onClick={() => setEditing(row)}>Edit</button>
                      <button className="text-button danger" type="button" onClick={() => handleDelete(row)}>Delete</button>
                    </td>
                  )}
                </tr>
              ))}
              {!rows.length && (
                <tr>
                  <td colSpan={visibleColumns.length + (user?.role === 'admin' ? 1 : 0)} className="empty-state">
                    {loading ? 'Loading...' : 'No records found'}
                  </td>
                </tr>
              )}
            </tbody>
          </table>
          </div>

          <div className="pager">
          <button className="ghost-button" disabled={pagination.page <= 1} onClick={() => load(pagination.page - 1)}>
            Previous
          </button>
          <span>
            Page {pagination.page} of {Math.max(1, Math.ceil(pagination.total / pagination.limit))}
          </span>
          <button
            className="ghost-button"
            disabled={pagination.page >= Math.ceil(pagination.total / pagination.limit)}
            onClick={() => load(pagination.page + 1)}
          >
            Next
          </button>
        </div>
          </>
        ) : (
          <section className="section">
            <div className="section-heading">
              <span className="eyebrow">{reportParam ? 'Report' : 'Student'}</span>
              <h2>{reportParam ? (reportParam === 'consolidate' ? 'Consolidate Report' : reportParam) : (actionParam || '').replace(/-/g, ' ')}</h2>
            </div>
            <div className="content-stack">
              <p className="empty-state">This page is reserved for the selected student action. Implement the specific UI here.</p>
            </div>
          </section>
        )}
      </section>

      {editing && (
        <div className="modal-backdrop" role="presentation">
          <form className="modal" onSubmit={handleSave}>
            <div className="modal-heading">
              <h2>{editing[pkName] ? `Edit #${editing[pkName]}` : `Add ${table}`}</h2>
              <button className="icon-button" type="button" onClick={() => setEditing(null)} aria-label="Close">x</button>
            </div>

            <div className="form-grid">
              {editableColumns.map((column) => (
                <label key={column.name}>
                  {column.name}
                  <input
                    value={editing[column.name] ?? ''}
                    onChange={(event) => setEditing((current) => ({ ...current, [column.name]: event.target.value }))}
                  />
                </label>
              ))}
            </div>

            <div className="modal-actions">
              <button className="ghost-button" type="button" onClick={() => setEditing(null)}>Cancel</button>
              <button className="primary-button compact" type="submit">Save</button>
            </div>
          </form>
        </div>
      )}
    </div>
  );
}
