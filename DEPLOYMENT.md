# 🚀 Deployment Guide - Event Management System

## 📋 Production Deployment Checklist

### Pre-Deployment
- [ ] **Environment Configuration**
  - [ ] Set `APP_ENV=production`
  - [ ] Set `APP_DEBUG=false`
  - [ ] Generate secure `APP_KEY`
  - [ ] Configure production database
  - [ ] Set up mail service (SMTP/SES)
  - [ ] Configure file storage (S3/local)
  - [ ] Set Redis for cache/sessions

- [ ] **Security Hardening**
  - [ ] SSL certificate configured
  - [ ] HTTPS redirect enabled
  - [ ] Security headers configured
  - [ ] Rate limiting configured
  - [ ] CORS settings reviewed

- [ ] **Performance Optimization**
  - [ ] Opcache enabled
  - [ ] Application cache cleared
  - [ ] Routes cached
  - [ ] Views cached
  - [ ] Config cached
  - [ ] Composer autoloader optimized

### Production Environment Setup

#### 1. Server Requirements
```bash
# Minimum server specs
CPU: 2 cores
RAM: 4GB
Storage: 20GB SSD
OS: Ubuntu 20.04+ / CentOS 8+

# Required software
PHP 8.2+ (with extensions)
Nginx 1.20+
MySQL 8.0+ / PostgreSQL 13+
Redis 6.0+
Node.js 18+
Supervisor
```

#### 2. PHP Configuration
```ini
# /etc/php/8.2/fpm/php.ini
memory_limit = 256M
max_execution_time = 60
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 20

# Extensions required
extension=bcmath
extension=ctype
extension=fileinfo
extension=json
extension=mbstring
extension=openssl
extension=pdo
extension=pdo_mysql
extension=tokenizer
extension=xml
extension=gd
extension=zip
extension=redis
```

#### 3. Nginx Configuration
```nginx
# /etc/nginx/sites-available/event-management
server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com;
    root /var/www/event-management/public;
    index index.php;

    # SSL Configuration
    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/javascript;

    # Laravel specific
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Static file caching
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Security
    location ~ /\.ht {
        deny all;
    }

    location ~ /storage/.*\.php$ {
        deny all;
    }
}
```

#### 4. MySQL Configuration
```sql
-- Create database and user
CREATE DATABASE event_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'event_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON event_management.* TO 'event_user'@'localhost';
FLUSH PRIVILEGES;

-- Performance tuning in /etc/mysql/mysql.conf.d/mysqld.cnf
[mysqld]
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
query_cache_type = 1
query_cache_size = 64M
max_connections = 100
```

## 🐳 Docker Deployment

### 1. Multi-stage Dockerfile
```dockerfile
# Build stage
FROM node:18-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci --only=production
COPY . .
RUN npm run build

# PHP stage
FROM php:8.2-fpm-alpine AS php-base

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    mysql-client \
    nginx \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install \
    bcmath \
    ctype \
    fileinfo \
    json \
    mbstring \
    openssl \
    pdo \
    pdo_mysql \
    tokenizer \
    xml \
    gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Production stage
FROM php-base AS production

WORKDIR /var/www

# Copy application
COPY . /var/www
COPY --from=node-builder /app/public/build /var/www/public/build

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/php.ini
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Optimize Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
```

### 2. Docker Compose for Production
```yaml
# docker-compose.prod.yml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
      target: production
    container_name: event-management-app
    restart: unless-stopped
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    volumes:
      - storage_data:/var/www/storage/app
      - ./storage/logs:/var/www/storage/logs
    depends_on:
      - database
      - redis
    networks:
      - event-network

  nginx:
    image: nginx:alpine
    container_name: event-management-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
      - ./ssl:/etc/nginx/ssl
      - storage_data:/var/www/storage/app
    depends_on:
      - app
    networks:
      - event-network

  database:
    image: mysql:8.0
    container_name: event-management-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: event_management
      MYSQL_USER: event_user
      MYSQL_PASSWORD: ${DB_PASSWORD}
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
    volumes:
      - db_data:/var/lib/mysql
      - ./docker/mysql/my.cnf:/etc/mysql/conf.d/my.cnf
    networks:
      - event-network

  redis:
    image: redis:alpine
    container_name: event-management-redis
    restart: unless-stopped
    volumes:
      - redis_data:/data
    networks:
      - event-network

  queue:
    build:
      context: .
      dockerfile: Dockerfile
      target: production
    container_name: event-management-queue
    restart: unless-stopped
    command: php artisan queue:work --sleep=3 --tries=3 --timeout=90
    depends_on:
      - database
      - redis
    networks:
      - event-network

volumes:
  db_data:
  redis_data:
  storage_data:

networks:
  event-network:
    driver: bridge
```

## ☁️ Cloud Deployment

### 1. AWS EC2 Deployment
```bash
#!/bin/bash
# AWS EC2 deployment script

# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server redis-server supervisor git curl

# Install PHP 8.2
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-redis php8.2-gd php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Clone and setup application
cd /var/www
sudo git clone https://github.com/alfrdzley/event-management.git
sudo chown -R $USER:www-data event-management
cd event-management

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Setup environment
cp .env.example .env
php artisan key:generate

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Configure services
sudo systemctl enable nginx mysql redis supervisor
sudo systemctl start nginx mysql redis supervisor
```

### 2. Laravel Forge Integration
```php
// forge-deploy.sh
cd /home/forge/event-management.com

git pull origin main

$FORGE_COMPOSER install --no-interaction --prefer-dist --optimize-autoloader --no-dev

if [ -f artisan ]; then
    $FORGE_PHP artisan migrate --force
    $FORGE_PHP artisan config:cache
    $FORGE_PHP artisan route:cache
    $FORGE_PHP artisan view:cache
    $FORGE_PHP artisan queue:restart
fi

npm ci && npm run build
```

### 3. DigitalOcean App Platform
```yaml
# .do/app.yaml
name: event-management
services:
- name: web
  source_dir: /
  github:
    repo: alfrdzley/event-management
    branch: main
    deploy_on_push: true
  build_command: |
    composer install --no-dev --optimize-autoloader
    npm ci && npm run build
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
  run_command: heroku-php-nginx public/
  environment_slug: php
  instance_count: 1
  instance_size_slug: basic-xxs
  envs:
  - key: APP_ENV
    value: production
  - key: APP_DEBUG
    value: false
  - key: APP_KEY
    value: ${APP_KEY}
  - key: DB_CONNECTION
    value: mysql
  - key: DB_HOST
    value: ${db.HOSTNAME}
  - key: DB_PORT
    value: ${db.PORT}
  - key: DB_DATABASE
    value: ${db.DATABASE}
  - key: DB_USERNAME
    value: ${db.USERNAME}
  - key: DB_PASSWORD
    value: ${db.PASSWORD}

databases:
- name: db
  engine: MYSQL
  version: "8"
  size: db-s-1vcpu-1gb
```

## 🔄 CI/CD Pipeline

### 1. GitHub Actions
```yaml
# .github/workflows/deploy.yml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: 8.2
        
    - name: Install dependencies
      run: composer install --prefer-dist --no-progress
      
    - name: Run tests
      run: php artisan test

  deploy:
    needs: test
    runs-on: ubuntu-latest
    steps:
    - name: Deploy to server
      uses: appleboy/ssh-action@v0.1.5
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        key: ${{ secrets.SSH_KEY }}
        script: |
          cd /var/www/event-management
          git pull origin main
          composer install --no-dev --optimize-autoloader
          npm ci && npm run build
          php artisan migrate --force
          php artisan config:cache
          php artisan route:cache
          php artisan view:cache
          php artisan queue:restart
          sudo systemctl reload nginx
```

### 2. GitLab CI/CD
```yaml
# .gitlab-ci.yml
stages:
  - test
  - build
  - deploy

variables:
  MYSQL_DATABASE: testing
  MYSQL_ROOT_PASSWORD: secret

test:
  stage: test
  image: php:8.2
  services:
    - mysql:8.0
  before_script:
    - apt-get update -qq && apt-get install -y git unzip
    - curl -sS https://getcomposer.org/installer | php
    - mv composer.phar /usr/local/bin/composer
    - composer install
  script:
    - php artisan test

deploy_production:
  stage: deploy
  image: alpine:latest
  before_script:
    - apk add --no-cache openssh-client
    - eval $(ssh-agent -s)
    - echo "$SSH_PRIVATE_KEY" | tr -d '\r' | ssh-add -
  script:
    - ssh -o StrictHostKeyChecking=no $SERVER_USER@$SERVER_HOST "
        cd /var/www/event-management &&
        git pull origin main &&
        composer install --no-dev --optimize-autoloader &&
        npm ci && npm run build &&
        php artisan migrate --force &&
        php artisan config:cache &&
        php artisan route:cache &&
        php artisan view:cache &&
        php artisan queue:restart &&
        sudo systemctl reload nginx"
  only:
    - main
```

## 📊 Monitoring & Logging

### 1. Application Monitoring
```php
// config/logging.php
'channels' => [
    'production' => [
        'driver' => 'stack',
        'channels' => ['daily', 'slack'],
    ],
    
    'slack' => [
        'driver' => 'slack',
        'url' => env('SLACK_WEBHOOK_URL'),
        'username' => 'Laravel',
        'emoji' => ':warning:',
        'level' => 'error',
    ],
],
```

### 2. Performance Monitoring
```bash
# Install New Relic (example)
wget -O - https://download.newrelic.com/548C16BF.gpg | sudo apt-key add -
echo 'deb http://apt.newrelic.com/debian/ newrelic non-free' | sudo tee /etc/apt/sources.list.d/newrelic.list
sudo apt update
sudo apt install newrelic-php5
sudo newrelic-install install
```

### 3. Log Rotation
```bash
# /etc/logrotate.d/laravel
/var/www/event-management/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 0644 www-data www-data
    postrotate
        php /var/www/event-management/artisan queue:restart
    endscript
}
```

## 🔧 Maintenance

### 1. Database Backup
```bash
#!/bin/bash
# backup-db.sh
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/mysql"
DB_NAME="event_management"

mkdir -p $BACKUP_DIR

mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/backup_$DATE.sql.gz

# Keep only 30 days of backups
find $BACKUP_DIR -name "backup_*.sql.gz" -mtime +30 -delete
```

### 2. Storage Cleanup
```bash
#!/bin/bash
# cleanup-storage.sh

# Clear temporary files
find /var/www/event-management/storage/app/temp -mtime +7 -delete

# Clear old logs
find /var/www/event-management/storage/logs -name "*.log" -mtime +30 -delete

# Clear expired QR codes
find /var/www/event-management/storage/app/public/qrcodes -name "payment_qr_*.png" -mtime +1 -delete
```

### 3. Health Check Script
```bash
#!/bin/bash
# health-check.sh

# Check if services are running
systemctl is-active --quiet nginx || echo "Nginx down!"
systemctl is-active --quiet mysql || echo "MySQL down!"
systemctl is-active --quiet redis || echo "Redis down!"

# Check application health
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://your-domain.com/up)
if [ $HTTP_STATUS != 200 ]; then
    echo "Application health check failed: $HTTP_STATUS"
fi

# Check database connectivity
php /var/www/event-management/artisan db:show || echo "Database connection failed!"
```

---

*Deploy with confidence! 🚀*
