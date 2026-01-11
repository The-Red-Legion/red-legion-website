<?php
declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Application bootstrap file
 * - Starts secure sessions
 * - Loads environment variables (from .env in dev)
 * - Establishes PDO database connection
 * - Sets some typical defaults (error handling, timezone)
 */

// 2. Session setup (with secure defaults)
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,    // prevent JS from reading session cookie
        'cookie_secure'   => isset($_SERVER['HTTPS']), // only over HTTPS
        'cookie_samesite' => 'Lax',   // mitigate CSRF
    ]);
}

// 3. Error reporting & timezone
error_reporting(E_ALL);
ini_set('display_errors', ($_ENV['APP_ENV'] ?? 'prod') !== 'prod' ? '1' : '0');
date_default_timezone_set($_ENV['APP_TZ'] ?? 'UTC');

// 4. Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->safeLoad();
}
$env = $_ENV + $_SERVER; // Apache mod_php sometimes populates $_SERVER

// 5. PDO database connection (strict + flexible)
$require = function (array $keys, array $env): void {
    foreach ($keys as $k) {
        // allow DB_PASS to be empty string but still "set"
        if (!array_key_exists($k, $env) || ($k !== 'DB_PASS' && trim((string)$env[$k]) === '')) {
            throw new RuntimeException("Missing required environment variable: {$k}");
        }
    }
};

try {
    // Require essentials. Allow either host or unix socket.
    $hasSocket = !empty($env['DB_SOCKET']);
    if ($hasSocket) {
        $require(['DB_SOCKET', 'DB_NAME', 'DB_USER', 'DB_PASS'], $env);
    } else {
        $require(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'], $env);
    }

    // Build DSN (host/port or unix socket)
    if ($hasSocket) {
        $dsn = sprintf(
            'mysql:unix_socket=%s;dbname=%s;charset=utf8mb4',
            $env['DB_SOCKET'],
            $env['DB_NAME']
        );
    } else {
        $port = isset($env['DB_PORT']) && (string)$env['DB_PORT'] !== '' ? (int)$env['DB_PORT'] : 3306;
        $dsn  = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $env['DB_HOST'],
            $port,
            $env['DB_NAME']
        );
    }

    // Optional SSL (if you use managed MySQL that requires SSL)
    // Provide any of these in the environment to enable SSL:
    // DB_SSL_CA, DB_SSL_CERT, DB_SSL_KEY
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    if (!empty($env['DB_SSL_CA']) || !empty($env['DB_SSL_CERT']) || !empty($env['DB_SSL_KEY'])) {
        // MySQL-specific constants; suppress if extension not loaded
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false; // tweak as needed
        if (!empty($env['DB_SSL_CA']))   { $options[PDO::MYSQL_ATTR_SSL_CA]   = $env['DB_SSL_CA']; }
        if (!empty($env['DB_SSL_CERT'])) { $options[PDO::MYSQL_ATTR_SSL_CERT] = $env['DB_SSL_CERT']; }
        if (!empty($env['DB_SSL_KEY']))  { $options[PDO::MYSQL_ATTR_SSL_KEY]  = $env['DB_SSL_KEY']; }
    }

    $pdo = new PDO($dsn, (string)$env['DB_USER'], (string)$env['DB_PASS'], $options);

    // Optional: align DB session time zone with app
    if (!empty($env['DB_TIMEZONE'])) {
        $stmt = $pdo->prepare("SET time_zone = :tz");
        $stmt->execute([':tz' => $env['DB_TIMEZONE']]);
    }
} catch (Throwable $e) {
    // Be verbose in dev, quiet in prod
    $isProd = ($env['APP_ENV'] ?? 'prod') === 'prod';
    if ($isProd) {
        error_log('Database bootstrap error: ' . $e->getMessage());
        die('Database connection failed.'); // generic message for users
    }
    // Dev: show the actual error to help diagnose
    die('Database connection failed: ' . $e->getMessage());
}


// 7. Return useful objects to entry points
return [
    'pdo'  => $pdo,
    'env'  => $env,
];
