# AsBeez POS — Offline & Sync (D)

Design for resilient offline-first operations using an Edge service at the store, with at-least-once delivery, idempotency, and clear conflict rules.

## Components
- Edge Service (Node/Express + SQLite): local APIs for POS/KDS, device integrations (printers, cash drawer), durable outbox/inbox, sync daemon.
- HQ (Laravel/MySQL): source of truth; outbox_events for externalization, processed_events for dedup, idempotency_keys for safe writes.

## Local Persistence (Edge)
- `edge_outbox(events)`: id, type, aggregate {type, public_id}, payload JSON, headers, occurred_at, attempts, last_error, published_at.
- `edge_inbox(events)`: id, source_id, type, payload, processed_at for dedup of HQ→Edge messages.
- `edge_mirrors`: lightweight mirrors of critical domain tables (orders, items, payments) for reprint and KDS even offline.

## Sync Loop
1. Producer writes domain rows and a single outbox event in one transaction.
2. Publisher reads newest unpublished events (FIFO), batches by type, POSTs to HQ `/api/v1/outbox/batch` with HMAC.
3. HQ validates signatures, enforces idempotency, persists domain state and outbox_events, returns ACK of accepted IDs.
4. Edge marks events as published or increments attempts/backoff on failure.

## Idempotency & Dedup
- Writes carry `Idempotency-Key`; HQ stores response and returns same on retries.
- Consumers record upstream event IDs in `processed_events` to drop duplicates.
- Envelope includes `id`, `type`, `occurred_at`, `aggregate`, `payload`.

## Backoff & Redelivery
- Exponential backoff: 1s, 2s, 5s, 10s, 30s, 60s, then every 5m with jitter.
- Poison events: after N attempts (e.g., 50), park for manual review; emit alert/metric.

## Conflict Resolution
- Catalog/Config: last-write-wins (timestamp + version); configs are idempotent snapshots.
- Orders/Payments: HQ is authoritative; Edge submits creates/updates; conflicting updates rejected with 409 and require retry or manual intervention.
- Inventory: do not allow resulting negative stock; reject with 422 and produce compensating adjustments via manager flow.
- Loyalty: reject redemptions exceeding balance; queue earn after paid; expire based on policy windows.

## Startup & Bootstrap
- On Edge boot: health checks, load device drivers, run pending migrations, then sync snapshots:
  - `GET /api/v1/config/snapshot` (branch/register/kitchen routes)
  - `GET /api/v1/catalog/snapshot` (products/modifiers)
- Resume outbox publishing where left off.

## Observability
- Metrics: queue depth, publish latency, success rate, poison count.
- Logs: correlate with `X-Request-ID` and event ids.
- Tracing: minimal spans around publish/ack and device I/O.

## Failure Modes
- Network down: POS/KDS work against Edge; queue grows; printers continue locally.
- Partial failures: individual event errors isolated; others continue.
- Clock skew: rely on server side ordering; avoid client timestamps for sequencing critical IDs.

## Security
- Mutual TLS or HMAC for Edge↔HQ; rotate keys.
- Encrypt API keys at rest on Edge; protect SQLite with OS ACLs.
- Least privilege tokens for POS/KDS.

## Event Envelope Schema
Edge publishes events in a consistent envelope:
```json
{
  "id": "edge-01HKTK4...",              
  "type": "order.created",              
  "aggregate": {"type": "order", "public_id": "01HK..."},
  "occurred_at": "2026-01-14T09:01:23Z",
  "payload": {"order": {"...": "..."}},
  "headers": {"branch_code": "BR-001", "register_code": "REG-1"}
}
```

## HMAC Signature Scheme
Authorization header:
`Authorization: Edge-HMAC key=<keyId>, ts=<RFC3339>, sig=<base64>`

Canonical string to sign:
```
<HTTP_METHOD>\n
<PATH_AND_QUERY>\n
<SHA256_HEX_BODY>\n
<RFC3339_TIMESTAMP>
```
Compute `sig = base64( HMAC_SHA256(secret, canonical) )`.

Example:
- Method: `POST`
- Path: `/api/v1/outbox/batch`
- Body SHA256 (hex): `4f7c...`
- ts: `2026-01-14T09:01:30Z`
- Header: `Authorization: Edge-HMAC key=reg:BR-001:REG-1, ts=2026-01-14T09:01:30Z, sig=AbCd...=`

Clock Skew: accept ±5 minutes; otherwise 401.

## Edge SQLite Schema (initial)
```sql
CREATE TABLE IF NOT EXISTS edge_outbox (
  id TEXT PRIMARY KEY,               -- ulid
  type TEXT NOT NULL,
  aggregate_type TEXT NOT NULL,
  aggregate_public_id TEXT NOT NULL,
  payload TEXT NOT NULL,             -- JSON
  headers TEXT NULL,                 -- JSON
  occurred_at TEXT NOT NULL,         -- ISO8601
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

CREATE TABLE IF NOT EXISTS edge_orders_mirror (
  public_id TEXT PRIMARY KEY,
  snapshot TEXT NOT NULL,            -- JSON order snapshot for reprint
  updated_at TEXT NOT NULL
);

CREATE INDEX IF NOT EXISTS ix_outbox_unpublished ON edge_outbox (published_at, created_at);
```

## Publisher Worker (pseudocode)
```pseudo
loop forever:
  batch = select 100 from edge_outbox where published_at is null order by created_at asc
  if batch empty: sleep(500ms); continue
  req = { events: map(toEnvelope, batch) }
  sig = hmac_sign(method=POST, path=/api/v1/outbox/batch, body=req, ts=now())
  resp = http.post(path, req, headers={Auth: sig, Idempotency-Key: ulid()})
  if resp.status in [200,202]:
     mark published_at=now() for accepted ids; for rejected: attempts+=1, last_error=resp.message
  else if resp.status in [429,500..]:
     backoff = nextBackoff(max(b.attempts) + 1); sleep(backoff)
  else:
     for e in batch: e.attempts+=1; e.last_error=resp.status
```

## Event Types
- `order.created`, `order.updated`, `order.paid`, `order.voided`
- `payment.captured`, `payment.refunded`
- `kitchen.ticket.created`, `kitchen.ticket.updated`
- `inventory.movement.created`
- `loyalty.earned`, `loyalty.redeemed`, `loyalty.reward.claimed`

## Device Integrations Interface
Abstract printers/drawers/scanners behind interfaces for portability:
```ts
interface Printer {
  init(): Promise<void>
  printReceipt(payload: ReceiptPayload): Promise<void>
  printKitchen(ticket: KitchenTicket): Promise<void>
}

interface CashDrawer {
  open(): Promise<void>
}
```
Provide ESC/POS and network implementations; select via config snapshot.
