# Status Enums

Canonical enum values:

| Enum | Values |
| --- | --- |
| `UserRole` | `admin`, `user` |
| `ProductStatus` | `approved`, `sold`, `hidden` |
| `OrderStatus` | `pending`, `processing`, `completed`, `cancelled` |
| `OrderItemStatus` | `pending`, `confirmed`, `cancelled` |
| `PaymentMethod` | `cash_on_delivery`, `bank_transfer` |
| `PaymentStatus` | `unpaid`, `paid` |
| `ComplaintStatus` | `pending`, `resolved` |

Rules:

- Database stores enum values in English.
- Models cast status/payment fields to PHP backed enums.
- Vietnamese labels are resolved from `lang/vi/status.php`.
- Controllers, Models, Services and Blade conditions must not compare Vietnamese labels.
