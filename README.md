# TechSecond

TechSecond là sàn C2C mua bán sản phẩm công nghệ và đồ cũ. Người dùng có thể là buyer và seller. Hệ thống mục tiêu hỗ trợ product, cart, checkout, order, review, complaint, chat, admin và PDF invoice.

Dự án đang chuyển đổi từ ASP.NET MVC 5 sang Laravel. Laravel là codebase chính và active application chạy tại repository root. Legacy code nằm trong `legacy/` để tham chiếu nghiệp vụ.

## 1. Project status

Audit hiện tại:

- Date: 2026-07-22.
- Commit nền: `5c9ed90c5899c4c6549dd2743154c59c0b6d0240`.
- Branch làm việc: `chore/pre-parallel-foundation`.

| Hạng mục | Trạng thái |
| --- | --- |
| Repository foundation | In progress |
| Laravel boot | Blocked |
| Database migrations | Not verified |
| Seeders | Not verified |
| Authentication | In progress |
| Product | In progress |
| Cart | In progress |
| Orders | In progress |
| Reviews | In progress |
| Complaints | In progress |
| Chat | In progress |
| Admin | In progress |
| PDF | In progress |
| Tests | Not verified |
| CI | In progress |
| Deployment readiness | Blocked |

## 2. Core features

Phạm vi nghiệp vụ mục tiêu:

- Authentication và profile.
- Buyer/seller role.
- Product và category.
- Product images.
- Search, filter và pagination.
- Cart.
- Multi-seller checkout.
- Order tracking.
- Seller order processing.
- Review.
- Complaint.
- Chat.
- Admin dashboard.
- PDF invoice.
- Một tính năng nâng cao qua Cache, Queue hoặc WebSocket.

Hiện tại repository mới ở foundation skeleton. Các workflow nghiệp vụ đầy đủ chưa được triển khai và chưa được kiểm chứng runtime.

## 3. Technology stack

| Layer | Technology |
| --- | --- |
| Backend | PHP target 8.4.x; detected PHP CLI 8.2.12; Laravel `v13.20.0` từ `composer.lock` |
| ORM | Eloquent |
| Database | MySQL target 8.4; MySQL CLI chưa xác minh |
| View | Blade |
| CSS | Bootstrap 5.3 qua npm/Vite |
| Build | Vite `^8.0.0` |
| Authentication | Laravel Auth |
| Authorization | Middleware và Policy |
| Validation | Form Request |
| Storage | Laravel Storage |
| PDF | DomPDF qua `barryvdh/laravel-dompdf v3.1.2` |
| Test | PHPUnit |
| Style | Laravel Pint |
| CI | GitHub Actions |

## 4. Repository structure

```text
.
├── app/
├── bootstrap/
├── config/
├── database/
├── docs/
├── lang/
├── legacy/
│   ├── aspnet-mvc/
│   ├── laravel-port-draft/
│   └── sql/
├── public/
├── resources/
├── routes/
│   └── modules/
├── storage/
├── tests/
├── tools/
│   ├── legacy-migration/
│   └── quality/
├── .github/workflows/
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── phpunit.xml
└── vite.config.js
```

`package-lock.json` sẽ xuất hiện sau khi chạy `npm install` một lần để tạo lock, rồi dùng `npm ci`.

## 5. Prerequisites

```text
PHP 8.4.x
Composer 2.x
MySQL 8.4
Node.js LTS
npm
Git
```

Kiểm tra:

```bash
php -v
composer --version
mysql --version
node -v
npm -v
git --version
```

## 6. Local setup

```bash
git clone <repository-url>
cd <repository-directory>
composer install
npm ci
```

Nếu `package-lock.json` chưa tồn tại, foundation owner phải tạo lock bằng `npm install` một lần rồi commit lock file. Thành viên không tự tạo lock riêng.

Tạo `.env`:

```bash
copy .env.example .env
php artisan key:generate
```

macOS/Linux:

```bash
cp .env.example .env
```

Database local:

```text
techsecond
techsecond_test
```

Mẫu `.env` không chứa credential thật:

```dotenv
APP_NAME=TechSecond
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=techsecond
DB_USERNAME=techsecond_app
DB_PASSWORD=
```

Sau khi xác minh database local:

```bash
php artisan migrate:fresh --seed
php artisan storage:link
npm run build
composer run dev
```

## 7. Database safety

`migrate:fresh` xóa toàn bộ bảng trong database hiện tại.

- Chỉ chạy trên `techsecond` hoặc `techsecond_test`.
- Không chạy trên database SQL Server legacy `TMDT`.
- Kiểm tra `DB_DATABASE` trước khi chạy.
- Không commit `.env`.
- Không chạy `clear_db.php`, `create_db.php`, `setup_*.php`, `fix_*.php` hoặc `convert_views.php` tự động.

## 8. Demo accounts

Seeder foundation tạo các username sau cho local/demo:

| Username | Role |
| --- | --- |
| admin | Admin |
| minhhuy | User |
| hoapham | User |
| quocbao | User |

Password demo được set bằng `Password123!` và phải được hash trong database. Chỉ dùng cho local/demo, không dùng production.

## 9. Development commands

```bash
php artisan about
php artisan route:list
php artisan migrate:fresh --seed
php artisan test
vendor/bin/pint
vendor/bin/pint --test
composer run check:quality
composer run test:foundation
composer run verify
npm ci
npm run dev
npm run build
```

Không chạy lệnh phá hủy database nếu chưa xác minh whitelist.

## 10. Testing

`phpunit.xml` uses MySQL testing database `techsecond_test`:

```text
DB_CONNECTION=mysql
DB_DATABASE=techsecond_test
CACHE_STORE=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
```

Chạy:

```bash
bash scripts/verify-parallel-readiness.sh --member-id TV1 --expected-commit <PARALLEL_BASE_COMMIT>
```

PowerShell:

```powershell
.\scripts\verify-parallel-readiness.ps1 -MemberId TV1 -ExpectedCommit <PARALLEL_BASE_COMMIT>
```

Use your own member id. The scripts read only `DB_CONNECTION` and `DB_DATABASE` from `.env.testing` and refuse `migrate:fresh` unless the database name starts with `techsecond_test`.

Do not use the legacy `TMDT` database for tests.

## 11. Architecture

```text
HTTP Request
→ Route
→ Middleware
→ Form Request
→ Controller
→ Service
→ Eloquent
→ MySQL
```

Docs liên quan:

- `docs/architecture/module-contracts.md`
- `docs/architecture/route-contract.md`
- `docs/database/schema-contract.md`
- `AGENTS.md`

## 12. Database model

Entity canonical:

- User
- Category
- Product
- ProductImage
- Cart
- CartItem
- Order
- OrderItem
- Review
- Complaint
- Message

## 13. Branch workflow

```text
main
develop
feature/*
fix/*
docs/*
test/*
chore/*
```

Pull Request flow:

```text
feature branch
→ Pull Request vào develop
→ review
→ CI pass
→ merge
```

Không push trực tiếp vào `main` hoặc `develop`.

## 14. Team module ownership

1. Foundation, Auth và Profile.
2. Category, Product, Search và Media.
3. Cart, Checkout, Orders và Inventory.
4. Review, Complaint và Admin.
5. Chat, PDF, Shared UI, Advanced feature và CI.

Xem `docs/architecture/module-contracts.md`.

## 15. Legacy code

- ASP.NET MVC legacy: `legacy/aspnet-mvc`.
- Laravel port cũ: `legacy/laravel-port-draft`.
- SQL dump cũ: `legacy/sql`.
- Conversion scripts: `tools/legacy-migration`.

Legacy không phải runtime. Không copy trực tiếp C#/Razor vào active Laravel source.

## 16. Troubleshooting

### PHP version không đúng

```bash
where php
php -v
```

### Composer không nhận PHP đúng

Composer dùng PHP CLI trong PATH. Kiểm tra `where php`, `php -v`, `composer --version`.

### MySQL connection refused

Kiểm tra service, host, port và `.env`.

### `vendor/autoload.php` missing

```bash
composer install
```

### `Vite manifest not found`

```bash
npm ci
npm run build
```

### Storage link không tạo được trên Windows

Bật Developer Mode hoặc chạy terminal có quyền phù hợp:

```bash
php artisan storage:link
```

### View not found trên Linux

Kiểm tra chữ hoa/thường của `resources/views` và tên trong `view(...)`.

### Test kết nối nhầm database

Kiểm tra `phpunit.xml`, `.env.testing` và `DB_DATABASE`.

## 17. Security notes

- Không commit `.env`.
- Không dùng password demo trong production.
- Không lưu password plain text.
- Không thay đổi dữ liệu bằng GET.
- Upload validate MIME và size.
- Dùng Policy cho dữ liệu thuộc người dùng.
- Document root là `public`.

## 18. Current limitations

- Gate 0 chưa pass.
- PHP CLI hiện là `8.2.12`, chưa đạt PHP 8.4 target.
- Composer CLI chưa có trong PATH.
- MySQL CLI chưa có trong PATH.
- `package-lock.json` chưa tạo.
- `vendor/` và `node_modules/` chưa tồn tại.
- Migrations/test/build chưa chạy thành công trong môi trường hiện tại.
- Business workflows mới ở skeleton.

## 19. Documentation

- `AGENTS.md`
- `docs/audits/pre-parallel-baseline.md`
- `docs/architecture/blade-contract.md`
- `docs/architecture/module-contracts.md`
- `docs/architecture/route-contract.md`
- `docs/architecture/status-enums.md`
- `docs/database/erd-v2.md`
- `docs/database/schema-contract.md`
- `docs/database/legacy-schema-mapping.md`
- `docs/development/coding-conventions.md`
- `docs/development/git-workflow.md`
- `docs/development/local-setup.md`
- `docs/development/pre-parallel-checklist.md`

## 20. License hoặc academic notice

Repository chưa có license root chính thức. Đây là dự án học tập. Không tuyên bố open-source license cho toàn bộ repository cho đến khi nhóm chọn license.
