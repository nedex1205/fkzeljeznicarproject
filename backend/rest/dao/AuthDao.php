<?php
require_once __DIR__ . '/BaseDao.php';

class AuthDao extends BaseDao {

    public function __construct() {
        parent::__construct('users');
        $this->fields = ['email', 'password', 'role']; // dozvoljena polja (preporuka)
    }

    public function get_user_by_email($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email";
        return $this->query_unique($query, ['email' => $email]);
    }
}
