<?php

declare(strict_types=1);

namespace Inspirum\Arrayable;

use JsonSerializable;
use Stringable;

/**
 * @template TKey of array-key
 * @template TValue
 * @extends \Inspirum\Arrayable\Arrayable<TKey, TValue>
 */
interface Model extends Arrayable, JsonSerializable, Stringable
{
}
