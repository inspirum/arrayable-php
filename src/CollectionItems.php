<?php

declare(strict_types=1);

namespace Inspirum\Arrayable;

use ArrayIterator;
use Traversable;
use function array_key_exists;
use function count;
use function json_encode;
use const JSON_THROW_ON_ERROR;

/**
 * @internal
 *
 * @template TKey of array-key
 * @template TValue
 */
trait CollectionItems
{
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

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return \Traversable<TKey,TValue>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @throws \JsonException
     */
    public function __toString(): string
    {
        return json_encode($this->jsonSerialize(), JSON_THROW_ON_ERROR);
    }
}
