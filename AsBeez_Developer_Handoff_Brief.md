# ASBEEZ POS — DEVELOPER HANDOFF BRIEF

## What We Are Building
A production-grade POS platform with inventory, accounting, loyalty, and governance controls.

## Core Philosophy
- Append-only ledgers
- Reversals, not edits
- Inventory moves only
- Period locking
- Mandatory audits
- Loyalty, not income

## Sprint 0 Focus
Sprint 0 builds rails, not features.

## Definition of Done
- Cannot bypass controls
- Cannot backdate
- Cannot mutate ledgers
- Cannot double-post events

## Evaluation Criteria
- Correctness
- Safety
- Test coverage
- Long-term maintainability

## Final Note
If the system requires manual data fixes, the design is wrong.
