import React from 'react';

export default function SelectInput({ label, value, onChange, options, placeholder, required = false, error, disabled = false }) {
  return (
    <div className="form-field">
      <label className="block text-sm font-medium mb-1">{label}</label>
      <select
        value={value}
        onChange={onChange || (() => {})}
        className="w-full border rounded px-3 py-2"
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
