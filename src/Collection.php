<?php

declare(strict_types=1);

namespace Inspirum\Arrayable;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Stringable;

/**
 * @template TItemKey of array-key
 * @template TItemValue
 * @template TKey of array-key
 * @template TValue of \Inspirum\Arrayable\Arrayable<TItemKey,TItemValue>
 * @extends \ArrayAccess<TKey,TValue>
 * @extends \IteratorAggregate<TKey,TValue>
 * @extends \Inspirum\Arrayable\Arrayable<TKey, array<TItemKey,TItemValue>>
 */
interface Collection extends ArrayAccess, Countable, IteratorAggregate, Arrayable, JsonSerializable, Stringable
{
}
