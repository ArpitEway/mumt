import React, { useEffect, useMemo, useRef, useState } from 'react';
import { toast } from 'react-hot-toast';
import { useNavigate, useParams } from 'react-router-dom';
import { api, API_URL } from '../api/client.js';
import { decodeId, encodeId } from '@mmyvv/shared/idEncryption';
import { studentRegistrationSchema } from '@mmyvv/shared';
import { useAuth } from '../state/AuthContext.jsx';
import PageWrapper from '../components/PageWrapper.jsx';
import SelectInput from '../components/forms/SelectInput.jsx';
import RadioInput from '../components/forms/RadioInput.jsx';
import TextInput from '../components/forms/TextInput.jsx';
import InputMask from 'react-input-mask';

export default function StudentRegistrationPage() {
  const navigate = useNavigate();
  const { user } = useAuth();
  const { id } = useParams();
  const studentId = decodeId(id) || user?.id;
  const [errors, setErrors] = useState({});
  const [isLoading, setIsLoading] = useState(false);
  const [isFetching, setIsFetching] = useState(false);
  const [photoPreview, setPhotoPreview] = useState(null);
  const [options, setOptions] = useState({
    session: '2026',
    eligibilities: [],
  });
  const [loadingOptions, setLoadingOptions] = useState(true);

  const [formData, setFormData] = useState({
    // Educational Details
    session: '2026',
    course: '',
    courseGroupId: '',
    eligibility: '',
    class: '',
    // Personal Details
    fullName: '',
    fatherName: '',
    motherName: '',
    gender: 'Male',
    medium: 'English',
    maritalStatus: 'Unmarried',
    phoneNumber: '',
    email: '',
    dateOfBirth: '',
    nationality: '',
    religion: '',
    category: '',
    minority: 'Yes',
    disabilityStatus: 'Yes',
    aadharNumber: '',
    // Current Address
    currentAddress: '',
    currentState: '',
    currentDistrict: '',
    currentCity: '',
    currentPinCode: '',
    // Permanent Address
    permanentAddress: '',
    permanentState: '',
    permanentDistrict: '',
    permanentCity: '',
    permanentPinCode: '',
    // Photo
    photo: null,
  });

  const [stateOptions, setStateOptions] = useState([]);
  const [districtOptions, setDistrictOptions] = useState([]);
  const [loadingLocationOptions, setLoadingLocationOptions] = useState(true);
  const [copyAddress, setCopyAddress] = useState(false);
  const [validationState, setValidationState] = useState({
    mobileExists: false,
    aadharExists: false,
    checkingMobile: false,
    checkingAadhar: false,
  });
  const debounceTimerRef = useRef({});

  useEffect(() => {
    return () => {
      clearTimeout(debounceTimerRef.current.mobile);
      clearTimeout(debounceTimerRef.current.aadhar);
    };
  }, []);

  const fetchResourceRows = async (table) => {
    const rows = [];
    let page = 1;
    let total = 0;

    do {
      const res = await api(`/resources/${table}?limit=100&page=${page}`);
      if (res?.data) {
        rows.push(...res.data);
      }
      total = res.pagination?.total || 0;
      page += 1;
    } while (rows.length < total && page <= 10);

    return rows;
  };

  const checkMobileUniqueness = (mobile) => {
    const cleanMobile = mobile.replace(/\D/g, '');

    if (cleanMobile.length !== 10) {
      setValidationState((prev) => ({ ...prev, mobileExists: false, checkingMobile: false }));
      return;
    }

    setValidationState((prev) => ({ ...prev, checkingMobile: true }));
    clearTimeout(debounceTimerRef.current.mobile);
    debounceTimerRef.current.mobile = setTimeout(async () => {
      try {
        const response = await api('/registration/check-mobile', {
          method: 'POST',
          body: {
            mobile: cleanMobile,
            excludeStudentId: studentId,
          },
        });

        const exists = response.exists || false;
        setValidationState((prev) => ({
          ...prev,
          mobileExists: exists,
          checkingMobile: false,
        }));

        setErrors((prev) => ({
          ...prev,
          phoneNumber: exists ? 'Mobile number already exists' : '',
        }));
      } catch (err) {
        console.error('Error checking mobile:', err);
        setValidationState((prev) => ({ ...prev, checkingMobile: false }));
      }
    }, 500);
  };

  const checkAadharUniqueness = (aadhar) => {
    const cleanAadhar = aadhar.replace(/\D/g, '');

    if (cleanAadhar.length !== 12) {
      setValidationState((prev) => ({ ...prev, aadharExists: false, checkingAadhar: false }));
      return;
    }

    setValidationState((prev) => ({ ...prev, checkingAadhar: true }));
    clearTimeout(debounceTimerRef.current.aadhar);
    debounceTimerRef.current.aadhar = setTimeout(async () => {
      try {
        const response = await api('/registration/check-aadhar', {
          method: 'POST',
          body: {
            aadhar: cleanAadhar,
            excludeStudentId: studentId,
          },
        });

        const exists = response.exists || false;
        setValidationState((prev) => ({
          ...prev,
          aadharExists: exists,
          checkingAadhar: false,
        }));

        setErrors((prev) => ({
          ...prev,
          aadharNumber: exists ? 'Aadhaar number already exists' : '',
        }));
      } catch (err) {
        console.error('Error checking aadhar:', err);
        setValidationState((prev) => ({ ...prev, checkingAadhar: false }));
      }
    }, 500);
  };

  const handleChange = (e) => {
    const { name, value } = e.target;

    if (name === 'phoneNumber') {
      checkMobileUniqueness(value);
    }
    if (name === 'aadharNumber') {
      checkAadharUniqueness(value);
    }

    setFormData((prev) => {
      const next = { ...prev, [name]: value };

      if (name === 'currentState') {
        next.currentDistrict = '';
        if (copyAddress) next.permanentDistrict = '';
      }

      if (copyAddress && name.startsWith('current')) {
        const permanentName = name.replace(/^current/, 'permanent');
        if (permanentName in prev) {
          next[permanentName] = value;
        }
      }

      return next;
    });

    if (errors[name]) setErrors((prev) => ({ ...prev, [name]: '' }));
  };

  const toggleCopyAddress = () => {
    setCopyAddress((prevCopy) => {
      if (!prevCopy) {
        setFormData((prevForm) => ({
          ...prevForm,
          permanentAddress: prevForm.currentAddress,
          permanentState: prevForm.currentState,
          permanentDistrict: prevForm.currentDistrict,
          permanentCity: prevForm.currentCity,
          permanentPinCode: prevForm.currentPinCode,
        }));
      }
      return !prevCopy;
    });
  };

  const handlePhotoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      // Check file size (max 5MB)
      if (file.size > 5 * 1024 * 1024) {
        setErrors(prev => ({ ...prev, photo: 'File size must be less than 5MB' }));
        toast.error('File size must be less than 5MB');
        return;
      }
      
      // Check file type
      if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
        setErrors(prev => ({ ...prev, photo: 'Only JPEG and PNG files are allowed' }));
        toast.error('Only JPEG and PNG files are allowed');
        return;
      }

      setFormData(prev => ({ ...prev, photo: file }));
      
      // Create preview
      const reader = new FileReader();
      reader.onloadend = () => {
        setPhotoPreview(reader.result);
      };
      reader.readAsDataURL(file);
      
      if (errors.photo) setErrors(prev => ({ ...prev, photo: '' }));
    }
  };

  const validateForm = () => {
    const result = studentRegistrationSchema.safeParse(formData);
    if (result.success) {
      setErrors({});
      return true;
    }

    const fieldErrors = result.error.flatten().fieldErrors;
    const newErrors = {};
    Object.keys(fieldErrors).forEach((field) => {
      newErrors[field] = fieldErrors[field]?.[0] || 'Invalid value';
    });

    setErrors(newErrors);
    return false;
  };

  useEffect(() => {
    let active = true;
    api('/registration/options')
      .then((res) => {
        if (!active) return;
        setOptions({
          session: res.session || '2026',
          eligibilities: Array.isArray(res.eligibilities) ? res.eligibilities : [],
        });
      })
      .catch((err) => {
        console.error('Failed to load registration options:', err);
      })
      .finally(() => {
        if (active) setLoadingOptions(false);
      });

    return () => {
      active = false;
    };
  }, []);

  const selectedEligibility = useMemo(
    () => options.eligibilities.find((item) => item.name === formData.eligibility),
    [formData.eligibility, options.eligibilities]
  );

 

  const courseOptions = selectedEligibility?.courses || [];
  const selectedCourse = useMemo(
    () => courseOptions.find((course) => String(course.id) === String(formData.courseGroupId) || course.name === formData.course),
    [formData.courseGroupId, formData.course, courseOptions]
  );

  useEffect(() => {
    if (!formData.course && selectedCourse?.name) {
      setFormData((prev) => ({ ...prev, course: selectedCourse.name }));
    }
  }, [selectedCourse, formData.course]);

  useEffect(() => {
    let active = true;

    const loadLocationOptions = async () => {
      try {
        const [states, districts] = await Promise.all([
          fetchResourceRows('state'),
          fetchResourceRows('distt'),
        ]);

        if (!active) return;

        setStateOptions(states.map((row) => ({ value: String(row.state_id || row.id || row.name), label: row.name })));
        setDistrictOptions(districts.map((row) => ({ value: String(row.distt_id || row.id || row.name), label: row.name, stateId: String(row.state_id) })));
      } catch (err) {
        console.error('Failed to load state/district options:', err);
      } finally {
        if (active) setLoadingLocationOptions(false);
      }
    };

    loadLocationOptions();

    return () => {
      active = false;
    };
  }, []);

  const currentDistrictOptions = useMemo(
    () => districtOptions.filter((district) => !district.stateId || district.stateId === String(stateOptions.find((s) => s.value === formData.currentState)?.value)),
    [districtOptions, formData.currentState, stateOptions]
  );

  const permanentDistrictOptions = useMemo(
    () => districtOptions.filter((district) => !district.stateId || district.stateId === String(stateOptions.find((s) => s.value === formData.permanentState)?.value)),
    [districtOptions, formData.permanentState, stateOptions]
  );

  useEffect(() => {
    if (!studentId) return;

    setIsFetching(true);
    api(`/students/${studentId}/form`)
      .then((res) => {
        const fieldValues = {};
        (res.fieldGroups || []).forEach((group) => {
          (group.fields || []).forEach((field) => {
            fieldValues[field.name] = field.value;
          });
        });

        const normalizedDob = fieldValues.dob ? String(fieldValues.dob).split('T')[0] : '';
        setFormData((prev) => ({
          ...prev,
          session: fieldValues.session || prev.session,
          course: fieldValues.course_name || prev.course,
          eligibility: fieldValues.eligibility || prev.eligibility,
          courseGroupId: fieldValues.course_group_id ? String(fieldValues.course_group_id) : prev.courseGroupId,
          class: fieldValues.class_name || prev.class,
          fullName: fieldValues.name || prev.fullName,
          fatherName: fieldValues.f_h_name || prev.fatherName,
          motherName: fieldValues.mother_name || prev.motherName,
          gender: fieldValues.gender || prev.gender,
          phoneNumber: fieldValues.p_mobile_no || prev.phoneNumber,
          email: fieldValues.p_email || prev.email,
          dateOfBirth: normalizedDob || prev.dateOfBirth,
          nationality: fieldValues.nationality || prev.nationality,
          religion: fieldValues.religion || prev.religion,
          category: fieldValues.category || prev.category,
          disabilityStatus: fieldValues.p_handicapped || prev.disabilityStatus,
          aadharNumber: fieldValues.adhar_no || prev.aadharNumber,
          currentAddress: fieldValues.c_address || prev.currentAddress,
          currentState: fieldValues.c_state || prev.currentState,
          currentDistrict: fieldValues.c_district || prev.currentDistrict,
          currentCity: fieldValues.c_city || prev.currentCity,
          currentPinCode: String(fieldValues.c_pin_code) || String(prev.currentPinCode),
          permanentAddress: fieldValues.p_address || prev.permanentAddress,
          permanentState: fieldValues.p_state || prev.permanentState,
          permanentDistrict: fieldValues.p_district || prev.permanentDistrict,
          permanentCity: fieldValues.p_city || prev.permanentCity,
          permanentPinCode: String(fieldValues.p_pin_code) || String(prev.permanentPinCode),
        }));

        if (res.photo) {
          const baseUrl = API_URL.replace(/\/api$/, '');
          setPhotoPreview(res.photo.startsWith('http') ? res.photo : `${baseUrl}${res.photo}`);
        }
      })
      .catch((err) => {
        console.error('Failed to load student registration data:', err);
      })
      .finally(() => {
        setIsFetching(false);
      });
  }, [studentId]);

  const classOptions = selectedEligibility?.name === 'HSSC'
    ? [
        { value: '', label: 'Select Class' },
        { value: 'I Year', label: 'I Year' },
        { value: 'II Year', label: 'II Year' },
        { value: 'III Year', label: 'III Year' },
        { value: 'IV Year', label: 'IV Year' },
      ]
    : selectedEligibility?.name === 'GRADUATION'
    ? [
        { value: '', label: 'Select Class' },
        { value: 'I Sem', label: 'I Sem' },
        { value: 'II Sem', label: 'II Sem' },
        { value: 'III Sem', label: 'III Sem' },
        { value: 'IV Sem', label: 'IV Sem' },
      ]
    : [{ value: '', label: 'Select Class' }];

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!studentId) {
      const message = 'Student ID is not available for registration update';
      setErrors({ submit: message });
      toast.error(message);
      return;
    }

    if (validationState.mobileExists) {
      const message = 'Mobile number already exists';
      setErrors((prev) => ({ ...prev, phoneNumber: message }));
      toast.error(message);
      return;
    }

    if (validationState.aadharExists) {
      const message = 'Aadhaar number already exists';
      setErrors((prev) => ({ ...prev, aadharNumber: message }));
      toast.error(message);
      return;
    }

    if (!validateForm()) {
      toast.error('Please correct the highlighted errors');
      return;
    }

    setIsLoading(true);
    try {
      const payload = {
        student: {
          session: formData.session,
          course_name: selectedCourse ? selectedCourse.name : formData.course,
          course_group_id: formData.courseGroupId || undefined,
          class_name: formData.class,
          eligibility: formData.eligibility,
          name: formData.fullName,
          f_h_name: formData.fatherName,
          mother_name: formData.motherName,
          gender: formData.gender,
          dob: formData.dateOfBirth,
          category: formData.category,
          adhar_no: formData.aadharNumber,
        },
        student_data: {
          nationality: formData.nationality,
          religion: formData.religion,
          p_mobile_no: formData.phoneNumber,
          p_email: formData.email,
          c_address: formData.currentAddress,
          c_state: formData.currentState,
          c_district: formData.currentDistrict,
          c_city: formData.currentCity,
          c_pin_code: formData.currentPinCode,
          p_address: formData.permanentAddress,
          p_state: formData.permanentState,
          p_district: formData.permanentDistrict,
          p_city: formData.permanentCity,
          p_pin_code: formData.permanentPinCode,
          p_handicapped: formData.disabilityStatus,
        }
      };

      await api(`/students/${studentId}/form`, {
        method: 'PUT',
        body: payload,
      });

      if (formData.photo) {
        const photoForm = new FormData();
        photoForm.append('photo', formData.photo);
        await api(`/students/${studentId}/photo`, {
          method: 'POST',
          body: photoForm,
        });
      }

      toast.success('Registration form saved successfully');
      navigate(`/student-dashboard/${id || encodeId(studentId)}`);
    } catch (err) {
      const errorMessage = err.message || 'Failed to submit form';
      setErrors({ submit: errorMessage });
      toast.error(errorMessage);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <PageWrapper>
      <div className="max-w-7xl mx-auto p-4">
        <div className="bg-white rounded-lg shadow-md">
          <div className="mb-6 px-6 pt-6">
            <h1 className="text-2xl font-bold text-gray-800">Registration Form</h1>
            <p className="text-gray-600 text-sm mt-1">Fill in all the required fields marked with <span className="text-red-500">*</span></p>
          </div>

          <form onSubmit={handleSubmit} className="px-6 pb-6">
            <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
              {/* Main form content - takes 3 columns */}
              <div className="lg:col-span-3 space-y-6">
                {/* Educational Details */}
                <div className="border-b pb-6">
                  <h2 className="text-lg font-bold text-gray-800 mb-4">Educational Details</h2>
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <SelectInput
                        label="Session *"
                        name="session"
                        value={formData.session}
                        onChange={handleChange}
                        options={[
                          { value: '2026', label: '2026' },
                          { value: '2025', label: '2025' },
                          { value: '2024', label: '2024' },
                        ]}
                        error={errors.session}
                        className="focus:outline-none focus:ring-2 focus:ring-primary-300"
                      />
                    </div>
                    <div>
                      <SelectInput
                        label="Eligibility *"
                        name="eligibility"
                        value={formData.eligibility}
                        onChange={(e) => {
                          handleChange(e);
                          setFormData((prev) => ({ ...prev, course: '', courseGroupId: '' }));
                        }}
                        options={
                          [{ value: '', label: loadingOptions ? 'Loading eligibilities...' : 'Select Eligibility' }]
                            .concat(options.eligibilities.map((item) => ({ value: item.name, label: item.name })))
                        }
                        error={errors.eligibility}
                        className="focus:outline-none focus:ring-2 focus:ring-primary-300"
                      />
                    </div>
                    <div>
                      <input type="hidden" name="courseGroupId" value={formData.courseGroupId} />
                      <SelectInput
                        label="Course *"
                        name="course"
                        value={selectedCourse?.name || formData.course}
                        onChange={(e) => {
                          const selectedName = e.target.value;
                          const found = courseOptions.find((c) => c.name === selectedName || String(c.id) === String(selectedName));
                          setFormData((prev) => ({
                            ...prev,
                            course: selectedName,
                            courseGroupId: found ? String(found.id) : '',
                          }));
                        }}
                        options={
                          [{ value: '', label: loadingOptions ? 'Loading courses...' : 'Select Course' }]
                            .concat(courseOptions.map((course) => ({ value: course.name, label: course.name || course.label })))
                        }
                        error={errors.course}
                        className="focus:outline-none focus:ring-2 focus:ring-primary-300"
                        disabled={!formData.eligibility}
                      />
                    </div>
                    <div>
                      {/* <SelectInput
                        label="Class *"
                        name="class"
                        value={formData.class}
                        onChange={handleChange}
                        options={[
                          { value: '', label: 'Select Class' },
                          { value: 'I-BM', label: 'I-BM' },
                          { value: 'I-CS', label: 'I-CS' },
                          { value: 'I-EC', label: 'I-EC' },
                        ]}
                        error={errors.class}
                        className="focus:outline-none focus:ring-2 focus:ring-primary-300"
                      /> */}
                      <SelectInput
  label="Class *"
  name="class"
  value={formData.class}
  onChange={handleChange}
  options={classOptions}
  error={errors.class}
  className="focus:outline-none focus:ring-2 focus:ring-primary-300"
/>
                    </div>
                  </div>
                </div>

                {/* Personal Details */}
                <div className="border-b pb-6">
                  <h2 className="text-lg font-bold text-gray-800 mb-4">Personal Details</h2>
                  
                  <div className="grid grid-cols-3 gap-4 mb-4">
                    <div>
                      <TextInput
                        label="Student Name *"
                        name="fullName"
                        placeholder="Full Name"
                        value={formData.fullName}
                        onChange={handleChange}
                        error={errors.fullName}
                      />
                    </div>
                     <div>
                      <TextInput
                        label="Father / Husband Name *"
                        name="fatherName"
                        value={formData.fatherName}
                        onChange={handleChange}
                        error={errors.fatherName}
                      />
                    </div>
                    <div>
                      <TextInput
                        label="Mother's Name *"
                        name="motherName"
                        value={formData.motherName}
                        onChange={handleChange}
                        error={errors.motherName}
                      />
                    </div>
                  </div>

                  <div className="grid grid-cols-3 gap-4 mb-4">
                    <RadioInput
                      label="Gender *"
                      name="gender"
                      value={formData.gender}
                      onChange={handleChange}
                      options={[
                        { value: 'Male', label: 'Male' },
                        { value: 'Female', label: 'Female' },
                      ]}
                      error={errors.gender}
                      className="lg:col-span-1"
                    />
                   
                    
                       <RadioInput
                      label="Medium *"
                      name="medium"
                      value={formData.medium}
                      onChange={handleChange}
                      options={[
                      { value: 'English', label: 'English' },
                          { value: 'Hindi', label: 'Hindi' },
                      ]}
                      error={errors.medium}
                      className="lg:col-span-1"
                    />
                   
                   
                        <RadioInput
                      label="Marital Status"
                        name="maritalStatus"
                        value={formData.maritalStatus}
                        onChange={handleChange}
                        options={[
                          { value: 'Unmarried', label: 'Unmarried' },
                          { value: 'Married', label: 'Married' },
                        ]}
                      error={errors.maritalStatus}
                      className="lg:col-span-1"
                    />
                   
                  </div>

                  <div className="grid grid-cols-3 gap-4 mb-4">
                    <div>
                      <TextInput
                        label="Mobile No. *"
                        name="phoneNumber"
                        placeholder="10 digits"
                        value={formData.phoneNumber}
                        onChange={handleChange}
                        error={errors.phoneNumber}
                        type="tel"
                      />
                    </div>
                    <div>
                      <TextInput
                        label="Email *"
                        name="email"
                        value={formData.email}
                        onChange={handleChange}
                        error={errors.email}
                        type="email"
                      />
                    </div>
                    <div>
                      <label className="block text-sm font-semibold text-gray-700 mb-2">Date of Birth <span className="text-red-500">*</span></label>
                      <InputMask
                        mask="9999-99-99"
                        value={formData.dateOfBirth}
                        onChange={handleChange}
                        name="dateOfBirth"
                      >
                        {(inputProps) => (
                          <input
                            {...inputProps}
                            type="text"
                            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300"
                            placeholder="YYYY-MM-DD"
                          />
                        )}
                      </InputMask>
                      {errors.dateOfBirth && <p className="text-red-500 text-sm mt-1">{errors.dateOfBirth}</p>}
                    </div>
                  </div>

                  <div className="grid grid-cols-3 gap-4 mb-4">
                    <div>
                      <SelectInput
                        label="Nationality *"
                        name="nationality"
                        value={formData.nationality}
                        onChange={handleChange}
                        options={[
                          { value: '', label: 'Select' },
                          { value: 'Indian', label: 'Indian' },
                          { value: 'Other', label: 'Other' },
                        ]}
                        error={errors.nationality}
                        className="focus:outline-none focus:ring-2 focus:ring-primary-300"
                      />
                    </div>
                    <div>
                      <SelectInput
                        label="Religion *"
                        name="religion"
                        value={formData.religion}
                        onChange={handleChange}
                        options={[
                          { value: '', label: 'Select' },
                          { value: 'Hindu', label: 'Hindu' },
                          { value: 'Muslim', label: 'Muslim' },
                          { value: 'Christian', label: 'Christian' },
                          { value: 'Sikh', label: 'Sikh' },
                          { value: 'Other', label: 'Other' },
                        ]}
                        error={errors.religion}
                        className="focus:outline-none focus:ring-2 focus:ring-primary-300"
                      />
                    </div>
                     <div>
                      <SelectInput
                        label="Category *"
                        name="category"
                        value={formData.category}
                        onChange={handleChange}
                        options={[
                          { value: '', label: 'Select' },
                          { value: 'General', label: 'General' },
                          { value: 'OBC', label: 'OBC' },
                          { value: 'SC', label: 'SC' },
                          { value: 'ST', label: 'ST' },
                        ]}
                        error={errors.category}
                        className="focus:outline-none focus:ring-2 focus:ring-primary-300"
                      />
                    </div>
                  </div>

                  <div className="grid grid-cols-3 gap-4 mb-4">
                    <TextInput
                      label="Aadhar Number"
                      name="aadharNumber"
                      placeholder="12 digits"
                      value={formData.aadharNumber}
                      onChange={handleChange}
                      error={errors.aadharNumber}
                    />
                    <RadioInput
                      label="Minority Status"
                      name="minority"
                      value={formData.minority}
                      onChange={handleChange}
                      options={[
                        { value: 'Yes', label: 'Yes' },
                        { value: 'No', label: 'No' },
                      ]}
                      className="lg:col-span-1"
                    />
                    <RadioInput
                      label="Person with Disability"
                      name="disabilityStatus"
                      value={formData.disabilityStatus}
                      onChange={handleChange}
                      options={[
                        { value: 'Yes', label: 'Yes' },
                        { value: 'No', label: 'No' },
                      ]}
                      className="lg:col-span-1"
                    />
                   
                  </div>

                </div>

                {/* Current Address */}
                <div className="border-b pb-6">
                  <h2 className="text-lg font-bold text-gray-800 mb-4">Current Address of Student</h2>
                  <div className="mb-4">
                    <label className="block text-sm font-semibold text-gray-700 mb-2">Address <span className="text-red-500">*</span></label>
                    <textarea
                      name="currentAddress"
                      rows="2"
                      value={formData.currentAddress}
                      onChange={handleChange}
                      className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300"
                    />
                    {errors.currentAddress && <p className="text-red-500 text-sm mt-1">{errors.currentAddress}</p>}
                  </div>
                  <div className="grid grid-cols-4 gap-4">
                    <div>
                      <SelectInput
                        label="State *"
                        name="currentState"
                        value={formData.currentState}
                        onChange={handleChange}
                        options={[
                          { value: '', label: loadingLocationOptions ? 'Loading states...' : 'Select State' },
                          ...stateOptions,
                        ]}
                        error={errors.currentState}
                        disabled={loadingLocationOptions}
                      />
                    </div>
                    <div>
                      <SelectInput
                        label="District *"
                        name="currentDistrict"
                        value={formData.currentDistrict}
                        onChange={handleChange}
                        options={[
                          { value: '', label: loadingLocationOptions ? 'Loading districts...' : 'Select District' },
                          ...currentDistrictOptions,
                        ]}
                        error={errors.currentDistrict}
                        disabled={loadingLocationOptions || !formData.currentState}
                      />
                    </div>
                    <div>
                      <TextInput
                        label="City *"
                        name="currentCity"
                        value={formData.currentCity}
                        onChange={handleChange}
                        error={errors.currentCity}
                      />
                    </div>
                    <div>
                      <TextInput
                        label="Pin Code *"
                        name="currentPinCode"
                        placeholder="6 digits"
                        value={formData.currentPinCode}
                        onChange={handleChange}
                        error={errors.currentPinCode}
                      />
                    </div>
                  </div>
                </div>

                {/* Permanent Address */}
                <div className="border-b pb-6">
                  <div className="flex items-center justify-between mb-4 gap-4">
                    <div>
                      <h2 className="text-lg font-bold text-gray-800">Permanent Address of Student</h2>
                      <p className="text-sm text-gray-600">Use current address for permanent address if same</p>
                    </div>
                    <label className="inline-flex items-center gap-2 text-sm text-gray-700">
                      <input
                        type="checkbox"
                        checked={copyAddress}
                        onChange={toggleCopyAddress}
                        className="form-checkbox h-4 w-4 text-primary-500"
                      />
                      Copy current address to permanent address
                    </label>
                  </div>
                  <div className="mb-4">
                    <label className="block text-sm font-semibold text-gray-700 mb-2">Address <span className="text-red-500">*</span></label>
                    <textarea
                      name="permanentAddress"
                      rows="2"
                      value={formData.permanentAddress}
                      onChange={handleChange}
                      className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300"
                      disabled={copyAddress}
                    />
                    {errors.permanentAddress && <p className="text-red-500 text-sm mt-1">{errors.permanentAddress}</p>}
                  </div>
                  <div className="grid grid-cols-4 gap-4">
                    <div>
                      <SelectInput
                        label="State *"
                        name="permanentState"
                        value={formData.permanentState}
                        onChange={handleChange}
                        options={[
                          { value: '', label: loadingLocationOptions ? 'Loading states...' : 'Select State' },
                          ...stateOptions,
                        ]}
                        error={errors.permanentState}
                        disabled={loadingLocationOptions}
                      />
                    </div>
                    <div>
                      <SelectInput
                        label="District *"
                        name="permanentDistrict"
                        value={formData.permanentDistrict}
                        onChange={handleChange}
                        options={[
                          { value: '', label: loadingLocationOptions ? 'Loading districts...' : 'Select District' },
                          ...permanentDistrictOptions,
                        ]}
                        error={errors.permanentDistrict}
                        disabled={loadingLocationOptions || !formData.permanentState}
                      />
                    </div>
                    <div>
                      <TextInput
                        label="City *"
                        name="permanentCity"
                        value={formData.permanentCity}
                        onChange={handleChange}
                        error={errors.permanentCity}
                        disabled={copyAddress}
                      />
                    </div>
                    <div>
                      <TextInput
                        label="Pin Code *"
                        name="permanentPinCode"
                        placeholder="6 digits"
                        value={formData.permanentPinCode}
                        onChange={handleChange}
                        error={errors.permanentPinCode}
                        disabled={copyAddress}
                      />
                    </div>
                  </div>
                </div>

                {/* Error Message */}
                {errors.submit && (
                  <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    {errors.submit}
                  </div>
                )}

                {/* Form Buttons */}
                <div className="flex gap-4 pt-6">
                  <button
                    type="button"
                    onClick={() => navigate(-1)}
                    className="flex-1 px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded hover:bg-gray-50 transition"
                  >
                    Cancel
                  </button>
                  <button
                    type="submit"
                    disabled={isLoading || isFetching}
                    className="flex-1 px-6 py-2 bg-primary-300 text-white font-semibold rounded hover:bg-primary-400 transition disabled:opacity-50"
                  >
                    {isLoading || isFetching ? 'Saving...' : 'Submit Application'}
                  </button>
                </div>
              </div>

              {/* Photo Upload - Right Side Column */}
              <div className="lg:col-span-1">
                <div className="bg-primary-50 rounded-lg p-6 sticky top-8 border border-primary-100">
                  <h3 className="text-lg font-bold text-gray-800 mb-4">Student Photo</h3>
                  
                  {photoPreview ? (
                    <div className="mb-4">
                      <img 
                        src={photoPreview} 
                        alt="Preview" 
                        className="w-full aspect-square object-cover rounded-lg border-2 border-primary-300"
                      />
                    </div>
                  ) : (
                    <div className="mb-4 w-full aspect-square bg-gray-200 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                      <div className="text-center">
                        <svg className="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                        </svg>
                        <p className="text-sm text-gray-500">No photo</p>
                      </div>
                    </div>
                  )}

                  <label className="block">
                    <input
                      type="file"
                      name="photo"
                      accept="image/jpeg,image/png,image/jpg"
                      onChange={handlePhotoChange}
                      className="hidden"
                    />
                    <span className="block w-full px-4 py-2 bg-primary-300 text-white text-center font-semibold rounded-lg cursor-pointer hover:bg-primary-400 transition">
                      Choose Photo
                    </span>
                  </label>

                  {photoPreview && (
                    <button
                      type="button"
                      onClick={() => {
                        setFormData(prev => ({ ...prev, photo: null }));
                        setPhotoPreview(null);
                      }}
                      className="w-full mt-2 px-4 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition"
                    >
                      Remove Photo
                    </button>
                  )}

                  {errors.photo && <p className="text-red-500 text-sm mt-2">{errors.photo}</p>}

                  <div className="mt-6 p-4 bg-white rounded-lg border border-primary-200">
                    <p className="text-xs text-slate-700">
                      <strong>Requirements:</strong>
                      <br />• JPG or PNG format
                      <br />• Max size: 5MB
                      <br />• Aspect Ratio: 1:1
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </PageWrapper>
  );
}

