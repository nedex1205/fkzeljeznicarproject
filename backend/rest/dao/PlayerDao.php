<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseDao.php';

final class PlayerDao extends BaseDao {

    public function __construct() {
        parent::__construct();
        $this->table  = 'players';
        $this->fields = ['name','position','number_'];
    }

   
}
