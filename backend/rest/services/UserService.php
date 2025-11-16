<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService {
    public function __construct() {
        $dao = new UserDao();
        parent::__construct($dao);
    }

    public function get_by_email($email) {
        $all = $this->dao->list();
        foreach ($all as $u) {
            if (strtolower($u['email']) === strtolower($email)) return $u;
        }
        return null;
    }
}
?>
