import React, { useEffect, useMemo, useState } from 'react';
import { toast, Toaster } from 'react-hot-toast';
import { Link, useNavigate } from 'react-router-dom';
import { api } from '../api/client';
import { registerSchema } from '@mmyvv/shared';
import PageWrapper from '../components/PageWrapper.jsx';
import TextInput from '../components/forms/TextInput.jsx';
import SelectInput from '../components/forms/SelectInput.jsx';
import SubmitButton from '../components/forms/SubmitButton.jsx';
import { useAuth } from '../state/AuthContext.jsx';

const initialForm = {
  session: '2026',
  fullName: '',
  fatherName: '',
  email: '',
  dob: '',
  mobile: '',
  boardUniversity: '',
  mode: 'Regular',
  category: '',
  course: '',
};

export default function Register() {
  const [form, setForm] = useState(initialForm);
  const [submitted, setSubmitted] = useState(false);
  const [error, setError] = useState('');
  const [options, setOptions] = useState({
    session: '2026',
    categories: [],
  });
  const [loadingOptions, setLoadingOptions] = useState(true);
  const [saving, setSaving] = useState(false);

  const navigate = useNavigate();
  const { login } = useAuth();

  useEffect(() => {
    let active = true;

    api('/registration/options')
      .then((res) => {
        if (!active) return;

        const session = res.session || '2026';

        setOptions({
          session,
          categories: Array.isArray(res.categories)
            ? res.categories
            : [],
        });

        setForm((current) => ({
          ...current,
          session,
        }));
      })
      .catch((err) => {
        if (active) {
          setError(
            err.message ||
              'Unable to load admission options'
          );
        }
      })
      .finally(() => {
        if (active) {
          setLoadingOptions(false);
        }
      });

    return () => {
      active = false;
    };
  }, []);

  const selectedCategory = useMemo(
    () =>
      options.categories.find(
        (item) => item.name === form.category
      ),
    [form.category, options.categories]
  );

  const courses = selectedCategory?.courses || [];


  function updateField(field, value) {
    setForm((current) => ({
      ...current,
      [field]: value,
    }));
  }

  const [fieldErrors, setFieldErrors] = useState({});

  function handleSubmit(event) {
    event.preventDefault();

    const mobile = form.mobile.replace(/\\D/g, '');
    const payload = { ...form, mobile };

    const result = registerSchema.safeParse(payload);
    if (!result.success) {
      // Populate field-specific errors
      const errors = result.error.flatten().fieldErrors;
      setFieldErrors(errors);
      // Show first error as generic message for alert area
      const firstError = Object.values(errors)[0][0];
      setError(firstError);
      return;
    }

    // Clear errors on successful validation
    setFieldErrors({});
    setError('');
    setSaving(true);
    setSubmitted(false);

    api('/registration', {
      method: 'POST',
      body: {
        ...form,
        mobile,
      },
    })
      .then(async (res) => {
        const id = res?.studentId;

        if (id) {
          try {
            await login({
              username: mobile,
              password: form.dob,
              role: 'student',
              nonEnrolled: true,
            });
            navigate(`/student-dashboard/${id}`);
          } catch (loginError) {
            setError(loginError.message || 'Registration succeeded but auto-login failed');
            toast.error(loginError.message || 'Auto-login failed');
            setSubmitted(true);
          }
        } else {
          setSubmitted(true);

          setForm({
            ...initialForm,
            session: options.session,
          });
        }
      })
      .catch((err) => {
        console.error(
          'Registration API error:',
          err
        );
        const errorMessage = err.message || err.response?.data?.message || 'Registration failed';
        setError(errorMessage);
        toast.error(errorMessage);
      })
      .finally(() => {
        setSaving(false);
      });
  }

  function handleReset() {
    setForm({
      ...initialForm,
      session: options.session,
    });

    setError('');
    setSubmitted(false);
  }

  return (
    <PageWrapper>
      <Toaster />
          <div className="register-page">
        <div className="register-card">
          <div className="section-heading">
            <span className="eyebrow">
              Create Your Account for Admission
              2026
            </span>
          </div>

          <form
            onSubmit={handleSubmit}
            className="space-y-4"
          >
            <input
              type="hidden"
              name="session"
              value={form.session}
            />

            <div className="register-grid">
            <TextInput
              label="Full Name"
              value={form.fullName}
              onChange={e => updateField('fullName', e.target.value)}
              placeholder="Enter full name"
              error={fieldErrors.fullName?.[0]}
            />

            <TextInput
              label="Father's / Husband's Name"
              value={form.fatherName}
              onChange={e => updateField('fatherName', e.target.value)}
              placeholder="Enter full name"
              error={fieldErrors.fatherName?.[0]}
            />

            <TextInput
              label="E-mail"
              type="email"
              value={form.email}
              onChange={e => updateField('email', e.target.value)}
              placeholder="Enter Email Address"
              error={fieldErrors.email?.[0]}
            />

            <TextInput
              label="Date Of Birth"
              type="date"
              value={form.dob}
              onChange={e => updateField('dob', e.target.value)}
              error={fieldErrors.dob?.[0]}
            />

           
                
               
                 <TextInput
              label="Mobile No."
              type="tel"
              value={form.mobile}
              onChange={e => updateField('mobile', e.target.value)}
              placeholder="Enter your Mobile No"
              error={fieldErrors.mobile?.[0]}
            />
      

            <SelectInput
              label="Board/University"
              value={form.boardUniversity}
              onChange={e => updateField('boardUniversity', e.target.value)}
              options={[{ value: '', label: 'Select' }, { value: 'yes', label: 'हाँ' }, { value: 'no', label: 'नहीं' }]}
              error={fieldErrors.boardUniversity?.[0]}
            />

            <SelectInput
              label="Mode"
              value={form.mode}
              onChange={() => {}}
              disabled
              options={[{ value: 'Regular', label: 'Regular' }]}
            />

            <SelectInput
              label="Category"
              value={form.category}
              onChange={e => { updateField('category', e.target.value); updateField('course', ''); }}
              loading={loadingOptions}
              options={[{ value: '', label: loadingOptions ? 'Loading categories...' : 'Select Category' }].concat(options.categories.map(item => ({ value: item.name, label: item.name })))}
              error={fieldErrors.category?.[0]}
            />

            <SelectInput
              label="Course"
              value={form.course}
              onChange={e => updateField('course', e.target.value)}
              disabled={!form.category || loadingOptions}
              options={[{ value: '', label: 'Select Course' }].concat(courses.map(courseOption => ({ value: courseOption.id, label: courseOption.name })))}
             error={fieldErrors.course?.[0]}
            />
            </div>
{/* 
            {error && (
              <div className="alert">
                {error}
              </div>
            )} */}

            {submitted && (
              <div className="success">
                Registration submitted
                successfully.
              </div>
            )}

            <div className="modal-actions">
              <button
                type="button"
                className="ghost-button"
                onClick={handleReset}
              >
                Reset
              </button>

              <SubmitButton loading={saving}>Create</SubmitButton>
            </div>
          </form>

          <div className="register-footer">
            Already have an account?{' '}
            <Link to="/login">
              Sign In
            </Link>
          </div>
        </div>
      </div>
    </PageWrapper>
  );
}