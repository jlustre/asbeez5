import express from 'express';
import dotenv from 'dotenv';
import { ensureDb } from './lib/db.js';
import { router as printRouter } from './routes/print.js';
import { router as outboxRouter } from './routes/outbox.js';

dotenv.config();
const app = express();
app.use(express.json({ limit: '1mb' }));

app.get('/health', (_req, res) => res.json({ status: 'ok' }));
app.use('/print', printRouter);
app.use('/outbox', outboxRouter);

const port = Number(process.env.PORT || 4710);
ensureDb();
app.listen(port, () => console.log(`[edge] listening on :${port}`));
