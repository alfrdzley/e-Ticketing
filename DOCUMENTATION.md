## ⚙️ Konfigurasi

### Environment Variables
```env
# Application
APP_NAME="Event Management System"
APP_ENV=local
APP_KEY=base64:your-app-key
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_management
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

# Queue Configuration
QUEUE_CONNECTION=database

# Cache Configuration
CACHE_STORE=file
SESSION_DRIVER=file

# File Storage
FILESYSTEM_DISK=local
```

### Payment Configuration
```env
# Payment Gateway Settings
PAYMENT_GATEWAY=qris
PAYMENT_DEFAULT_BANK=BNI
PAYMENT_DEFAULT_ACCOUNT=1234567890
```

## 🗄️ Struktur Database

### Core Tables

#### Users Table
```sql
users (
    id, name, email, phone, email_verified_at, 
    password, remember_token, timestamps
)
```

#### Events Table
```sql
events (
    id, name, slug, description, banner_image_url,
    start_date, end_date, location, address, price, quota,
    status, category_id, organizer_id, payment_info, timestamps
)
```

#### Bookings Table
```sql
bookings (
    id, booking_code, user_id, event_id, quantity,
    unit_price, total_amount, discount_amount, final_amount,
    status, payment_method, payment_date, expired_at,
    booker_name, booker_email, booker_phone,
    ticket_qr_code_path, ticket_pdf_path, timestamps
)
```

### Supporting Tables

#### Event Categories
```sql
event_categories (
    id, name, slug, description, color, icon, is_active, timestamps
)
```

#### Organizers
```sql
organizers (
    id, name, description, slug, contact_email, 
    contact_phone, is_active, timestamps
)
```

#### Tickets
```sql
tickets (
    id, ticket_code, qr_code, booking_id, attendee_info,
    is_checked_in, checked_in_at, special_requirements, timestamps
)
```

### Relationships
- `User` → `Booking` (One to Many)
- `Event` → `Booking` (One to Many)
- `Event` → `EventCategory` (Many to One)
- `Event` → `Organizer` (Many to One)
- `Booking` → `Ticket` (One to Many)

## 🔗 API Documentation

### Public Endpoints

#### Get Events
```http
GET /api/events
```
Response:
```json
{
    "data": [
        {
            "id": 1,
            "name": "Tech Conference 2025",
            "slug": "tech-conference-2025",
            "description": "Annual tech conference",
            "start_date": "2025-09-15T09:00:00Z",
            "price": 150000,
            "quota": 500,
            "available_seats": 250
        }
    ]
}
```

#### Create Booking
```http
POST /api/bookings
```
Request:
```json
{
    "event_id": 1,
    "quantity": 2,
    "booker_name": "John Doe",
    "booker_email": "john@example.com",
    "booker_phone": "+6281234567890"
}
```

### Admin Endpoints

#### Validate Ticket
```http
POST /tickets/validate
```
Request:
```json
{
    "qr_data": "encoded_qr_string",
    "validator_name": "Security Guard",
    "entry_gate": "Gate A"
}
```

## 👥 User Journey

### Customer Journey
1. **Discovery** → Browse events di homepage
2. **Selection** → Pilih event dan lihat detail
3. **Registration** → Register/login jika belum punya akun
4. **Booking** → Isi form booking dan konfirmasi
5. **Payment** → Upload bukti transfer atau scan QR
6. **Confirmation** → Terima konfirmasi via email
7. **Ticket Access** → Download ticket digital/PDF
8. **Event Entry** → Scan QR code di entrance

### Admin Journey
1. **Login** → Akses admin panel Filament
2. **Event Management** → Create/edit events
3. **Booking Monitor** → Review incoming bookings
4. **Payment Verification** → Approve/reject payments
5. **Ticket Validation** → Use scanner untuk validasi entry
6. **Analytics** → Monitor performance metrics

## 🎛️ Admin Panel

Admin panel dapat diakses di `/admin` menggunakan Filament 3.x:

### Dashboard Features
- **Statistics Overview**: Total events, bookings, revenue
- **Recent Activities**: Latest bookings dan payments
- **Quick Actions**: Create event, verify payments

### Resource Management
- **Events Resource**: CRUD dengan form builder
- **Bookings Resource**: Monitoring dan approval
- **Users Resource**: User management
- **Categories Resource**: Event categorization

### Access Control
```php
// Admin middleware configuration
'auth' => [
    'guard' => 'web',
    'pages' => [
        'login' => \Filament\Pages\Auth\Login::class,
    ],
],
```

## 🧪 Testing

### Running Tests
```bash
# Run all tests
composer test

# Run specific test
php artisan test --filter BookingTest

# Run with coverage
php artisan test --coverage
```

### Test Structure
```
tests/
├── Feature/
│   ├── BookingTest.php
│   ├── EventTest.php
│   └── PaymentTest.php
└── Unit/
    ├── BookingServiceTest.php
    └── QRCodeServiceTest.php
```

### Example Test
```php
public function test_user_can_create_booking()
{
    $user = User::factory()->create();
    $event = Event::factory()->create(['quota' => 100]);
    
    $response = $this->actingAs($user)
        ->post("/bookings/events/{$event->id}", [
            'quantity' => 2,
            'booker_name' => 'Test User',
            'booker_email' => 'test@example.com',
        ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('bookings', [
        'user_id' => $user->id,
        'event_id' => $event->id,
    ]);
}
```

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Set up mail configuration
- [ ] Configure file storage (S3/local)
- [ ] Set up SSL certificate
- [ ] Configure queue worker
- [ ] Set up monitoring (logs, performance)

### Docker Deployment
```dockerfile
FROM php:8.2-fpm-alpine

WORKDIR /var/www

COPY . /var/www
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

EXPOSE 9000
```

### Server Requirements
- **PHP**: 8.2+ dengan extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- **Web Server**: Nginx/Apache dengan PHP-FPM
- **Database**: MySQL 8.0+ atau PostgreSQL 13+
- **Cache**: Redis untuk production
- **Queue**: Supervisor untuk queue workers

## 📊 Performance Optimization

### Database Optimization
```php
// Use eager loading
$events = Event::with(['category', 'organizer', 'bookings'])->get();

// Database indexing
Schema::table('bookings', function (Blueprint $table) {
    $table->index(['event_id', 'status', 'booking_date']);
});
```

### Caching Strategy
```php
// Cache expensive queries
$popularEvents = Cache::remember('popular_events', 3600, function () {
    return Event::withCount('bookings')
        ->orderBy('bookings_count', 'desc')
        ->take(10)
        ->get();
});
```

### Asset Optimization
```bash
# Optimize images
npm install imagemin-cli -g
imagemin public/images/* --out-dir=public/images/optimized

# Minify CSS/JS
npm run build
```

## 🔒 Security

### Security Features
- **CSRF Protection**: All forms protected dengan Laravel CSRF
- **XSS Prevention**: Blade template escaping
- **SQL Injection**: Eloquent ORM prevents SQL injection
- **Authentication**: Secure password hashing dengan bcrypt
- **Authorization**: Gate dan Policy untuk access control
- **File Upload**: Validated file types dan sizes

### Security Best Practices
```php
// Input validation
$request->validate([
    'email' => 'required|email|max:255',
    'booking_code' => 'required|string|max:20|regex:/^[A-Z0-9]+$/',
]);

// File upload security
$request->validate([
    'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
]);
```

## 🤝 Contributing

### Development Workflow
1. Fork repository
2. Create feature branch: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push branch: `git push origin feature/amazing-feature`
5. Open Pull Request

### Code Standards
- Follow PSR-12 coding standards
- Use Laravel best practices
- Write tests for new features
- Update documentation

### Development Tools
```bash
# Code formatting
./vendor/bin/pint

# Static analysis
./vendor/bin/phpstan analyse

# Testing
php artisan test
```

## 📞 Support

### Documentation
- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

### Community
- [Laravel Indonesia](https://t.me/laravelindonesia)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/laravel)
- [GitHub Issues](https://github.com/alfrdzley/event-management/issues)

### Contact
- **Developer**: [@alfrdzley](https://github.com/alfrdzley)
- **Email**: support@eventmanagement.com
- **Website**: [eventmanagement.com](https://eventmanagement.com)

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

**Made with ❤️ by alfrdzley**

*Event Management System - Making event organization simple and efficient*
