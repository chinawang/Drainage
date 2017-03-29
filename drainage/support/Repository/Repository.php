<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/29
 * Time: 13:24
 */

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class Repository implements RepositoryContract
{
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    private $app;

    /**
     * @var Model
     */
    protected $model;

    /**
     * Repository constructor.
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;

        $this->makeModel();
    }

    /**
     * Bind Model.
     *
     * @return string
     */
    abstract protected function model();

    /**
     * 创建记录
     *
     * @param array $attributes
     * @return Model|bool
     */
    public function create(array $attributes = [])
    {
        $model = $this->model->newInstance($attributes);
        $saved = $model->save();
        return $saved ? $model : false;
    }

    /**
     * 批量插入记录
     *
     * @param array $attributes
     * @return bool
     */
    public function insert(array $attributes)
    {
        return $this->model->insert($attributes);
    }

    /**
     * 通过主键删除记录
     *
     * @param $ID
     * @return bool
     */
    public function delete($ID)
    {
        return $this->model->where($this->model->getKeyName(), $ID)->delete();
    }

    /**
     * 通过自定义条件删除记录
     *
     * @param mixed $conditions
     * @return bool
     */
    public function deleteBy($conditions)
    {
        return $this->model->where($conditions)->delete();
    }

    /**
     * 通过主键更新记录
     *
     * @param $ID
     * @param array $attributes
     * @return bool
     */
    public function update($ID, array $attributes)
    {
        return $this->model->where($this->model->getKeyName(), $ID)->update($attributes);
    }

    /**
     * 通过自定义条件更新记录
     *
     * @param mixed $conditions
     * @param array $attributes
     * @return int
     */
    public function updateBy($conditions, array $attributes)
    {
        return $this->model->where($conditions)->update($attributes);
    }

    /**
     * 通过主键查询记录
     *
     * @param $ID
     * @param array $with
     * @param array $columns
     * @return Model
     */
    public function find($ID, array $with = [], array $columns = ['*'])
    {
        return $this->model->with($with)->find($ID, $columns);
    }

    /**
     * 通过自定义条件查询记录
     *
     * @param $conditions
     * @param array $with
     * @param array $columns
     * @return Model
     */
    public function findBy($conditions, array $with = [], array $columns = ['*'])
    {
        return $this->model->where($conditions)->with($with)->first($columns);
    }

    /**
     * 通过主键列表查询记录集合
     *
     * @param array $IDs
     * @param array $with
     * @param array $fields
     * @return Collection
     */
    public function get(array $IDs, array $with = [], array $fields = ['*'])
    {
        return $this->model->with($with)->findMany($IDs, $fields);
    }

    /**
     * 通过自定义条件查询记录集合
     *
     * @param $conditions
     * @param array $with
     * @param array $fields
     * @return Collection
     */
    public function getBy($conditions, array $with = [], array $fields = ['*'])
    {
        return $this->model->where($conditions)->with($with)->get($fields);
    }

    /**
     * 查询所有记录
     *
     * @param array $with
     * @param array $fields
     * @return Collection
     */
    public function all(array $with = [], array $fields = ['*'])
    {
        return $this->model->with($with)->get($fields);
    }

    /**
     * 通过自定义条件获取某个字段的数组, 也可以用$keyColumn对应的值做为键名
     *
     * @param $conditions
     * @param $valueColumn
     * @param $keyColumn
     * @return \Illuminate\Support\Collection
     */
    public function pluck($conditions = null, $valueColumn, $keyColumn = null)
    {
        $model = $this->model;

        if ( ! is_null($conditions) ) {
            $model = $model->where($conditions);
        }

        return $model->pluck($valueColumn, $keyColumn);
    }

    /**
     * 通过自定义条件计数
     *
     * @param $conditions
     * @return int
     */
    public function count($conditions = null)
    {
        $model = $this->model;

        if ( !is_null($conditions) ) {
            $model = $model->where($conditions);
        }

        return $model->count();
    }

    /**
     * 通过自定义条件求和
     *
     * @param $conditions
     * @param $column
     * @return mixed
     */
    public function sum($conditions, $column)
    {
        $model = $this->model;

        if ( !is_null($conditions) ) {
            $model = $model->where($conditions);
        }

        return $model->sum($column);
    }

    /**
     * 字段自增
     *
     * @param $conditions
     * @param $column
     * @param int $count
     * @return int
     */
    public function increment($conditions, $column, $count = 1)
    {
        return $this->model->where($conditions)->increment($column, $count);
    }

    /**
     * 字段自增
     *
     * @param $conditions
     * @param $column
     * @param int $count
     * @return int
     */
    public function decrement($conditions, $column, $count = 1)
    {
        return $this->model->where($conditions)->decrement($column, $count);
    }

    /**
     * @return Model
     * @throws RepositoryException
     */
    protected function makeModel()
    {
        $model = $this->app->make($this->model());

        if ( !$model instanceof Model ) {
            throw new RepositoryException(
                "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        return $this->model = $model;
    }
}