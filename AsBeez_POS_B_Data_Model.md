# AsBeez POS — Data Model (B)

This document defines the relational schema for the fastfood POS, inventory, loyalty, and reliability layers intended for MySQL 8 (InnoDB, utf8mb4). It favors append-only ledgers for auditability and clear, minimal foreign keys to reduce migration coupling. Names align with Laravel conventions.

## Conventions
- Primary keys: BIGINT UNSIGNED AUTO_INCREMENT unless noted; system-unique public IDs use CHAR(26) ULIDs where needed.
- Timestamps: created_at, updated_at (nullable for immutable/ledger tables with updated_at omitted or left unchanged).
- Monetary: store in minor units (INT cents) to avoid float errors.
- Points/qty: INT for counts; DECIMAL(12,3) for weighted goods.
- Soft deletes: only where required operationally.
- Idempotency: client_request_id (UUID/ULID) unique per create path.

## Branch & Registers
These complement existing branches and branch_units.

```sql
CREATE TABLE IF NOT EXISTS registers (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  branch_id BIGINT UNSIGNED NOT NULL,
  branch_unit_id BIGINT UNSIGNED NOT NULL,
  code VARCHAR(32) NOT NULL,
  name VARCHAR(100) NOT NULL,
  fiscal_number VARCHAR(64) NULL,
  config JSON NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  UNIQUE KEY ux_register_code (branch_id, code),
  KEY ix_register_unit (branch_unit_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Catalog (minimal for POS MVP)
```sql
CREATE TABLE IF NOT EXISTS products (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sku VARCHAR(64) NOT NULL,
  name VARCHAR(200) NOT NULL,
  category VARCHAR(100) NULL,
  price_cents INT NOT NULL,
  tax_rate_bp INT NOT NULL DEFAULT 1200, -- basis points (e.g., 12%)
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  UNIQUE KEY ux_products_sku (sku),
  KEY ix_products_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS product_modifiers (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(120) NOT NULL,
  price_delta_cents INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  KEY ix_pm_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Orders
```sql
CREATE TABLE IF NOT EXISTS orders (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  public_id CHAR(26) NOT NULL, -- ULID for external reference
  client_request_id CHAR(36) NULL, -- for idempotency on create
  branch_id BIGINT UNSIGNED NOT NULL,
  branch_unit_id BIGINT UNSIGNED NOT NULL,
  register_id BIGINT UNSIGNED NULL,
  employee_id BIGINT UNSIGNED NULL,
  order_number INT NOT NULL, -- daily per-branch or per-register sequence
  order_date DATE NOT NULL,
  type ENUM('dine_in','takeaway','delivery') NOT NULL DEFAULT 'takeaway',
  status ENUM('open','awaiting_payment','paid','voided','refunded') NOT NULL DEFAULT 'open',
  customer_id BIGINT UNSIGNED NULL,
  subtotal_cents INT NOT NULL DEFAULT 0,
  discount_cents INT NOT NULL DEFAULT 0,
  tax_cents INT NOT NULL DEFAULT 0,
  total_cents INT NOT NULL DEFAULT 0,
  loyalty_earned INT NOT NULL DEFAULT 0,
  loyalty_redeemed INT NOT NULL DEFAULT 0,
  notes VARCHAR(500) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  UNIQUE KEY ux_orders_public (public_id),
  UNIQUE KEY ux_orders_daily_num (branch_id, order_date, order_number),
  UNIQUE KEY ux_orders_idem (client_request_id),
  KEY ix_orders_branch (branch_id, created_at),
  KEY ix_orders_status (status, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS order_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  line_number INT NOT NULL,
  product_id BIGINT UNSIGNED NULL, -- snapshot values below
  sku VARCHAR(64) NOT NULL,
  name VARCHAR(200) NOT NULL,
  qty DECIMAL(12,3) NOT NULL DEFAULT 1,
  unit_price_cents INT NOT NULL,
  discount_cents INT NOT NULL DEFAULT 0,
  tax_rate_bp INT NOT NULL,
  tax_cents INT NOT NULL DEFAULT 0,
  total_cents INT NOT NULL DEFAULT 0,
  route_station VARCHAR(64) NULL, -- kitchen route hint
  status ENUM('new','prepping','ready','served','voided') NOT NULL DEFAULT 'new',
  notes VARCHAR(300) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  UNIQUE KEY ux_order_items_line (order_id, line_number),
  KEY ix_order_items_route (route_station, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS order_item_modifiers (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_item_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(120) NOT NULL,
  price_delta_cents INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  KEY ix_oim_item (order_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Payments & Receipts
```sql
CREATE TABLE IF NOT EXISTS payments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  public_id CHAR(26) NOT NULL,
  client_request_id CHAR(36) NULL,
  order_id BIGINT UNSIGNED NOT NULL,
  method ENUM('cash','card','qr','wallet','other') NOT NULL,
  amount_cents INT NOT NULL,
  change_cents INT NOT NULL DEFAULT 0,
  provider VARCHAR(64) NULL,
  provider_txn_id VARCHAR(100) NULL,
  status ENUM('authorized','captured','failed','voided','refunded') NOT NULL DEFAULT 'captured',
  captured_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  UNIQUE KEY ux_payments_public (public_id),
  UNIQUE KEY ux_payments_idem (client_request_id),
  KEY ix_payments_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS receipts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  register_id BIGINT UNSIGNED NULL,
  receipt_number INT NOT NULL,
  receipt_date DATE NOT NULL,
  is_reprint TINYINT(1) NOT NULL DEFAULT 0,
  reprint_of BIGINT UNSIGNED NULL,
  payload JSON NULL, -- printable snapshot & template variables
  printed_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  UNIQUE KEY ux_receipts_daily (register_id, receipt_date, receipt_number),
  KEY ix_receipts_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Kitchen Display System (KDS)
```sql
CREATE TABLE IF NOT EXISTS kitchen_tickets (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  ticket_number INT NOT NULL, -- daily per-station or per-branch
  station VARCHAR(64) NOT NULL,
  status ENUM('queued','prepping','ready','served','voided') NOT NULL DEFAULT 'queued',
  routed_at TIMESTAMP NULL,
  started_at TIMESTAMP NULL,
  ready_at TIMESTAMP NULL,
  served_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  UNIQUE KEY ux_kt_daily (station, DATE(routed_at), ticket_number),
  KEY ix_kt_status (station, status, routed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS kitchen_ticket_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kitchen_ticket_id BIGINT UNSIGNED NOT NULL,
  order_item_id BIGINT UNSIGNED NOT NULL,
  qty DECIMAL(12,3) NOT NULL DEFAULT 1,
  notes VARCHAR(300) NULL,
  status ENUM('queued','prepping','ready','served','voided') NOT NULL DEFAULT 'queued',
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  KEY ix_kti_ticket (kitchen_ticket_id),
  KEY ix_kti_item (order_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Inventory & Costing
```sql
CREATE TABLE IF NOT EXISTS stock_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sku VARCHAR(64) NOT NULL,
  name VARCHAR(200) NOT NULL,
  uom VARCHAR(16) NOT NULL DEFAULT 'ea', -- unit of measure
  is_sellable TINYINT(1) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  UNIQUE KEY ux_stock_sku (sku)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS stock_ledger_entries (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  branch_id BIGINT UNSIGNED NOT NULL,
  stock_item_id BIGINT UNSIGNED NOT NULL,
  ref_type VARCHAR(32) NOT NULL, -- 'receipt','consume','waste','adjust','transfer'
  ref_id BIGINT UNSIGNED NULL,
  qty_delta DECIMAL(12,3) NOT NULL, -- positive in, negative out
  unit_cost_cents INT NULL, -- for receipts/average costing
  note VARCHAR(300) NULL,
  occurred_at TIMESTAMP NOT NULL,
  created_at TIMESTAMP NULL,
  KEY ix_sle_item_branch_time (stock_item_id, branch_id, occurred_at),
  KEY ix_sle_ref (ref_type, ref_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS product_recipes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT UNSIGNED NOT NULL,
  stock_item_id BIGINT UNSIGNED NOT NULL,
  qty_per_ea DECIMAL(12,3) NOT NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  UNIQUE KEY ux_recipe_component (product_id, stock_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Customers & Loyalty
```sql
CREATE TABLE IF NOT EXISTS customers (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  public_id CHAR(26) NOT NULL,
  name VARCHAR(200) NULL,
  phone VARCHAR(32) NULL,
  email VARCHAR(200) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  UNIQUE KEY ux_customers_public (public_id),
  UNIQUE KEY ux_customers_phone (phone),
  UNIQUE KEY ux_customers_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS loyalty_accounts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_id BIGINT UNSIGNED NOT NULL,
  points_balance INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  UNIQUE KEY ux_loyalty_customer (customer_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS bp_ledger_entries (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT UNSIGNED NOT NULL,
  order_id BIGINT UNSIGNED NULL,
  points_delta INT NOT NULL, -- +earn, -redeem, -expire
  reason ENUM('earn','redeem','adjust','expire') NOT NULL,
  occurred_at TIMESTAMP NOT NULL,
  created_at TIMESTAMP NULL,
  KEY ix_bp_account_time (account_id, occurred_at),
  KEY ix_bp_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS loyalty_rewards (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(64) NOT NULL,
  name VARCHAR(200) NOT NULL,
  cost_points INT NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  UNIQUE KEY ux_rewards_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS reward_claims (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  account_id BIGINT UNSIGNED NOT NULL,
  reward_id BIGINT UNSIGNED NOT NULL,
  order_id BIGINT UNSIGNED NULL,
  status ENUM('pending','fulfilled','voided') NOT NULL DEFAULT 'pending',
  claimed_at TIMESTAMP NULL,
  fulfilled_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  KEY ix_claims_account (account_id, status),
  KEY ix_claims_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Reliability & Sync
```sql
CREATE TABLE IF NOT EXISTS outbox_events (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  aggregate_type VARCHAR(64) NOT NULL, -- 'order','payment','inventory','loyalty'
  aggregate_id BIGINT UNSIGNED NOT NULL,
  event_type VARCHAR(64) NOT NULL,
  payload JSON NOT NULL,
  headers JSON NULL,
  queued_at TIMESTAMP NOT NULL,
  published_at TIMESTAMP NULL,
  attempts INT NOT NULL DEFAULT 0,
  last_error VARCHAR(500) NULL,
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  KEY ix_outbox_unpublished (published_at, queued_at),
  KEY ix_outbox_agg (aggregate_type, aggregate_id, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS processed_events (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  source VARCHAR(64) NOT NULL, -- e.g., 'edge','hq'
  event_id VARCHAR(100) NOT NULL, -- upstream unique id
  processed_at TIMESTAMP NOT NULL,
  UNIQUE KEY ux_processed (source, event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS idempotency_keys (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  endpoint VARCHAR(120) NOT NULL,
  idempotency_key VARCHAR(100) NOT NULL,
  status_code INT NOT NULL,
  response_body MEDIUMTEXT NULL,
  created_at TIMESTAMP NOT NULL,
  UNIQUE KEY ux_idem (endpoint, idempotency_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS audit_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  actor_type VARCHAR(32) NOT NULL, -- 'employee','system','admin'
  actor_id BIGINT UNSIGNED NULL,
  action VARCHAR(64) NOT NULL,
  subject_type VARCHAR(64) NULL,
  subject_id BIGINT UNSIGNED NULL,
  ip VARCHAR(45) NULL,
  user_agent VARCHAR(255) NULL,
  payload JSON NULL,
  occurred_at TIMESTAMP NOT NULL,
  KEY ix_audit_time (occurred_at),
  KEY ix_audit_actor (actor_type, actor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Key Relationships (summary)
- orders 1→N order_items → N order_item_modifiers
- orders 1→N payments, 1→N receipts
- orders 1→N kitchen_tickets → N kitchen_ticket_items (join to order_items)
- Inventory: product_recipes maps products to stock_items; sales create consumption entries in stock_ledger_entries
- Loyalty: customers → loyalty_accounts → N bp_ledger_entries; reward_claims link to orders optionally
- Reliability: domain saves to outbox; publishers deliver; consumers record processed_events; write paths enforce idempotency_keys

## Indexing Guidelines
- High-cardinality filters: add composite indexes that match expected WHERE clauses by order: e.g., (status, created_at) for orders views.
- Sequences: ensure uniqueness and lookup via (branch_id, order_date, order_number) and (register_id, receipt_date, receipt_number).
- Time-series ledgers: (occurred_at) leading with partition-friendly strategies if needed later.

## Notes
- Foreign keys can be added selectively after stabilization; for now, application-level integrity and careful cascades are recommended to keep migrations resilient.
- Monetary fields use INT cents; taxes via basis points to support precise inclusion/exclusion rules.
