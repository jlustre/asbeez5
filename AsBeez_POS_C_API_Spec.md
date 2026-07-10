# AsBeez POS — API Spec (C)

This document defines the API surface for POS → HQ and Edge → HQ interactions. Principles: versioned REST (v1), idempotent writes, consistent pagination and filtering, strong audit, and secure transport (HTTPS only).

## Principles
- Versioning: `/api/v1/...` with additive changes preferred.
- Auth: Bearer tokens (Sanctum/JWT) for backoffice/admin; API key or HMAC for Edge; device tokens for registers.
- Idempotency: `Idempotency-Key` header (UUID/ULID) required for POST/PUT/PATCH that create/modify resources. Server stores request/response in `idempotency_keys`.
- Request IDs: `X-Request-ID` echoed back for tracing; include in logs/audit.
- Context headers: `X-Register-Code`, `X-Branch-Code` optional hints.
- Pagination: `?page=1&per_page=50`; responses include `meta` and `links`.

## Authentication
- Admin/Backoffice: `Authorization: Bearer <token>` (Laravel Sanctum personal access token).
- Edge: `X-Api-Key: <key>` or HMAC scheme `Authorization: Edge-HMAC key=..., ts=..., sig=...`.
- POS Device Bootstrap: `POST /api/v1/registers/{code}/token` → one-time device token.

## Error Model
```json
{
  "error": {
    "code": "string",
    "message": "human readable",
    "details": {"field": ["problem..."]}
  }
}
```

## Orders

### Create Order
- `POST /api/v1/orders`
- Headers: `Idempotency-Key`, `X-Register-Code` (optional)
- Request:
```json
{
  "client_request_id": "8b9a0c30-9a2e-4a9e-9fdf-1e1b2c3d4e5f",
  "context": {"branch_code": "BR-001", "register_code": "REG-1", "employee_public_id": "01HJ..."},
  "order": {
    "public_id": "01HK...",  
    "type": "takeaway",
    "customer_public_id": null,
    "items": [
      {"sku": "BRG001", "name": "Burger", "qty": 1, "unit_price_cents": 12000, "modifiers": [{"name": "No Onions", "price_delta_cents": 0}]},
      {"sku": "FRY001", "name": "Fries", "qty": 1, "unit_price_cents": 5000}
    ],
    "discount_cents": 0,
    "notes": "Lunch special"
  }
}
```
- Response 201:
```json
{
  "public_id": "01HK...",
  "order_number": 123,
  "order_date": "2026-01-14",
  "totals": {"subtotal_cents": 17000, "tax_cents": 2040, "total_cents": 19040},
  "status": "open"
}
```

### Add Payment
- `POST /api/v1/orders/{public_id}/payments`
- Headers: `Idempotency-Key`
- Request:
```json
{
  "method": "cash",
  "amount_cents": 20000,
  "provider": null,
  "provider_txn_id": null
}
```
- Response 201:
```json
{
  "payment_public_id": "01HKPAY...",
  "status": "captured",
  "change_cents": 960
}
```

### Get Order
- `GET /api/v1/orders/{public_id}` → 200 with full order + items + payments.
- `GET /api/v1/orders?status=paid&from=2026-01-14&to=2026-01-14&branch_code=BR-001&page=1&per_page=50`

## Kitchen
- `POST /api/v1/kitchen/tickets` create or re-route a ticket to station.
- `PATCH /api/v1/kitchen/tickets/{id}` update status: prepping/ready/served/voided.
- `GET /api/v1/kitchen/tickets?station=GRILL&status=queued`

## Inventory
- `POST /api/v1/inventory/movements`
```json
{
  "ref_type": "receipt",
  "ref_id": 9876,
  "branch_code": "BR-001",
  "lines": [{"sku": "BUN", "qty": 100, "uom": "ea", "unit_cost_cents": 500}]
}
```
- `GET /api/v1/inventory/stock?branch_code=BR-001&sku=BUN`

## Loyalty
- `POST /api/v1/loyalty/earn`
```json
{"customer_public_id": "01HJ...", "order_public_id": "01HK...", "points": 19}
```
- `POST /api/v1/loyalty/redeem`
```json
{"customer_public_id": "01HJ...", "order_public_id": "01HK...", "points": 50}
```
- `GET /api/v1/customers/{public_id}/loyalty` → account + last 50 entries.
- `POST /api/v1/loyalty/rewards/{code}/claim` → creates `reward_claims`.

## Sync (Edge ⇄ HQ)
- `POST /api/v1/outbox/batch` (Edge → HQ)
```json
{
  "events": [
    {"id": "edge-01...", "type": "order.created", "aggregate": {"type": "order", "public_id": "01HK..."}, "occurred_at": "2026-01-14T09:01:23Z", "payload": {"...": "..."}}
  ]
}
```
- Response 202:
```json
{"accepted": ["edge-01..."], "rejected": []}
```
- `GET /api/v1/catalog/snapshot?since=2026-01-01T00:00:00Z` → stream products/modifiers.
- `GET /api/v1/config/snapshot` → branch/register/kitchen routing config JSON.

## Admin & Reports (outline)
- `GET /api/v1/reports/sales-summary?from=...&to=...&branch_code=...`
- `GET /api/v1/reports/product-mix?from=...&to=...`
- `POST /api/v1/registers` (admin) create registers; `GET /api/v1/registers?branch_code=...`

## Security
- All endpoints require HTTPS.
- Validate and log `X-Request-ID`.
- Enforce `Idempotency-Key` for write endpoints; return 409 if conflicting payload for same key.
- RBAC enforced: cashier vs supervisor vs manager.

## Webhooks (optional)
- Outbound events: `order.paid`, `payment.captured`, `inventory.receipt.created`, `loyalty.earned` with retries and HMAC signatures.

## Standard Response Envelope
Successful responses MAY wrap data in a consistent envelope for list endpoints.
```json
{
  "data": [ {"public_id": "01HK...", "status": "paid"} ],
  "meta": {"page": 1, "per_page": 50, "total": 1234},
  "links": {"next": "/api/v1/orders?page=2&per_page=50"}
}
```

## Validation & Error Codes
- 400: invalid request payload; include `details` per field
- 401: missing/invalid auth token or signature
- 403: permission denied by RBAC
- 404: resource not found
- 409: idempotency conflict or state conflict
- 422: business rule violation (e.g., negative stock, insufficient points)
- 429: rate limited
- 500: unexpected server error

## Pagination, Filtering, Sorting
- Pagination: `page`, `per_page` (max 200). Include `meta` and `links`.
- Filtering: by common fields (e.g., `status`, `branch_code`, `from`, `to`).
- Sorting: `sort=created_at` or `sort=-created_at` for descending.

## Resource Schemas (summaries)
- `Order`: `{ public_id, order_number, order_date, type, status, totals, items[], payments[] }`
- `Payment`: `{ public_id, method, amount_cents, status, provider, provider_txn_id }`
- `KitchenTicket`: `{ id, order_public_id, station, status, routed_at, items[] }`
- `InventoryMovement`: `{ id, ref_type, ref_id, branch_code, lines[] }`
- `LoyaltyAccount`: `{ customer_public_id, points_balance, recent_entries[] }`

## Auth & Device Bootstrap Endpoints

### Issue Device Token
- `POST /api/v1/registers/{code}/token`
- Request: `{ "branch_code": "BR-001", "device_fingerprint": "hash", "description": "Front Counter POS" }`
- Response 201: `{ "device_token": "edge_...", "expires_at": "2026-02-14T00:00:00Z" }`

### Refresh Device Token
- `POST /api/v1/devices/token/refresh` (auth: current device token)
- Response 200: `{ "device_token": "edge_...", "expires_at": "..." }`

### Revoke Device Token
- `POST /api/v1/devices/token/revoke` → 204

## Admin Endpoints (selected)
- Registers:
  - `POST /api/v1/registers` create
  - `GET /api/v1/registers?branch_code=...` list
  - `PATCH /api/v1/registers/{id}` update
- Catalog:
  - `POST /api/v1/products` create product
  - `PATCH /api/v1/products/{id}` update price or activation
  - `POST /api/v1/products/{id}/modifiers` add modifier

## Reports Endpoints (selected)
- Sales summary: `GET /api/v1/reports/sales-summary?from=...&to=...&branch_code=...`
- Product mix: `GET /api/v1/reports/product-mix?from=...&to=...`
- Hourly sales: `GET /api/v1/reports/hourly?date=...&branch_code=...`

## Rate Limits
- Include headers: `X-RateLimit-Limit`, `X-RateLimit-Remaining`, `Retry-After` (when 429).
- Typical limits: writes 60/min per device; reads 600/min per device (configurable).

## Security: Edge HMAC (overview)
- Use `Authorization: Edge-HMAC key=<keyId>, ts=<RFC3339>, sig=<base64>`.
- Signature computed over: `<HTTP_METHOD>\n<PATH>\n<SHA256_HEX_BODY>\n<ts>` with HMAC-SHA256 using device secret.
- See Offline & Sync (D) for canonicalization and examples.
