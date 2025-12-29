<?php
declare(strict_types=1);

header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authentication, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require __DIR__ . '/services/config.php';
require __DIR__ . '/services/DB.php';

require __DIR__ . '/dao/CrudDao.php';
require __DIR__ . '/dao/BaseDao.php';
require __DIR__ . '/dao/ProductDao.php';
require __DIR__ . '/dao/PlayerDao.php';
require __DIR__ . '/dao/MatchDao.php';
require __DIR__ . '/dao/UserDao.php';
require __DIR__ . '/dao/OrderDao.php';
require __DIR__ . '/dao/AuthDao.php';

require __DIR__ . '/services/BaseService.php';
require __DIR__ . '/services/ProductService.php';
require __DIR__ . '/services/PlayerService.php';
require __DIR__ . '/services/MatchService.php';
require __DIR__ . '/services/UserService.php';
require __DIR__ . '/services/OrderService.php';
require __DIR__ . '/services/AuthService.php';

Flight::set('productService', new ProductService());
Flight::set('playerService',  new PlayerService());
Flight::set('matchService',   new MatchService());
Flight::set('userService',    new UserService());
Flight::set('orderService',   new OrderService());


Flight::register('auth_service', AuthService::class);


Flight::route('/*', function () {

    $url = Flight::request()->url;
    $method = strtoupper(Flight::request()->method);

    // normalizuj
    $url = '/' . ltrim($url, '/');

// PUBLIC endpoints
    if ($method === 'OPTIONS') return true;
    if ($url === '/' || $url === '' ) return true;
    if (strpos($url, '/auth/login') === 0) return true;
    if (strpos($url, '/auth/register') === 0) return true;
    if (strpos($url, '/auth/ping') === 0) return true; // privremeno, možeš poslije maknuti


    
    try {
        $token = Flight::request()->getHeader("Authentication");
        if (!$token) {
            Flight::halt(401, "Missing authentication header");
        }

        $decoded = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
        $user = $decoded->user ?? null;

        if (!$user) {
            Flight::halt(401, "Invalid token payload");
        }

        Flight::set('user', $user);

        $is_write = in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true);
        $role = $user->role ?? 'user';

        if ($is_write && $role !== 'admin') {
            Flight::halt(403, "Admins only");
        }

        return true;

    } catch (Exception $e) {
        Flight::halt(401, $e->getMessage());
    }
});


require __DIR__ . '/routes/AuthRoute.php';
require __DIR__ . '/routes/products.php';
require __DIR__ . '/routes/players.php';
require __DIR__ . '/routes/matches.php';
require __DIR__ . '/routes/users.php';
require __DIR__ . '/routes/orders.php';

Flight::route('GET /', function () {
    echo 'FK Željezničar API radi ✅';
});

Flight::start();
