# 🎫 Event Management System

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-^8.2-blue.svg)](https://php.net)
[![Filament](https://img.shields.io/badge/Filament-3.x-orange.svg)](https://filamentphp.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Sistem manajemen event komprehensif yang dibangun dengan Laravel 12, Filament Admin Panel, dan Laravel Breeze Authentication. Mendukung booking tiket, pembayaran QR code, dan manajemen event secara lengkap.

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Struktur Database](#-struktur-database)
- [API Documentation](#-api-documentation)
- [User Journey](#-user-journey)
- [Admin Panel](#-admin-panel)
- [Testing](#-testing)
- [Deployment](#-deployment)
- [Contributing](#-contributing)

## 🚀 Fitur Utama

### 👤 User Features
- **Autentikasi Lengkap**: Register, login, forgot password dengan Laravel Breeze
- **Event Discovery**: Browse dan search event dengan filter kategori
- **Booking System**: Booking tiket dengan validasi quota dan pricing
- **Payment Integration**: QR Code payment dengan QRIS support
- **Digital Tickets**: QR code tickets untuk entry validation
- **User Dashboard**: Statistik booking, history, dan manajemen profil

### 👨‍💼 Admin Features
- **Event Management**: CRUD events dengan kategori dan organizer
- **Booking Management**: Monitor dan konfirmasi booking
- **User Management**: Kelola user dan permission
- **Payment Verification**: Approve/reject payment dengan bukti transfer
- **Analytics Dashboard**: Statistik penjualan dan event performance
- **QR Code Scanner**: Validasi tiket di pintu masuk

### 🔧 System Features
- **QR Code Generation**: Automatic QR generation untuk payment dan tickets
- **Email Notifications**: Konfirmasi booking dan payment updates
- **File Management**: Upload bukti pembayaran dan event banners
- **Responsive Design**: Mobile-first dengan Tailwind CSS
- **Real-time Updates**: Live updates menggunakan Livewire

## 🛠 Teknologi yang Digunakan

### Backend
- **Laravel 12.x** - Framework PHP modern
- **Laravel Breeze** - Authentication scaffolding
- **Filament 3.x** - Admin panel dengan Livewire
- **MySQL** - Primary database
- **Redis** - Caching dan session storage

### Frontend
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Livewire 3.x** - Full-stack framework untuk Laravel
- **Vite** - Modern build tool

### Third-party Services
- **chillerlan/php-qrcode** - QR code generation
- **barryvdh/laravel-dompdf** - PDF generation
- **Laravel Pail** - Log monitoring
- **Laravel Telescope** - Application debugging

## 📦 Instalasi

### Prerequisites
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL 8.0+
- Redis (optional)

### Step 1: Clone Repository
```bash
git clone https://github.com/alfrdzley/event-management.git
cd event-management
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 3: Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create storage link
php artisan storage:link
```

### Step 4: Database Setup
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE event_management;"

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed
```

### Step 5: Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### Step 6: Start Development Server
```bash
# Laravel server
php artisan serve

# Or using the dev script (includes queue, logs, and vite)
composer run dev
```
