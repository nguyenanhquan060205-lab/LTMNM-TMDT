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

## Service Contract Registry

Concrete implementations are only listed when a foundation implementation exists.
`CONTRACT_ONLY` means the module owner can type-hint the interface now, but owns
the business implementation on their feature branch.

| Contract | Interface | Concrete implementation hien tai | Owner | Methods | Depends on |
| --- | --- | --- | --- | --- | --- |
| Auth | `App\Contracts\Services\AuthServiceContract` | `App\Services\AuthService` - IMPLEMENTED_FOUNDATION | TV1 | `register`, `attemptLogin`, `logout`, `currentUser` | `User`, Laravel Auth |
| Profile | `App\Contracts\Services\ProfileServiceContract` | CONTRACT_ONLY - IMPLEMENTED_BY_MODULE_OWNER_LATER | TV1 | `updateProfile`, `updatePassword`, `updateBankInformation` | `User` |
| Category | `App\Contracts\Services\CategoryServiceContract` | CONTRACT_ONLY - IMPLEMENTED_BY_MODULE_OWNER_LATER | TV2 | `listForSelection`, `create`, `update`, `delete` | `Category` |
| Product | `App\Contracts\Services\ProductServiceContract` | CONTRACT_ONLY - IMPLEMENTED_BY_MODULE_OWNER_LATER | TV2 | `publicIndex`, `createForSeller`, `update`, `changeStatus`, `hide` | `User`, `Product`, `ProductStatus` |
| Media | `App\Contracts\Services\MediaServiceContract` | `App\Services\MediaService` - IMPLEMENTED_FOUNDATION | TV2, TV5 review for chat upload | `storeAvatar`, `storeProductImage`, `storeMessageImage`, `delete` | `User`, `Product`, `UploadedFile`, `Storage` |
| Cart | `App\Contracts\Services\CartServiceContract` | CONTRACT_ONLY - IMPLEMENTED_BY_MODULE_OWNER_LATER | TV3 | `getOrCreateForUser`, `addProduct`, `updateQuantity`, `removeItem` | `User`, `Product`, `Cart`, `CartItem` |
| Order | `App\Contracts\Services\OrderServiceContract` | CONTRACT_ONLY - IMPLEMENTED_BY_MODULE_OWNER_LATER | TV3 | `checkout`, `listForBuyer`, `cancelByBuyer` | `User`, `Order`, transaction/inventory contract |
| Seller order | `App\Contracts\Services\SellerOrderServiceContract` | CONTRACT_ONLY - IMPLEMENTED_BY_MODULE_OWNER_LATER | TV3 | `listForSeller`, `confirmItem`, `cancelItem`, `updateAggregateStatus` | `User`, `Order`, `OrderItem` |
| Review | `App\Contracts\Services\ReviewServiceContract` | CONTRACT_ONLY - IMPLEMENTED_BY_MODULE_OWNER_LATER | TV4 | `canReview`, `createForOrderItem`, `hasExistingReview` | `User`, `OrderItem`, `Review` |
| Complaint | `App\Contracts\Services\ComplaintServiceContract` | CONTRACT_ONLY - IMPLEMENTED_BY_MODULE_OWNER_LATER | TV4 | `createForOrderItem`, `listForUser`, `resolve` | `User`, `OrderItem`, `Complaint`, `ComplaintStatus` |
| Admin dashboard | `App\Contracts\Services\AdminDashboardServiceContract` | CONTRACT_ONLY - IMPLEMENTED_BY_MODULE_OWNER_LATER | TV4 | `statistics` | Aggregated read models; no query builder returned to Blade |
| Chat | `App\Contracts\Services\ChatServiceContract` | CONTRACT_ONLY - IMPLEMENTED_BY_MODULE_OWNER_LATER | TV5 | `conversation`, `send`, `markAsRead` | `User`, `Message` |
| Invoice | `App\Contracts\Services\InvoiceServiceContract` | CONTRACT_ONLY - IMPLEMENTED_BY_MODULE_OWNER_LATER | TV5 | `generatePdf` | `Order`, Symfony `Response` |

## Realtime Contract

- Channel file: `routes/channels.php`.
- Private channel: `users.{userId}`.
- Authorization: only the authenticated user whose `users.id` equals `{userId}` can subscribe.
- Event: `App\Events\MessageSent`.
- Event alias: `message.sent`.
- Event payload: `message_id`, `sender_id`, `receiver_id`, optional `product_id`, optional `content`, optional `image_path`, `sent_at`.
- Payload must not include password, email, bank information, or full `User` model.

## Shared File Ownership

- `routes/web.php`: TV1/TV5, import-only.
- `bootstrap/app.php`: TV1, TV5 review.
- `routes/channels.php`: TV5, TV1 review.
- `app/Enums/*`: shared contract PR only.
- Foundation migrations: frozen; new schema changes use forward migrations.
- `composer.json`, `composer.lock`, `package.json`, `package-lock.json`: TV5/Platform.
- Shared models: owner plus dependent reviewer.
- Layouts/components: TV5.
- CI workflow: TV5.
