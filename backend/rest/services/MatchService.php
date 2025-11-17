<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/MatchDao.php';

class MatchService extends BaseService {
    public function __construct() {
        $dao = new MatchDao();
        parent::__construct($dao);
    }

    public function get_by_competition($competition) {
        $all = $this->dao->list();
        return array_values(array_filter($all, fn($m) => strtolower($m['competition']) === strtolower($competition)));
    }
}
?>
