<?php

declare(strict_types=1);

namespace Inspirum\Arrayable;

use Inspirum\Arrayable\Arrayable as TValue;
use function array_values;

/**
 * @template TItemKey of array-key
 * @template TItemValue
 * @template TValue of \Inspirum\Arrayable\Arrayable<TItemKey,TItemValue>
 * @extends  \Inspirum\Arrayable\BaseCollection<TItemKey,TItemValue,int,TValue>
 */
abstract class BaseListCollection extends BaseCollection
{
    /**
     * @param list<TValue> $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);
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
        return array_values(parent::toArray());
    }

    /**
     * @return list<array<TItemKey,TItemValue>>
     */
    public function jsonSerialize(): array
    {
        return array_values(parent::jsonSerialize());
    }
}
