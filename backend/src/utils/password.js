import crypto from 'crypto';
import bcrypt from 'bcryptjs';

export function md5(value) {
  return crypto.createHash('md5').update(String(value)).digest('hex');
}

export async function matchesLegacyOrHash(input, stored, { legacyMd5 = false } = {}) {
  if (!stored) return false;
  if (legacyMd5 && md5(input) === stored) return true;
  if (String(input) === String(stored)) return true;
  if (String(stored).startsWith('$2')) {
    return bcrypt.compare(input, stored);
  }
  return false;
}
