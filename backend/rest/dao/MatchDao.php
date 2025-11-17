<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseDao.php';

final class MatchDao extends BaseDao {

    public function __construct() {
        parent::__construct();
        $this->table  = 'matches';
        $this->fields = ['date_','opponent','venue','competition'];
    }
}
