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

export default function TextInput({ label, name, value, onChange, placeholder, required = false, type = 'text', error, onBlur }) {
  return (
    <div className="form-field">
      <label className="block text-sm font-medium mb-1" htmlFor={name}>{renderLabel(label)}</label>
      <input
        id={name}
        name={name}
        type={type}
        value={value}
        onChange={onChange}
        onBlur={onBlur}
        placeholder={placeholder}
        required={required}
        className="w-full border rounded px-3 py-2"
      />
      {error && <div className="text-sm text-red-600 mt-1">{error}</div>}
    </div>
  );
}
