# 1. Kéo hệ điều hành ảo (Image) từ Docker Hub về. Ở đây dùng môi trường PHP 8.3 được cài sẵn Web Server Apache.
FROM php:8.3-apache

# 2. Cài đặt các thư viện lõi của hệ điều hành Linux (Ubuntu/Debian) để hỗ trợ PHP giải nén, xử lý ảnh, tải file...
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# 3. Kích hoạt các gói mở rộng (Extensions) bắt buộc của PHP để Laravel có thể hoạt động (ví dụ: pdo_mysql để gọi CSDL, gd để xử lý ảnh)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 4. Bật module "rewrite" của Apache, giúp định tuyến (Routing) các đường dẫn đẹp của Laravel không bị lỗi 404
RUN a2enmod rewrite

# 5. Cài đặt công cụ Composer (Trình quản lý thư viện của PHP) bằng cách copy nó từ một Image khác vào
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Chỉ định thư mục làm việc mặc định bên trong Container là /var/www/html (Thư mục chuẩn của Apache)
WORKDIR /var/www/html

# 7. Sao chép (Copy) toàn bộ mã nguồn đồ án từ máy tính của bạn vào trong Container
COPY . .

# 8. Gọi lệnh Composer để tải toàn bộ thư viện cần thiết trong file composer.json (Bỏ qua thư viện rác dùng để Dev)
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader

# 9. Cấp quyền (Phân quyền 775) cho các thư mục nhạy cảm để Laravel có thể ghi file (Log, Cache, Session, Hình ảnh upload)
RUN mkdir -p /var/www/html/storage/logs
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Trỏ thư mục gốc (Root) của Web Server chui thẳng vào thư mục /public của Laravel. Giúp bảo mật toàn bộ code bên ngoài
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 11. Bước khởi động: Lấy bí mật (Biến môi trường .env) từ Render gắn vào Code, sau đó Bật máy chủ Apache lên chạy 24/24
CMD sh -c "cp /etc/secrets/.env /var/www/html/.env 2>/dev/null; chown www-data:www-data /var/www/html/.env 2>/dev/null; chmod 644 /var/www/html/.env 2>/dev/null; apache2-foreground"
