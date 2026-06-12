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

export default function SelectInput({
  label,
  name,
  id,
  value,
  onChange,
  options,
  placeholder,
  required = false,
  error,
  disabled = false,
  className = '',
}) {
  return (
    <div className="form-field">
      <label className="block text-sm font-medium mb-1" htmlFor={id || name}>{renderLabel(label)}</label>
      <select
        id={id || name}
        name={name}
        value={value}
        onChange={onChange || (() => {})}
        className={`w-full border rounded px-3 py-2 ${className}`}
        required={required}
        disabled={disabled}
      >
        {placeholder && (
          <option value="" disabled>
            {placeholder}
          </option>
        )}
        {options.map((opt) => (
          <option key={opt.value} value={opt.value}>
            {opt.label}
          </option>
        ))}
      </select>
      {error && <div className="text-sm text-red-600 mt-1">{error}</div>}
    </div>
  );
}
