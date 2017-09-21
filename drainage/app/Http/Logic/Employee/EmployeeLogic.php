<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/9/20
 * Time: 22:13
 */

namespace App\Http\Logic\Employee;

use App\Repositories\Employee\EmployeeRepository;
use Support\Logic\Logic;


class EmployeeLogic extends Logic
{
    protected $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function findEmployee($employeeId)
    {
        $conditions = ['delete_process' => 0,'id' => $employeeId];
        $employee = $this->employeeRepository->findBy($conditions);
        return $employee;
    }

    public function getEmployees($pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $conditions = ['delete_process' => 0];
        $employeeList = $this->employeeRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);
        return $employeeList;
    }

    public function getEmployeesBy($conditions,$pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $employeeList = $this->employeeRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);
        return $employeeList;
    }

    public function getEmployeesByIDs($employeeIDs)
    {
        $employeeList = $this->employeeRepository->get($employeeIDs);
        return $employeeList;
    }

    public function getAllEmployees()
    {
        $conditions = ['delete_process' => 0];
        $employeeList = $this->employeeRepository->getBy($conditions);
        return $employeeList;
    }

    public function createEmployee($attributes)
    {
        return $this->employeeRepository->create($attributes);
    }

    public function updateEmployee($employeeId,$input)
    {
        return $this->employeeRepository->update($employeeId,$input);
    }

    public function deleteEmployee($employeeId)
    {
        return $this->employeeRepository->deleteByFlag($employeeId);
    }

}