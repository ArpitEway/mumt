import express from 'express';
import path from 'path';
import { fileURLToPath } from 'url';
import cors from 'cors';
import { securityHeaders } from './middleware/securityHeaders.js';
import morgan from 'morgan';
import { env } from './config/env.js';
import authRoutes from './routes/auth.routes.js';
import adminRoutes from './routes/admin.routes.js';
import resourceRoutes from './routes/resources.routes.js';
import studentRoutes from './routes/student.routes.js';
import registrationRoutes from './routes/registration.routes.js';
import paymentRoutes from './routes/payment.routes.js';
import { errorHandler, notFound } from './middleware/errorHandler.js';

export const app = express();
const __dirname = path.dirname(fileURLToPath(import.meta.url));

app.use(securityHeaders());
app.use(cors({ origin: env.frontendUrl, credentials: true }));
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true }));
app.use(morgan(env.nodeEnv === 'production' ? 'combined' : 'dev'));
app.use('/uploads', express.static(path.resolve(__dirname, '../uploads')));
app.use('/assets', express.static(path.resolve(__dirname, '../../assets')));

app.get('/api/health', (req, res) => {
  res.json({ ok: true, service: 'mumt-backend' });
});

app.use('/api/auth', authRoutes);
app.use('/api/admin', adminRoutes);
app.use('/api/resources', resourceRoutes);
app.use('/api/students', studentRoutes);
app.use('/api/registration', registrationRoutes);
app.use('/api/payment', paymentRoutes);

app.use(notFound);
app.use(errorHandler);
