const MULTIPLIER = 7919;
const OFFSET = 12345;

function toBase64Url(value) {
  const base64 = typeof window !== 'undefined' && window.btoa
    ? window.btoa(value)
    : Buffer.from(value, 'utf-8').toString('base64');

  return base64
    .replace(/\+/g, '-')
    .replace(/\//g, '_')
    .replace(/=+$/, '');
}

function fromBase64Url(value) {
  const base64 = value
    .replace(/-/g, '+')
    .replace(/_/g, '/');

  const padded = base64.padEnd(base64.length + (4 - (base64.length % 4)) % 4, '=');

  return typeof window !== 'undefined' && window.atob
    ? window.atob(padded)
    : Buffer.from(padded, 'base64').toString('utf-8');
}

export function encodeId(id) {
  const numeric = Number(id);
  if (Number.isNaN(numeric) || numeric <= 0) {
    return '';
  }
  const obfuscated = numeric * MULTIPLIER + OFFSET;
  return toBase64Url(String(obfuscated));
}

export function decodeId(value) {
  if (!value || typeof value !== 'string') {
    return null;
  }

  if (/^\d+$/.test(value)) {
    return Number(value);
  }

  try {
    const decoded = fromBase64Url(value);
    const numeric = Number(decoded);
    if (Number.isNaN(numeric)) return null;

    const original = (numeric - OFFSET) / MULTIPLIER;
    return Number.isInteger(original) ? original : null;
  } catch {
    return null;
  }
}
