# AsBeez POS — Implementation Plan (I)

Phased roadmap to implement the MVP and foundation for production. Aligns with docs A–H and targets iterative delivery with verifiable milestones, tests, and rollout safety.

## Environments & Tooling
- Repos: monorepo (Laravel API + docs) initially; separate repos later for Edge, POS, KDS if needed.
- Branching: trunk-based with short-lived feature branches; PR reviews mandatory.
- CI/CD:
  - Laravel: Pest tests, PHPStan, Pint; build artifact; `.env.example` verified.
  - Node/TS: ESLint, Vitest/Jest; typecheck; package lock consistency.
  - Electron: code sign (dev placeholder), build per-platform later.
- Secrets: use `.env` locally; 1Password/Vault in CI; no secrets in repo.
- Observability: Monolog → file (dev), structured JSON (prod); HTTP access logs with `X-Request-ID`.

## Milestones
- M0 — Project Setup (1–2 days)
  - CI pipelines for Laravel and Node templates
  - Base Makefile/NPM scripts; editorconfig & lint rules
  - Seed data parity and migrate:fresh stability
- M1 — Core Backend & Orders (5–7 days)
  - Migrations for orders, order_items, payments, receipts, kitchen, inventory, loyalty, reliability tables (per B)
  - Eloquent models & policies; DTO/Resource transformers
  - Orders API: create + get + list, server totals calc, idempotency keys
  - POS session auth confirmed for web routes; API tokens for devices
  - Outbox: write events on order create/update
  - Tests: unit totals calc; feature create->list; idempotency replay
- M2 — Payments & Receipts (3–5 days)
  - Payments API; state machine (authorized/captured/refunded)
  - Receipt numbering + persistence; printer payload snapshot
  - Outbox events: payment.captured; receipt.created
  - Tests: payment capture edge cases; receipt sequence uniqueness
- M3 — Kitchen (3–5 days)
  - Ticket routing on order submit; station rules from config snapshot
  - KDS endpoints: create/update ticket state; queries by station
  - Tests: lifecycle transitions; concurrency and idempotency
- M4 — Inventory & Loyalty (5–7 days)
  - Stock ledger consumption via recipes on paid orders
  - Inventory movement API; negative-stock rejection; manager override path
  - Loyalty earn/redeem + ledger; reward claims
  - Tests: recipe math; redemption limits; expiration policy stubs
- M5 — Edge Service (5–7 days)
  - Node/Express + SQLite: edge_outbox, edge_inbox, mirrors
  - HMAC auth; publisher with backoff & dedup
  - Printer module (ESC/POS jobs), drawer kick; test endpoints
  - Tests: publisher retries; print mapping unit tests
- M6 — POS & KDS Clients (7–10 days)
  - POS (Electron+React): shell, register workflow, offline cache, Edge API
  - KDS (React): station view, expo view, hotkeys
  - E2E happy path: new sale → payment → receipt → KDS flow
- M7 — Security & Compliance (3–5 days)
  - RBAC gates; manager PIN prompts; audit log writes across actions
  - Rate limits on key endpoints; privacy masking
- M8 — Reporting & Hardening (3–5 days)
  - Sales summary/product mix endpoints; basic dashboard
  - Performance passes (N+1, indexes, caching hot lookups)
  - Backup/restore playbooks; incident drills

## Backend Tasks (Laravel)
- Migrations: generate from B; ensure guarded FKs or app-level integrity
- Models with casts for money (cents) and ULIDs
- Controllers: Orders, Payments, Receipts, Kitchen, Inventory, Loyalty, Sync, Admin
- Middleware: Idempotency, RequestID, HMAC verifier (for Edge endpoints)
- Outbox publisher (queue worker): deliver to message bus or webhook (future)
- Policies & Gates: map to permission levels P2–P5
- Tests with Pest: factories, feature specs, idempotency replay, totals

## Edge Tasks (Node/Express + SQLite)
- Project scaffold: TypeScript, ESLint, SQLite via better-sqlite3 or knex
- Tables: edge_outbox, edge_inbox, edge_orders_mirror
- HTTP: `/outbox/publish` to HQ; `/print` for jobs; `/devices/print/test`
- HMAC signing & verification helpers; time skew checks
- Drivers: ESC/POS over USB/serial/TCP; mock device for dev
- Retry loop with exponential backoff + jitter; poison queue handling

## POS (Electron + React)
- App shell: header/footer status, cashier session (re-use existing Laravel pos session for web mode; device token for Electron)
- Register: product grid, cart, modifiers, tender panel, suspend/resume
- Data: local cache (IndexedDB) for catalog/config and drafts
- API: talk to Edge for printing & queue; Edge→HQ behind the scenes
- Hotkeys per E; accessibility features
- Minimal E2E: Spectron/Playwright tests for add/tender/print

## KDS (React)
- Station & Expo views; ticket tiles; timers & color thresholds
- Keyboard/bump bar support; offline mirror via Edge
- E2E: advance ticket states; reconcile conflicts

## Testing Strategy
- Unit: totals, tax, recipe math, HMAC signer
- Feature/API: idempotency, auth, RBAC, pagination, filters
- Contract: JSON schema for events & printer jobs between POS/Edge/HQ
- E2E: POS happy path, KDS station flow, print preview
- Load: simulate 100 orders/min; ensure DB indexes suffice

## Data & Seeds
- Deterministic seeds for demo (branch BR-001, products, recipes, employees)
- Fixture orders for reports & KDS testing

## Deployment & Ops
- Dev: Laragon/Valet + Node; `.env` templates
- Staging: single VM or containers; backups; log shipping
- Prod: phased rollout per store; device provisioning checklists
- Backups: MySQL and Edge SQLite nightly; restore drills

## Risks & Mitigations
- Device drivers variability → abstract printer/drawer; test with 2–3 models
- Offline queue growth → monitor depth, disk usage alerts
- Data drift between Edge and HQ → periodic snapshots & reconciliation
- Regulatory variance → keep printing footer and taxation configurable

## Acceptance Criteria (MVP)
- Create order → capture payment → receipt prints; totals accurate
- Tickets route to stations; KDS transitions to served
- Outbox publishes to HQ with idempotency; retries on failure
- Inventory consumption recorded; loyalty points issued
- Audit logs present for key actions; RBAC enforced

## Demo Script
1. Login as cashier; New Sale
2. Add items + modifiers; apply discount (supervisor PIN)
3. Cash payment; receipt prints; drawer opens
4. KDS shows tickets; mark ready/served; expo completes
5. Reports show sale; audit trail lists actions
6. Toggle offline mode; create sale; verify queued sync when online
