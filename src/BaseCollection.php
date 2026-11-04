<?php

declare(strict_types=1);

namespace Inspirum\Arrayable;

use InvalidArgumentException;
use function array_map;

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
     * @use \Inspirum\Arrayable\CollectionItems<TKey,TValue>
     */
    use CollectionItems;

    /**
     * @param array<TKey,TValue> $items
     */
    public function __construct(
        protected array $items = [],
    ) {
    }

    /**
     * @param TKey|null $offset
     * @param TValue $value
     *
     * @throws \InvalidArgumentException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            throw new InvalidArgumentException('Collection offset is required, use list collection to append items');
        }

        $this->items[$offset] = $value;
    }

    /**
     * @param TKey $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * @return array<TKey,TValue>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return array<TKey,array<TItemKey,TItemValue>>
     */
    public function __toArray(): array
    {
        return array_map(static fn (Arrayable $item): array => $item->__toArray(), $this->items);
    }

    /**
     * @return array<TKey,array<TItemKey,TItemValue>>
     */
    public function toArray(): array
    {
        return $this->__toArray();
    }

    /**
     * @return array<TKey,array<TItemKey,TItemValue>>
     */
    public function jsonSerialize(): array
    {
        return $this->__toArray();
    }
}
