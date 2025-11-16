<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/OrderDao.php';

class OrderService extends BaseService {
    public function __construct() {
        $dao = new OrderDao();
        parent::__construct($dao);
    }

    public function getByUserId($user_id) {
        return $this->dao->getByUserId($user_id);
    }

    public function add_item($order_id, $product_id, $qty) {
        return $this->dao->addItem($order_id, $product_id, $qty);
    }

    public function finalize($order_id) {
        return $this->dao->finalizeTotal($order_id);
    }

    public function update_status($order_id, $status) {
        return $this->dao->updateStatus($order_id, $status);
    }
}
?>
