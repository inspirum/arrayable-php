<?php

declare(strict_types=1);

namespace Inspirum\Arrayable;

use function json_encode;
use const JSON_THROW_ON_ERROR;

/**
 * @template TKey of array-key
 * @template TValue
 * @implements \Inspirum\Arrayable\Model<TKey,TValue>
 */
abstract class BaseModel implements Model
{
    /**
     * @return array<TKey,TValue>
     */
    abstract public function __toArray(): array;

    /**
     * @return array<TKey,TValue>
     */
    public function toArray(): array
    {
        return $this->__toArray();
    }

    /**
     * @return array<TKey,TValue>
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
