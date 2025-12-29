<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseDao.php';

final class OrderDao extends BaseDao {

    public function __construct() {
        parent::__construct('orders');
        $this->fields = ['user_id','status','total'];
    }


    public function get(int $id): ?array {
  
        $sql = "SELECT * FROM {$this->table} WHERE {$this->idColumn} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch();
        if (!$order) return null;

       
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = :id");
        $stmt->execute([':id' => $id]);
        $order['items'] = $stmt->fetchAll();

        return $order;
    }

 
    public function getByUserId(int $user_id): array {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :uid ORDER BY {$this->idColumn} DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $user_id]);
        return $stmt->fetchAll();
    }


    public function addItem(int $orderId, int $productId, int $qty): bool {
        
        $p = $this->db->prepare("SELECT price FROM products WHERE id = :pid");
        $p->execute([':pid' => $productId]);
        $row = $p->fetch();
        if (!$row) return false;

        $unitPrice = $row['price'];

        $sql = "INSERT INTO order_items (order_id, product_id, qty, unit_price)
                VALUES (:oid, :pid, :qty, :price)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':oid'   => $orderId,
            ':pid'   => $productId,
            ':qty'   => $qty,
            ':price' => $unitPrice
        ]);
    }

 
    public function finalizeTotal(int $orderId): bool {
        $sql = "UPDATE orders o
                JOIN (
                    SELECT order_id, SUM(qty * unit_price) AS total_sum
                    FROM order_items
                    WHERE order_id = :oid
                ) x ON o.id = x.order_id
                SET o.total = x.total_sum
                WHERE o.id = :oid2";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':oid' => $orderId, ':oid2' => $orderId]);
    }

  
    public function updateStatus(int $orderId, string $status): bool {
        $sql = "UPDATE orders SET status = :st WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':st' => $status, ':id' => $orderId]);
    }

    
}
