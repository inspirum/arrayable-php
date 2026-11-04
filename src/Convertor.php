<?php

declare(strict_types=1);

namespace Inspirum\Arrayable;

use RuntimeException;
use UnexpectedValueException;
use stdClass;
use function is_int;
use function is_iterable;
use function is_string;
use function sprintf;
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
     * @param positive-int $depth
     *
     * @return array<int|string,mixed>
     *
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     */
    public static function toArray(mixed $data, ?int $limit = null, int $depth = 512): array
    {
        $limit ??= PHP_INT_MAX;

        self::assertPositive($limit, 'Limit');
        self::assertPositive($depth, 'Depth');

        return self::toArrayAtLevel($data, $limit, $depth, 1);
    }

    private static function assertPositive(int $value, string $name): void
    {
        if ($value <= 0) {
            throw new UnexpectedValueException(sprintf('%s value should be positive number', $name));
        }
    }

    /**
     * @return ($level is 1 ? array<mixed> : mixed)
     */
    private static function toArrayAtLevel(mixed $data, int $limit, int $depth, int $level): mixed
    {
        if ($level > $limit) {
            return $data;
        }

        if ($data instanceof Arrayable) {
            $data = $data->__toArray();
        } elseif ($data instanceof stdClass) {
            $data = (array) $data;
        }

        if (is_iterable($data)) {
            if ($level > $depth) {
                throw new RuntimeException(sprintf('Maximum depth of %d exceeded', $depth));
            }

            $arrayData = [];
            foreach ($data as $k => $v) {
                if (!is_int($k) && !is_string($k)) {
                    throw new RuntimeException('Iterable key must be int|string');
                }

                $arrayData[$k] = self::toArrayAtLevel($v, $limit, $depth, $level + 1);
            }

            return $arrayData;
        }

        if ($level === 1) {
            throw new RuntimeException('Cannot cast to array');
        }

        return $data;
    }
}
