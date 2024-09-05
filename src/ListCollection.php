<?php

declare(strict_types=1);

namespace Inspirum\Arrayable;

/**
 * @template TItemKey of array-key
 * @template TItemValue
 * @template TValue of \Inspirum\Arrayable\Arrayable<TItemKey,TItemValue>
 * @extends \Inspirum\Arrayable\Collection<TItemKey,TItemValue,int,TValue>
 */
interface ListCollection extends Collection
{
}
