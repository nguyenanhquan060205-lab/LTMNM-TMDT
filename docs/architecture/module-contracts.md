# Module Contracts

## Shared files

Do not edit without coordination:

- `composer.json`, `composer.lock`
- `package.json`, `package-lock.json`
- `vite.config.js`
- `bootstrap/app.php`
- `routes/web.php`, `routes/modules/*`
- `database/migrations/*`
- `app/Enums/*`
- `app/Models/*`
- `resources/views/layouts/*`
- `resources/css/app.css`, `resources/js/app.js`
- `phpunit.xml`
- `.github/workflows/*`

## 1. Foundation/Auth/Profile

- Tables owned: `users`, `sessions`, `password_reset_tokens`.
- Models owned: `User`.
- Routes owned: `auth.*`, `profile.*`.
- Services owned: `AuthService`, `ProfileService`.
- Policies owned: `UserPolicy`.
- Events emitted: `UserRegistered`, `ProfileUpdated`.
- Data consumed: order/product counts from other modules.
- Review owner: Foundation owner.

## 2. Category/Product/Search/Media

- Tables owned: `categories`, `products`, `product_images`.
- Models owned: `Category`, `Product`, `ProductImage`.
- Routes owned: `products.*`, `seller.products.*`, `admin.categories.*`.
- Services owned: `ProductService`, `MediaService`, `CategoryService`.
- Policies owned: `ProductPolicy`.
- Events emitted: `ProductCreated`, `ProductUpdated`, `ProductHidden`.
- Data consumed: `users.id` as seller.
- Review owner: Product owner.

## 3. Cart/Checkout/Orders/Inventory

- Tables owned: `carts`, `cart_items`, `orders`, `order_items`.
- Models owned: `Cart`, `CartItem`, `Order`, `OrderItem`.
- Routes owned: `cart.*`, `orders.*`, `seller.orders.*`, `seller.order-items.*`.
- Services owned: `CartService`, `OrderService`, `SellerOrderService`.
- Policies owned: `OrderPolicy`.
- Events emitted: `OrderPlaced`, `OrderCancelled`, `InventoryAdjusted`.
- Data consumed: products and users.
- Review owner: Order owner.

## 4. Review/Complaint/Admin

- Tables owned: `reviews`, `complaints`.
- Models owned: `Review`, `Complaint`.
- Routes owned: `reviews.*`, `complaints.*`, `admin.*`.
- Services owned: `ReviewService`, `ComplaintService`, `AdminDashboardService`.
- Policies owned: `ReviewPolicy`, `ComplaintPolicy`.
- Events emitted: `ReviewCreated`, `ComplaintResolved`.
- Data consumed: order items, products, users.
- Review owner: Admin/Review owner.

## 5. Chat/PDF/Shared UI/Advanced feature/CI

- Tables owned: `messages`, queue/cache tables when used.
- Models owned: `Message`.
- Routes owned: `messages.*`, `invoices.*`.
- Services owned: `ChatService`, `InvoiceService`.
- Policies owned: `MessagePolicy`.
- Events emitted: `MessageSent`, `InvoiceGenerated`.
- Data consumed: users, products, orders.
- Review owner: Shared UI/CI owner.
