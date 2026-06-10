import { env } from '../config/env.js';
import jwt from 'jsonwebtoken';

/**
 * roleGuard – restrict access to specific user roles.
 * Usage: `app.use('/api/admin', authGuard, roleGuard('admin'), adminRouter);`
 */
export const roleGuard = (...allowedRoles) => {
  return (req, res, next) => {
    if (!req.user) {
      return res.status(401).json({ success: false, error: 'Unauthenticated' });
    }
    if (!allowedRoles.includes(req.user.role)) {
      return res.status(403).json({ success: false, error: `Requires ${allowedRoles.join('/') } role` });
    }
    return next();
  };
};
