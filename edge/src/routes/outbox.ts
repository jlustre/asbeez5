import { Router } from 'express';
import { getDb } from '../lib/db.js';

export const router = Router();

router.post('/publish', (req, res) => {
  const { events } = req.body || {};
  if (!Array.isArray(events)) return res.status(400).json({ error: { code: 'invalid', message: 'events[] required' } });

  const db = getDb();
  const insert = db.prepare(`INSERT OR IGNORE INTO edge_outbox (id,type,aggregate_type,aggregate_public_id,payload,headers,occurred_at,created_at)
    VALUES (@id,@type,@aggregate_type,@aggregate_public_id,@payload,@headers,@occurred_at,@created_at)`);

  const now = new Date().toISOString();
  const accepted: string[] = [];
  for (const e of events) {
    try {
      insert.run({
        id: String(e.id),
        type: String(e.type),
        aggregate_type: String(e.aggregate?.type || ''),
        aggregate_public_id: String(e.aggregate?.public_id || ''),
        payload: JSON.stringify(e.payload ?? {}),
        headers: JSON.stringify(e.headers ?? {}),
        occurred_at: String(e.occurred_at || now),
        created_at: now,
      });
      accepted.push(String(e.id));
    } catch (err) {
      // ignore duplicates or invalid rows for skeleton
    }
  }
  return res.status(202).json({ accepted, rejected: [] });
});
