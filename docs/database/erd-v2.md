# TechSecond ERD v2

Nguồn chân lý schema là Laravel migrations trong `database/migrations`.

```mermaid
erDiagram
    users ||--o{ products : sells
    users ||--|| carts : owns
    users ||--o{ orders : buys
    users ||--o{ orders : sells
    users ||--o{ reviews : writes
    users ||--o{ complaints : files
    users ||--o{ messages : sends
    users ||--o{ messages : receives
    categories ||--o{ products : contains
    products ||--o{ product_images : has
    products ||--o{ cart_items : selected
    products ||--o{ order_items : sold_as
    products ||--o{ reviews : reviewed
    products ||--o{ messages : discussed
    carts ||--o{ cart_items : has
    orders ||--o{ order_items : has
    order_items ||--o| reviews : reviewed_by
    order_items ||--o{ complaints : disputed_by
```

Ghi chú:

- `orders` đại diện một buyer và một seller. Checkout multi-seller phải tạo nhiều order.
- `order_items` lưu snapshot `product_name`, `unit_price`, `subtotal`.
- Review và complaint gắn với `order_item`.
- Product dùng soft delete.
