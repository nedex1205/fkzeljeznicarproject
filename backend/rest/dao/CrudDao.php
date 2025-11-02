<?php
declare(strict_types=1);
interface CrudDao {
  public function create(array $data): int;
  public function get(int $id): ?array;
  public function list(): array;
  public function update(int $id, array $data): bool;
  public function delete(int $id): bool;
}
