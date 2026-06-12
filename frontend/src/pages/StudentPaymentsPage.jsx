import React, { useEffect, useMemo, useState } from 'react';
import { toast } from 'react-hot-toast';
import { api } from '../api/client.js';
import { useAuth } from '../state/AuthContext.jsx';
import { useParams } from 'react-router-dom';
import { StudentDashboardShell } from './StudentDashboard.jsx';
import { decodeId } from '@mmyvv/shared/idEncryption';

const paymentDetailFields = [
  ['payment_date', 'Payment Date'],
  ['payment_time', 'Payment Time'],
  ['fees_head', 'Fees Head'],
  ['amount', 'Amount'],
  ['payment_status', 'Payment Status'],
  ['payment_mode', 'Payment Mode'],
  ['payment', 'Payment Flag'],
  ['clientTxnId', 'Client Transaction ID'],
  ['PGTxnNo', 'PG Transaction No'],
  ['SabPaisaTxId', 'SabPaisa Transaction ID'],
  ['entry_time', 'Entry Time']
];

function displayValue(value) {
  if (value === null || value === undefined || value === '') return '-';
  return value;
}

export default function StudentPaymentsPage() {
  const { id } = useParams();
  const { user } = useAuth();
  const studentId = decodeId(id) || user?.id;
  const [payments, setPayments] = useState([]);
  const [selectedPayment, setSelectedPayment] = useState(null);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!studentId) return;
    loadPayments();
  }, [studentId]);

  async function loadPayments() {
    try {
      setLoading(true);
      setError('');
      const history = await api(`/students/${studentId}/payments`);
      setPayments(history?.data || []);
    } catch (err) {
      setError(err.message || 'Failed to load payment history');
    } finally {
      setLoading(false);
    }
  }

  const hasPayments = useMemo(() => payments.length > 0, [payments]);

  if (loading) return <div className="empty-state">Loading payment history...</div>;

  const content = (
    <div className="dashboard-card student-page-card">
      <div className="student-page-header">
        <div>
          <span className="eyebrow">Payment</span>
          <h2>Payment History</h2>
          <p>View all saved payment entries.</p>
        </div>
      </div>

      {error && <div className="alert">{error}</div>}

      <div className="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Date</th>
              <th>Head</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Transaction ID</th>
              <th>View</th>
            </tr>
          </thead>
          <tbody>
            {payments.map((payment) => (
              <tr key={payment.id}>
                <td>{payment.payment_date || payment.entry_time || '-'}</td>
                <td>{payment.fees_head || '-'}</td>
                <td>Rs. {payment.amount || 0}</td>
                <td>{payment.payment_status || payment.payment || '-'}</td>
                <td>{payment.clientTxnId || payment.PGTxnNo || payment.SabPaisaTxId || '-'}</td>
                <td>
                  <button
                    type="button"
                    className="ghost-button student-inline-button"
                    onClick={() => setSelectedPayment(payment)}
                  >
                    View
                  </button>
                </td>
              </tr>
            ))}

            {!hasPayments && (
              <tr>
                <td colSpan="6">No payment history found.</td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {selectedPayment && (
        <div className="modal-backdrop" onClick={() => setSelectedPayment(null)}>
          <div className="modal student-payment-modal" onClick={(event) => event.stopPropagation()}>
            <div className="modal-heading">
              <h2>Payment Details</h2>
              <button className="icon-button" type="button" onClick={() => setSelectedPayment(null)}>x</button>
            </div>

            <div className="detail-grid student-payment-detail-grid">
              {paymentDetailFields.map(([key, label]) => (
                <div className="detail-item" key={key}>
                  <span>{label}</span>
                  <strong>{displayValue(selectedPayment[key])}</strong>
                </div>
              ))}
            </div>
          </div>
        </div>
      )}
    </div>
  );

  if (id) {
    return <StudentDashboardShell studentId={studentId}>{content}</StudentDashboardShell>;
  }

  return content;
}
