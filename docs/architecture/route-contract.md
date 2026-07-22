# Route Contract

All routes must have names. State-changing routes must not use GET.

| Area | Route names |
| --- | --- |
| Public | `home`, `products.index`, `products.show` |
| Auth | `auth.login.create`, `auth.login.store`, `auth.logout`, `auth.register.create`, `auth.register.store` |
| Profile | `profile.show`, `profile.edit`, `profile.update`, `profile.password.update`, `profile.bank.update` |
| Seller products | `seller.products.index`, `seller.products.create`, `seller.products.store`, `seller.products.edit`, `seller.products.update`, `seller.products.destroy` |
| Cart | `cart.index`, `cart.items.store`, `cart.items.update`, `cart.items.destroy` |
| Orders | `orders.index`, `orders.show`, `orders.store`, `orders.cancel`, `seller.orders.index`, `seller.orders.show`, `seller.order-items.confirm`, `seller.order-items.cancel` |
| Reviews | `reviews.create`, `reviews.store` |
| Complaints | `complaints.index`, `complaints.show`, `complaints.create`, `complaints.store` |
| Messages | `messages.index`, `messages.show`, `messages.store`, `messages.read`, `messages.destroy` |
| Invoices | `invoices.show`, `invoices.download` |
| Admin | `admin.dashboard`, `admin.users.index`, `admin.users.lock`, `admin.users.unlock`, `admin.products.index`, `admin.products.update-status`, `admin.orders.index`, `admin.complaints.index`, `admin.complaints.update`, `admin.categories.index`, `admin.categories.store`, `admin.categories.update`, `admin.categories.destroy` |

Module route files live in `routes/modules`.
