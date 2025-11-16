<?php
declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_NAME = 'fkzeljeznicarpro';  
const DB_USER = 'root';        
const DB_PASS = '';            

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
