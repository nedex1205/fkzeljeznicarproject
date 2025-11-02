<?php
declare(strict_types=1);
require_once __DIR__ . '/../services/DB.php';
require_once __DIR__ . '/CrudDao.php';

final class UserDao implements CrudDao {
  private PDO $db;
  public function __construct(){ $this->db = DB::conn(); }

  public function create(array $d): int {
    $s=$this->db->prepare("INSERT INTO users (email,password_hash,full_name) VALUES (?,?,?)");
    $s->execute([$d['email'],$d['password_hash'],$d['full_name'] ?? null]);
    return (int)$this->db->lastInsertId();
  }
  public function get(int $id): ?array {
    $s=$this->db->prepare("SELECT * FROM users WHERE id=?"); $s->execute([$id]);
    $r=$s->fetch(); return $r?:null;
  }
  public function list(): array {
    $s=$this->db->query("SELECT * FROM users ORDER BY id DESC"); return $s->fetchAll();
  }
  public function update(int $id, array $d): bool {
    $s=$this->db->prepare("UPDATE users SET email=?, password_hash=?, full_name=? WHERE id=?");
    return $s->execute([$d['email'],$d['password_hash'],$d['full_name'] ?? null,$id]);
  }
  public function delete(int $id): bool {
    $s=$this->db->prepare("DELETE FROM users WHERE id=?"); return $s->execute([$id]);
  }
}
