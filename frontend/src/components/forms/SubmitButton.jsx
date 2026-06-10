import React from 'react';

export default function SubmitButton({ children, onClick, disabled }) {
  return (
    <button
      type="submit"
      className="primary-button px-4 py-2"
      onClick={onClick}
      disabled={disabled}
    >
      {children}
    </button>
  );
}
