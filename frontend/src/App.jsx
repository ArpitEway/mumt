import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import { useAuth } from './state/AuthContext.jsx';
import AdminLogin from './pages/AdminLogin.jsx';
import AdminDashboard from './pages/AdminDashboard.jsx';
import AdminProtected from './components/AdminProtected.jsx';
import Login from './pages/Login.jsx';
import Dashboard from './pages/Dashboard.jsx';
import ResourcePage from './pages/ResourcePage.jsx';
import StudentProfile from './pages/StudentProfile.jsx';
import Register from './pages/Register.jsx';
import Payment from './pages/Payment.jsx';
import CourseBuilder from './pages/CourseBuilder.jsx';
import StudentDashboard from './pages/StudentDashboard.jsx';
import StudentRegistrationPage from './pages/StudentRegistrationPage.jsx';
import StudentDocumentsPage from './pages/StudentDocumentsPage.jsx';
import StudentPaymentsPage from './pages/StudentPaymentsPage.jsx';
import Admission from './pages/Admission.jsx';
import MinimalLayout from './components/MinimalLayout.jsx';

function Protected({ children }) {
  const { isAuthenticated } = useAuth();
  if (!isAuthenticated) return <Navigate to="/login" replace />;
  return children;
}

export default function App() {
  const { isAuthenticated } = useAuth();

  return (
    <Routes>
      {/* Public routes */}
      <Route path="/login" element={<Login />} />
      <Route path="/register" element={<Register />} />
      <Route path="/admission" element={<Admission />} />
      <Route path="/admin-login" element={<AdminLogin />} />

      {/* Admin routes */}
      <Route path="/admin/dashboard" element={<AdminProtected><Dashboard /></AdminProtected>} />
      <Route path="/payment/:id" element={<Payment />} />
      <Route path="/students/registration/:id" element={<StudentRegistrationPage />} />
      <Route path="/students/documents/:id" element={<StudentDocumentsPage />} />
      <Route path="/students/payments/:id" element={<StudentPaymentsPage />} />
      <Route path="/student-dashboard/:id" element={<StudentDashboard />} />

      {/* Protected application routes */}
      <Route
        path="/"
        element={
          <Protected>
            <MinimalLayout />
          </Protected>
        }
      >
        <Route index element={<Dashboard />} />
        <Route path="students/profile" element={<StudentProfile />} />
        <Route path="students/dashboard" element={<StudentDashboard />} />
        <Route path="students/registration" element={<StudentRegistrationPage />} />
        <Route path="students/documents" element={<StudentDocumentsPage />} />
        <Route path="students/payments" element={<StudentPaymentsPage />} />
        <Route path="course-builder" element={<CourseBuilder />} />
        <Route path="resources/:table" element={<ResourcePage />} />
      </Route>

      {/* Catch‑all */}
      <Route path="*" element={<Navigate to={isAuthenticated ? '/' : '/login'} replace />} />
    </Routes>
  );
}
