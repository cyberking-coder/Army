# Phase 1 — Brand & Design System

## Color Palette

### Primary (brand purple, matches reference admin)
| Token | Hex | Use |
|---|---|---|
| `primary-900` | `#4C1D95` | Deep accents |
| `primary-700` | `#6B21A8` | **Main brand purple** |
| `primary-500` | `#A855F7` | Hover states |
| `primary-100` | `#F5F3FF` | Backgrounds |

### Neutral
| Token | Hex |
|---|---|
| `gray-900` | `#111827` |
| `gray-700` | `#374151` |
| `gray-500` | `#6B7280` |
| `gray-200` | `#E5E7EB` |
| `gray-100` | `#F3F4F6` |

### Semantic
| Token | Hex | Use |
|---|---|---|
| `success` | `#10B981` | Successful badges |
| `warning` | `#F59E0B` | Pending badges |
| `danger`  | `#EF4444` | Rejected badges |
| `info`    | `#3B82F6` | Info chips |

### Patriotic (sparingly, hero only)
| Saffron | White | Green | Chakra Blue |
|---|---|---|---|
| `#FF9933` | `#FFFFFF` | `#138808` | `#000080` |

## Typography

**Public:** Poppins (headings) + Inter (body)
**Admin:** system font stack

| Role | Size | Weight |
|---|---|---|
| H1 | 36px | 700 |
| H2 | 28px | 700 |
| H3 | 22px | 600 |
| Body | 16px | 400 |
| Small | 14px | 400 |

## Tokens
- Spacing: 4/8/12/16/24/32/48/64/96 (Tailwind default)
- Radius: buttons `8px`, cards `12px`, avatars `9999px`
- Shadows: sm/md/lg (Tailwind default)
- Buttons: `px-4 py-2 rounded-lg font-medium transition-colors`
- Status badges: `bg-amber-100/text-amber-800` (pending) etc.

## Breakpoints
`sm 640 · md 768 · lg 1024 · xl 1280 · 2xl 1536`

Mobile-first. Admin sidebar collapses below `lg`.

## Accessibility
- WCAG 2.1 AA minimum
- Body contrast ≥ 4.5:1, large text ≥ 3:1
- Focus ring 2px `primary-500`
- All forms have labels; errors announced to screen readers

## Logo
Minimum 32px height (web), 24px (mobile nav). Clear space = logo height. White logo on dark surfaces; full-color on light.
