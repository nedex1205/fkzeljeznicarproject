<?php
declare(strict_types=1);
require_once __DIR__ . '/../services/DB.php';
require_once __DIR__ . '/CrudDao.php';

final class ProductDao implements CrudDao {
  private PDO $db;
  public function __construct(){ $this->db = DB::conn(); }

  public function create(array $d): int {
    $s=$this->db->prepare("INSERT INTO products (name,price,category,img_url) VALUES (?,?,?,?)");
    $s->execute([$d['name'],$d['price'],$d['category'],$d['img_url'] ?? null]);
    return (int)$this->db->lastInsertId();
  }
  public function get(int $id): ?array {
    $s=$this->db->prepare("SELECT * FROM products WHERE id=?"); $s->execute([$id]);
    $r=$s->fetch(); return $r?:null;
  }
  public function list(): array {
    $s=$this->db->query("SELECT * FROM products ORDER BY id DESC"); return $s->fetchAll();
  }
  public function update(int $id, array $d): bool {
    $s=$this->db->prepare("UPDATE products SET name=?, price=?, category=?, img_url=? WHERE id=?");
    return $s->execute([$d['name'],$d['price'],$d['category'],$d['img_url'] ?? null,$id]);
  }
  public function delete(int $id): bool {
    $s=$this->db->prepare("DELETE FROM products WHERE id=?"); return $s->execute([$id]);
  }
}
