<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseDao.php';

final class UserDao extends BaseDao {

    public function __construct() {
        parent::__construct();
        $this->table  = 'users';
        $this->fields = ['email','password_hash','full_name'];
    }


}
