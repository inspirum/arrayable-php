<?php

declare(strict_types=1);

namespace Inspirum\Arrayable\Tests;

use Inspirum\Arrayable\Arrayable;
use Inspirum\Arrayable\BaseCollection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Throwable;
use function array_map;
use function iterator_to_array;
use function json_encode;

final class BaseCollectionTest extends TestCase
{
    /**
     * @param array<array<mixed>> $data
     */
    #[DataProvider('providesToArray')]
    public function testJsonSerialize(array $data, string|Throwable $result): void
    {
        if ($result instanceof Throwable) {
            $this->expectException($result::class);
            $this->expectExceptionMessage($result->getMessage());
        }

        $items = array_map(fn (array $data): Arrayable => $this->createModel($data), $data);

        $collection = new class ($items) extends BaseCollection {
        };

        self::assertSame($data, $collection->__toArray());
        self::assertSame($data, $collection->toArray());
        self::assertSame($data, $collection->jsonSerialize());
        self::assertSame($result, (string) $collection);
        self::assertSame($result, json_encode($collection));
    }

    /**
     * @return iterable<array<string,mixed>>
     */
    public static function providesToArray(): iterable
    {
        yield [
            'data' => [
                [1, 3, 4],
            ],
            'result' => '[[1,3,4]]',
        ];

        yield [
            'data' => [
                [1, 3, 4],
                ['a' => 1, 3, 'c' => true, 4],
            ],
            'result' => '[[1,3,4],{"a":1,"0":3,"c":true,"1":4}]',
        ];
    }

    public function testCollection(): void
    {
        $items = array_map(fn (array $data): Arrayable => $this->createModel($data), ['first' => [1, 2, 3], 'second' => [4, 5, 6], 'third' => [7, 8, 9]]);

        $collection = new class ($items) extends BaseCollection {
        };

        self::assertCount(3, $collection);
        self::assertSame($items, $collection->getItems());

        self::assertFalse(isset($collection[0]));
        self::assertTrue(isset($collection['first']));
        unset($collection['first']);
        self::assertFalse(isset($collection['first']));
        self::assertSame([4, 5, 6], $collection['second']->__toArray());
        $collection['second'] = $this->createModel(['foo' => 'bar']);
        $collection->offsetAdd($this->createModel(['bar1' => 'foo']));
        $collection->offsetAdd($this->createModel(['bar2' => 'foo']));

        self::assertSame(['foo' => 'bar'], $collection['second']->__toArray());
        self::assertSame(['bar2' => 'foo'], $collection[1]->__toArray());

        self::assertSame($collection->getItems(), iterator_to_array($collection));
    }

    /**
     * @param array<mixed,mixed> $data
     *
     * @return \Inspirum\Arrayable\Arrayable<int|string,mixed>
     */
    private function createModel(array $data): Arrayable
    {
        return new class ($data) implements Arrayable {
            /**
             * @param array<mixed> $data
             */
            public function __construct(private array $data)
            {
            }

            /**
             * @return array<mixed>
             */
            public function __toArray(): array
            {
                return $this->data;
            }
        };
    }
}
