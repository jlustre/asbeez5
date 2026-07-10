# AsBeez POS — Security & Compliance (H)

RBAC, audit, validation controls, privacy, and risk policies for a production POS.

## RBAC Matrix (summary)
- Cashier (P2): New sale, suspend/resume, line void (own sale), reprint last receipt, cash tender/drawer on capture.
- Supervisor (P3): Discounts (<=10%), order void (open), reprint any receipt, open drawer (logged), price override (<=5%).
- Assistant Manager (P4): Discounts (<=20%), refunds (with receipt; time window), manual inventory receipt, pay-in/out.
- Manager (P5): Full overrides, refund without receipt (policy), price override (>20%), end-of-day Z, user/employee management.

Map these to middleware gates: `permission:2..5` and named abilities (e.g., `can:orders.refund`).

## Authentication & Session
- POS login: employee_code + PIN (rate limited) with lockout after 5 failed attempts in 10 min; cooldown 15 min.
- PIN storage: bcrypt/argon2id hashes (`pos_pin`), never plaintext.
- Session: httpOnly, secure cookies; session timeout 12 hours or end-of-shift; immediate logout on manager command.
- Admin portal: 2FA (TOTP/WebAuthn) recommended for P4+ roles.

## Data Protection
- Transport: HTTPS/TLS 1.2+; HSTS on admin endpoints.
- Edge↔HQ: HMAC or mTLS; rotate keys; least-privilege API keys stored encrypted at rest.
- At rest: encrypt secrets (API keys, device tokens); avoid storing primary card data; tokenized references only.
- Logs: redact PII (phone/email) and secrets by default.

## Audit Logging
- Event fields: `actor_type`, `actor_id`, `action`, `subject_type`, `subject_id`, `payload (minimal)`, `ip`, `user_agent`, `occurred_at`.
- Actions to log (non-exhaustive):
  - login.success/failure, logout
  - sale.created/updated/paid/voided
  - item.voided, discount.applied, price_override.applied
  - payment.captured/failed/refunded/voided
  - drawer.opened, payin.created, payout.created
  - receipt.reprinted
  - inventory.receipt/adjust/waste
  - user.created/updated/disabled
- Retention: 2 years minimum (ops) and per jurisdiction for fiscal logs (often 5–7 years).

## Validation & Business Rules
- Order totals recomputed server-side; reject mismatches (>1 cent).
- Prevent negative inventory on moves; use manager override flow for exceptions.
- Discounts within role thresholds; larger discounts require manager PIN at time of action.
- Refunds: require original receipt unless manager override; enforce time windows.
- Idempotency: enforce `Idempotency-Key` for all write endpoints; store response.

## Privacy
- Data minimization: store only necessary PII (phone/email for loyalty) with consent.
- Access controls: restrict PII fields to roles (manager/admin); mask in UI by default.
- Subject rights: support export/delete requests where legally required; maintain suppression list.
- Anonymization for analytics exports.

## Logging & Monitoring
- Rate limits per device/user; alert on spikes and repeated failures.
- Metrics: auth failures, void/refund rates, drawer opens, average discounts.
- Error budgets and SLOs for POS critical paths; alert on breach.

## Device & App Hardening
- Electron POS: code signing, auto-update over TLS, integrity checks; content security policy (CSP) for web assets.
- Windows endpoint: limited user account, kiosk mode, USB lockdown where possible.
- Disable developer tools in production; restrict local file system access.

## Incident Response
- Classify incidents: P0 (data breach), P1 (payment outage), P2 (printing outage)…
- Playbooks: rotate keys, revoke device tokens, force logout, freeze refunds, enable safe mode (cash-only).
- Post-incident: audit trail export, root cause, and action items.

## Risk Disclosures (Customer-Facing)
- Offline payments: may not reflect real-time loyalty balances; reconciled upon sync.
- Refund policy: specify time window and method.
- Receipt privacy: mask partial card numbers; no full PAN storage.

## Compliance Notes (jurisdictional)
- VAT/GST: show tax breakdown and registration on receipts where required.
- E-invoicing: prepare payloads in jurisdictions mandating real-time clearance (out of scope for MVP).
- PCI DSS: do not store cardholder data; use PCI-compliant payment providers.
