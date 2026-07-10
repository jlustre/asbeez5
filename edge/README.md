# AsBeez Edge Service (Skeleton)

Node/Express + SQLite service for local device integration and durable outbox/inbox.

## Features (skeleton)
- Express server with health endpoint
- SQLite init (edge_outbox/edge_inbox) and simple insert
- HMAC signer/verifier utilities (per spec D)
- Print test endpoint (mock)

## Quick start
```bash
cd edge
npm install
npm run dev
```

## Endpoints
- GET /health → { status: "ok" }
- POST /print/test → prints test page (mock log)
- POST /outbox/publish → mock push to HQ, logs payload

Configure HQ URL and keys in `.env` (copied from `.env.example`).
