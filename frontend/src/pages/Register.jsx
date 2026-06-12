import React, { useEffect, useMemo, useState, useRef } from 'react';
import { toast } from 'react-hot-toast';
import { Link, useNavigate } from 'react-router-dom';
import InputMask from 'react-input-mask';
import { api } from '../api/client';
import { registerSchema } from '@mmyvv/shared';
import { encodeId } from '@mmyvv/shared/idEncryption';
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
  aadharNo: '',
  eligibility: '',
  course: '',
  courseName: '',
};

export default function Register() {
  const [form, setForm] = useState(initialForm);
  const [submitted, setSubmitted] = useState(false);
  const [error, setError] = useState('');
  const [options, setOptions] = useState({
    session: '2026',
    eligibilities: [],
  });
  const [loadingOptions, setLoadingOptions] = useState(true);
  const [saving, setSaving] = useState(false);

  // Validation state
  const [validationState, setValidationState] = useState({
    mobileExists: false,
    aadharExists: false,
    checkingMobile: false,
    checkingAadhar: false,
  });

  const debounceTimerRef = useRef({});

  const navigate = useNavigate();
  const { login } = useAuth();

  // Cleanup debounce timers on unmount
  useEffect(() => {
    return () => {
      clearTimeout(debounceTimerRef.current.mobile);
      clearTimeout(debounceTimerRef.current.aadhar);
    };
  }, []);

  useEffect(() => {
    let active = true;

    api('/registration/options')
      .then((res) => {
        if (!active) return;

        const session = res.session || '2026';

        setOptions({
          session,
          eligibilities: Array.isArray(res.eligibilities)
            ? res.eligibilities
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

  const selectedEligibility = useMemo(
    () =>
      options.eligibilities.find(
        (item) => item.name === form.eligibility
      ),
    [form.eligibility, options.eligibilities]
  );

  const courses = selectedEligibility?.courses || [];


  function updateField(field, value) {
    setForm((current) => ({
      ...current,
      [field]: value,
    }));
  }

  // Check if mobile number already exists
  function checkMobileUniqueness(mobile) {
    const cleanMobile = mobile.replace(/\D/g, '');
    
    if (cleanMobile.length !== 10) {
      setValidationState(prev => ({ ...prev, mobileExists: false, checkingMobile: false }));
      return;
    }

    setValidationState(prev => ({ ...prev, checkingMobile: true }));

    // Debounce the API call
    clearTimeout(debounceTimerRef.current.mobile);
    debounceTimerRef.current.mobile = setTimeout(async () => {
      try {
        const response = await api('/registration/check-mobile', {
          method: 'POST',
          body: { mobile: cleanMobile }
        });
        setValidationState(prev => ({ 
          ...prev, 
          mobileExists: response.exists || false,
          checkingMobile: false 
        }));
      } catch (err) {
        console.error('Error checking mobile:', err);
        setValidationState(prev => ({ ...prev, checkingMobile: false }));
      }
    }, 500);
  }

  // Check if aadhar number already exists
  function checkAadharUniqueness(aadhar) {
    const cleanAadhar = aadhar.replace(/\D/g, '');
    
    if (cleanAadhar.length !== 12) {
      setValidationState(prev => ({ ...prev, aadharExists: false, checkingAadhar: false }));
      return;
    }

    setValidationState(prev => ({ ...prev, checkingAadhar: true }));

    // Debounce the API call
    clearTimeout(debounceTimerRef.current.aadhar);
    debounceTimerRef.current.aadhar = setTimeout(async () => {
      try {
        const response = await api('/registration/check-aadhar', {
          method: 'POST',
          body: { aadhar: cleanAadhar }
        });
        setValidationState(prev => ({ 
          ...prev, 
          aadharExists: response.exists || false,
          checkingAadhar: false 
        }));
      } catch (err) {
        console.error('Error checking aadhar:', err);
        setValidationState(prev => ({ ...prev, checkingAadhar: false }));
      }
    }, 500);
  }

  const [fieldErrors, setFieldErrors] = useState({});

  function handleSubmit(event) {
    event.preventDefault();

    const mobile = form.mobile.replace(/\\D/g, '');
    const payload = { ...form, mobile };

    // Check for existing mobile or aadhar
    if (validationState.mobileExists) {
      setError('Mobile number already exists');
      return;
    }

    if (validationState.aadharExists) {
      setError('Aadhaar number already exists');
      return;
    }

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
            toast.success('Registration successful');
            navigate(`/student-dashboard/${encodeId(id)}`);
          } catch (loginError) {
            const msg = loginError.message || 'Registration succeeded but auto-login failed';
            setError(msg);
            toast.error(msg);
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
            <input
              type="hidden"
              name="courseName"
              value={form.courseName}
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

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Date Of Birth
              </label>
              <InputMask
                mask="99/99/9999"
                value={form.dob}
                onChange={e => updateField('dob', e.target.value)}
                placeholder="DD/MM/YYYY"
                className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              {fieldErrors.dob?.[0] && (
                <p className="text-red-500 text-sm mt-1">{fieldErrors.dob[0]}</p>
              )}
            </div>
                 <TextInput
              label="Mobile No."
              type="tel"
              value={form.mobile}
              onChange={e => {
                updateField('mobile', e.target.value);
                checkMobileUniqueness(e.target.value);
              }}
              onBlur={() => checkMobileUniqueness(form.mobile)}
              placeholder="Enter your Mobile No"
              error={validationState.mobileExists ? 'Mobile number already exists' : (validationState.checkingMobile ? 'Checking...' : fieldErrors.mobile?.[0])}
            />

             <TextInput
              label="Aadhaar No."
              value={form.aadharNo}
              onChange={e => {
                updateField('aadharNo', e.target.value);
                checkAadharUniqueness(e.target.value);
              }}
              onBlur={() => checkAadharUniqueness(form.aadharNo)}
              placeholder="Enter your Aadhaar No."
              error={validationState.aadharExists ? 'Aadhaar number already exists' : (validationState.checkingAadhar ? 'Checking...' : fieldErrors.aadharNo?.[0])}
            />

            <SelectInput
              label="Eligibility"
              value={form.eligibility}
              onChange={e => { updateField('eligibility', e.target.value); updateField('course', ''); updateField('courseName', ''); }}
              loading={loadingOptions}
              options={[{ value: '', label: loadingOptions ? 'Loading eligibilities...' : 'Select Eligibility' }].concat(options.eligibilities.map(item => ({ value: item.name, label: item.name })))}
              error={fieldErrors.eligibility?.[0]}
            />

            <SelectInput
              label="Course"
              value={form.course}
              onChange={e => {
                updateField('course', e.target.value);
                const selected = courses.find(courseOption => String(courseOption.id) === String(e.target.value));
                updateField('courseName', selected?.name || '');
              }}
              disabled={!form.eligibility || loadingOptions}
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