import React, { useEffect, useState } from 'react';
import { api } from '../api/client.js';

export default function StudentProfile() {
  const [profile, setProfile] = useState(null);
  const [papers, setPapers] = useState([]);
  const [error, setError] = useState('');

  useEffect(() => {
    Promise.all([api('/students/profile'), api('/students/papers')])
      .then(([profileData, papersData]) => {
        setProfile(profileData.data);
        setPapers(papersData.data);
      })
      .catch((err) => setError(err.message));
  }, []);

  if (error) return <div className="alert">{error}</div>;
  if (!profile) return <div className="empty-state">Loading profile...</div>;

  return (
    <div className="content-stack">
      <section className="section">
        <div className="section-heading">
          <span className="eyebrow">Student</span>
          <h2>{profile.name || profile.student_name || profile.enrollment_no}</h2>
        </div>
        <div className="detail-grid">
          {['student_id', 'enrollment_no', 'dob', 'course_name', 'class_id', 'father_name', 'p_mobile_no'].map((field) => (
            <div className="detail-item" key={field}>
              <span>{field}</span>
              <strong>{profile[field] || '-'}</strong>
            </div>
          ))}
        </div>
      </section>

      <section className="section">
        <div className="section-heading">
          <span className="eyebrow">Examination</span>
          <h2>Theory Papers</h2>
        </div>
        <div className="module-grid">
          {papers.map((paper) => (
            <article className="module-card" key={paper.id}>
              <h3>{paper.paper_name || paper.name}</h3>
              <p>{paper.paper_code || '-'}</p>
            </article>
          ))}
          {!papers.length && <p className="empty-state">No active exam papers found.</p>}
        </div>
      </section>
    </div>
  );
}
