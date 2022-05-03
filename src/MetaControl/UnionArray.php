<?php

declare(strict_types = 1);

namespace Dekcz\MetaControl;

use ArrayAccess;
use Countable;
use Iterator;

/**
 * @implements ArrayAccess<string,string>
 * @implements Iterator<string,string>
 */
class UnionArray implements ArrayAccess, Iterator, Countable
{

    /** @var array<int,string> $keys */
    private array $keys = [];

    /** @var array<int,string> $values */
    private array $values = [];
    private int $pointer = 0;

    /**
     * @param array<string,string> $from
     */
    public function __construct(array $from = [])
    {
        $this->keys = array_keys($from);
        $this->values = array_values($from);
    }

    /**
     * @param string $m_needle
     * @param array<int,string> $a_haystack
     * @param bool $b_strict
     * @return array<int,string>
     */
    private function arraySearchAll(string $m_needle, array $a_haystack, bool $b_strict = false): array
    {
        return array_intersect_key($a_haystack, array_flip(array_keys($a_haystack, $m_needle, $b_strict)));
    }

    /**
     * iteration
     */
    public function count(): int
    {
        return count($this->keys);
    }

    public function current(): mixed
    {
        return $this->values[$this->pointer];
    }

    public function key(): mixed
    {
        return $this->keys[$this->pointer];
    }

    public function next(): void
    {
        $this->pointer++;
    }

    public function rewind(): void
    {
        $this->pointer = 0;
    }

    public function valid(): bool
    {
        return isset($this->keys[$this->pointer]);
    }

    /**
     * just fetches the first found entry
     */
    public function offsetGet(mixed $offset): mixed
    {
        if (($i = array_search($offset, $this->keys)) !== false) {
            return $this->values[$i];
        }

        return null;
    }

    /**
     * @param string $key
     * @return array<int,string>
     */
    public function offsetGetAll(string $key): array
    {
        $a = [];
        foreach ($this->arraySearchAll($key, $this->keys) as $i => $v) {
            $a[] = $this->values[$i];
        }

        return $a;
    }

    /**
     * will only append new entries, not overwrite existing
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->keys[] = (string) $offset;
        $this->values[] = (string) $value;
    }

    /**
     * removes first matching entry
     */
    public function offsetUnset(mixed $offset): void
    {
        if (($i = array_search($offset, $this->keys)) !== false) {
            unset($this->keys[$i]);
            unset($this->values[$i]);
            // keep entries continuos for iterator
            $this->keys = array_values($this->keys);
            $this->values = array_values($this->values);
        }
    }

    public function offsetUnsetAll(string $key): void
    {
        $tmp = $this->keys;
        foreach ($this->arraySearchAll($key, $tmp) as $i => $v) {
            unset($this->keys[$i]);
            unset($this->values[$i]);
        }

        // reindex
        $this->keys = array_values($this->keys);
        $this->values = array_values($this->values);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_search($key, $this->keys) !== false;
    }

}
