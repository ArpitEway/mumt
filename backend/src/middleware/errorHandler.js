export function notFound(req, res) {
  res.status(404).json({ message: `Route not found: ${req.method} ${req.originalUrl}` });
}

export function errorHandler(error, req, res, next) {
  const status = error.status || 500;
  const payload = {
    message: status === 500 ? 'Internal server error' : error.message,
    ...(error.details && { details: error.details })
  };

  if (process.env.NODE_ENV !== 'production') {
    payload.detail = error.message;
    // Log full error for local debugging
    // eslint-disable-next-line no-console
    console.error(error && (error.stack || error));
  }

  res.status(status).json(payload);
}
