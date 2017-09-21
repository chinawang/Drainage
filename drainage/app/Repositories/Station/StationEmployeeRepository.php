<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/9/21
 * Time: 13:26
 */

namespace App\Repositories\Station;

use App\Models\Station\StationEmployeeModel;
use Support\Repository\Repository;

class StationEmployeeRepository extends Repository
{
    /**
     * @return string
     */
    protected function model()
    {
        return StationEmployeeModel::class;
    }

    /**
     * 新增
     *
     * @param $stationID
     * @param array $employeeIDs
     * @return int
     */
    public function addEmployees($stationID, array $employeeIDs)
    {
        $attributes = [];

        array_map(function($employeeID) use ($stationID, &$attributes) {
            array_push($attributes, [
                'station_id' => $stationID,
                'employee_id' => $employeeID,
            ]);
        }, $employeeIDs);

        return $this->insert($attributes);
    }

    /**
     * 删除
     *
     * @param $stationID
     * @param array $employeeIDs
     * @return int
     */
    public function deleteEmployees($stationID, array $employeeIDs)
    {
        return $this->model->where('station_id', $stationID)->whereIn('employee_id', $employeeIDs)->delete();
    }

    /**
     * 分页获取列表
     *
     * @param mixed $conditions
     * @param string $orderColumn
     * @param string $orderDirection
     * @param int $cursor
     * @param int $size
     * @param array $with
     * @param array $fiel
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByPage($conditions, $orderColumn, $orderDirection, $cursor, $size, array $with = [], array $fields = ['*'])
    {
        return $this->model->where($conditions)
            ->orderBy($orderColumn, $orderDirection)
            ->skip($cursor)->take($size)->with($with)
            ->get($fields);
    }

    /**
     * 按条件获取分页数据
     *
     * @param $conditions
     * @param $size
     * @param $orderColumn
     * @param $orderDirection
     * @param int $cursorPage
     * @return mixed
     */
    public function getPaginate($conditions, $size, $orderColumn, $orderDirection, $cursorPage = null)
    {
        return $this->model->where($conditions)
            ->orderBy($orderColumn, $orderDirection)
            ->paginate($size, $columns = ['*'], $pageName = 'page', $cursorPage);
    }
}