<?php
declare(strict_types=1);

// =======================
// DEBUG
// =======================
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));


// =======================
// CONFIG CLASS (tvoja)
// =======================
class Config
{
    public static function DB_NAME()
    {
        return 'fkzeljeznicarpro'; 
    }

    public static function DB_PORT()
    {
        return 3306;
    }

    public static function DB_USER()
    {
        return 'root';
    }

    public static function DB_PASSWORD()
    {
        return '';
    }

    public static function DB_HOST()
    {
        return '127.0.0.1';
    }

    public static function JWT_SECRET()
    {
        return 'your_key_string';
    }
}


// =======================
// DB CONST DEFINITIONS
// (koriste se u DB.php i DAO sloju)
// =======================

define('DB_HOST', Config::DB_HOST());
define('DB_NAME', Config::DB_NAME());
define('DB_USER', Config::DB_USER());
define('DB_PASS', Config::DB_PASSWORD());
define('DB_PORT', Config::DB_PORT());


// =======================
// CORS (NE DIRAJ)
// =======================
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


// =======================
// JSON helper funkcije
// =======================
function json_input(): array
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function json_out($data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function not_found(): void
{
    json_out(['error' => 'not_found'], 404);
}

function bad_request(string $msg = 'bad_request'): void
{
    json_out(['error' => $msg], 400);
}
