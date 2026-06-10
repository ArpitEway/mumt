import React, { useEffect, useState } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import { api } from '../api/client';
import PageWrapper from '../components/PageWrapper.jsx';

export default function Payment() {
  const { id } = useParams();
  const navigate = useNavigate();

  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [data, setData] = useState(null);

  useEffect(() => {
    if (!id) return setError('Missing student id');

    setLoading(true);

    api(`/payment/${id}`)
      .then((res) => {
        setData(res);
      })
      .catch((err) => {
        setError(err.message || 'Failed to load');
      })
      .finally(() => {
        setLoading(false);
      });
  }, [id]);

  function handlePayNow() {
    api(`/payment/${id}/pay`, { method: 'POST' })
      .then((res) => {
        navigate(res.redirectTo || `/student-dashboard/${id}`);
      })
      .catch((err) => {
        setError(err.message || 'Payment failed');
      });
  }

  const topActions = (
    <Link to="/" className="site-header-button">
      Dashboard
    </Link>
  );

  if (loading) {
    return (
      <PageWrapper>
        <div className="payment-page">
          <div className="payment-card">
            <h2>Loading payment details...</h2>
          </div>
        </div>
      </PageWrapper>
    );
  }

  if (error) {
    return (
      <PageWrapper>
        <div className="payment-page">
          <div className="payment-card">
            <div className="alert">{error}</div>
          </div>
        </div>
      </PageWrapper>
    );
  }

  const student = data?.student || {};
  const amount = data?.amount || 0;

  return (
    <PageWrapper>
      <div className="payment-page">
        <div className="payment-card redesigned-payment-card">
          <div className="payment-header">
            <div>
              <span className="eyebrow">Admission 2026</span>
              <h1>Admission Payment</h1>
            </div>

            <div className="payment-badge">
              Secure Payment
            </div>
          </div>

          <div className="payment-layout">
            <div className="payment-student-card">
              <h3>Student Information</h3>

              <div className="payment-info-grid">
                <div className="payment-info-item">
                  <span>Student Name</span>
                  <strong>{student.name || student.student_name || '-'}</strong>
                </div>

                <div className="payment-info-item">
                  <span>Enrollment No.</span>
                  <strong>{student.enrollment_no || '-'}</strong>
                </div>

                <div className="payment-info-item">
                  <span>Mobile Number</span>
                  <strong>{student.mobile || '-'}</strong>
                </div>

                <div className="payment-info-item">
                  <span>Email Address</span>
                  <strong>{student.email || '-'}</strong>
                </div>
              </div>
            </div>

            <div className="payment-summary-card">
              <h3>Fee Summary</h3>

              <div className="payment-summary-row">
                <span>Portal Fees</span>
                <strong>₹ {amount}</strong>
              </div>

              <div className="payment-summary-row total">
                <span>Total Amount</span>
                <strong>₹ {amount}</strong>
              </div>

              <button
                className="primary-button payment-button"
                onClick={handlePayNow}
              >
                Pay Now
              </button>
            </div>
          </div>
        </div>
      </div>
    </PageWrapper>
  );
}
