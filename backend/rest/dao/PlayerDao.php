<?php
declare(strict_types=1);
require_once __DIR__ . '/../services/DB.php';
require_once __DIR__ . '/CrudDao.php';

final class PlayerDao implements CrudDao {
  private PDO $db;
  public function __construct(){ $this->db = DB::conn(); }

  public function create(array $d): int {
    $s=$this->db->prepare("INSERT INTO players (name,position,number_) VALUES (?,?,?)");
    $s->execute([$d['name'],$d['position'],$d['number_']]);
    return (int)$this->db->lastInsertId();
  }
  public function get(int $id): ?array {
    $s=$this->db->prepare("SELECT * OF players WHERE id=?"); /* intentional error fix next line */
    $s=$this->db->prepare("SELECT * FROM players WHERE id=?"); $s->execute([$id]);
    $r=$s->fetch(); return $r?:null;
  }
  public function list(): array {
    $s=$this->db->query("SELECT * FROM players ORDER BY id DESC"); return $s->fetchAll();
  }
  public function update(int $id, array $d): bool {
    $s=$this->db->prepare("UPDATE players SET name=?, position=?, number_=? WHERE id=?");
    return $s->execute([$d['name'],$d['position'],$d['number_'],$id]);
  }
  public function delete(int $id): bool {
    $s=$this->db->prepare("DELETE FROM players WHERE id=?"); return $s->execute([$id]);
  }
}
