<?php

declare(strict_types=1);

namespace Inspirum\Arrayable;

use RuntimeException;
use UnexpectedValueException;
use stdClass;
use function is_iterable;
use const PHP_INT_MAX;

final class Convertor
{
    /**
     * Can be cast to array
     */
    public static function isArrayable(mixed $data): bool
    {
        return is_iterable($data) || $data instanceof Arrayable || $data instanceof stdClass;
    }

    /**
     * Cast anything to array
     *
     * @param positive-int|null $limit
     *
     * @return array<int|string,mixed>
     *
     * @throws \RuntimeException
     */
    public static function toArray(mixed $data, ?int $limit = null): array
    {
        return self::toArrayWithDepth($data, $limit ?? PHP_INT_MAX, 1);
    }

    /**
     * @return ($depth is 1 ? array<mixed> : mixed)
     */
    private static function toArrayWithDepth(mixed $data, int $limit, int $depth): mixed
    {
        if ($limit <= 0) {
            throw new UnexpectedValueException('Limit value should be positive number');
        }

        if ($depth > $limit) {
            return $data;
        }

        if ($data instanceof Arrayable) {
            $data = $data->__toArray();
        } elseif ($data instanceof stdClass) {
            $data = (array) $data;
        }

        if (is_iterable($data)) {
            $arrayData = [];
            foreach ($data as $k => $v) {
                $arrayData[$k] = self::toArrayWithDepth($v, $limit, $depth + 1);
            }

            return $arrayData;
        }

        if ($depth === 1) {
            throw new RuntimeException('Cannot cast to array');
        }

        return $data;
    }
}
