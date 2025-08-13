# 🎯 Performance Optimization Guide

## ⚡ Application Performance

### 1. Laravel Optimization

#### Caching Strategy
```bash
# Production cache commands
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Clear all caches when needed
php artisan optimize:clear
```

#### Autoloader Optimization
```bash
# Optimize Composer autoloader
composer install --optimize-autoloader --no-dev

# Generate optimized class loader
php artisan optimize
```

#### Session & Cache Configuration
```php
// config/cache.php
'default' => env('CACHE_DRIVER', 'redis'),

'stores' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
    ],
],

// config/session.php
'driver' => env('SESSION_DRIVER', 'redis'),
'connection' => 'session',
```

### 2. Database Optimization

#### Query Optimization
```php
// app/Models/Events.php
class Events extends Model
{
    // Eager loading relationships
    protected $with = ['bookings'];
    
    // Index commonly queried fields
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('event_date', '>=', now());
    }
    
    // Use database indexes
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->index(['status', 'event_date']);
            $table->index('created_at');
        });
    }
}
```

#### Database Indexes
```sql
-- Create performance indexes
CREATE INDEX idx_events_status_date ON events(status, event_date);
CREATE INDEX idx_bookings_user_id ON bookings(user_id);
CREATE INDEX idx_bookings_event_status ON bookings(event_id, booking_status);
CREATE INDEX idx_users_email ON users(email);

-- Composite indexes for common queries
CREATE INDEX idx_bookings_user_event ON bookings(user_id, event_id);
CREATE INDEX idx_events_date_status ON events(event_date, status);
```

#### Query Monitoring
```php
// app/Providers/AppServiceProvider.php
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;

public function boot()
{
    if (app()->environment('local')) {
        DB::listen(function (QueryExecuted $query) {
            if ($query->time > 1000) { // Log slow queries (>1s)
                Log::warning('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . 'ms'
                ]);
            }
        });
    }
}
```

### 3. Frontend Optimization

#### Asset Compilation
```javascript
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs', '@tailwindcss/forms'],
                    filament: ['@filament/tables', '@filament/forms'],
                }
            }
        },
        cssCodeSplit: true,
        minify: 'esbuild',
        target: 'es2015'
    }
});
```

#### Image Optimization
```php
// app/Services/ImageOptimizationService.php
class ImageOptimizationService
{
    public function optimizeQRCode($qrCodePath)
    {
        $image = imagecreatefrompng($qrCodePath);
        
        // Enable compression
        imagepng($image, $qrCodePath, 6);
        imagedestroy($image);
        
        return $qrCodePath;
    }
    
    public function createWebPVersion($imagePath)
    {
        $image = imagecreatefromjpeg($imagePath);
        $webpPath = str_replace('.jpg', '.webp', $imagePath);
        
        imagewebp($image, $webpPath, 80);
        imagedestroy($image);
        
        return $webpPath;
    }
}
```

## 🚀 Server Performance

### 1. PHP-FPM Optimization
```ini
; /etc/php/8.2/fpm/pool.d/www.conf
[www]
user = www-data
group = www-data
listen = /run/php/php8.2-fpm.sock
listen.owner = www-data
listen.group = www-data

; Process management
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500

; Performance tuning
request_terminate_timeout = 60
request_slowlog_timeout = 30
slowlog = /var/log/php-fpm-slow.log

; Resource limits
rlimit_files = 65536
rlimit_core = 0

; Security
security.limit_extensions = .php
```

### 2. Nginx Optimization
```nginx
# /etc/nginx/nginx.conf
worker_processes auto;
worker_rlimit_nofile 65535;

events {
    worker_connections 1024;
    use epoll;
    multi_accept on;
}

http {
    # Basic Settings
    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 15;
    types_hash_max_size 2048;
    client_max_body_size 16M;

    # Gzip Settings
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types
        text/plain
        text/css
        text/xml
        text/javascript
        application/json
        application/javascript
        application/xml+rss;

    # Caching
    open_file_cache max=200000 inactive=20s;
    open_file_cache_valid 30s;
    open_file_cache_min_uses 2;
    open_file_cache_errors on;

    # Rate Limiting
    limit_req_zone $binary_remote_addr zone=login:10m rate=5r/m;
    limit_req_zone $binary_remote_addr zone=api:10m rate=10r/s;
}
```

### 3. MySQL Optimization
```ini
# /etc/mysql/mysql.conf.d/mysqld.cnf
[mysqld]
# Memory settings
innodb_buffer_pool_size = 2G
innodb_log_buffer_size = 16M
key_buffer_size = 128M
max_connections = 100
thread_cache_size = 16

# InnoDB settings
innodb_file_per_table = 1
innodb_flush_log_at_trx_commit = 2
innodb_log_file_size = 512M
innodb_flush_method = O_DIRECT

# Query cache
query_cache_type = 1
query_cache_size = 128M
query_cache_limit = 2M

# Slow query log
slow_query_log = 1
long_query_time = 2
slow_query_log_file = /var/log/mysql/slow.log
log_queries_not_using_indexes = 1
```

### 4. Redis Configuration
```conf
# /etc/redis/redis.conf
maxmemory 1gb
maxmemory-policy allkeys-lru

# Persistence
save 900 1
save 300 10
save 60 10000

# Network
timeout 300
tcp-keepalive 300

# Performance
hash-max-ziplist-entries 512
hash-max-ziplist-value 64
list-max-ziplist-size -2
set-max-intset-entries 512
zset-max-ziplist-entries 128
zset-max-ziplist-value 64
```

## 📊 Monitoring & Profiling

### 1. Laravel Telescope
```bash
# Install Telescope for development
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

```php
// config/telescope.php
'watchers' => [
    Watchers\QueryWatcher::class => [
        'enabled' => env('TELESCOPE_QUERY_WATCHER', true),
        'slow' => 100, // ms
    ],
    
    Watchers\RequestWatcher::class => [
        'enabled' => env('TELESCOPE_REQUEST_WATCHER', true),
        'size_limit' => 64,
    ],
],
```

### 2. Performance Monitoring
```php
// app/Http/Middleware/PerformanceMonitoring.php
class PerformanceMonitoring
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);
        $response = $next($request);
        $duration = microtime(true) - $start;
        
        if ($duration > 1.0) { // Log slow requests
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'duration' => $duration . 's',
                'memory' => memory_get_peak_usage(true)
            ]);
        }
        
        return $response;
    }
}
```

### 3. Database Query Optimization
```php
// app/Console/Commands/AnalyzeQueries.php
class AnalyzeQueries extends Command
{
    protected $signature = 'app:analyze-queries';
    
    public function handle()
    {
        // Enable query logging
        DB::enableQueryLog();
        
        // Run typical operations
        $events = Events::with('bookings.user')->active()->get();
        $bookings = Booking::with('event', 'user')->recent()->get();
        
        // Analyze queries
        $queries = DB::getQueryLog();
        
        foreach ($queries as $query) {
            if ($query['time'] > 100) { // >100ms
                $this->warn("Slow query: {$query['query']} ({$query['time']}ms)");
            }
        }
    }
}
```

## 🎯 Load Balancing

### 1. Nginx Load Balancer
```nginx
# /etc/nginx/conf.d/load-balancer.conf
upstream event_management_backend {
    least_conn;
    server 192.168.1.10:80 weight=3 max_fails=3 fail_timeout=30s;
    server 192.168.1.11:80 weight=3 max_fails=3 fail_timeout=30s;
    server 192.168.1.12:80 weight=2 max_fails=3 fail_timeout=30s backup;
}

server {
    listen 80;
    server_name event-management.com;
    
    location / {
        proxy_pass http://event_management_backend;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        
        # Health check
        proxy_next_upstream error timeout invalid_header http_500 http_502 http_503;
        proxy_connect_timeout 2s;
        proxy_send_timeout 30s;
        proxy_read_timeout 30s;
    }
}
```

### 2. Session Sharing
```php
// config/session.php
'driver' => 'redis',
'connection' => 'session',
'store' => 'session',

// config/database.php
'redis' => [
    'session' => [
        'url' => env('REDIS_SESSION_URL'),
        'host' => env('REDIS_SESSION_HOST', '127.0.0.1'),
        'password' => env('REDIS_SESSION_PASSWORD'),
        'port' => env('REDIS_SESSION_PORT', '6379'),
        'database' => env('REDIS_SESSION_DB', '1'),
    ],
],
```

## 🔄 Caching Strategies

### 1. Model Caching
```php
// app/Models/Events.php
class Events extends Model
{
    public function scopeCached($query)
    {
        return Cache::remember(
            'events_active_' . md5($query->toSql()),
            now()->addMinutes(30),
            fn() => $query->get()
        );
    }
    
    public static function getPopular()
    {
        return Cache::remember('events_popular', now()->addHour(), function() {
            return static::withCount('bookings')
                        ->orderBy('bookings_count', 'desc')
                        ->limit(10)
                        ->get();
        });
    }
}
```

### 2. View Caching
```php
// app/Http/Controllers/EventController.php
public function index()
{
    $events = Cache::remember('events_list', now()->addMinutes(15), function() {
        return Events::with('bookings')->active()->paginate(10);
    });
    
    return view('events.index', compact('events'));
}
```

### 3. Fragment Caching
```blade
{{-- resources/views/events/index.blade.php --}}
@cache('events_sidebar', now()->addHour())
    <div class="sidebar">
        @foreach(Events::getPopular() as $event)
            <div class="popular-event">{{ $event->title }}</div>
        @endforeach
    </div>
@endcache
```

## 📈 Performance Metrics

### 1. Key Performance Indicators
```php
// app/Services/PerformanceMetrics.php
class PerformanceMetrics
{
    public function getMetrics()
    {
        return [
            'response_time' => $this->getAverageResponseTime(),
            'memory_usage' => memory_get_peak_usage(true),
            'database_queries' => $this->getQueryCount(),
            'cache_hit_ratio' => $this->getCacheHitRatio(),
            'active_users' => $this->getActiveUsers(),
        ];
    }
    
    private function getAverageResponseTime()
    {
        return Cache::get('avg_response_time', 0);
    }
    
    private function getCacheHitRatio()
    {
        $hits = Cache::get('cache_hits', 0);
        $misses = Cache::get('cache_misses', 0);
        
        return $hits + $misses > 0 ? ($hits / ($hits + $misses)) * 100 : 0;
    }
}
```

### 2. Performance Monitoring Dashboard
```php
// app/Http/Controllers/Admin/PerformanceController.php
class PerformanceController extends Controller
{
    public function dashboard()
    {
        $metrics = app(PerformanceMetrics::class)->getMetrics();
        
        return view('admin.performance', compact('metrics'));
    }
    
    public function optimize()
    {
        // Clear all caches
        Artisan::call('optimize:clear');
        
        // Rebuild caches
        Artisan::call('optimize');
        
        return redirect()->back()->with('success', 'Performance optimized!');
    }
}
```

---

*Optimize for speed and scale! ⚡*
