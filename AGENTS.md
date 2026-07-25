# AGENTS.md

## 1. Project identity

TechSecond là sàn thương mại điện tử C2C cho mua bán sản phẩm công nghệ và đồ cũ. Dự án đang chuyển đổi từ ASP.NET MVC 5 sang Laravel.

Laravel là codebase chính và active application nằm tại repository root. ASP.NET MVC và bản Laravel port cũ nằm trong `legacy/` để tham chiếu nghiệp vụ, không phải runtime chính. Giai đoạn hiện tại là pre-parallel foundation trước khi năm thành viên phát triển song song.

Không tuyên bố dự án ready nếu Gate 0 chưa chạy pass.

## 2. Source of truth

Thứ tự ưu tiên:

1. Active Laravel source tại repository root.
2. Laravel migrations trong `database/migrations`.
3. Contract trong `docs/`.
4. Automated tests.
5. ASP.NET MVC legacy chỉ dùng tham chiếu nghiệp vụ.
6. SQL Server script và SQL dump cũ chỉ dùng đối chiếu, không phải schema runtime.

Không sửa database trực tiếp để thay migration. Không copy C#/Razor vào active Laravel source.

## 3. Repository layout

Target layout đang được thiết lập:

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

`package-lock.json` chỉ được coi là sẵn sàng sau khi `npm install` tạo lock và `npm ci` pass.

## 4. Technology contract

- PHP 8.4.x.
- Laravel 13.x.
- Composer 2.x.
- MySQL 8.4.
- Eloquent ORM.
- Blade.
- Bootstrap 5.3 qua npm/Vite.
- ES Modules và Fetch API.
- Laravel Auth.
- Middleware và Policy.
- Form Request.
- Laravel Storage.
- PHPUnit.
- Laravel Pint.
- GitHub Actions.
- `barryvdh/laravel-dompdf`.

Không dùng Tailwind trong active frontend. Không dùng Bootstrap CDN nếu Bootstrap đã bundle qua Vite. Không chạy `composer update` toàn bộ nếu chỉ cần `composer install`.

## 5. Architecture rules

```text
Route
→ Middleware
→ Form Request
→ Controller
→ Service
→ Eloquent Model
→ MySQL
```

Controller chỉ nhận request, gọi service và trả response. Controller không chứa transaction dài, truy vấn phức tạp dùng chung, hard-code trạng thái, ghép đường dẫn upload, kiểm tra quyền lặp lại hoặc workflow checkout/review/chat đầy đủ.

Service chịu trách nhiệm business rule, transaction, inventory, phối hợp model và event. Model chỉ chứa relationship, cast, scope và accessor/mutator nhỏ. Blade chỉ hiển thị dữ liệu.

## 6. Database rules

- Bảng/cột dùng English `snake_case`.
- Primary key là `id`.
- Foreign key là `{model}_id`.
- Không dùng composite primary key.
- Không dùng SQL trigger cho nghiệp vụ.
- Không lưu password plain text.
- Không hard-code user ID.
- Trạng thái lưu bằng PHP backed enum value tiếng Anh.
- Nhãn tiếng Việt đặt trong `lang/vi`.
- Review và Complaint gắn với `order_item`.
- Checkout tạo order riêng theo từng seller.
- Product dùng soft delete.
- Order item lưu snapshot tên và giá sản phẩm.
- Không sửa migration đã merge; tạo migration mới cho thay đổi tiếp theo.

Database an toàn cho destructive commands:

```text
techsecond
techsecond_test
```

Không chạy destructive commands trên `TMDT`, `production`, `prod`, `staging` hoặc database chưa xác minh.

## 7. Canonical models

```text
User
Category
Product
ProductImage
Cart
CartItem
Order
OrderItem
Review
Complaint
Message
```

Các model legacy như `NguoiDung`, `SanPham`, `HoaDon` chỉ được giữ trong `legacy/`.

## 8. Canonical enums

```text
UserRole
ProductStatus
OrderStatus
OrderItemStatus
PaymentMethod
PaymentStatus
ComplaintStatus
```

Mọi enum là string-backed enum và model phải cast sang enum tương ứng.

## 9. Route rules

- Mọi route phải có name.
- URI dùng `kebab-case`.
- Route name dùng dot notation.
- Route thay đổi dữ liệu không dùng GET.
- POST cho tạo.
- PATCH/PUT cho cập nhật.
- DELETE cho xóa.
- Logout dùng POST.
- Admin route dùng `auth`, `not_locked`, `admin`.
- Route tài nguyên cần Policy hoặc authorization phù hợp.
- Route chia trong `routes/modules`.

## 10. Authentication and authorization

- `App\Models\User` extends `Authenticatable`.
- Dùng Laravel Auth.
- Password phải hash.
- Không lưu nguyên User model thủ công trong session.
- User bị khóa bị chặn bởi middleware `not_locked`.
- Admin route dùng middleware `admin`.
- Quyền tài nguyên dùng Policy.
- Không lấy user ID từ form để quyết định authenticated user.

## 11. Validation and file uploads

- Dùng Form Request.
- Upload kiểm tra MIME type, extension, kích thước và loại ảnh.
- Dùng Laravel Storage public disk.
- Không ghi trực tiếp vào `public/Content`.
- Không tự ghép path bằng chuỗi từ user input.

## 12. Frontend and Blade

- View directories lowercase.
- Layout dùng `@vite`.
- Bootstrap import từ npm.
- Không Bootstrap CDN.
- Không jQuery legacy.
- Không Razor/C# markers.
- Form thay đổi dữ liệu dùng `@csrf`.
- Update/delete forms dùng `@method`.

## 13. Encoding

Active source dùng UTF-8. Không có mojibake như `Ä`, `Ã`, `Â`, `á»`, `áº` trong active Laravel source. Tiếng Việt hiển thị lấy từ language files.

## 14. Tests

Mỗi module phải có test cho happy path, validation, authentication, authorization, ownership, database constraint, transaction rollback và regression quan trọng.

Gate chuẩn:

```bash
composer validate --strict
composer check-platform-reqs
php artisan migrate:fresh --seed
vendor/bin/pint --test
php artisan test
composer run check:quality
npm ci
npm run build
```

Không ghi PASS nếu command chưa chạy.

## 15. Quality requirements

- Không Razor trong active source.
- Không mojibake.
- Không hard-code trạng thái nghiệp vụ.
- Không unsafe GET mutation.
- Không plaintext password.
- Không active reference tới `public/Content` hoặc `public/Scripts`.
- Không commit `.env`, `vendor`, `node_modules`, build output không cần thiết.
- Pint, tests, quality scanner và frontend build phải pass trước ready.

## 16. Git workflow

Branch chuẩn:

```text
main
develop
feature/*
fix/*
refactor/*
test/*
docs/*
chore/*
```

Không push trực tiếp vào `main` hoặc `develop`. Commit dùng `feat:`, `fix:`, `refactor:`, `test:`, `docs:`, `chore:`, `build:`, `ci:`.

## 17. Shared files

Hạn chế sửa đồng thời:

```text
composer.json
composer.lock
package.json
package-lock.json
vite.config.js
bootstrap/app.php
routes/web.php
routes/modules/*
database/migrations/*
app/Enums/*
app/Models/*
resources/views/layouts/*
resources/js/app.js
resources/css/app.css
phpunit.xml
.github/workflows/*
docs/architecture/*
docs/database/*
```

## 18. Definition of Done

Done khi scope đúng, migrations/relationships đúng, có Form Request, có Service nếu có nghiệp vụ, có Policy/Middleware nếu có quyền, có test, route named, không hard-code status/path/user id, Pint pass, tests pass, quality pass, frontend build pass, docs cập nhật và CI pass.

## 19. Agent operating procedure

1. Đọc `AGENTS.md`.
2. Đọc docs liên quan.
3. Kiểm tra Git.
4. Kiểm tra scope.
5. Ghi baseline.
6. Thay đổi nhỏ.
7. Chạy test gần nhất.
8. Chạy gate cần thiết.
9. Kiểm tra diff.
10. Báo cáo file, test và rủi ro.

Hoàn thành toàn bộ file-only work trước khi báo manual action.

## 20. Current project status

- Branch làm việc: `chore/pre-parallel-foundation`.
- Starting commit: `5c9ed90c5899c4c6549dd2743154c59c0b6d0240`.
- Laravel active source: repository root.
- Laravel version locked: `v13.20.0` từ `composer.lock`.
- PHP requirement: `^8.4` trong `composer.json`.
- Detected PHP CLI: `8.2.12`, chưa đạt target.
- Composer CLI: Chưa xác minh vì không có trong PATH.
- MySQL CLI: Chưa xác minh vì không có trong PATH.
- Node.js detected: `v24.15.0`.
- npm detected: `11.12.1`.
- Legacy isolation: ASP.NET và Laravel port cũ đã đưa vào `legacy/`; scripts chuyển đổi nằm trong `tools/legacy-migration`.
- Test/build status: Chưa PASS cho đến khi dependency install và Gate 0 chạy thành công.
