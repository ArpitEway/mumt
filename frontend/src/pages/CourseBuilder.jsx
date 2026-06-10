import React, { useState } from 'react';
import PageWrapper from '../components/PageWrapper.jsx';
import { api } from '../api/client.js';
import { useAuth } from '../state/AuthContext.jsx';

function SubjectRow({ subject, onChange, onRemove }) {
  return (
    <div className="course-subject-row">
      <input
        placeholder="Subject name"
        value={subject.name}
        onChange={(e) => onChange('name', e.target.value)}
      />

      <input
        placeholder="Code"
        value={subject.code}
        onChange={(e) => onChange('code', e.target.value)}
      />

      <input
        placeholder="Credits"
        value={subject.credits}
        onChange={(e) => onChange('credits', e.target.value)}
      />

      <input
        placeholder="Description"
        value={subject.desc}
        onChange={(e) => onChange('desc', e.target.value)}
      />

      <button
        type="button"
        className="ghost-button"
        onClick={onRemove}
      >
        -
      </button>
    </div>
  );
}

export default function CourseBuilder() {
  const { user } = useAuth();

  const [courseName, setCourseName] = useState('');
  const [durationYears, setDurationYears] = useState(1);
  const [semesters, setSemesters] = useState(2);
  const [created, setCreated] = useState(null);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');

  const [subjectsBySem, setSubjectsBySem] = useState(
    Array.from({ length: 2 }, () => [])
  );

  function ensureSemCount(n) {
    setSubjectsBySem((prev) => {
      const next = prev.slice(0, n);

      while (next.length < n) {
        next.push([]);
      }

      return next;
    });
  }

  function handleSemestersChange(value) {
    const n = Number(value) || 1;

    setSemesters(n);
    ensureSemCount(n);
  }

  function addSubject(semIndex) {
    setSubjectsBySem((prev) => {
      const next = [...prev];

      next[semIndex] = [
        ...next[semIndex],
        {
          name: '',
          code: '',
          credits: '',
          desc: '',
        },
      ];

      return next;
    });
  }

  function updateSubject(semIndex, subjIndex, field, value) {
    setSubjectsBySem((prev) => {
      const next = [...prev];

      next[semIndex][subjIndex] = {
        ...next[semIndex][subjIndex],
        [field]: value,
      };

      return next;
    });
  }

  function removeSubject(semIndex, subjIndex) {
    setSubjectsBySem((prev) => {
      const next = [...prev];

      next[semIndex] = next[semIndex].filter(
        (_, index) => index !== subjIndex
      );

      return next;
    });
  }

  async function handleCreateCourse(e) {
    e.preventDefault();

    if (!courseName.trim()) {
      setError('Course name required');
      return;
    }

    setError('');

    const payload = {
      course_name: courseName,
      duration_years: Number(durationYears),
      semesters: Number(semesters),
      subjects: subjectsBySem,
    };

    setSaving(true);

    try {
      await api('/resources/course', {
        method: 'POST',
        body: payload,
      });

      setCreated(payload);

      setCourseName('');
      setDurationYears(1);
      setSemesters(2);
      setSubjectsBySem(Array.from({ length: 2 }, () => []));
    } catch (err) {
      console.error(err);

      const saved = JSON.parse(
        localStorage.getItem('local_courses') || '[]'
      );

      saved.push(payload);

      localStorage.setItem(
        'local_courses',
        JSON.stringify(saved)
      );

      setCreated(payload);
    } finally {
      setSaving(false);
    }
  }

  function resetForm() {
    setCourseName('');
    setDurationYears(1);
    setError('');
    setCreated(null);

    setSubjectsBySem(
      Array.from({ length: semesters }, () => [])
    );
  }

  if (user?.role !== 'admin') {
    return <div className="alert">Access denied</div>;
  }

  return (
    <PageWrapper>
      <section className="section">
        <div className="section-heading">
          <span className="eyebrow">Course Builder</span>
          <h2>Create a New Course</h2>
        </div>

        <form onSubmit={handleCreateCourse}>
          <div className="form-grid">
            <label>
              Course Name
              <input
                value={courseName}
                onChange={(e) =>
                  setCourseName(e.target.value)
                }
                required
              />
            </label>

            <label>
              Duration (Years)
              <input
                type="number"
                min="1"
                value={durationYears}
                onChange={(e) =>
                  setDurationYears(e.target.value)
                }
              />
            </label>

            <label>
              Semesters
              <input
                type="number"
                min="1"
                value={semesters}
                onChange={(e) =>
                  handleSemestersChange(e.target.value)
                }
              />
            </label>
          </div>

          <div className="course-subjects-stack">
            <h3>Subjects Per Semester</h3>

            {subjectsBySem.map((subjects, semIndex) => (
              <div
                key={semIndex}
                className="course-semester-card"
              >
                <h4>Semester {semIndex + 1}</h4>

                {subjects.map((subject, subjIndex) => (
                  <SubjectRow
                    key={subjIndex}
                    subject={subject}
                    onChange={(field, value) =>
                      updateSubject(
                        semIndex,
                        subjIndex,
                        field,
                        value
                      )
                    }
                    onRemove={() =>
                      removeSubject(
                        semIndex,
                        subjIndex
                      )
                    }
                  />
                ))}

                <button
                  type="button"
                  className="ghost-button"
                  onClick={() =>
                    addSubject(semIndex)
                  }
                >
                  Add Subject
                </button>
              </div>
            ))}
          </div>

          {error && (
            <div className="alert">
              {error}
            </div>
          )}

          <div className="modal-actions course-actions">
            <button
              type="button"
              className="ghost-button"
              onClick={resetForm}
            >
              Reset
            </button>

            <button
              type="submit"
              className="primary-button"
              disabled={saving}
            >
              {saving
                ? 'Saving...'
                : 'Save Course'}
            </button>
          </div>
        </form>

        {created && (
          <div className="alert course-created-alert">
            Course created successfully.
          </div>
        )}
      </section>
    </PageWrapper>
  );
}