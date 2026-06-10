import React, { useEffect, useMemo, useState } from 'react';
import { toast, Toaster } from 'react-hot-toast';
import { api, API_URL } from '../api/client.js';
import { useAuth } from '../state/AuthContext.jsx';
import { useParams } from 'react-router-dom';
import { StudentDashboardShell } from './StudentDashboard.jsx';
import { studentFormSchema } from '@mmyvv/shared';
import TextInput from '../components/forms/TextInput.jsx';

function displayValue(value) {
  if (value === null || value === undefined || value === '') return '-';
  return value;
}

export default function StudentRegistrationPage() {
  const { id } = useParams();
  const { user } = useAuth();
  const studentId = id || user?.id;
  const [formMeta, setFormMeta] = useState({
    canEdit: false,
    formStatus: 'N',
    reviewMode: false,
    fieldGroups: []
  });
  const [formValues, setFormValues] = useState({
    student: {},
    student_data: {}
  });
  const [error, setError] = useState('');
  const [message, setMessage] = useState('');
  const [loading, setLoading] = useState(true);
  const [photoPath, setPhotoPath] = useState('');
  const [photoFile, setPhotoFile] = useState(null);
  const [fieldErrors, setFieldErrors] = useState({});

  useEffect(() => {
    if (!studentId) return;
    loadForm();
  }, [studentId]);

  async function loadForm() {
    try {
      setLoading(true);
      setError('');

      const response = await api(`/students/${studentId}/form`);
      setFormMeta({
        canEdit: Boolean(response?.canEdit),
        formStatus: response?.formStatus || 'N',
        reviewMode: Boolean(response?.reviewMode),
        fieldGroups: response?.fieldGroups || []
      });
      setPhotoPath(response?.photo || '');
      // Aggregate fields from all groups (Admission Details, Personal Details, etc.)
      const studentEntries = [];
      const studentDataEntries = [];
      response?.fieldGroups?.forEach((group) => {
        if (group.table === 'student') {
          group.fields?.forEach((f) => studentEntries.push([f.name, f.value ?? '']));
        } else if (group.table === 'student_data') {
          group.fields?.forEach((f) => studentDataEntries.push([f.name, f.value ?? '']));
        }
      });
      const newFormValues = {
        student: Object.fromEntries(studentEntries),
        student_data: Object.fromEntries(studentDataEntries)
      };
      setFormValues(newFormValues);
      console.log('Form values set to', newFormValues);
      console.log('Full response', response);
    } catch (err) {
      setError(err.message || 'Failed to load registration form');
    } finally {
      setLoading(false);
    }
  }

  function updateFormField(table, field, value) {
    setFormValues((current) => ({
      ...current,
      [table]: {
        ...current[table],
        [field]: value
      }
    }));
  }

  const formPayload = useMemo(() => ({
    student: formValues.student || {},
    student_data: formValues.student_data || {}
  }), [formValues]);

  async function handleSubmit(event) {
    event.preventDefault();
    try {
      setError('');
      setMessage('');

      if (photoFile) {
        const body = new FormData();
        body.append('photo', photoFile);
        const token = localStorage.getItem('mumt_token');
        const response = await fetch(`${API_URL}/students/${studentId}/photo`, {
          method: 'POST',
          headers: token ? { Authorization: `Bearer ${token}` } : {},
          body
        });
        const data = await response.json();
        if (!response.ok) {
          throw new Error(data.message || 'Could not upload photograph');
        }
        setPhotoPath(data.photo || '');
      }

   
        // Validate payload against schema
        const validationResult = studentFormSchema.safeParse(formPayload);
        if (!validationResult.success) {
          const rawFieldErrs = validationResult.error.format();
          console.log('DEBUG: Zod raw errors →', rawFieldErrs);

          const flattenErrors = (obj, prefix = '') => {
  const out = {};

  for (const [key, value] of Object.entries(obj)) {
    if (key === '_errors') {
      if (value.length > 0 && prefix) {
        out[prefix] = value;
      }
      continue;
    }

    const path = prefix ? `${prefix}.${key}` : key;

    if (typeof value === 'object' && value !== null) {
      Object.assign(out, flattenErrors(value, path));
    }
  }

  return out;
};

          const fieldErrs = flattenErrors(rawFieldErrs);
          console.log(flattenErrors(validationResult.error.format()));
          console.log('DEBUG: flattened errors →', fieldErrs);
          setFieldErrors(fieldErrs);
          console.log('DEBUG: setFieldErrors called with', fieldErrs);
          toast.error('Please correct the highlighted errors.');
          return;
        }
        // ---------------------------------------------------------------------

        const response = await api(`/students/${studentId}/form`, {
          method: 'PUT',
          body: formPayload
        });
      setMessage(response.message || 'Registration form submitted');
      await loadForm();
    } catch (err) {
      setError(err.message || 'Could not submit form');
      if (err.details) setFieldErrors(err.details); else setFieldErrors({});
    }
  }

  if (loading) return <div className="empty-state">Loading registration form...</div>;

  const content = (
    <div className="dashboard-card student-page-card">
      <Toaster />
      <div className="student-page-header">
        <div>
          <span className="eyebrow">Registration</span>
          <h2>{formMeta.canEdit ? 'Edit Form' : 'Review Form'}</h2>
          <p>{formMeta.canEdit ? 'Form status: Pending' : 'Form status: Under Review'}</p>
        </div>
        <div className="payment-badge">{formMeta.canEdit ? 'Editable' : 'Locked for Review'}</div>
      </div>
      {error && <div className="alert">{error}</div>}
      {message && <div className="alert success-alert">{message}</div>}
      <form onSubmit={handleSubmit} className="student-form-stack">
        <section className="form-band">
          <h2>Photograph</h2>
          <div className="register-grid">
            <div className="register-field">
              <label htmlFor="student-photo">Upload Photograph</label>
              {photoPath ? (
                <div className="student-photo-preview-wrap">
                  <img className="student-photo-preview" src={`${API_URL.replace('/api', '')}${photoPath}`} alt="Student photograph" />
                </div>
              ) : (
                <div className="register-readonly-field">No photograph uploaded</div>
              )}

              {formMeta.canEdit && (
                <input
                  id="student-photo"
                  type="file"
                  accept="image/*"
                  onChange={(event) => setPhotoFile(event.target.files?.[0] || null)}
                />
              )}
            </div>
          </div>
        </section>

        {formMeta.fieldGroups.map((group, index) => (
          
          <section className="form-band" key={`${group.table}-${index}`} >
            <h2>{group.title}</h2>

            <div className="register-grid">
              {group.fields.map((field) => (
                <div className="register-field" key={`${group.table}-${field.name}`}>
                  {formMeta.canEdit ? (
  <TextInput
    label={field.label}
    name={`${group.table}-${field.name}`}
    type={field.type || 'text'}
    value={formValues[group.table]?.[field.name] || ''}
    onChange={(event) =>
      updateFormField(field.table || group.table, field.name, event.target.value)
    }
    error={fieldErrors[`${group.table}.${field.name}`]?.join(', ')}
  />
) : (
  <div className="register-readonly-field">
    {displayValue(field.value)}
  </div>
)}
                </div>
              ))}
            </div>
          </section>
        ))}

        {formMeta.canEdit && (
          <div className="form-actions">
            <button className="primary-button" type="submit">
              Submit Form
            </button>
          </div>
        )}
      </form>
    </div>
  );

  if (id) {
    return <StudentDashboardShell studentId={studentId}>{content}</StudentDashboardShell>;
  }

  return content;
}
