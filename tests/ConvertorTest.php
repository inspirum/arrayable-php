<?php

declare(strict_types=1);

namespace Inspirum\Arrayable\Tests;

use ArrayIterator;
use Arrayable as CoreArrayable;
use Inspirum\Arrayable\Arrayable;
use Inspirum\Arrayable\Convertor;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;
use stdClass;

final class ConvertorTest extends TestCase
{
    #[DataProvider('providesIsArrayable')]
    public function testIsArrayable(mixed $data, bool $result): void
    {
        self::assertSame($result, Convertor::isArrayable($data));
    }

    /**
     * @return iterable<string,array<string,mixed>>
     */
    public static function providesIsArrayable(): iterable
    {
        yield 'array' => [
            'data' => [1, 2],
            'result' => true,
        ];

        yield 'iterator' => [
            'data' => new ArrayIterator([1, 2]),
            'result' => true,
        ];

        yield 'generator' => [
            'data' => (static function () {
                yield 1;
            })(),
            'result' => true,
        ];

        yield 'stdClass' => [
            'data' => new stdClass(),
            'result' => true,
        ];

        yield 'arrayable' => [
            'data' => new class implements Arrayable {
                /**
                 * @return array<mixed>
                 */
                public function __toArray(): array
                {
                    return [];
                }
            },
            'result' => true,
        ];

        yield 'string' => [
            'data' => '1',
            'result' => false,
        ];

        yield 'object' => [
            'data' => new class {
            },
            'result' => false,
        ];
    }

    /**
     * @param array<mixed> $result
     * @param positive-int|null $limit
     * @param positive-int $depth
     */
    #[DataProvider('providesToArray')]
    public function testToArray(mixed $data, array|Throwable $result, ?int $limit = null, int $depth = 512): void
    {
        if ($result instanceof Throwable) {
            $this->expectException($result::class);
            $this->expectExceptionMessage($result->getMessage());
        }

        self::assertEquals($result, Convertor::toArray($data, $limit, $depth));
    }

    /**
     * @return iterable<array<string,mixed>>
     */
    public static function providesToArray(): iterable
    {
        yield [
            'data' => [1, 3, 4],
            'result' => [1, 3, 4],
        ];

        yield [
            'data' => '1',
            'result' => new RuntimeException('Cannot cast to array'),
        ];

        yield [
            'data' => (static function () {
                $o = new stdClass();
                $o->foo = 'bar';
                $o->debug = true;

                return $o;
            })(),
            'result' => [
                'foo' => 'bar',
                'debug' => true,
            ],
        ];

        yield [
            'data' => [
                2,
                (static function () {
                    $o = new stdClass();
                    $o->foo = 'bar';
                    $o->debug = true;

                    return $o;
                })(),
            ],
            'result' => [
                2,
                [
                    'foo' => 'bar',
                    'debug' => true,
                ],
            ],
        ];

        yield [
            'data' => new class implements Arrayable {
                /**
                 * @return array<mixed>
                 */
                public function __toArray(): array
                {
                    return ['1', 2, 'test'];
                }
            },
            'result' => ['1', 2, 'test'],
        ];

        yield [
            'data' => new class implements CoreArrayable {
                /**
                 * @return array<mixed>
                 */
                public function __toArray(): array
                {
                    return ['1' => 2, '3' => 5];
                }
            },
            'result' => ['1' => 2, '3' => 5],
        ];

        yield [
            'data' => new ArrayIterator([3, 4, 5]),
            'result' => [3, 4, 5],
        ];

        yield [
            'data' => (static function () {
                yield 2.3;
                yield 8.10;
            })(),
            'result' => [2.3, 8.10],
        ];

        yield [
            'data' => [
                1,
                3,
                [
                    3,
                    new ArrayIterator([
                        3,
                        4,
                        (static function () {
                            yield 2.3;
                            yield new class implements Arrayable {
                                /**
                                 * @return array<mixed>
                                 */
                                public function __toArray(): array
                                {
                                    return ['1', 2, 'test'];
                                }
                            };
                        })(),
                    ]),
                ],
            ],
            'result' => [1, 3, [3, [3, 4, [2.3, ['1', 2, 'test']]]]],
        ];

        yield [
            'data' => new ArrayIterator([
                1,
                new ArrayIterator([3, new ArrayIterator([4, new ArrayIterator([3, 4, 5])])]),
            ]),
            'result' => [1, [3, [4, [3, 4, 5]]]],
        ];

        yield [
            'data' => new ArrayIterator([
                1,
                new ArrayIterator([3, new ArrayIterator([4, new ArrayIterator([3, 4, 5])])]),
            ]),
            'result' => [1, new ArrayIterator([3, new ArrayIterator([4, new ArrayIterator([3, 4, 5])])])],
            'limit' => 1,
        ];

        yield [
            'data' => new ArrayIterator([
                1,
                new ArrayIterator([3, new ArrayIterator([4, new ArrayIterator([3, 4, 5])])]),
            ]),
            'result' => [1, [3, [4, new ArrayIterator([3, 4, 5])]]],
            'limit' => 3,
        ];

        yield [
            'data' => '1',
            'result' => new InvalidArgumentException('Limit value should be positive number'),
            'limit' => -1,
        ];

        yield [
            'data' => '1',
            'result' => new InvalidArgumentException('Limit value should be positive number'),
            'limit' => 0,
        ];

        yield [
            'data' => [1, [2, [3]]],
            'result' => [1, [2, [3]]],
            'depth' => 3,
        ];

        yield [
            'data' => [1, [2, [3]]],
            'result' => new RuntimeException('Maximum depth of 2 exceeded'),
            'depth' => 2,
        ];

        yield [
            'data' => [1, [2, [3]]],
            'result' => [1, [2, [3]]],
            'limit' => 2,
            'depth' => 2,
        ];

        yield [
            'data' => (static function () {
                $o = new stdClass();
                $o->self = $o;

                return $o;
            })(),
            'result' => new RuntimeException('Maximum depth of 512 exceeded'),
        ];

        yield [
            'data' => [1],
            'result' => new InvalidArgumentException('Depth value should be positive number'),
            'depth' => 0,
        ];
    }
}
