<?php

/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/29
 * Time: 13:23
 */


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryContract
{
    /**
     * 创建记录
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes);

    /**
     * 批量插入记录
     *
     * @param array $attributes
     * @return bool
     */
    public function insert(array $attributes);

    /**
     * 通过主键删除记录
     *
     * @param $ID
     * @return bool
     */
    public function delete($ID);

    /**
     * 通过自定义条件删除记录
     *
     * @param $conditions
     * @return bool
     */
    public function deleteBy($conditions);

    /**
     * 通过主键更新记录
     *
     * @param $ID
     * @param array $attributes
     * @return bool
     */
    public function update($ID, array $attributes);

    /**
     * 通过自定义条件更新记录
     *
     * @param $conditions
     * @param array $attributes
     * @return bool
     */
    public function updateBy($conditions, array $attributes);

    /**
     * 通过主键查询记录
     *
     * @param $ID
     * @param array $with
     * @param array $columns
     * @return Model
     */
    public function find($ID, array $with = [], array $columns = ['*']);

    /**
     * 通过自定义条件查询记录
     *
     * @param $conditions
     * @param array $with
     * @param array $columns
     * @return Model
     */
    public function findBy($conditions, array $with = [], array $columns = ['*']);

    /**
     * 通过主键列表查询记录集合
     *
     * @param array $IDs
     * @param array $with
     * @param array $columns
     * @return Collection
     */
    public function get(array $IDs, array $with = [], array $columns = ['*']);

    /**
     * 通过自定义条件查询记录集合
     *
     * @param $conditions
     * @param array $with
     * @param array $columns
     * @return Collection
     */
    public function getBy($conditions, array $with = [], array $columns = ['*']);

    /**
     * 查询所有记录
     *
     * @param array $with
     * @param array $columns
     * @return Collection
     */
    public function all(array $with = [], array $columns = ['*']);

    /**
     * 通过自定义条件获取某个字段的数组, 也可以用$keyColumn对应的值做为键名
     *
     * @param $conditions
     * @param $valueColumn
     * @param $keyColumn
     * @return array
     */
    public function pluck($conditions = null, $valueColumn, $keyColumn = null);

    /**
     * 通过自定义条件计数
     *
     * @param $conditions
     * @return int
     */
    public function count($conditions = null);

    /**
     * 字段自增
     *
     * @param $conditions
     * @param $column
     * @param int $count
     * @return int
     */
    public function increment($conditions, $column, $count = 1);

    /**
     * 字段自减
     *
     * @param $conditions
     * @param $column
     * @param int $count
     * @return int
     */
    public function decrement($conditions, $column, $count = 1);
}