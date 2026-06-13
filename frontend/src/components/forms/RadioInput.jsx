import React from 'react';

function renderLabel(label) {
  if (!label) return null;
  const parts = label.split('*');
  if (parts.length === 1) return label;
  return parts.reduce((acc, part, index) => {
    if (index === parts.length - 1) {
      return [...acc, part];
    }
    return [...acc, part, <span key={index} className="text-red-500">*</span>];
  }, []);
}

export default function RadioInput({ label, name, value, onChange, options, error, className = '' }) {
  return (
    <div className={`form-field ${className}`}>
      <label style={{ display: 'block', fontSize: '14px', fontWeight: '500', marginBottom: '8px' }}>
        {renderLabel(label)}
      </label>
      <div style={{ display: 'flex', gap: '32px', alignItems: 'center', margin: '0' }}>
        {options.map((opt) => (
          <div 
            key={opt.value} 
            style={{ 
              display: 'flex', 
              alignItems: 'center', 
              gap: '8px',
              cursor: 'pointer'
            }}
          >
            <input
              type="radio"
              name={name}
              value={opt.value}
              checked={value === opt.value}
              onChange={onChange}
              className="custom-radio"
              style={{ 
                margin: '0',
                padding: '2px',
                flexShrink: 0,
                cursor: 'pointer'
              }}
            />
            <span style={{ 
              display: 'inline', 
              fontSize: '14px',
              margin: '0',
              padding: '0',
              lineHeight: '1'
            }}>
              {opt.label}
            </span>
          </div>
        ))}
      </div>
      {error && <div className="text-sm text-red-600 mt-2">{error}</div>}
    </div>
  );
}
