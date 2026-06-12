import React, { useState } from 'react';
import { NavLink, useNavigate, useParams } from 'react-router-dom';
import { useAuth } from '../state/AuthContext.jsx';
import { api } from '../api/client.js';
import { decodeId, encodeId } from '@mmyvv/shared/idEncryption';
import PageWrapper from '../components/PageWrapper.jsx';

export function StudentDashboardShell({ children, studentId }) {
  const { logout, user } = useAuth();
  const navigate = useNavigate();
  const [showDropdown, setShowDropdown] = useState(false);
  const [showModal, setShowModal] = useState(false);
  const [currentPassword, setCurrentPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [modalError, setModalError] = useState('');
  const [modalSuccess, setModalSuccess] = useState('');

  function handleLogout() {
    logout();
    navigate('/login');
  }

  async function handlePasswordChange(event) {
    event.preventDefault();
    setModalError('');
    setModalSuccess('');

    if (newPassword !== confirmPassword) {
      setModalError('New passwords do not match');
      return;
    }

    try {
      const res = await api('/auth/change-password', {
        method: 'POST',
        body: { currentPassword, newPassword }
      });
      setModalSuccess(res.message || 'Password changed successfully');
      setCurrentPassword('');
      setNewPassword('');
      setConfirmPassword('');
      setTimeout(() => {
        setShowModal(false);
        setModalSuccess('');
      }, 1500);
    } catch (err) {
      setModalError(err.message || 'Failed to change password');
    }
  }

  const topActions = (
    <>
      <NavLink
        to={studentId ? `/student-dashboard/${encodeId(studentId)}` : '/'}
        className="site-header-button"
      >
        Dashboard
      </NavLink>

      <div className="site-action-wrap">
        <button
          className="site-header-button"
          onClick={() => setShowDropdown((current) => !current)}
        >
          <span>Action</span>
          <span className="site-header-button-caret">{showDropdown ? '^' : 'v'}</span>
        </button>

        {showDropdown && (
          <div className="site-action-menu">
            <button
              onClick={() => {
                setShowModal(true);
                setShowDropdown(false);
              }}
            >
              Change Password
            </button>
            <button onClick={handleLogout}>
              Logout
            </button>
          </div>
        )}
      </div>
    </>
  );

  return (
    <PageWrapper>
      {children}

      {showModal && (
        <div className="modal-backdrop">
          <form className="modal password-modal" onSubmit={handlePasswordChange}>
            <div className="modal-heading">
              <h2>Change Password</h2>
              <button className="icon-button" type="button" onClick={() => setShowModal(false)}>x</button>
            </div>

            <div className="form-grid password-modal-grid">
              {modalError && <div className="alert">{modalError}</div>}
              {modalSuccess && <div className="alert success-alert">{modalSuccess}</div>}

              <label>
                Current Password
                <input
                  type="password"
                  value={currentPassword}
                  onChange={(event) => setCurrentPassword(event.target.value)}
                  required
                />
              </label>

              <label>
                New Password
                <input
                  type="password"
                  value={newPassword}
                  onChange={(event) => setNewPassword(event.target.value)}
                  required
                />
              </label>

              <label>
                Confirm New Password
                <input
                  type="password"
                  value={confirmPassword}
                  onChange={(event) => setConfirmPassword(event.target.value)}
                  required
                />
              </label>
            </div>

            <div className="modal-actions">
              <button className="ghost-button" type="button" onClick={() => setShowModal(false)}>Cancel</button>
              <button className="primary-button compact" type="submit">Save</button>
            </div>
          </form>
        </div>
      )}
    </PageWrapper>
  );
}

export default function StudentDashboard() {
  const { id } = useParams();
  const { user } = useAuth();
  const navigate = useNavigate();

  const studentId = decodeId(id) || user?.id;

  const items = studentId
    ? [
        { id: 1, label: 'Registration', link: `/students/registration/${encodeId(studentId)}` },
        { id: 2, label: 'Document', link: `/students/documents/${encodeId(studentId)}` },
        { id: 3, label: 'Payment', link: `/students/payments/${encodeId(studentId)}` }
      ]
    : [
        { id: 1, label: 'Registration', link: '/students/registration' },
        { id: 2, label: 'Document', link: '/students/documents' },
        { id: 3, label: 'Payment', link: '/students/payments' }
      ];

  const content = (
    <div className="dashboard-card student-dashboard-home">
      <h2 className="dashboard-title">Student Dashboard</h2>

      <div className="dashboard-split student-dashboard-home-split">
        <div className="dashboard-sidebar-tabs student-menu-panel">
          <button type="button" className="dashboard-tab-btn active">
            <span>Student</span>
            <span className="chevron">&raquo;</span>
          </button>
        </div>

        <div className="dashboard-content-panel student-home-options-panel">
          <div className="actions-grid student-home-actions-grid">
            {items.map((item) => (
              <button
                key={item.id}
                type="button"
                className="action-btn-card"
                onClick={() => navigate(item.link)}
              >
                {item.label}
              </button>
            ))}
          </div>
        </div>
      </div>
    </div>
  );

  return <StudentDashboardShell studentId={studentId}>{content}</StudentDashboardShell>;
}
