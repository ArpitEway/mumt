import React from 'react';

export default function TextInput({ label, name, value, onChange, placeholder, required = false, type = 'text', error }) {
  return (
    <div className="form-field">
      <label className="block text-sm font-medium mb-1" htmlFor={name}>{label}</label>
      <input
        id={name}
        name={name}
        type={type}
        value={value}
        onChange={onChange}
        placeholder={placeholder}
        required={required}
        className="w-full border rounded px-3 py-2"
      />
      {error && <div className="text-sm text-red-600 mt-1">{error}</div>}
    </div>
  );
}
