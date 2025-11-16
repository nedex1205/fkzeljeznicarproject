<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/ProductDao.php';

class ProductService extends BaseService {
    public function __construct() {
        $dao = new ProductDao();
        parent::__construct($dao);
    }

    public function get_by_category($category) {
        $all = $this->dao->list();
        return array_values(array_filter($all, fn($p) => strtolower($p['category']) === strtolower($category)));
    }
}
?>
