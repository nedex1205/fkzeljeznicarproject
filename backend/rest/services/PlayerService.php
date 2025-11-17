<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/PlayerDao.php';

class PlayerService extends BaseService {
    public function __construct() {
        $dao = new PlayerDao();
        parent::__construct($dao);
    }

    public function get_by_position($position) {
        $all = $this->dao->list();
        return array_values(array_filter($all, fn($p) => strtoupper($p['position']) === strtoupper($position)));
    }
}
?>
