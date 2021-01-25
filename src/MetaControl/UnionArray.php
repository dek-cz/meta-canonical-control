<?php

declare(strict_types=1);

namespace Vrestihnat\MetaControl;

class UnionArray implements \ArrayAccess, \Iterator, \Countable
{

  private array $keys = [];
  private array $values = [];
  private int $pointer = 0;

  public function __construct(array $from = [])
  {
    $this->keys = array_keys($from);
    $this->values = array_values($from);
  }

  private function arraySearchAll($m_needle, $a_haystack, $b_strict = false)
  {
    return array_intersect_key($a_haystack, array_flip(array_keys($a_haystack, $m_needle, $b_strict)));
  }

  // iteration
  public function count(): int
  {
    return count($this->keys);
  }

  public function current()
  {
    return $this->values[$this->pointer];
  }

  public function key()
  {
    return $this->keys[$this->pointer];
  }

  public function next()
  {
    $this->pointer++;
  }

  public function rewind()
  {
    $this->pointer = 0;
  }

  public function valid()
  {
    return isset($this->keys[$this->pointer]);
  }

  // just fetches the first found entry
  public function offsetGet($key)
  {
    if (($i = array_search($key, $this->keys)) !== false) {
      return $this->values[$i];
    }
    return null;
  }

  public function offsetGetAll($key): array
  {
    $a = [];
    foreach ($this->arraySearchAll($key, $this->keys) as $i => $v) {
      $a[] = $this->values[$i];
    }
    return $a;
  }

  // will only append new entries, not overwrite existing
  public function offsetSet($key, $value)
  {
    $this->keys[] = $key;
    $this->values[] = $value;
  }

  // removes first matching entry
  public function offsetUnset($key)
  {
    if (($i = array_search($key, $this->keys)) !== false) {
      unset($this->keys[$i]);
      unset($this->values[$i]);
      // keep entries continuos for iterator
      $this->keys = array_values($this->keys);
      $this->values = array_values($this->values);
    }
  }

  public function offsetUnsetAll($key)
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

  public function offsetExists($key)
  {
    return array_search($key, $this->keys) !== FALSE;
  }

}
