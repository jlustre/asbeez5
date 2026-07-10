# AsBeez KDS — UI/UX (F)

Kitchen Display System designed for rapid prep with minimal touches, clear prioritization, and robust offline behavior.

## Stations & Routing
- Stations: GRILL, FRY, DRINKS, DESSERT, PACK/EXPO (configurable).
- Routing: rules by product category or explicit `route_station` on order item.
- Splitting: a single order can create tickets per station; Expo sees combined view.

## Ticket Lifecycle
- States: queued → prepping → ready → served → voided.
- Timers: show elapsed time; color thresholds (e.g., 3 min yellow, 5 min red).
- Actions: Start (prepping), Ready, Serve, Void; Recall (Expo only).

## Ticket Tile Design
- Header: Ticket #, Order #, Type (dine-in/takeaway/delivery), Time-in.
- Body: Item lines with qty, modifiers, notes; allergy badges.
- Footer: Buttons (Prepping/Ready/Serve) and a quick Notes button; bump/recall at Expo.
- Color Coding: state-based background; allergies in red badge; high-priority orders pulsing border.

## Screens
- Station View: grid/list of tickets for a single station.
- Expo View: consolidated tickets across stations; can mark served for whole order.
- All-Day Items: aggregated items (e.g., total patties needed) to aid batch prep.

## Hotkeys & Bump Bar
- Up/Down/Left/Right: navigate between tiles.
- Enter: select ticket / advance primary action.
- R: mark Ready; S: mark Served; V: Void (confirm).
- Bump bar integration (optional): map hardware keys to the above.

## Filters & Sorting
- Sort by oldest, promised time, or priority.
- Filter by order type, allergy flag, or specific modifiers (e.g., no salt).

## Offline Behavior
- Works fully against Edge mirroring; state changes queue to outbox.
- Visual network indicator; retries with backoff.

## Error Handling
- Conflicts (already served): show toast; pull latest state and reconcile.
- Printer errors (kitchen chits): show status and provide manual reprint.

## Accessibility
- Large type mode; high contrast; minimal reliance on small tap targets.
- Screen reader labels for actions and states; focus rings.

## Performance
- 60 FPS list scrolling; incremental rendering for long queues.
- Debounce refresh; optimistic UI for state transitions with rollback on failure.

## Expo Flow (Example)
1. Ticket arrives for GRILL & FRY.
2. GRILL marks Ready; FRY pending.
3. Expo sees both; when all stations Ready, Expo taps Serve to complete order.

## Audit & Metrics
- Log transitions with actor and timestamps.
- Metrics: avg time to Ready/Serve by station and by product.
