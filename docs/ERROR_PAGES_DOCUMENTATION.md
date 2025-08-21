# Error Pages Documentation

## Overview
This documentation covers the custom error pages and fallback routes implemented for the Event Management application.

## Error Pages Created

### 1. 404 - Page Not Found (`errors/404.blade.php`)
**Features:**
- Modern gradient background with floating animation
- Clear error message and navigation options
- "Go Back" and "Go Home" buttons
- Link to browse events
- Contact support information
- Responsive design

**Usage:**
- Automatically triggered when a route is not found
- Accessible via fallback route

### 2. 403 - Access Denied (`errors/403.blade.php`)
**Features:**
- Lock icon with pulse animation
- Different actions based on authentication status
- For authenticated users: Shows current user info with logout option
- For unauthenticated users: Shows login/register options
- Smart action buttons with appropriate redirects

**Usage:**
- Triggered when user lacks permission to access a resource
- Handled via exception handler in `bootstrap/app.php`

### 3. 500 - Internal Server Error (`errors/500.blade.php`)
**Features:**
- Warning icon with red gradient
- User-friendly error message
- Automatic error reporting notification
- Refresh button and navigation options
- Debug information in development mode

**Usage:**
- Triggered for server errors (status codes 500+)
- Only shown in production mode

### 4. 503 - Service Unavailable (`errors/503.blade.php`)
**Features:**
- Maintenance icon with yellow/orange gradient
- Maintenance mode messaging
- Social media links for updates
- Estimated downtime information

**Usage:**
- Used during maintenance mode
- Can be triggered with `php artisan down`

### 5. 419 - Session Expired (`errors/419.blade.php`)
**Features:**
- Clock icon representing time expiration
- CSRF token mismatch explanation
- Refresh functionality
- Context-aware navigation based on auth status

**Usage:**
- Triggered when CSRF token expires or mismatches
- Common during long form submissions

### 6. Authentication Fallback (`auth.blade.php`)
**Features:**
- Clean authentication interface
- Animated background particles
- Smart detection of user authentication status
- Multiple action options (Login, Register, Browse as Guest)
- Terms and privacy policy links

**Usage:**
- Fallback page for authentication errors
- Can be used as a general auth landing page
- Accessible via `/auth` route

### 7. Error Layout Template (`errors/layout.blade.php`)
**Features:**
- Reusable layout for consistent error page design
- Blade sections for customization
- Animated illustrations
- Debug information in development mode
- Responsive design framework

**Usage:**
- Base template for creating new error pages
- Extends with specific error content

## Implementation Details

### Exception Handling
Exception handling is configured in `bootstrap/app.php`:

```php
->withExceptions(function (Exceptions $exceptions): void {
    // Authentication errors (401)
    $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
        // Redirect to auth fallback page
    });

    // Authorization errors (403)
    $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
        // Show 403 error page
    });

    // CSRF token mismatch (419)
    $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
        // Show 419 error page
    });

    // 404 errors
    $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
        // Show 404 error page
    });

    // 500 errors
    $exceptions->render(function (\Throwable $e, $request) {
        // Show 500 error page in production
    });
});
```

### Fallback Routes
Defined in `routes/web.php`:

```php
// Authentication fallback route
Route::get('/auth', function () {
    return view('auth');
})->name('auth.fallback');

// 404 fallback route - must be at the end
Route::fallback(function () {
    if (request()->expectsJson()) {
        return response()->json(['message' => 'Not Found'], 404);
    }
    return response()->view('errors.404', [], 404);
});
```

## Design Features

### Animations
- **404**: Floating animation for the illustration
- **403**: Pulse animation for the lock icon
- **500**: Bounce animation for warning icon
- **Auth**: Floating particles background animation

### Color Schemes
- **404**: Blue gradient (lost/navigation theme)
- **403**: Red/orange gradient (warning/danger theme)
- **500**: Red/pink gradient (error/danger theme)
- **503**: Yellow/orange gradient (maintenance/warning theme)
- **419**: Orange/red gradient (time/expiration theme)
- **Auth**: Blue gradient (trust/professional theme)

### Responsive Design
- Mobile-first approach
- Flexible button layouts
- Readable typography on all devices
- Touch-friendly interaction areas

## Testing Error Pages

### 404 Error
```bash
# Visit any non-existent route
curl http://localhost/non-existent-page
```

### 403 Error
```php
// In a controller, throw authorization exception
throw new \Illuminate\Auth\Access\AuthorizationException('Access denied');
```

### 500 Error
```php
// Trigger server error in development
throw new \Exception('Test server error');
```

### 419 Error
```html
<!-- Submit form without CSRF token -->
<form method="POST" action="/test">
    <!-- Missing @csrf -->
    <button type="submit">Submit</button>
</form>
```

### Auth Fallback
```bash
# Visit auth fallback page
curl http://localhost/auth
```

## Customization

### Adding New Error Pages
1. Create new blade file in `resources/views/errors/`
2. Extend `errors.layout` or create standalone
3. Add exception handling in `bootstrap/app.php` if needed

### Modifying Existing Pages
- Edit blade files in `resources/views/errors/`
- Customize sections: title, code, message, icon, etc.
- Update animations and styling in `<style>` sections

### Configuration
- Debug mode controls error detail visibility
- App name is pulled from config
- Routes can be customized in `routes/web.php`

## Security Considerations

### Information Disclosure
- Debug information only shown in development
- Generic error messages in production
- No sensitive data in error responses

### CSRF Protection
- 419 page guides users to refresh forms
- Proper token handling messaging

### Authentication Flow
- Secure fallback to auth page
- No credential exposure in error states
- Proper logout functionality

## Accessibility

### Screen Reader Support
- Semantic HTML structure
- Proper heading hierarchy
- Alt text for icons and illustrations

### Keyboard Navigation
- Focus management on interactive elements
- Logical tab order
- Accessible button states

### Visual Design
- High contrast color schemes
- Readable font sizes
- Clear visual hierarchy

## Browser Compatibility
- Modern CSS features with fallbacks
- JavaScript enhancement (progressive)
- Cross-browser animation support
- Mobile browser optimization

## Performance
- Minimal external dependencies
- Optimized animations
- Efficient CSS and JavaScript
- Fast page load times

This error handling system provides a professional and user-friendly experience while maintaining security and accessibility standards.
