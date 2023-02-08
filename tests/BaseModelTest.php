<?php

declare(strict_types=1);

namespace Inspirum\Arrayable\Tests;

use Inspirum\Arrayable\BaseModel;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Throwable;
use function json_encode;

final class BaseModelTest extends TestCase
{
    /**
     * @param array<mixed> $data
     */
    #[DataProvider('providesToArray')]
    public function testJsonSerialize(array $data, string|Throwable $result): void
    {
        if ($result instanceof Throwable) {
            self::expectException($result::class);
            self::expectExceptionMessage($result->getMessage());
        }

        $model = new class ($data) extends BaseModel {
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

        self::assertSame($data, $model->__toArray());
        self::assertSame($data, $model->toArray());
        self::assertSame($data, $model->jsonSerialize());
        self::assertSame($result, (string) $model);
        self::assertSame($result, json_encode($model));
    }

    /**
     * @return iterable<array<string, mixed>>
     */
    public static function providesToArray(): iterable
    {
        yield [
            'data'   => [1, 3, 4],
            'result' => '[1,3,4]',
        ];

        yield [
            'data'   => ['a' => 1, 3, 'c' => true, 4],
            'result' => '{"a":1,"0":3,"c":true,"1":4}',
        ];
    }
}
