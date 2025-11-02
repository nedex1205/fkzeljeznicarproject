<?php
declare(strict_types=1);
require_once __DIR__ . '/../services/DB.php';

final class OrderDao {
  private PDO $db;
  public function __construct(){ $this->db = DB::conn(); }

  public function create(int $userId, string $status='NEW'): int {
    $s=$this->db->prepare("INSERT INTO orders (user_id,status,total) VALUES (?,?,0)");
    $s->execute([$userId,$status]);
    return (int)$this->db->lastInsertId();
  }
  public function list(): array {
    $s=$this->db->query("SELECT * FROM orders ORDER BY id DESC"); return $s->fetchAll();
  }
  public function get(int $id): ?array {
    $o=$this->db->prepare("SELECT * FROM orders WHERE id=?"); $o->execute([$id]);
    $row=$o->fetch(); if(!$row) return null;
    $i=$this->db->prepare("SELECT * FROM order_items WHERE order_id=?"); $i->execute([$id]);
    $row['items']=$i->fetchAll();
    return $row;
  }
  public function addItem(int $orderId,int $productId,int $qty): bool {
    $p=$this->db->prepare("SELECT price FROM products WHERE id=?"); $p->execute([$productId]);
    $r=$p->fetch(); if(!$r) return false;
    $unit=$r['price'];
    $i=$this->db->prepare("INSERT INTO order_items (order_id,product_id,qty,unit_price) VALUES (?,?,?,?)");
    return $i->execute([$orderId,$productId,$qty,$unit]);
  }
  public function finalizeTotal(int $orderId): bool {
    $sql="UPDATE orders o JOIN (SELECT order_id, SUM(qty*unit_price) s FROM order_items WHERE order_id=? ) x
          ON o.id=x.order_id SET o.total=x.s WHERE o.id=?";
    $st=$this->db->prepare($sql); return $st->execute([$orderId,$orderId]);
  }
  public function updateStatus(int $orderId,string $status): bool {
    $st=$this->db->prepare("UPDATE orders SET status=? WHERE id=?"); return $st->execute([$status,$orderId]);
  }
  public function delete(int $id): bool {
    $st=$this->db->prepare("DELETE FROM orders WHERE id=?"); return $st->execute([$id]);
  }
}
