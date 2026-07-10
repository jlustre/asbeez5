import { Router } from 'express';

export const router = Router();

router.post('/test', async (_req, res) => {
  // In a real implementation, send test page to printer
  res.json({ ok: true, message: 'Print test enqueued (mock).' });
});
