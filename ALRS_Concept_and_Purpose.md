# AsBeez Loyalty Reward System (ALRS)
*A loyalty engine that turns everyday purchases into long-term rewards—without subscriptions, recruiting, or exposing profit margins.*

## 1. Purpose and goals
**ALRS** is designed to increase customer loyalty and repeat purchases across AsBeez businesses (e.g., Grocery, Bakery, Fastfood, Rice Retail, Farms) by awarding points that:
- **Reward customers for shopping**, not for recruiting.
- **Fund rewards from real profitability**, using internal margin snapshots.
- **Convert rewards to store credit only**, with approval/holding controls.
- **Scale predictably** through a structured “Reward Cell” placement system.
- **Prevent abuse and infinite loops** (e.g., no points earned when spending store credit).

ALRS uses two point types—**QBP** and **MBP**—plus a reward-unit called an **ARC** (AsBeez Reward Cell). Together, they create a system that is transparent to members (they can see their points and progress) while keeping sensitive margin data internal.

---

## 2. Key entities and terms

### 2.1 ALRS (AsBeez Loyalty Reward System)
The overall program that manages:
- Point calculation and ledgers
- Reward conversion to store credit
- Reward Cell (ARC) creation and placement
- Community reward distribution to eligible uplines
- Operational reserve funding for the AsBeez web/app platform

### 2.2 ARC (AsBeez Reward Cell)
**ARC** is the renamed concept formerly called “ABC.” An ARC is a **loyalty reward unit** unlocked through shopping activity. Customers can own **unlimited ARCs**.

ARCs are placed into a structured network called the **ARC Grid** (a forced 3-wide matrix). An ARC may receive MBP rewards if it is considered **eligible (active)**.

### 2.3 ARC Grid (Forced 3-wide matrix, 12-level reward horizon)
- The ARC Grid is a **3-wide, breadth-first filled structure**.
- Each new ARC is placed into the next available slot in a **BFS (breadth-first search)** manner.
- Rewards can flow to **up to 12 eligible uplines** above where the new ARC is placed.
- The grid is designed for predictable expansion and simple auditing.

> Important: Public-facing messaging should avoid “upline/downline” terms. Internally, “upline” is a technical term for the 12 eligible ARCs above a placement.

### 2.4 QBP (Qualified Business Points)
**QBP** is the customer’s *progress counter* used **only** to unlock new ARCs.

**QBP characteristics:**
- Earned **only** from a customer’s **own purchases**.
- Computed from **actual margin snapshots** at the time of sale (internal use only).
- **Not redeemable**, not transferable, and not paid out.
- Used to trigger ARC creation: **every 100 QBP → 1 ARC minted**.
- QBP is **not** earned from being an upline ARC.

> Think of QBP like “progress points” or “unlock points.”

### 2.5 MBP (Monetary Business Points)
**MBP** is the program’s *reward currency* used for **store-credit rewards** and **platform operations**.

**MBP characteristics:**
- Earned through ALRS reward events (e.g., upline rewards when an ARC is placed).
- Convertible to **AsBeez store credit** (not cash) under defined rules.
- Subject to **holding periods** and/or **claim approval**.
- **Does not** unlock ARCs and **never** contributes to QBP thresholds.

> Think of MBP like a “reward wallet balance.”

---

## 3. How points are created (profit-funded, margin-private)

### 3.1 Margin snapshot (internal only)
ALRS computes QBP from a **margin snapshot** stored internally per order item:
- Unit sale price at the time of purchase
- Unit cost at the time of purchase
- Quantity

**Customers never see:**
- Unit cost
- Profit margin
- Reward budget math
- Pool balances

Customers only see:
- QBP awarded per receipt/order
- QBP lifetime totals and progress
- MBP wallet and store credit results

### 3.2 QBP calculation rule (internal)
- Compute profit from the snapshot: *(sale price − cost) × quantity* (loss leaders can be treated as zero profit for rewards).
- Allocate a reward budget: **10% of profit margin**.
- Convert to points: **10 points = ₱1 value-equivalent** for reward budgeting.
- QBP awarded is typically an integer (rounded down) to avoid over-crediting.

### 3.3 No points when paying with store credit
To prevent reward loops and protect margins:

**If any part of a purchase uses store credit:**
- **No QBP is awarded**
- **No MBP is awarded**
- Consequently, **no ARC is minted** from that purchase

This rule ensures customers can’t convert rewards into credit and immediately generate more rewards from credit spending.

---

## 4. ARC creation (unlocking reward cells)

### 4.1 ARC minting threshold
- **Every 100 QBP earned by a customer unlocks 1 ARC**.
- Customers can mint **unlimited ARCs** over time as they continue shopping.

### 4.2 QBP lifetime and “unlocked” tracking
To ensure ARCs are minted correctly, the system tracks:
- **QBP lifetime**: total QBP earned from purchases
- **QBP unlocked**: total QBP already used to mint ARCs in 100-point increments

Example:
- QBP lifetime = 260
- QBP unlocked = 200
- Newly mintable = 60 (not enough)
- If QBP lifetime becomes 320 → eligible = 300; newly mintable = 100 → mint 1 ARC

### 4.3 No “ARC bonus points”
Creating an ARC does **not** automatically award the owner extra points.
- No “new ARC reward” to the owner
- Rewards are handled via MBP distribution rules (next section)

---

## 5. MBP allocation when an ARC is minted (60/40 rule)

Each time **1 ARC is minted**, the system creates **100 MBP** and allocates it:

- **60 MBP**: intended for upline rewards (up to 12 eligible uplines × 5 MBP each)
- **40 MBP**: goes directly to **Portal Reserve** (operations budget)

This is the baseline ALRS economic split used to fund:
- community rewards (upline MBP)
- platform salaries, perks, maintenance, and operations (portal reserve)

---

## 6. Upline MBP rewards on placement (eligibility + skipping)

### 6.1 Standard upline reward amount
When a new ARC is **placed** in the ARC Grid:
- The system attempts to reward **12 eligible uplines**
- Each eligible upline receives: **5 MBP**
- Maximum upline distribution: **60 MBP**

### 6.2 Eligibility (active status)
An upline ARC is eligible only if:
- The ARC is active **and**
- The owner is active

If an upline ARC’s owner is **inactive**, it is **skipped**.

### 6.3 Skipping inactive uplines and paying the next active ARC
If an upline position is inactive:
- The reward is not lost.
- The system continues upward until it finds the next **active** ARC.
- The goal is to pay **12 active uplines** if possible.

This creates a “first 12 active uplines” selection model, rather than “levels 1–12 regardless of status.”

### 6.4 If fewer than 12 active uplines exist
If the placement has **fewer than 12 active uplines available** (structural shortage):
- Pay the existing active uplines found.
- **Unused MBP from the 60 MBP upline budget transfers immediately to Portal Reserve.**
- No “pending payout” is created for missing uplines.

This rule increases portal funding early and prevents “ghost liabilities.”

---

## 7. Pool liquidity vs structural shortage (two different reasons payouts may differ)

ALRS distinguishes between:

### 7.1 Structural shortage (not enough eligible uplines)
Cause: not enough active uplines exist above the placement.
Result:
- Unused portion of the 60 MBP upline budget is **redirected to Portal Reserve immediately**.

### 7.2 Liquidity shortage (pool temporarily lacks balance)
Cause: eligible uplines exist, but the **Global Reward Pool** doesn’t have enough MBP at that moment.
Result:
- Pay as many eligible uplines as possible immediately.
- Remaining eligible payouts stay **pending**.
- Pending payouts are paid later as the pool refills (scheduled processing).

> Structural shortage → portal reserve transfer.  
> Liquidity shortage → pending payouts for eligible uplines.

---

## 8. Store credit conversion and claims

### 8.1 Store credit is funded by MBP only
- **MBP** can be converted to **AsBeez store credit**.
- **QBP cannot be converted**; it only unlocks ARCs.

### 8.2 Holding period and approval (protection controls)
To protect the system from returns/fraud and maintain financial stability:
- MBP may start as **pending** and become **available** after a holding period (e.g., 15–30 days).
- Conversions to store credit can require **claim submission** and **approval**.
- Admin tools should provide an approval workflow and audit logs.

### 8.3 No earning from store credit purchases
Reinforced rule:
- If a purchase uses store credit, it earns **no QBP** and **no MBP**.

---

## 9. Transparency without margin disclosure

ALRS is designed to be transparent in outcomes without exposing internal margin data.

### 9.1 What customers can see
- QBP earned per receipt/order
- QBP lifetime and progress to next ARC
- Total ARCs owned
- MBP pending/available balances
- Store credit balance and conversion status
- A history of their own point events (customer-safe)

### 9.2 What is internal / need-to-know
- Item costs and profit margins
- Reward budget math per item
- Pool balances and reserve accounting details
- Fraud rules and flags
- Internal operational costs and payroll budgets

---

## 10. Operational roles and access (recommended)
To protect sensitive information and simplify operations:
- **Customer:** QR, QBP/MBP balances, receipts, claims, store credit
- **Cashier:** scan QR, attach member to order, apply store credit, view success confirmation
- **Manager:** branch-level reports (no costs by default)
- **Admin:** configuration, claims approval, audits
- **Finance:** pool/reserve dashboards, internal margin reports (need-to-know)

---

## 11. Customer experience summary (simple story)

1) Customer signs up for free and gets a member QR.
2) Customer shops at AsBeez and scans QR at checkout.
3) Purchases award **QBP** (progress points).
4) Every **100 QBP** unlocks a new **ARC**.
5) Each ARC minted adds **MBP** to the system:
   - Up to 60 MBP intended for community rewards
   - 40 MBP to portal reserve
6) When ARCs are placed, eligible active uplines can receive **MBP**.
7) MBP can be converted into **AsBeez store credit** (after rules/approval).
8) Purchases using store credit do not generate points.

---

## 12. Why ALRS works (design strengths)
- **Profit-funded**: rewards come from real margins (internally tracked).
- **No recruiting**: loyalty is driven by shopping behavior.
- **Auditable**: every point movement can be ledgered.
- **Stable economics**: no points on store credit prevents loops.
- **Operational funding**: portal reserve consistently funds platform upkeep.
- **Fair eligibility**: inactive owners are skipped; rewards go to active cells.
- **Scalable**: forced matrix with BFS placement scales predictably.

---

## 13. Implementation notes (non-code)
- Use a customer-safe “receipt points summary” record so customers can see their earned QBP per order without exposing internal margin.
- Use concurrency-safe placement and pool spending to avoid double placement or overspending.
- Keep separate views/resources for customer vs admin APIs.
- Log “skipped due to inactivity” events for internal audit and dispute handling.
- Clearly word public messaging as a loyalty program, not a business/income model.

---

## 14. Public-friendly description (optional copy)
**AsBeez Loyalty Reward System** turns everyday shopping into a rewards experience. Your purchases earn **Qualified Points (QBP)** that unlock **Reward Cells (ARC)** over time. Reward Cells help power community rewards and support the AsBeez platform. Eligible rewards are tracked transparently and may be converted into **AsBeez store credit** once requirements are met and the request is approved. There are no sign-up fees, no subscriptions, and no recruiting—just shop and earn.
