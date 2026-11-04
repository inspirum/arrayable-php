<?php

declare(strict_types=1);

namespace Inspirum\Arrayable;

use OutOfRangeException;
use function array_map;
use function array_values;
use function count;
use function is_int;
use function sprintf;

/**
 * @template TItemKey of array-key
 * @template TItemValue
 * @template TValue of \Inspirum\Arrayable\Arrayable<TItemKey,TItemValue>
 * @implements \Inspirum\Arrayable\ListCollection<TItemKey,TItemValue,TValue>
 */
abstract class BaseListCollection implements ListCollection
{
    /**
     * @use \Inspirum\Arrayable\CollectionItems<int,TValue>
     */
    use CollectionItems;

    /**
     * @var array<int,TValue>
     */
    protected array $items;

    /**
     * @param array<TValue> $items
     */
    public function __construct(array $items = [])
    {
        $this->items = array_values($items);
    }

    /**
     * @param int|null $offset
     * @param TValue $value
     *
     * @throws \OutOfRangeException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->offsetAdd($value);

            return;
        }

        if ($this->isOutOfRange($offset)) {
            throw new OutOfRangeException(sprintf('List offset must be an integer between 0 and %d', count($this->items)));
        }

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
     * @param int $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);

        $this->items = array_values($this->items);
    }

    /**
     * @return list<TValue>
     */
    public function getItems(): array
    {
        return array_values($this->items);
    }

    /**
     * @return list<array<TItemKey,TItemValue>>
     */
    public function __toArray(): array
    {
        return array_values(array_map(static fn (Arrayable $item): array => $item->__toArray(), $this->items));
    }

    /**
     * @return list<array<TItemKey,TItemValue>>
     */
    public function toArray(): array
    {
        return $this->__toArray();
    }

    /**
     * @return list<array<TItemKey,TItemValue>>
     */
    public function jsonSerialize(): array
    {
        return $this->__toArray();
    }

    private function isOutOfRange(mixed $offset): bool
    {
        return !is_int($offset) || $offset < 0 || $offset > count($this->items);
    }
}
