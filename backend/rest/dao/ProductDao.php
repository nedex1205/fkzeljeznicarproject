<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseDao.php';

final class ProductDao extends BaseDao {

    public function __construct() {
        parent::__construct('products');
        $this->fields = ['name','price','category','img_url'];
    }

    
};