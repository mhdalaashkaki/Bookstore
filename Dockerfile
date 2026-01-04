# 1. استخدام صورة PHP مع Apache
FROM php:8.2-apache

# 2. تثبيت المكتبات اللازمة للنظام وقواعد البيانات (Postgres)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    nodejs \
    npm

# 3. تنظيف الكاش لتقليل الحجم
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 4. تثبيت إضافات PHP الضرورية (مهم جداً pdo_pgsql عشان Neon)
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# 5. تفعيل mod_rewrite في Apache (ضروري لروابط Laravel)
RUN a2enmod rewrite

# 6. ضبط مجلد العمل وتغيير الروت ليكون public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf.0

# 7. تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 8. نسخ ملفات المشروع
WORKDIR /var/www/html
COPY . .

# 9. تثبيت مكتبات PHP و Node.js وبناء المشروع
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN npm install
RUN npm run build

# 10. ضبط الصلاحيات
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 11. المنفذ الذي سيفتح عليه التطبيق
EXPOSE 80
