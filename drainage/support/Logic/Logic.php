<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:01
 */

namespace Support\Logic;

use Illuminate\Database\Eloquent\Collection;
use Support\Exception\InvalidParameterException;

abstract class Logic
{
    /**
     * 按指定ID列表排序
     * @param Collection $collection
     * @param array $IDs
     * @return Collection
     */
    protected function sortCollectionBySpecifiedIDs(Collection $collection, array $IDs)
    {
        $sortedCollection = Collection::make();

        foreach ($IDs as $ID) {
            $item = $collection->find($ID);

            if ( $item ) {
                $sortedCollection->push($item);
            }
        }

        return $sortedCollection;
    }

    /**
     * 过滤创建数据
     * @param array $attributes
     * @param array $allowKeys
     * @return array
     * @throws InvalidParameterException
     */
    protected function filterCreate(array $attributes, array $allowKeys)
    {
        $attributes = array_only($attributes, $allowKeys);

        if ( count($allowKeys) != count($attributes) ) {
            throw new InvalidParameterException;
        }

        return $attributes;
    }

    /**
     * 过滤更新数据
     * @param array $attributes
     * @param array $allowKeys
     * @return array
     * @throws InvalidParameterException
     */
    protected function filterUpdate(array $attributes, array $allowKeys)
    {
        $attributes = array_only($attributes, $allowKeys);

        if ( empty($attributes) ) {
            throw new InvalidParameterException;
        }

        return $attributes;
    }
}