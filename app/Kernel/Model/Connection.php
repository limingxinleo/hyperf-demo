<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Kernel\Model;

use Closure;
use Generator;
use Hyperf\Database\ConnectionInterface;
use Hyperf\Database\Query\Builder;
use Hyperf\Database\Query\Expression;

class Connection implements ConnectionInterface
{
    public function table($table): Builder
    {
        // TODO: Implement table() method.
    }

    public function raw($value): Expression
    {
        // TODO: Implement raw() method.
    }

    public function selectOne(string $query, array $bindings = [], bool $useReadPdo = true)
    {
        // TODO: Implement selectOne() method.
    }

    public function select(string $query, array $bindings = [], bool $useReadPdo = true): array
    {
        // TODO: Implement select() method.
    }

    public function cursor(string $query, array $bindings = [], bool $useReadPdo = true): Generator
    {
        // TODO: Implement cursor() method.
    }

    public function insert(string $query, array $bindings = []): bool
    {
        // TODO: Implement insert() method.
    }

    public function update(string $query, array $bindings = []): int
    {
        // TODO: Implement update() method.
    }

    public function delete(string $query, array $bindings = []): int
    {
        // TODO: Implement delete() method.
    }

    public function statement(string $query, array $bindings = []): bool
    {
        // TODO: Implement statement() method.
    }

    public function affectingStatement(string $query, array $bindings = []): int
    {
        // TODO: Implement affectingStatement() method.
    }

    public function unprepared(string $query): bool
    {
        // TODO: Implement unprepared() method.
    }

    public function prepareBindings(array $bindings): array
    {
        // TODO: Implement prepareBindings() method.
    }

    public function transaction(Closure $callback, int $attempts = 1)
    {
        // TODO: Implement transaction() method.
    }

    public function beginTransaction(): void
    {
        // TODO: Implement beginTransaction() method.
    }

    public function commit(): void
    {
        // TODO: Implement commit() method.
    }

    public function rollBack(): void
    {
        // TODO: Implement rollBack() method.
    }

    public function transactionLevel(): int
    {
        // TODO: Implement transactionLevel() method.
    }

    public function pretend(Closure $callback): array
    {
        // TODO: Implement pretend() method.
    }
}
