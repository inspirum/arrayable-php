<?php

declare(strict_types=1);

namespace Inspirum\Arrayable;

use ArrayIterator;
use Traversable;
use function array_key_exists;
use function array_map;
use function count;
use function json_encode;
use const JSON_THROW_ON_ERROR;

/**
 * @template TItemKey of array-key
 * @template TItemValue
 * @template TKey of array-key
 * @template TValue of \Inspirum\Arrayable\Arrayable<TItemKey,TItemValue>
 * @implements \Inspirum\Arrayable\Collection<TItemKey,TItemValue,TKey,TValue>
 */
abstract class BaseCollection implements Collection
{
    /**
     * @param array<TKey, TValue> $items
     */
    public function __construct(
        protected array $items = [],
    ) {
    }

    /**
     * @param TKey $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * @param TKey $offset
     *
     * @return TValue
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * @param TKey   $offset
     * @param TValue $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->items[$offset] = $value;
    }

    /**
     * @param TValue $value
     */
    public function offsetAdd(mixed $value): void
    {
        $this->items[] = $value;
    }

    /**
     * @param TKey $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return \Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @return array<TKey, TValue>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return array<TKey, array<TItemKey, TItemValue>>
     */
    public function __toArray(): array
    {
        return array_map(static fn(Arrayable $item): array => $item->__toArray(), $this->items);
    }

    /**
     * @return array<TKey, array<TItemKey, TItemValue>>
     */
    public function toArray(): array
    {
        return $this->__toArray();
    }

    /**
     * @return array<TKey, array<TItemKey, TItemValue>>
     */
    public function jsonSerialize(): array
    {
        return $this->__toArray();
    }

    /**
     * @throws \JsonException
     */
    public function __toString(): string
    {
        return json_encode($this->jsonSerialize(), JSON_THROW_ON_ERROR);
    }
}
