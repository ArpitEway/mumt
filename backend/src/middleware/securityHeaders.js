import helmet from 'helmet';

/**
 * securityHeaders
 *
 * Applies Helmet security defaults and a tailored Content‑Security‑Policy.
 * The function returns a middleware that can be used with `app.use(securityHeaders())`.
 */
export const securityHeaders = () =>
  helmet({
    contentSecurityPolicy: {
      directives: {
        defaultSrc: ["'self'"],
        scriptSrc: ["'self'", "'unsafe-inline'"],
        styleSrc: ["'self'", "'unsafe-inline'"],
      },
    },
    referrerPolicy: { policy: 'no-referrer' },
    crossOriginResourcePolicy: { policy: 'cross-origin' },
  });
