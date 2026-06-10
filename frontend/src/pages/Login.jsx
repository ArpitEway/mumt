import { useState } from 'react';
import InputMask from 'react-input-mask';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth } from '../state/AuthContext.jsx';
import { loginSchema } from '@mmyvv/shared';

export default function Login() {
  const [formData, setFormData] = useState({  mobileNo: '', enrollmentNo: '', dob: '' });
  const [studentType, setStudentType] = useState('non-enrolled');
  const [errors, setErrors] = useState({});
  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();
  const { login } = useAuth();

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
    if (errors[name]) setErrors((prev) => ({ ...prev, [name]: '' }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});
    setIsLoading(true);

    try {
      const payload = studentType === 'non-enrolled'
        ? { username: formData.mobileNo, password: formData.dob, role: 'student', nonEnrolled: true }
        : { username: formData.enrollmentNo, password: formData.dob, role: 'student', nonEnrolled: false };

      const result = loginSchema.safeParse(payload);
      if (!result.success) {
        const fieldErrors = result.error.flatten().fieldErrors;
        const mappedErrors = {};
        if (studentType === 'non-enrolled') {
          if (fieldErrors.username) mappedErrors.mobileNo = fieldErrors.username;
        } else {
          if (fieldErrors.username) mappedErrors.enrollmentNo = fieldErrors.username;
        }
        if (fieldErrors.password) mappedErrors.dob = fieldErrors.password;
        setErrors(mappedErrors);
        setIsLoading(false);
        return;
      }

      const user = await login(payload);
      navigate(`/student-dashboard/${user.id}`); // navigate using logged-in user ID
    } catch (err) {
      setErrors({ submit: err.message || 'Login failed' });
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-r from-primary-100 to-primary-50">
      <header className="w-full bg-gradient-to-r from-primary-300 to-primary-400 text-white px-4 py-2 mb-0 shadow-md">
        <div className="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
          <div className="text-sm text-white flex items-center gap-4 font-medium">
            <span>Maharishi Road, Mangla Bilaspur - 495001
Chhattisgarh</span>
            <span className="hidden sm:inline">|</span>
            <a href="mailto:utdpaniin@gmail.com" className="underline text-white">mumt.registrar1@gmail.com</a>
          </div>
          <Link to="/admin-login" className="inline-flex items-center justify-center rounded-lg border-2 border-slate-800 bg-white px-5 py-1 text-sm font-semibold text-primary-300 transition hover:bg-white/60">Administrator Login</Link>
        </div>
      </header>

      <div className="w-full bg-white/60 border-b-2 border-primary-100 px-4 py-6 mb-8">
        <div className="max-w-7xl mx-auto flex items-center justify-between">
          <div className="flex items-center gap-4">
            <div className="w-12 h-12 rounded-full bg-primary-50 flex items-center justify-center text-xl"> <img className="site-brand-logo" src="/assets/logo.png" alt="University logo" /></div>
            <h2 className="text-2xl font-bold text-primary-300">Maharishi University of Management and Technology Bilaspur</h2>
          </div>
          {/* <div className="w-16 h-16 rounded-full bg-primary-50 flex items-center justify-center text-4xl hidden sm:flex">🧑‍🎓</div> */}
        </div>
      </div>

      <main className="max-w-7xl mx-auto px-4 pb-8 grid gap-6 lg:grid-cols-[1fr_1.4fr] items-start lg:items-stretch">
        <section className="bg-white/95 rounded-[24px] border-2 border-primary-200 shadow-[0_20px_50px_rgba(253,184,19,0.12)] p-8 h-full flex flex-col">
          <div className="mb-8 flex flex-col items-center">
            <div className="w-16 h-16 rounded-full bg-primary-50 flex items-center justify-center mb-4">
              <span className="text-2xl"><img className="site-brand-logo" src="/assets/logo.png" alt="University logo" /></span>
            </div>
            <h1 className="text-3xl font-bold text-slate-900 text-center">SIGN IN</h1>
            <p className="text-center text-slate-600 text-sm mt-2">Enter Your Login Credential</p>
          </div>

          <div className="space-y-5">
            <div className="border-2 border-primary-200 rounded-lg bg-primary-50 p-4">
              <div className="flex items-center gap-4 justify-center">
                <label className="flex items-center cursor-pointer">
                  <input
                    type="radio"
                    name="studentType"
                    value="non-enrolled"
                    checked={studentType === 'non-enrolled'}
                    onChange={() => setStudentType('non-enrolled')}
                    className="mr-2 accent-primary-400"
                  />
                  <span className="text-sm font-medium text-slate-700">Non Enrolled Student</span>
                </label>
                <label className="flex items-center cursor-pointer">
                  <input
                    type="radio"
                    name="studentType"
                    value="enrolled"
                    checked={studentType === 'enrolled'}
                    onChange={() => setStudentType('enrolled')}
                    className="mr-2 accent-primary-400"
                  />
                  <span className="text-sm font-medium text-slate-700">Enrolled Student</span>
                </label></div>
              
            </div>
            <form onSubmit={handleSubmit} className="space-y-4">
            {studentType === 'non-enrolled' ? (
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-2">Mobile No</label>
                <input
                  type="text"
                  name="mobileNo"
                  value={formData.mobileNo}
                  onChange={handleChange}
                  placeholder="Enter your mobile number"
                  className="input-field"
                />
                {errors.mobileNo && <p className="text-accent-300 text-sm mt-1">{errors.mobileNo[0]}</p>}
              </div>
            ) : (
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-2">Enrollment No</label>
                <input
                  type="text"
                  name="enrollmentNo"
                  value={formData.enrollmentNo}
                  onChange={handleChange}
                  placeholder="Enter your enrollment number"
                  className="input-field"
                />
                {errors.enrollmentNo && <p className="text-accent-300 text-sm mt-1">{errors.enrollmentNo[0]}</p>}
              </div>
            )}
            <div>
              <label className="block text-sm font-medium text-slate-700 mb-2">Date Of Birth</label>
                <InputMask
                  mask="99/99/9999"
                  placeholder="DD/MM/YYYY"
                  value={formData.dob}
                  onChange={handleChange}
                >
                  {(inputProps) => (
                    <input
                      {...inputProps}
                      type="text"
                      name="dob"
                      className="input-field"
                    />
                  )}
                </InputMask>
              {errors.dob && <p className="text-accent-300 text-sm mt-1">{errors.dob[0]}</p>}
            </div>

              {errors.submit && (
                <div className="bg-accent-100 border border-accent-300 text-accent-600 px-4 py-3 rounded">
                  {errors.submit}
                </div>
              )}

              <button
                type="submit"
                disabled={isLoading}
                className="w-full btn-primary disabled:opacity-50 py-3"
              >
                {isLoading ? 'Signing in...' : 'Sign In'}
              </button>
            </form>

            <p className="text-center text-sm text-slate-600">
              नवीन प्रवेश हेतु <Link to="/register" className="text-primary-300 font-semibold hover:text-primary-400">Signup करें</Link>
            </p>
          </div>
        </section>

        <aside className="rounded-[24px] border-2 border-primary-200 bg-white/95 p-8 shadow-[0_20px_50px_rgba(253,184,19,0.12)]">
          <div className="mb-6">
            <h2 className="text-xl font-bold text-slate-900 mb-2">रजिस्ट्रेशन प्रक्रिया -</h2>
            <p className="text-sm text-slate-700 mb-4">उन छात्रों के लिए रजिस्ट्रेशन प्रक्रिया जो पोर्टल पर प्रथम बार रजिस्टर होंगे-</p>
            
            <ol className="space-y-3 text-sm text-slate-700 list-decimal list-inside">
              <li>सबसे पहले SIGNUP पर क्लिक कर अपना महाविद्यालय चुनते हुए अपना अकाउंट बनायें एवं वांछित भुगतान करें।</li>
              <li>पोर्टल पर Non Enrolled Student के रूप में अपने मोबाइल नं. एवं जन्मतिथि के द्वारा लॉग-इन करें।</li>
              <li>Registration form को पूरा भरें। वांछित डॉक्यूमेंट्स अपलोड करें।</li>
              <li>सम्बन्धित महाविद्यालय द्वारा आपके फार्म का वेरिफिकेशन किया जायेगा।फॉर्म यदि कोई कमी पाई जाती है तो आपको आपके लॉगिन के माध्यम से सूचित किया जायेगा।</li>
              <li>महाविद्यालय आपकी फॉर्म Approve किया तो आपको Enrollment/Registration no. जारी किया जायेगा।</li>
              <li>Enrollment/Registration no. जारी हो जाने के पश्चात विद्यार्थी को गूगल के प्लेटफॉर्म द्वारा विविध की पड़वाई एप्लीकेशन डाउनलोड कर उसे Enrollment/Registration no. एवं जन्मतिथि से ही ओपन करने की अनुमति की जारी है।</li>
            </ol>
          </div>
          
          <div className="mb-4 pt-4 border-t border-primary-100">
            <h3 className="text-lg font-bold text-slate-900 mb-3">Document Verification (दस्तावेजों के सत्यापन) सम्बन्धी सूचना -</h3>
            <p className="text-sm text-slate-700 mb-2">
              <strong>1. Document Verification विविध के विविध विभागों द्वारा किया जाता है।</strong> अतः इस समस्या में अपने सम्बन्धित विभाग में ही सीधे कर। तकनीकी सहायता हेतु प्रवेश Complaint Section अथवा मोबाइल नंबर पर हुई समस्या में शिकायत दर्ज न करें।
            </p>
          </div>
          
          <div className="pt-4 border-t border-primary-100">
            <h3 className="font-semibold text-white mb-2">पोर्टल पर पहले से रजिस्टर्ड छात्रों के लिए प्रक्रिया -</h3>
            <ol className="space-y-2 text-sm text-slate-700 list-decimal list-inside">
              <li>पोर्टल पर अपना उपयोक्ता नाम एवं पासवर्ड डालकर लॉग इन करें।</li>
              <li>अपने स्वंय के प्रश्नोत्तर (Paper Select) करें।</li>
            </ol>
          </div>
        </aside>
      
      </main>
      
      <footer className="w-full bg-gradient-to-r from-primary-300 to-primary-400 text-white mt-12 shadow-md">
         <div className="max-w-7xl mx-auto px-4 py-4 text-center">
        MUMT © 2026. All rights reserved.
          {/* <p className="text-sm text-primary-600 mb-4 font-semibold text-center">
            किसी भी समस्या के लिए संपर्क जानकारी इस प्रकार है :-
          </p>
          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 text-center mb-4">
            <div className="text-sm">
              <p className="font-semibold text-primary-600">Dr Sankalp Mishra Ji</p>
              <p className="text-white/80 font-medium">8827087772</p>
            </div>
            <div className="text-sm">
              <p className="font-semibold text-white">Dr Shubham Sharma Ji</p>
              <p className="text-white/80 font-medium">9425464950</p>
            </div>
            <div className="text-sm">
              <p className="font-semibold text-white">Dr Akhilesh Dwivedi Ji</p>
              <p className="text-white/80 font-medium">8858216123</p>
            </div>
            <div className="text-sm">
              <p className="font-semibold text-white">Dr Upendra Bhargav</p>
              <p className="text-white/80 font-medium">9893528889</p>
            </div>
            <div className="text-sm">
              <p className="font-semibold text-white">Dr Tulsidas Pargaha Ji</p>
              <p className="text-white/80 font-medium">9479475988<br/>7089954293</p>
            </div>
          </div>
          <p className="text-xs text-white/80 text-center">
            तकनीकी सहायता Mode 9302366908 (कृपय इस नं को काल न करे) <a href="mailto:techsupptmpsav@gmail.com" className="text-white font-semibold hover:underline">Mail techsupptmpsav@gmail.com</a>
          </p> */}
        </div>
      </footer>
    </div>
  );
}
