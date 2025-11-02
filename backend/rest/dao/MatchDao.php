<?php
declare(strict_types=1);
require_once __DIR__ . '/../services/DB.php';
require_once __DIR__ . '/CrudDao.php';

final class MatchDao implements CrudDao {
  private PDO $db;
  public function __construct(){ $this->db = DB::conn(); }

  public function create(array $d): int {
    $s=$this->db->prepare("INSERT INTO matches (date_,opponent,venue,competition) VALUES (?,?,?,?)");
    $s->execute([$d['date_'],$d['opponent'],$d['venue'],$d['competition']]);
    return (int)$this->db->lastInsertId();
  }
  public function get(int $id): ?array {
    $s=$this->db->prepare("SELECT * FROM matches WHERE id=?"); $s->execute([$id]);
    $r=$s->fetch(); return $r?:null;
  }
  public function list(): array {
    $s=$this->db->query("SELECT * FROM matches ORDER BY date_ DESC"); return $s->fetchAll();
  }
  public function update(int $id, array $d): bool {
    $s=$this->db->prepare("UPDATE matches SET date_=?, opponent=?, venue=?, competition=? WHERE id=?");
    return $s->execute([$d['date_'],$d['opponent'],$d['venue'],$d['competition'],$id]);
  }
  public function delete(int $id): bool {
    $s=$this->db->prepare("DELETE FROM matches WHERE id=?"); return $s->execute([$id]);
  }
}
