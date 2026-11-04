<?php

declare(strict_types=1);

namespace Inspirum\Arrayable\Tests\Types;

use Inspirum\Arrayable\Arrayable;
use Inspirum\Arrayable\BaseCollection;
use Inspirum\Arrayable\BaseListCollection;
use Inspirum\Arrayable\Collection;
use Inspirum\Arrayable\ListCollection;

final class CollectionTypes
{
    /**
     * @param \Inspirum\Arrayable\BaseCollection<string,int,string,\Inspirum\Arrayable\Arrayable<string,int>> $collection
     *
     * @return \Inspirum\Arrayable\Arrayable<string,int>
     */
    public function baseCollectionItem(BaseCollection $collection): Arrayable
    {
        return $collection['key'];
    }

    /**
     * @param \Inspirum\Arrayable\BaseCollection<string,int,string,\Inspirum\Arrayable\Arrayable<string,int>> $collection
     *
     * @return array<string,\Inspirum\Arrayable\Arrayable<string,int>>
     */
    public function baseCollectionItems(BaseCollection $collection): array
    {
        return $collection->getItems();
    }

    /**
     * @param \Inspirum\Arrayable\BaseCollection<string,int,string,\Inspirum\Arrayable\Arrayable<string,int>> $collection
     *
     * @return array<string,array<string,int>>
     */
    public function baseCollectionToArray(BaseCollection $collection): array
    {
        return $collection->toArray();
    }

    /**
     * @param \Inspirum\Arrayable\BaseCollection<string,int,string,\Inspirum\Arrayable\Arrayable<string,int>> $collection
     *
     * @return array<string,array<string,int>>
     */
    public function baseCollectionJsonSerialize(BaseCollection $collection): array
    {
        return $collection->jsonSerialize();
    }

    /**
     * @param \Inspirum\Arrayable\BaseListCollection<int,bool,\Inspirum\Arrayable\Arrayable<int,bool>> $collection
     *
     * @return \Inspirum\Arrayable\Arrayable<int,bool>
     */
    public function baseListCollectionItem(BaseListCollection $collection): Arrayable
    {
        return $collection[0];
    }

    /**
     * @param \Inspirum\Arrayable\BaseListCollection<int,bool,\Inspirum\Arrayable\Arrayable<int,bool>> $collection
     *
     * @return list<\Inspirum\Arrayable\Arrayable<int,bool>>
     */
    public function baseListCollectionItems(BaseListCollection $collection): array
    {
        return $collection->getItems();
    }

    /**
     * @param \Inspirum\Arrayable\BaseListCollection<int,bool,\Inspirum\Arrayable\Arrayable<int,bool>> $collection
     *
     * @return list<array<int,bool>>
     */
    public function baseListCollectionToArray(BaseListCollection $collection): array
    {
        return $collection->toArray();
    }

    /**
     * @param \Inspirum\Arrayable\BaseListCollection<int,bool,\Inspirum\Arrayable\Arrayable<int,bool>> $collection
     *
     * @return list<array<int,bool>>
     */
    public function baseListCollectionJsonSerialize(BaseListCollection $collection): array
    {
        return $collection->jsonSerialize();
    }

    /**
     * @param \Inspirum\Arrayable\Collection<string,float,int,\Inspirum\Arrayable\Arrayable<string,float>> $collection
     *
     * @return array<int,\Inspirum\Arrayable\Arrayable<string,float>>
     */
    public function collectionItems(Collection $collection): array
    {
        return $collection->getItems();
    }

    /**
     * @param \Inspirum\Arrayable\Collection<string,float,int,\Inspirum\Arrayable\Arrayable<string,float>> $collection
     *
     * @return array<int,array<string,float>>
     */
    public function collectionToArray(Collection $collection): array
    {
        return $collection->toArray();
    }

    /**
     * @param \Inspirum\Arrayable\ListCollection<string,float,\Inspirum\Arrayable\Arrayable<string,float>> $collection
     *
     * @return list<\Inspirum\Arrayable\Arrayable<string,float>>
     */
    public function listCollectionItems(ListCollection $collection): array
    {
        return $collection->getItems();
    }

    /**
     * @param \Inspirum\Arrayable\ListCollection<string,float,\Inspirum\Arrayable\Arrayable<string,float>> $collection
     *
     * @return list<array<string,float>>
     */
    public function listCollectionToArray(ListCollection $collection): array
    {
        return $collection->toArray();
    }
}
