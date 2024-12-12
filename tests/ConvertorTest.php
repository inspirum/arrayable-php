<?php

declare(strict_types=1);

namespace Inspirum\Arrayable\Tests;

use ArrayIterator;
use Arrayable as CoreArrayable;
use Inspirum\Arrayable\Arrayable;
use Inspirum\Arrayable\Convertor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;
use UnexpectedValueException;
use stdClass;

final class ConvertorTest extends TestCase
{
    /**
     * @param array<mixed> $result
     * @param positive-int|null $limit
     */
    #[DataProvider('providesToArray')]
    public function testToArray(mixed $data, array|Throwable $result, ?int $limit = null): void
    {
        if ($result instanceof Throwable) {
            $this->expectException($result::class);
            $this->expectExceptionMessage($result->getMessage());
        }

        self::assertSame(!($result instanceof Throwable), Convertor::isArrayable($data));
        self::assertEquals($result, Convertor::toArray($data, $limit));
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
            'result' => new UnexpectedValueException('Limit value should be positive number'),
            'limit' => -1,
        ];

        yield [
            'data' => '1',
            'result' => new UnexpectedValueException('Limit value should be positive number'),
            'limit' => 0,
        ];
    }
}
