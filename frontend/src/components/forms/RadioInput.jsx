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
      <label className="block text-sm font-medium mb-1">{renderLabel(label)}</label>
      <div className="flex gap-4 flex-wrap">
        {options.map((opt) => (
          <label key={opt.value} className="radio-option flex items-center gap-2 text-sm">
            <input
              type="radio"
              name={name}
              value={opt.value}
              checked={value === opt.value}
              onChange={onChange}
              className="mr-2 custom-radio"
            />
            <span className="radio-label">{opt.label}</span>
          </label>
        ))}
      </div>
      {error && <div className="text-sm text-red-600 mt-1">{error}</div>}
    </div>
  );
}
