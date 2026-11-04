<?php

declare(strict_types=1);

namespace Inspirum\Arrayable;

use OutOfRangeException;
use function array_values;
use function count;
use function is_int;
use function sprintf;

/**
 * @template TItemKey of array-key
 * @template TItemValue
 * @template TValue of \Inspirum\Arrayable\Arrayable<TItemKey,TItemValue>
 * @extends  \Inspirum\Arrayable\BaseCollection<TItemKey,TItemValue,int,TValue>
 */
abstract class BaseListCollection extends BaseCollection
{
    /**
     * @param array<TValue> $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct(array_values($items));
    }

    /**
     * @param int|null $offset
     * @param TValue $value
     *
     * @throws \OutOfRangeException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset !== null && $this->isOutOfRange($offset)) {
            throw new OutOfRangeException(sprintf('List offset must be an integer between 0 and %d', count($this->items)));
        }

        parent::offsetSet($offset, $value);
    }

    /**
     * @param int $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        parent::offsetUnset($offset);

        $this->items = array_values($this->items);
    }

    /**
     * @return list<TValue>
     */
    public function getItems(): array
    {
        return array_values(parent::getItems());
    }

    /**
     * @return list<array<TItemKey,TItemValue>>
     */
    public function __toArray(): array
    {
        return array_values(parent::__toArray());
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
