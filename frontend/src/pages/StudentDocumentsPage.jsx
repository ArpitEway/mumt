import React, { useEffect, useState } from 'react';
import { api, API_URL } from '../api/client.js';
import { useAuth } from '../state/AuthContext.jsx';
import { useParams } from 'react-router-dom';
import { StudentDashboardShell } from './StudentDashboard.jsx';

export default function StudentDocumentsPage() {
  const { id } = useParams();
  const { user } = useAuth();
  const studentId = id || user?.id;
  const [documents, setDocuments] = useState({ required: [], uploaded: [] });
  const [uploadFiles, setUploadFiles] = useState({});
  const [error, setError] = useState('');
  const [message, setMessage] = useState('');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!studentId) return;
    loadDocuments();
  }, [studentId]);

  async function loadDocuments() {
    try {
      setLoading(true);
      setError('');
      const response = await api(`/students/${studentId}/documents`);
      setDocuments(response || { required: [], uploaded: [] });
    } catch (err) {
      setError(err.message || 'Failed to load documents');
    } finally {
      setLoading(false);
    }
  }

  async function uploadDocument(documentItem) {
    try {
      const file = uploadFiles[documentItem.id];
      if (!file) {
        setError('Please choose a file first');
        return;
      }

      setError('');
      setMessage('');

      const body = new FormData();
      body.append('document', file);
      body.append('documentCategoryId', documentItem.id);
      body.append('documentName', documentItem.documentName);

      const token = localStorage.getItem('mumt_token');
      const response = await fetch(`${API_URL}/students/${studentId}/documents`, {
        method: 'POST',
        headers: token ? { Authorization: `Bearer ${token}` } : {},
        body
      });
      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'Upload failed');
      }

      setMessage(data.message || 'Document uploaded');
      await loadDocuments();
    } catch (err) {
      setError(err.message || 'Upload failed');
    }
  }

  if (loading) return <div className="empty-state">Loading documents...</div>;

  const content = (
    <div className="dashboard-card student-page-card">
      <div className="student-page-header">
        <div>
          <span className="eyebrow">Document</span>
          <h2>Student Documents</h2>
          <p>Upload and review required documents here.</p>
        </div>
      </div>

      {error && <div className="alert">{error}</div>}
      {message && <div className="alert success-alert">{message}</div>}

      <div className="document-list">
        {documents?.required?.map((item) => {
          const uploaded = documents?.uploaded?.find(
            (doc) => Number(doc.document_category_id) === Number(item.id)
          );

          return (
            <div className="document-row" key={item.id}>
              <div>
                <strong>{item.documentName}</strong>
                <span>{uploaded ? `Uploaded: ${uploaded.document_image}` : 'Pending'}</span>
              </div>

              <input
                type="file"
                onChange={(event) =>
                  setUploadFiles((current) => ({
                    ...current,
                    [item.id]: event.target.files?.[0] || null
                  }))
                }
              />

              <button
                type="button"
                className="primary-button compact"
                onClick={() => uploadDocument(item)}
              >
                Upload
              </button>
            </div>
          );
        })}

        {!documents?.required?.length && <p>No document rules configured.</p>}
      </div>
    </div>
  );

  if (id) {
    return <StudentDashboardShell studentId={studentId}>{content}</StudentDashboardShell>;
  }

  return content;
}
