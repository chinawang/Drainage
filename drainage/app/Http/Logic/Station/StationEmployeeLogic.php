<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/9/21
 * Time: 13:40
 */

namespace App\Http\Logic\Station;

use App\Repositories\Employee\EmployeeRepository;
use App\Repositories\Station\StationEmployeeRepository;
use Support\Logic\Logic;

class StationEmployeeLogic extends Logic
{
    protected $stationEmployeeRepository;
    protected $employeeRepository;

    public function __construct(StationEmployeeRepository $stationEmployeeRepository,EmployeeRepository $employeeRepository)
    {
        $this->stationEmployeeRepository = $stationEmployeeRepository;
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findStationEmployee($id)
    {
        $stationEmployee = $this->stationEmployeeRepository->find($id);
        return $stationEmployee;
    }

    /**
     * @param $stationId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStationEmployees($stationId)
    {
        $conditions['station_id'] = $stationId;
        $stationEmployeeList = $this->stationEmployeeRepository->getBy($conditions);
        return $stationEmployeeList;
    }

    /**
     * 根据泵站ID查找工作人员ID列表
     *
     * @param $stationId
     * @return array
     */
    public function getEmployeeIDsByStationID($stationId)
    {
        $stationEmployeeList = $this->getStationEmployees($stationId);
        $employeeIDs = array();

        foreach ($stationEmployeeList as $stationEmployee){
            $employeeIDs[] = $stationEmployee['employee_id'];
        }

        return $employeeIDs;
    }

    /**
     * 根据泵站ID查找工作人员信息
     *
     * @param $stationId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEmployeesByStation($stationId)
    {
        $employeeIDs = $this->getEmployeeIDsByStationID($stationId);
        $employees = $this->employeeRepository->get($employeeIDs);
        return $employees;
    }

    /**
     * @param $stationId
     * @param $employeeIDs
     * @return int
     */
    public function addStationEmployees($stationId,$employeeIDs)
    {
        return $this->stationEmployeeRepository->addEmployees($stationId,$employeeIDs);
    }

    /**
     * @param $stationId
     * @param $employeeIDs
     * @return int
     */
    public function deleteStationEmployees($stationId,$employeeIDs)
    {
        return $this->stationEmployeeRepository->deleteEmployees($stationId,$employeeIDs);
    }

    public function setStationEmployees($stationId,$employeeIDs)
    {
        /**
         * 已经分配工作人员
         */
        $assignEmployeeIDs =$this->getEmployeeIDsByStationID($stationId);

        $deleteResult = true;
        $addResult = true;

        /**
         * 找出删除的人员
         * 假如已有的角色集合是A，界面传递过得角色集合是B
         * 角色集合A当中的某个角色不在角色集合B当中，就应该删除
         * array_diff();计算补集
         */
        $deleteEmployeeIDs = array_diff($assignEmployeeIDs,$employeeIDs);

        if($deleteEmployeeIDs)
        {
            $deleteResult = $this->stationEmployeeRepository->deleteEmployees($stationId,$deleteEmployeeIDs);
        }

        /**
         * 找出添加的人员
         * 假如已有的角色集合是A，界面传递过得角色集合是B
         * 角色集合B当中的某个角色不在角色集合A当中，就应该添加
         */
        $newEmployeeIDs = array_diff($employeeIDs,$assignEmployeeIDs);

        if($newEmployeeIDs)
        {
            $addResult = $this->stationEmployeeRepository->addEmployees($stationId,$newEmployeeIDs);
        }

        return ($deleteResult && $addResult);
    }

}