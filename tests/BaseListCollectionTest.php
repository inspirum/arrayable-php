<?php

declare(strict_types=1);

namespace Inspirum\Arrayable\Tests;

use Inspirum\Arrayable\Arrayable;
use Inspirum\Arrayable\ListCollection;
use Inspirum\Arrayable\Tests\Fixtures\ListCollectionFixture;
use OutOfRangeException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function array_keys;
use function array_map;
use function iterator_to_array;

final class BaseListCollectionTest extends TestCase
{
    public function testConstructorReindexesItems(): void
    {
        $collection = $this->createCollection(['first' => [1], 'second' => [2]]);

        self::assertInstanceOf(ListCollection::class, $collection);
        self::assertSame([[1], [2]], $collection->__toArray());
        self::assertSame([0, 1], array_keys(iterator_to_array($collection)));
        self::assertSame([2], $collection[1]->__toArray());
    }

    public function testOffsetUnsetKeepsList(): void
    {
        $collection = $this->createCollection([[1], [2], [3]]);

        unset($collection[0]);

        self::assertCount(2, $collection);
        self::assertTrue(isset($collection[0]));
        self::assertFalse(isset($collection[2]));
        self::assertSame([2], $collection[0]->__toArray());
        self::assertSame($collection->getItems(), iterator_to_array($collection));
        self::assertSame([[2], [3]], $collection->__toArray());
    }

    public function testOffsetSet(): void
    {
        $collection = $this->createCollection([[1], [2]]);

        $collection[] = $this->createModel([3]);
        $collection->offsetAdd($this->createModel([4]));
        $collection[4] = $this->createModel([5]);
        $collection[0] = $this->createModel([0]);

        self::assertSame([[0], [2], [3], [4], [5]], $collection->__toArray());
        self::assertSame($collection->getItems(), iterator_to_array($collection));
    }

    #[DataProvider('providesOutOfRangeOffset')]
    public function testOffsetSetOutOfRange(mixed $offset): void
    {
        $collection = $this->createCollection([[1], [2]]);

        $this->expectException(OutOfRangeException::class);
        $this->expectExceptionMessage('List offset must be an integer between 0 and 2');

        $collection[$offset] = $this->createModel([3]); // @phpstan-ignore offsetAssign.dimType
    }

    /**
     * @return iterable<string,array<string,mixed>>
     */
    public static function providesOutOfRangeOffset(): iterable
    {
        yield 'negative' => [
            'offset' => -1,
        ];

        yield 'gap' => [
            'offset' => 3,
        ];

        yield 'string' => [
            'offset' => 'first',
        ];
    }

    /**
     * @param array<array<mixed>> $data
     */
    private function createCollection(array $data): ListCollectionFixture
    {
        $items = array_map(fn (array $data): Arrayable => $this->createModel($data), $data);

        return new ListCollectionFixture($items);
    }

    /**
     * @param array<mixed> $data
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
