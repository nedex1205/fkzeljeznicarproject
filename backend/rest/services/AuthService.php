<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthService extends BaseService {
   private $auth_dao;
   public function __construct() {
       $this->auth_dao = new AuthDao();
       parent::__construct(new AuthDao);
   }


   public function get_user_by_email($email){
       return $this->auth_dao->get_user_by_email($email);
   }


   public function register($entity) {
    
    $email = trim((string)($entity['email'] ?? ''));
    $password = (string)($entity['password'] ?? '');

    if ($email === '' || $password === '') {
        return ['success' => false, 'error' => 'Email and password are required.'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'Invalid email format.'];
    }

    if (strlen($password) < 6) {
        return ['success' => false, 'error' => 'Password must be at least 6 characters long.'];
    }

    $email_exists = $this->auth_dao->get_user_by_email($email);
    if ($email_exists) {
        return ['success' => false, 'error' => 'Email already registered.'];
    }

    $entity['email'] = $email;
    $entity['password'] = password_hash($password, PASSWORD_BCRYPT);

    if (!isset($entity['role']) || $entity['role'] === '') {
        $entity['role'] = 'user';
    }

    $entity = parent::add($entity);

    unset($entity['password']);
    return ['success' => true, 'data' => $entity];
}

public function login($entity) {
    
    $email = trim((string)($entity['email'] ?? ''));
    $password = (string)($entity['password'] ?? '');

    if ($email === '' || $password === '') {
        return ['success' => false, 'error' => 'Email and password are required.'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'Invalid email format.'];
    }

    if (strlen($password) < 6) {
        return ['success' => false, 'error' => 'Invalid username or password.'];
    }

    $user = $this->auth_dao->get_user_by_email($email);
    if (!$user || !password_verify($password, $user['password'])) {
        return ['success' => false, 'error' => 'Invalid username or password.'];
    }

    unset($user['password']);

    $jwt_payload = [
        'user' => $user,
        'iat'  => time(),
        'exp'  => time() + (60 * 60 * 24)
    ];

    $token = JWT::encode($jwt_payload, Config::JWT_SECRET(), 'HS256');

    return ['success' => true, 'data' => array_merge($user, ['token' => $token])];
}

}
