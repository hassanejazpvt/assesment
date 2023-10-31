<?php

declare(strict_types=1);

namespace Hassan\Assesment\Core;

class Model
{
    protected $table;

    protected $pk = 'id';

    protected $columns = [];

    public function all(): ?array
    {
        $results = DB::query()->select('*')->from($this->table)->fetchAllAssociative();
        $this->resetQueryBuilder();

        return $results;
    }

    public function where(array $where): self
    {
        foreach ($where as $k => $v) {
            if (is_string($v)) {
                DB::query()->andWhere("`{$k}` = \"{$v}\"");
            } else {
                DB::query()->andWhere("`{$k}` = {$v}");
            }
        }

        return $this;
    }

    public function whereIn(string $column, array $values): self
    {
        $query = DB::query();
        $query->where($query->expr()->in($column, $values));

        return $this;
    }

    public function orWhere(array $where): self
    {
        foreach ($where as $k => $v) {
            if (is_string($v)) {
                DB::query()->orWhere("`{$k}` = \"{$v}\"");
            } else {
                DB::query()->orWhere("`{$k}` = {$v}");
            }
        }

        return $this;
    }

    public function orderBy(string $sort, string $order = null): self
    {
        DB::query()->orderBy($sort, $order);

        return $this;
    }

    public function get(): ?array
    {
        return $this->all();
    }

    public function insert(array $data): int
    {
        DB::connection()->insert($this->table, only($data, $this->getColumns()));

        return (int) DB::connection()->lastInsertId();
    }

    public function max(string $column): int
    {
        return DB::query()->from($this->table)->select("MAX({$column}) as max")->fetchAssociative()['max'] ?? 0;
    }

    public function update(array $data, int $id): bool
    {
        return DB::connection()->update($this->table, only($data, $this->getColumns()), [$this->getPk() => $id]);
    }

    public function find(int $id): ?array
    {
        $result = DB::query()->select('*')->where($this->getPk() . ' = ' . $id)->from($this->table)->fetchAssociative();
        $this->resetQueryBuilder();

        return $result;
    }

    public function count(): int
    {
        $result = DB::query()->select('COUNT(*) as CNT')->from($this->table)->fetchAssociative();
        $this->resetQueryBuilder();

        return $result['CNT'] ?: 0;
    }

    public function delete(int $id): bool
    {
        return (bool) DB::connection()->delete($this->table, [$this->getPk() => $id]);
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getPk(): string
    {
        return $this->pk;
    }

    private function resetQueryBuilder(): void
    {
        DB::query()->resetQueryParts();
    }
}
