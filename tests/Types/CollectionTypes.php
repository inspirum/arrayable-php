<?php

declare(strict_types=1);

namespace Inspirum\Arrayable\Tests\Types;

use Inspirum\Arrayable\BaseCollection;
use Inspirum\Arrayable\BaseListCollection;
use Inspirum\Arrayable\Collection;
use Inspirum\Arrayable\ListCollection;
use function PHPStan\Testing\assertType;

final class CollectionTypes
{
    /**
     * @param \Inspirum\Arrayable\BaseCollection<string,int,string,\Inspirum\Arrayable\Arrayable<string,int>> $collection
     */
    public function baseCollection(BaseCollection $collection): void
    {
        assertType('Inspirum\Arrayable\Arrayable<string, int>', $collection['key']);
        assertType('array<string, Inspirum\Arrayable\Arrayable<string, int>>', $collection->getItems());
        assertType('array<string, array<string, int>>', $collection->__toArray());
        assertType('array<string, array<string, int>>', $collection->toArray());
        assertType('array<string, array<string, int>>', $collection->jsonSerialize());
    }

    /**
     * @param \Inspirum\Arrayable\BaseListCollection<int,bool,\Inspirum\Arrayable\Arrayable<int,bool>> $collection
     */
    public function baseListCollection(BaseListCollection $collection): void
    {
        assertType('Inspirum\Arrayable\Arrayable<int, bool>', $collection[0]);
        assertType('list<Inspirum\Arrayable\Arrayable<int, bool>>', $collection->getItems());
        assertType('list<array<int, bool>>', $collection->__toArray());
        assertType('list<array<int, bool>>', $collection->toArray());
        assertType('list<array<int, bool>>', $collection->jsonSerialize());
    }

    /**
     * @param \Inspirum\Arrayable\Collection<string,float,int,\Inspirum\Arrayable\Arrayable<string,float>> $collection
     */
    public function collection(Collection $collection): void
    {
        assertType('array<int, Inspirum\Arrayable\Arrayable<string, float>>', $collection->getItems());
        assertType('array<int, array<string, float>>', $collection->toArray());
    }

    /**
     * @param \Inspirum\Arrayable\ListCollection<string,float,\Inspirum\Arrayable\Arrayable<string,float>> $collection
     */
    public function listCollection(ListCollection $collection): void
    {
        assertType('list<Inspirum\Arrayable\Arrayable<string, float>>', $collection->getItems());
        assertType('list<array<string, float>>', $collection->toArray());
    }
}
