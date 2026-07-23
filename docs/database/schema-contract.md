# Schema Contract

## Principles

- Migrations là nguồn chân lý.
- Bảng/cột dùng English `snake_case`.
- Primary key là `id`.
- Foreign key là `{model}_id`.
- Không dùng composite primary key.
- Không dùng SQL trigger cho nghiệp vụ.
- Trạng thái lưu bằng enum value tiếng Anh.
- Nhãn tiếng Việt nằm trong `lang/vi/status.php`.

## Tables

| Table | Owner | Notes |
| --- | --- | --- |
| `users` | Foundation/Auth/Profile | Auth model chính, password hashed, unique username/email/phone |
| `categories` | Product module | Unique name |
| `products` | Product module | Seller/category FK, soft delete, status enum |
| `product_images` | Media/Product module | Cascade theo product; one cover enforced ở service |
| `carts` | Cart module | One cart per user |
| `cart_items` | Cart module | Unique cart/product, không lưu subtotal |
| `orders` | Order module | One buyer and one seller per order |
| `order_items` | Order module | Snapshot product name and price |
| `reviews` | Review module | Unique order item, rating 1-5 in validation |
| `complaints` | Complaint/Admin module | Gắn order item |
| `messages` | Chat module | Sender/receiver and optional product context |
| `notifications` | Platform/TV5 | Laravel database notification table; morph notifiable columns, data, nullable read timestamp |

## Notifications

`notifications` is in foundation scope and uses Laravel's standard database
notification table:

- `id` UUID primary key.
- `type`.
- `notifiable_type` and `notifiable_id` morph columns.
- `data`.
- nullable `read_at`.
- timestamps.

## Upload Storage Contract

Upload limits are centralized in `config/uploads.php`.

| Type | Disk | Directory | MIME types | Extensions | Max KB |
| --- | --- | --- | --- | --- | --- |
| Avatar | `FILESYSTEM_DISK` default `public` | `avatars` | `image/jpeg`, `image/png`, `image/webp` | `jpg`, `jpeg`, `png`, `webp` | 2048 |
| Product image | `FILESYSTEM_DISK` default `public` | `products` | `image/jpeg`, `image/png`, `image/webp` | `jpg`, `jpeg`, `png`, `webp` | 4096 |
| Message image | `FILESYSTEM_DISK` default `public` | `messages` | `image/jpeg`, `image/png`, `image/webp` | `jpg`, `jpeg`, `png`, `webp` | 4096 |

Services must store storage-relative paths only. Absolute local paths and
direct `public/Content` style paths are not part of the active Laravel schema.

## Safety

`migrate:fresh` chỉ được chạy khi `DB_DATABASE` là `techsecond` hoặc `techsecond_test`.
