FROM php:8.3-apache

# Cài đặt các thư viện hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Cài đặt các extension PHP bắt buộc cho Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Kích hoạt Apache mod_rewrite
RUN a2enmod rewrite

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Copy toàn bộ mã nguồn vào
COPY . .

# Cài đặt các thư viện (bỏ qua nếu vendor đã được push lên github, nhưng thường thì nên chạy lệnh này)
RUN composer install --no-dev --optimize-autoloader

# Phân quyền cho thư mục Laravel
RUN mkdir -p /var/www/html/storage/logs
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Trỏ thư mục gốc của Apache vào thư mục /public của Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Pass environment variables to PHP
RUN echo "PassEnv APP_NAME APP_ENV APP_KEY APP_DEBUG DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD SESSION_DRIVER FILESYSTEM_DISK QUEUE_CONNECTION BROADCAST_CONNECTION CLOUDINARY_URL GEMINI_API_KEY PUSHER_APP_ID PUSHER_APP_KEY PUSHER_APP_SECRET PUSHER_APP_CLUSTER" >> /etc/apache2/apache2.conf

EXPOSE 80
