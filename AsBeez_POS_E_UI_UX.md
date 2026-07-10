# AsBeez POS — UI/UX (E)

Fast, keyboard-first, error-resistant flows for high-throughput quick-service. This spec defines screens, flows, components, and hotkeys for cashier performance and minimal cognitive load.

## Core UX Principles
- Speed first: keyboard shortcuts for all core actions; minimal dialog friction.
- Stability: actions are predictable, undo-friendly, and require confirmation for destructive operations.
- Visibility: totals, change due, and statuses are always visible; offline/network indicators are clear.
- Recoverability: suspend/resume, reprint, and manager overrides are simple and auditable.
- Accessibility: high contrast, large touch targets, screen-reader friendly labels, ARIA roles.

## Roles & Permissions (mapping)
- Cashier (P2): sell, reprint own receipt, suspend/resume, open drawer on cash sale.
- Supervisor (P3): apply line/order discounts within limits, void items/orders, reprint any receipt.
- Manager (P4+): refunds/returns, price overrides, end-of-day, drawer blind close, reports.

## Primary Screens
1. Login (Employee Code + PIN)
2. Home (Tiles: New Sale, Reprint, Refund, Reports, Settings)
3. Sale/Register (Primary workspace)
4. Tender/Payment (cash, card, QR/wallet, split)
5. Order Search & Reprint
6. Refund/Return (by receipt lookup)
7. Manager Overrides
8. Z/End-of-Day & Cash Management (Pay-in/Pay-out, Blind Close)

## Main Flow: New Sale
1. From Home → New Sale → opens Register.
2. Add items via product grid, PLU entry, or barcode scan.
3. Apply modifiers and quantity.
4. Optional discounts (role-limited) or coupons.
5. Tender: choose method; capture amounts; auto-calc change.
6. Print customer receipt and send kitchen tickets.
7. Return to Register or Home by preference.

## Item Entry Methods
- Grid: Large buttons grouped by category; scrolling pages; search box.
- PLU: Type SKU/PLU then Enter to add (with default qty 1).
- Barcode: Focus on hidden input; scanning adds immediately.
- Quick Qty: `2 × BUR` adds two Burgers (type 2, `*`, scan/select item).

## Modifiers
- When an item requires modifiers, show a side panel with grouped options (Required, Optional, Substitutions). Support price deltas and notes.
- Predef quick mods: No Onions, Extra Sauce, etc.

## Cart & Totals
- Cart list with line numbers, name, qty, price, discount, total.
- Totals bar: Subtotal, Discount, Tax, Total, Paid, Change Due.
- Clear indicators for tax-inclusive/exclusive pricing.

## Tendering
- Methods: Cash, Card, QR/Wallet, Other.
- Cash: quick buttons (Exact, 50, 100, 200, 500, 1000), auto change.
- Split payments: add multiple tenders; show remaining due.
- Drawer kick on cash capture; receipt auto-print.

## Suspend/Resume
- Suspend sale with note; shows in Suspended list. Resume restores full cart state.

## Reprint
- Search by receipt number, order number, last N receipts, or amount/date.

## Refund/Return
- Lookup original receipt; select lines; choose refund method (original tender or cash with override).
- Enforce policy (time window, manager approval threshold).

## Error & Offline States
- Offline: banner + icon; orders queue locally; tender rules degrade gracefully.
- Payment failure: show clear error with next steps; allow retry or alternate tender.
- Device errors (printer/drawer): toast + status icon; provide retry + fallback.

## Hotkeys (Windows, configurable)
- Global
  - F1: New Sale
  - F2: Reprint
  - F3: Refund
  - F4: Suspend/Resume list
  - F5: Search Product
  - F6: Price Override (supervisor)
  - F7: Discount (line/order)
  - F8: Manager Override
  - F9: Cash Drawer (supervisor; logs event)
  - F10: Reports
  - F11: Toggle Fullscreen
  - F12: Settings
- Register
  - Enter: Add/confirm
  - ESC: Back/cancel
  - +/-: Increase/Decrease qty on selected line
  - Del: Void selected line (confirm)
  - Ctrl+D: Line discount
  - Ctrl+Shift+D: Order discount
  - Ctrl+F: Search
  - Ctrl+P: Print last receipt
  - Alt+C: Cash tender
  - Alt+K: Card tender
  - Alt+Q: QR/Wallet tender
  - Alt+S: Split payments
  - Alt+E: Exact cash
- Numeric Pad
  - 0–9: numeric input
  - *: multiply for quantity (e.g., 2 * [item])
  - /: quick divide (e.g., split equal parts)

## Components
- AppShell: header (store/branch/register, cashier), footer (status: network, printer, drawer), clock, version.
- ProductGrid: paged tiles by category; hover tooltips with price.
- SearchBar: SKU/PLU/name search; recent items.
- ModifierPanel: grouped options; toggleable chips; validation for required choices.
- CartList: keyboard-focusable lines; icons for void/discount/modifiers.
- TotalsBar: highlights change due after payment.
- TenderPanel: cash keypad, quick-amount buttons, split list.
- Hotbar: frequently used actions with assigned hotkeys.
- StatusIndicators: network, printer, drawer; with tooltips and logs link.
- Toasts/Dialogs: non-blocking toasts; confirm dialogs for destructive actions.

## Layout
- Left: ProductGrid & Categories.
- Right: CartList (top), TotalsBar (bottom).
- Slide-over panels: ModifierPanel, TenderPanel, SuspendList, ReprintSearch.

## Performance & Reliability
- 60 FPS target on grid interactions; <50ms item add perceived latency.
- All input debounce <100ms; async actions show progress.
- Autosave draft sale state every 2 seconds.

## Accessibility & i18n
- High contrast theme; text scaling 125–150% presets.
- ARIA roles for lists, dialogs, and buttons; focus outlines visible.
- Locale-aware currency formatting and number input.

## Audit & Security
- Log: login/logout, price override, discounts, voids, refunds, drawer opens, reprints.
- Role gating for sensitive actions; inline manager PIN prompts.

## Configurable Options
- Hotkeys mapping, category layout, quick cash amounts, rounding rules, receipt copy count, default reprint behavior.
