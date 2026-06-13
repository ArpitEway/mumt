import React, { useEffect, useState } from 'react';
import { toast } from 'react-hot-toast';
import { api, API_URL } from '../api/client.js';
import { useAuth } from '../state/AuthContext.jsx';
import { useParams } from 'react-router-dom';
import { StudentDashboardShell } from './StudentDashboard.jsx';
import { decodeId } from '@mmyvv/shared/idEncryption';

export default function StudentDocumentsPage() {
  const { id } = useParams();
  const { user } = useAuth();
  const studentId = decodeId(id) || user?.id;
  const [documents, setDocuments] = useState({ required: [], uploaded: [] });
  const [uploadFiles, setUploadFiles] = useState({});
  const [error, setError] = useState('');
  const [message, setMessage] = useState('');
  const [loading, setLoading] = useState(true);
  const [studentInfo, setStudentInfo] = useState({
    name: '',
    fatherName: '',
    session: '',
    formNo: '',
    course: '',
    class: ''
  });

  useEffect(() => {
    if (!studentId) return;
    loadStudentInfo();
    loadDocuments();
  }, [studentId]);

  async function loadStudentInfo() {
    try {
      const response = await api(`/students/${studentId}/form`);
      if (response?.fieldGroups) {
        const fieldValues = {};
        response.fieldGroups.forEach((group) => {
          (group.fields || []).forEach((field) => {
            fieldValues[field.name] = field.value;
          });
        });
        setStudentInfo({
          name: fieldValues.name || '',
          fatherName: fieldValues.f_h_name || '',
          session: fieldValues.session || '',
          formNo: fieldValues.form_no || '',
          course: fieldValues.course_name || '',
          class: fieldValues.class_name || ''
        });
      }
    } catch (err) {
      console.error('Failed to load student info:', err);
    }
  }

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
        const errorMessage = 'Please choose a file first';
        setError(errorMessage);
        toast.error(errorMessage);
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

      const successMessage = data.message || 'Document uploaded';
      setMessage(successMessage);
      toast.success(successMessage);
      await loadDocuments();
      setUploadFiles((prev) => ({ ...prev, [documentItem.id]: null }));
    } catch (err) {
      const errorMessage = err.message || 'Upload failed';
      setError(errorMessage);
      toast.error(errorMessage);
    }
  }

  if (loading) return <div className="empty-state">Loading documents...</div>;

  const content = (
    <div className="max-w-6xl mx-auto p-4">
      <div className="bg-white rounded-lg shadow-md p-6">
        <h1 className="text-2xl font-bold text-gray-800 mb-2">Upload Admission Document</h1>
        <p className="text-gray-600 text-sm mb-6">Upload and review required documents here.</p>

        {/* Student Info Header */}
        <div className="border border-primary-200 rounded-lg p-6 mb-8 bg-primary-50">
          <div className="grid grid-cols-3 gap-8">
            <div>
              <p className="text-xs font-semibold text-gray-600 mb-1">Student</p>
              <p className="text-lg font-bold text-gray-800">{studentInfo.name}</p>
            </div>
            <div>
              <p className="text-xs font-semibold text-gray-600 mb-1">Session</p>
              <p className="text-lg font-bold text-gray-800">{studentInfo.session}</p>
            </div>
            <div>
              <p className="text-xs font-semibold text-gray-600 mb-1">Course</p>
              <p className="text-lg font-bold text-gray-800">{studentInfo.course}</p>
            </div>
            <div>
              <p className="text-xs font-semibold text-gray-600 mb-1">Father</p>
              <p className="text-lg font-bold text-gray-800">{studentInfo.fatherName}</p>
            </div>
            <div>
              <p className="text-xs font-semibold text-gray-600 mb-1">Form No</p>
              <p className="text-lg font-bold text-gray-800">{studentInfo.formNo}</p>
            </div>
            <div>
              <p className="text-xs font-semibold text-gray-600 mb-1">Class</p>
              <p className="text-lg font-bold text-gray-800">{studentInfo.class}</p>
            </div>
          </div>
        </div>

        {error && <div className="mb-4 p-4 bg-red-50 text-red-700 rounded-lg border border-red-200">{error}</div>}
        {message && <div className="mb-4 p-4 bg-green-50 text-green-700 rounded-lg border border-green-200">{message}</div>}

        {/* Document Grid */}
        <div className="mb-8">
          <div className="grid grid-cols-2 gap-6">
            {documents?.required?.map((item) => {
              const uploaded = documents?.uploaded?.find(
                (doc) => Number(doc.document_category_id) === Number(item.id)
              );
              const fileName = uploadFiles[item.id]?.name;

              return (
                <div key={item.id} className="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                  <div className="mb-4">
                    <p className="font-semibold text-gray-800">{item.documentName} <span className="text-primary-300">*</span></p>
                    <p className="text-sm text-gray-600 mt-2">
                      {uploaded ? (
                        <span className="text-green-600 font-medium">✓ {uploaded.document_image}</span>
                      ) : fileName ? (
                        <span className="text-blue-600 font-medium">📄 {fileName}</span>
                      ) : (
                        <span className="text-gray-500">Pending</span>
                      )}
                    </p>
                  </div>
                  <label className="block">
                    <input
                      type="file"
                      className="hidden"
                      onChange={(event) =>
                        setUploadFiles((current) => ({
                          ...current,
                          [item.id]: event.target.files?.[0] || null
                        }))
                      }
                    />
                    <span className="block w-full px-4 py-2 bg-primary-300 text-white text-center font-semibold rounded-lg cursor-pointer hover:bg-primary-400 transition text-sm">
                      Browse
                    </span>
                  </label>
                </div>
              );
            })}
          </div>

          {!documents?.required?.length && (
            <p className="text-center text-gray-600 py-8">No document rules configured.</p>
          )}
        </div>

        {/* Instructions */}
        <div className="mb-8 p-5 bg-primary-50 border border-primary-200 rounded-lg">
          <p className="font-semibold text-gray-800 mb-3">Instructions:</p>
          <ul className="list-disc list-inside space-y-2 text-sm text-gray-700">
            <li>प्रत्येक अनिवार्य कागज-पत्र स्कैन करके ही अपलोड करें आकार 60 के.बी. से 250 के.बी. के मध्य होना चाहिए।</li>
            <li>यदि आप्का aadhar form मे फॉर्म के साथ की जानी है प्रमाण पत्र की आवश्यकता नही है।</li>
            <li>अन्य अनिवार्य कागज़ों के साथ जीएफ जी submit करना आवश्यक है।</li>
          </ul>
        </div>

        {/* Submit Button */}
        <div className="flex justify-center">
          <button
            type="button"
            className="px-8 py-2 bg-primary-300 text-white font-semibold rounded-lg hover:bg-primary-400 transition"
          >
            Submit
          </button>
        </div>
      </div>
    </div>
  );

  if (id) {
    return <StudentDashboardShell studentId={studentId}>{content}</StudentDashboardShell>;
  }

  return content;
}
