<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/ProductDao.php';

class ProductService extends BaseService {
    public function __construct() {
        $dao = new ProductDao();
        parent::__construct($dao);
    }

    private function validate_product($entity) {
        $name = trim((string)($entity['name'] ?? ''));
        $category = trim((string)($entity['category'] ?? ''));
        $price = $entity['price'] ?? null;

        if ($name === '') return "Product name is required.";
        if (strlen($name) < 2) return "Product name must be at least 2 characters.";

        if ($category === '') return "Category is required.";

        if ($price === null || $price === '') return "Price is required.";
        if (!is_numeric($price)) return "Price must be a number.";
        if ((float)$price <= 0) return "Price must be greater than 0.";

        $entity['name'] = $name;
        $entity['category'] = $category;
        $entity['price'] = (float)$price;

        return null; 
    }

    public function add($entity) {
        $err = $this->validate_product($entity);
        if ($err) throw new Exception($err);
        return parent::add($entity);
    }

    public function update($id, $entity) {
        $err = $this->validate_product($entity);
        if ($err) throw new Exception($err);
        return parent::update($id, $entity);
    }

    public function get_by_category($category) {
        $category = trim((string)$category);
        $all = $this->dao->list();
        return array_values(array_filter($all, fn($p) => strtolower($p['category']) === strtolower($category)));
    }
}
?>
