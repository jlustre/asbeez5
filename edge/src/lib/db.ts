import Database from 'better-sqlite3';

let db: Database.Database;

export function ensureDb() {
  if (db) return db;
  db = new Database('edge.db');
  db.pragma('journal_mode = WAL');
  db.exec(`
    CREATE TABLE IF NOT EXISTS edge_outbox (
      id TEXT PRIMARY KEY,
      type TEXT NOT NULL,
      aggregate_type TEXT NOT NULL,
      aggregate_public_id TEXT NOT NULL,
      payload TEXT NOT NULL,
      headers TEXT NULL,
      occurred_at TEXT NOT NULL,
      attempts INTEGER NOT NULL DEFAULT 0,
      last_error TEXT NULL,
      published_at TEXT NULL,
      created_at TEXT NOT NULL
    );
    CREATE TABLE IF NOT EXISTS edge_inbox (
      id TEXT PRIMARY KEY,
      source TEXT NOT NULL,
      payload TEXT NOT NULL,
      processed_at TEXT NOT NULL
    );
  `);
  return db;
}

export function getDb() {
  if (!db) ensureDb();
  return db;
}
