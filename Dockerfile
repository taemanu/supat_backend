#php ใช้ version 8.2 สามารถเปลี่ยนได้ตามที่เราต้องการแต่ต้องเป็นตัว fpm นะครับ เพื่อที่จะให้กับ nginx
FROM php:7.4-fpm

#Install คำสั่งสำหรับการลง package ที่ laravel required ไว้นะครับ
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    nano \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    libgd-dev
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
#Mine

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-external-gd
RUN docker-php-ext-install gd
RUN docker-php-ext-install bcmath

# ดึงเวอร์ชั่นล่าสุดของ Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ติดตั้ง Node.js และ npm
# RUN curl -sL https://deb.nodesource.com/setup_14.x | bash -
# RUN apt-get install -y nodejs
# RUN apt-get install -y npm

# กำหนดไดเร็กทอรีทำงาน
WORKDIR /var/www

# คัดลอกเนื้อหาไดเร็กทอรีแอปพลิเคชันปัจจุบัน
# COPY . /var/www

# ติดตั้งแพกเกจพึ่งพา
# RUN composer install --no-scripts

# คัดลอกจุดเข้าปัจจุบันสำหรับ Artisan
COPY ./artisan /usr/local/bin/artisan

# สร้างคีย์แอปพลิเคชัน Laravel
# RUN php artisan key:generate

# RUN chown -R www-data:www-data /var/www/storage
# RUN chown -R www-data:www-data /var/www/bootstrap
# RUN chown -R www-data:www-data /var/www/public

# Export WORKDIR
# ENV WORKDIR=/var/www

# เปิดพอร์ต 9000 และเริ่มเซิร์ฟเวอร์ php-fpm
EXPOSE 9000
CMD ["php-fpm"]