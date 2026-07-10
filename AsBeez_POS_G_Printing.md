# AsBeez POS — Printing (G)

Receipt and kitchen print formats, ESC/POS mappings, and drawer control for robust, device-agnostic output via Edge.

## Printer Profiles
- Receipt: Thermal 80mm and 58mm (ESC/POS-compatible; e.g., Epson TM-T20, Star emulation).
- Kitchen: Impact or thermal (high-contrast, loud buzzer optional).
- Character width (typical):
  - 80mm: 48 (Font A, 12x24), 42 (with wider codepage), compressed up to 64 (Font C).
  - 58mm: 32–42 depending on device; default 32.
- Profiles define: width chars, supported features (cut, beep, QR), code page, drawer pins.

## Receipt Layout (Customer)
Header
- Store name (bold, center), address, tax reg no, branch code, register code, cashier, date/time, order number.

Body (Items)
- Format: `LN  QTY  NAME                     AMT`
- Modifiers: indented `  • No Onions`, `  • Extra Sauce (+10.00)`
- Subtotals per group (optional).

Totals
- Subtotal, Discounts, Tax, Total (bold, double-height optional).
- Tender lines: `CASH 200.00`, `CHANGE 9.60`.
- Loyalty: points earned/redeemed (if applicable).

Footer
- Thank-you message, contact, return policy, Wi-Fi info (optional).
- QR code (optional): link to feedback or eReceipt.
- Cut paper.

Example (80mm, plain text)
```
ASBEEZ FASTFOOD
Branch BR-001  Reg REG-1  Cashier J.DOE
14-Jan-2026 12:31  Order# 000123
----------------------------------------------
1  BURGER CLASSIC                 120.00
   • No Onions
1  FRIES MEDIUM                    50.00
----------------------------------------------
SUBTOTAL                          170.00
TAX (12%)                          20.40
TOTAL                             190.40
CASH                              200.00
CHANGE                              9.60
----------------------------------------------
Loyalty Earned: 19 pts
Thank you! asbeez.com/feedback
```

## Kitchen Chit Layout
Header
- Station, Ticket #, Order #, Order Type, Time-in (big/bold), Table/Name (if any).

Lines
- `QTY  ITEM NAME` in large font; modifiers/notes below; allergy badge `ALLERGY!` in inverse/bold.

Footer
- Optional: reprint marker, QR with order id, buzzer/beep.

Example
```
[GRILL]  TKT #045  ORD #000123  12:31
TAKEAWAY
--------------------------------
1  BURGER CLASSIC
   NO ONIONS
   NOTE: WELL DONE
--------------------------------
```

## Edge Print Job Schema
Jobs are posted from POS/KDS to Edge in a device-agnostic JSON:
```json
{
  "type": "receipt|kitchen",
  "profile": "receipt_80mm_default",
  "copies": 1,
  "content": [
    {"op": "text", "data": "ASBEEZ FASTFOOD", "align": "center", "bold": true},
    {"op": "kv", "k": "Order#", "v": "000123"},
    {"op": "rule"},
    {"op": "row", "cols": [
      {"text": "1 BURGER CLASSIC"},
      {"text": "120.00", "align": "right"}
    ]},
    {"op": "indent", "data": "• No Onions"},
    {"op": "rule"},
    {"op": "total", "k": "TOTAL", "v": "190.40", "emphasis": true},
    {"op": "qr", "data": "https://asbeez.com/feedback?o=000123", "size": 6},
    {"op": "cut"}
  ]
}
```
Supported ops: `text`, `kv` (key/value), `row` (columns), `indent`, `rule`, `total`, `barcode`, `qr`, `image`, `cut`, `beep`, `drawer`.

## ESC/POS Mapping (common)
Initialization
- Reset: ESC @ → `1B 40`
- Select code page (example PC437): ESC t n → `1B 74 00` (device dependent)

Styles
- Align: ESC a n (0=left,1=center,2=right) → `1B 61 01`
- Bold on/off: ESC E n → `1B 45 01` / `1B 45 00`
- Double height/width: GS ! n → `1D 21 11` (both); reset `1D 21 00`
- Underline on/off: ESC - n → `1B 2D 01` / `1B 2D 00`

Rules & Rows
- Horizontal rule: print `-` repeated to width.
- Columns: pad to column widths based on profile; monospaced assumptions.

Barcodes/QR
- Barcode: GS k m ... (depends on symbology)
- QR: `1D 28 6B ...` (Store QR data, size, error correction, print)

Cut & Beep
- Full cut: GS V 66 0 → `1D 56 42 00`
- Partial cut: GS V 66 1 → `1D 56 42 01`
- Beep: ESC ( A p m t → device-specific; many use `1B 42 n t`

Cash Drawer (RJ-11)
- Pulse: ESC p m t1 t2 → `1B 70 00 32 32` (DK-1) or `1B 70 01 32 32` (DK-2)
- Safety: only fire on successful cash capture and log an audit event.

## Device Profiles (examples)
```json
{
  "receipt_80mm_default": {
    "width": 48,
    "codepage": 0,
    "cut": true,
    "drawer": {"pin": 0, "t1": 50, "t2": 50},
    "qr": {"moduleSize": 6, "ecLevel": 48}
  },
  "receipt_58mm_default": {"width": 32, "codepage": 0, "cut": false},
  "kitchen_impact": {"width": 42, "beep": true}
}
```

## Localization
- Currency formatting from locale; 2-decimal default.
- Code page or UTF-8 to match device; fallback transliteration where needed.

## Reliability
- Print queue with retries (3x) and exponential backoff at Edge.
- Preview mode (render to text/PNG) for testing without device.
- Reprint includes `is_reprint` and watermark flag in payload.

## Compliance
- Show tax registration, VAT breakdown (if applicable), and legal footer per jurisdiction.
- Receipt numbering sequences tracked in `receipts` table.

## Test Page
- Edge provides `/devices/print/test` to print: fonts, alignments, barcode/QR, cut, and drawer pulse.
