<?php
declare(strict_types=1);

require_once __DIR__ . '/../services/DB.php';
require_once __DIR__ . '/CrudDao.php';

abstract class BaseDao implements CrudDao {

    /**
     * @var PDO
     */
    protected PDO $db;

    /**
     * @var string
     */
    protected string $table;

    /**
     * @var string
     */
    protected string $idColumn = 'id';

    /**
     * @var array
     */
    protected array $fields = [];

    public function __construct(string $table) {
        $this->db = DB::conn();
        $this->table = $table;
    }

    protected function query(string $query, array $params = []): array {
       $stmt = $this->db->prepare($query);
       $stmt->execute($params);
       return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function query_unique(string $query, array $params = []): ?array {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row : null;
    } 


    public function create(array $data): int {
        $data = $this->filterFields($data);
        if (empty($data)) {
            throw new InvalidArgumentException("No valid fields for insert into {$this->table}");
        }

        $columns = array_keys($data);
        $placeholders = array_map(fn($c) => ':' . $c, $columns);

        $sql = "INSERT INTO {$this->table} (" . implode(',', $columns) . ")
                VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $this->db->prepare($sql);

        foreach ($data as $col => $val) {
            $stmt->bindValue(':' . $col, $val);
        }

        $stmt->execute();
        return (int)$this->db->lastInsertId();
    }

    public function get(int $id): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->idColumn} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function list(): array {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$this->idColumn} DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function update(int $id, array $data): bool {
        $data = $this->filterFields($data);
        if (empty($data)) {
            throw new InvalidArgumentException("No valid fields for update of {$this->table}");
        }

        $sets = [];
        foreach ($data as $col => $val) {
            $sets[] = "{$col} = :{$col}";
        }

        $sql = "UPDATE {$this->table} SET " . implode(',', $sets) . "
                WHERE {$this->idColumn} = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;

        return $stmt->execute($data);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM {$this->table} WHERE {$this->idColumn} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }



    protected function filterFields(array $data): array {
        if (empty($this->fields)) return $data;
        return array_intersect_key($data, array_flip($this->fields));
    }
}
