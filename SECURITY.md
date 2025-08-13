# 🔒 Security Guide - Event Management System

## 🛡️ Application Security

### 1. Authentication & Authorization

#### Laravel Breeze Security Enhancements
```php
// config/auth.php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
],

// Password policy
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
    ],
],
```

#### Strong Password Policy
```php
// app/Rules/StrongPassword.php
class StrongPassword implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value);
    }
    
    public function message()
    {
        return 'Password must contain at least 8 characters, including uppercase, lowercase, number, and special character.';
    }
}

// app/Http/Requests/Auth/RegisterRequest.php
public function rules()
{
    return [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'phone' => ['required', 'string', 'max:20', 'unique:users'],
        'password' => ['required', 'confirmed', new StrongPassword()],
    ];
}
```

#### Two-Factor Authentication
```php
// Install Laravel Fortify for 2FA
composer require laravel/fortify

// app/Models/User.php
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use TwoFactorAuthenticatable;
    
    protected $fillable = [
        'name', 'email', 'phone', 'password', 'two_factor_secret', 'two_factor_recovery_codes'
    ];
    
    protected $hidden = [
        'password', 'remember_token', 'two_factor_recovery_codes', 'two_factor_secret'
    ];
}
```

### 2. Input Validation & Sanitization

#### Form Request Validation
```php
// app/Http/Requests/BookingRequest.php
class BookingRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }
    
    public function rules()
    {
        return [
            'event_id' => ['required', 'exists:events,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'attendee_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'attendee_email' => ['required', 'email', 'max:255'],
            'attendee_phone' => ['required', 'string', 'max:20', 'regex:/^[\+]?[0-9\-\(\)\s]+$/'],
        ];
    }
    
    public function messages()
    {
        return [
            'attendee_name.regex' => 'Name can only contain letters and spaces.',
            'attendee_phone.regex' => 'Please enter a valid phone number.',
        ];
    }
    
    protected function prepareForValidation()
    {
        $this->merge([
            'attendee_name' => strip_tags($this->attendee_name),
            'attendee_email' => filter_var($this->attendee_email, FILTER_SANITIZE_EMAIL),
            'attendee_phone' => preg_replace('/[^0-9\+\-\(\)\s]/', '', $this->attendee_phone),
        ]);
    }
}
```

#### CSRF Protection
```php
// All forms must include CSRF token
// resources/views/components/form.blade.php
<form method="POST" action="{{ $action }}">
    @csrf
    {{ $slot }}
</form>

// Verify CSRF in middleware (enabled by default)
// app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\VerifyCsrfToken::class,
    ],
];
```

#### XSS Prevention
```php
// Use Blade's automatic escaping
// resources/views/events/show.blade.php
<h1>{{ $event->title }}</h1> <!-- Automatically escaped -->
<p>{{ $event->description }}</p>

// For trusted HTML content, use explicit escaping
<div>{!! Purifier::clean($event->content) !!}</div>

// Install HTML Purifier for safe HTML
composer require mews/purifier
```

### 3. SQL Injection Prevention

#### Eloquent Query Security
```php
// app/Models/Events.php
class Events extends Model
{
    // Use query builder with parameter binding
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category); // Safe
    }
    
    // Avoid raw queries, but if needed, use bindings
    public function getEventsByDateRange($start, $end)
    {
        return DB::select('SELECT * FROM events WHERE event_date BETWEEN ? AND ?', [$start, $end]);
    }
    
    // Never concatenate user input directly
    // WRONG: "SELECT * FROM events WHERE title = '" . $title . "'"
    // CORRECT: Use Eloquent or parameter binding
}
```

#### Mass Assignment Protection
```php
// app/Models/User.php
class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'phone', 'password'
    ];
    
    protected $guarded = [
        'id', 'is_admin', 'email_verified_at'
    ];
    
    protected $hidden = [
        'password', 'remember_token'
    ];
}

// app/Models/Booking.php
class Booking extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'quantity', 'total_price',
        'attendee_name', 'attendee_email', 'attendee_phone'
    ];
    
    protected $guarded = [
        'id', 'booking_status', 'payment_status'
    ];
}
```

### 4. File Upload Security

#### Secure File Handling
```php
// app/Http/Controllers/FileUploadController.php
class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048', // 2MB max
            ]
        ]);
        
        $file = $request->file('file');
        
        // Generate secure filename
        $filename = Str::random(32) . '.' . $file->getClientOriginalExtension();
        
        // Store in non-public directory
        $path = $file->storeAs('uploads', $filename, 'private');
        
        // Validate file content
        $this->validateFileContent($file);
        
        return response()->json(['path' => $path]);
    }
    
    private function validateFileContent($file)
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'application/pdf'];
        $fileMime = $file->getMimeType();
        
        if (!in_array($fileMime, $allowedMimes)) {
            throw new ValidationException('Invalid file type');
        }
        
        // Additional content validation
        if (str_starts_with($fileMime, 'image/')) {
            $imageInfo = getimagesize($file->getPathname());
            if (!$imageInfo) {
                throw new ValidationException('Invalid image file');
            }
        }
    }
}
```

### 5. Rate Limiting & Throttling

#### API Rate Limiting
```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'api' => [
        'throttle:api',
    ],
];

protected $routeMiddleware = [
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
];

// routes/web.php
Route::middleware(['throttle:10,1'])->group(function () {
    Route::post('/booking', [BookingController::class, 'store']);
    Route::post('/payment', [PaymentController::class, 'process']);
});

// Login throttling
Route::middleware(['throttle:5,1'])->group(function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});
```

#### Custom Rate Limiting
```php
// app/Http/Middleware/CustomThrottle.php
class CustomThrottle
{
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            Log::warning('Rate limit exceeded', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl()
            ]);
            
            return response()->json([
                'message' => 'Too many attempts. Try again in ' . $seconds . ' seconds.'
            ], 429);
        }
        
        RateLimiter::hit($key, $decayMinutes * 60);
        
        return $next($request);
    }
    
    protected function resolveRequestSignature($request)
    {
        return sha1($request->ip() . '|' . $request->route()->getName());
    }
}
```

### 6. Session Security

#### Secure Session Configuration
```php
// config/session.php
return [
    'driver' => env('SESSION_DRIVER', 'redis'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => true,
    'encrypt' => true,
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => 'sessions',
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env('SESSION_COOKIE', Str::slug(env('APP_NAME', 'laravel'), '_').'_session'),
    'path' => '/',
    'domain' => env('SESSION_DOMAIN'),
    'secure' => env('SESSION_SECURE_COOKIE', true),
    'http_only' => true,
    'same_site' => 'lax',
    'partitioned' => false,
];
```

#### Session Regeneration
```php
// app/Http/Controllers/Auth/AuthenticatedSessionController.php
public function store(LoginRequest $request)
{
    $request->authenticate();
    
    // Regenerate session ID to prevent session fixation
    $request->session()->regenerate();
    
    // Log successful login
    Log::info('User logged in', [
        'user_id' => auth()->id(),
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent()
    ]);
    
    return redirect()->intended(RouteServiceProvider::HOME);
}

public function destroy(Request $request)
{
    // Log logout
    Log::info('User logged out', [
        'user_id' => auth()->id(),
        'ip' => $request->ip()
    ]);
    
    Auth::guard('web')->logout();
    
    // Invalidate session
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect('/');
}
```

### 7. Environment Security

#### Secure Environment Configuration
```bash
# .env.example
APP_NAME="Event Management System"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_management
DB_USERNAME=
DB_PASSWORD=

# Security
SESSION_SECURE_COOKIE=true
SANCTUM_STATEFUL_DOMAINS=your-domain.com
TRUSTED_PROXIES=*

# File permissions
chmod 600 .env
chown www-data:www-data .env
```

#### Security Headers
```php
// app/Http/Middleware/SecurityHeaders.php
class SecurityHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // Content Security Policy
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net; " .
               "style-src 'self' 'unsafe-inline' fonts.googleapis.com; " .
               "font-src 'self' fonts.gstatic.com; " .
               "img-src 'self' data: https:; " .
               "connect-src 'self'";
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        return $response;
    }
}
```

### 8. Error Handling & Logging

#### Secure Error Handling
```php
// app/Exceptions/Handler.php
class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];
    
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    
    public function render($request, Throwable $exception)
    {
        // Don't expose sensitive information in production
        if (app()->environment('production')) {
            if ($exception instanceof ValidationException) {
                return parent::render($request, $exception);
            }
            
            // Log the actual error
            Log::error('Application error', [
                'exception' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);
            
            // Return generic error to user
            return response()->view('errors.500', [], 500);
        }
        
        return parent::render($request, $exception);
    }
}
```

#### Security Audit Logging
```php
// app/Services/SecurityAuditLogger.php
class SecurityAuditLogger
{
    public function logSuspiciousActivity($type, $details)
    {
        Log::channel('security')->warning("Suspicious activity: {$type}", [
            'details' => $details,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()
        ]);
        
        // Send alert if critical
        if ($this->isCritical($type)) {
            $this->sendSecurityAlert($type, $details);
        }
    }
    
    private function isCritical($type)
    {
        return in_array($type, [
            'multiple_failed_logins',
            'sql_injection_attempt',
            'xss_attempt',
            'file_upload_attack'
        ]);
    }
    
    private function sendSecurityAlert($type, $details)
    {
        // Send to security team
        Mail::to(config('security.alert_email'))
            ->send(new SecurityAlertMail($type, $details));
    }
}
```

## 🔐 Infrastructure Security

### 1. Server Hardening

#### Firewall Configuration
```bash
# UFW firewall setup
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw enable

# Fail2ban for intrusion prevention
sudo apt install fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

#### Fail2ban Configuration
```ini
# /etc/fail2ban/jail.local
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 3
ignoreip = 127.0.0.1/8

[sshd]
enabled = true
port = ssh
filter = sshd
logpath = /var/log/auth.log

[nginx-http-auth]
enabled = true
filter = nginx-http-auth
port = http,https
logpath = /var/log/nginx/error.log

[nginx-limit-req]
enabled = true
filter = nginx-limit-req
port = http,https
logpath = /var/log/nginx/error.log
maxretry = 10
```

### 2. SSL/TLS Configuration

#### Strong SSL Configuration
```nginx
# /etc/nginx/sites-available/event-management
server {
    listen 443 ssl http2;
    server_name your-domain.com;
    
    # SSL Configuration
    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/private.key;
    
    # Strong SSL settings
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;
    
    # OCSP stapling
    ssl_stapling on;
    ssl_stapling_verify on;
    
    # HSTS
    add_header Strict-Transport-Security "max-age=63072000; includeSubDomains; preload" always;
}
```

### 3. Database Security

#### MySQL Security Configuration
```sql
-- Remove test database and anonymous users
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.user WHERE User='';
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');

-- Create application user with limited privileges
CREATE USER 'event_app'@'localhost' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON event_management.* TO 'event_app'@'localhost';
FLUSH PRIVILEGES;
```

#### Database Connection Security
```php
// config/database.php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'options' => [
        PDO::ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        PDO::ATTR_SSL_VERIFY_SERVER_CERT => false,
    ],
],
```

## 🔍 Security Monitoring

### 1. Intrusion Detection
```bash
# Install AIDE for file integrity monitoring
sudo apt install aide
sudo aideinit
sudo mv /var/lib/aide/aide.db.new /var/lib/aide/aide.db

# Create daily check cron job
echo "0 2 * * * /usr/bin/aide --check" | sudo crontab -
```

### 2. Log Monitoring
```bash
# Install log monitoring tools
sudo apt install logwatch
sudo apt install chkrootkit
sudo apt install rkhunter

# Configure automated security scans
echo "0 3 * * * /usr/bin/chkrootkit" | sudo crontab -
echo "0 4 * * * /usr/bin/rkhunter --check --skip-keypress" | sudo crontab -
```

### 3. Application Security Monitoring
```php
// app/Console/Commands/SecurityScan.php
class SecurityScan extends Command
{
    protected $signature = 'security:scan';
    
    public function handle()
    {
        $this->info('Running security scan...');
        
        // Check for suspicious activities
        $this->checkFailedLogins();
        $this->checkUnusualTraffic();
        $this->checkFileIntegrity();
        
        $this->info('Security scan completed.');
    }
    
    private function checkFailedLogins()
    {
        $failedLogins = DB::table('failed_jobs')
            ->where('created_at', '>', now()->subHour())
            ->count();
            
        if ($failedLogins > 50) {
            $this->warn("High number of failed logins: {$failedLogins}");
        }
    }
}
```

---

*Security is not a feature, it's a foundation! 🔒*
