<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:13
 */

namespace App\Http\Logic\Rbac;


use App\Repositories\Rbac\PermissionRepository;
use Support\Logic\Logic;

class PermissionLogic extends Logic
{
    /**
     * @var PermissionRepository
     */
    protected $permissionRepository;

    /**
     * PermissionLogic constructor.
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @param $permissionId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findPermission($permissionId)
    {
        $conditions = [
            'delete_process' => 0,
            'id' => $permissionId
        ];
        $permission = $this->permissionRepository->findBy($conditions);
        return $permission;
    }

    /**
     * @param $pageSize
     * @param $orderColumn
     * @param $orderDirection
     * @param null $cursorPage
     * @return mixed
     */
    public function getPermissions($pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        $conditions = ['delete_process' => 0];
        $permissionList = $this->permissionRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);
        return $permissionList;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPermissions()
    {
        $conditions = [
            'delete_process' => 0
        ];
        $permissionList = $this->permissionRepository->getBy($conditions);
        return $permissionList;
    }

    /**
     * @param $permissionIDs
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPermissionsByIDs($permissionIDs)
    {
        $permissionList = $this->permissionRepository->get($permissionIDs);
        return $permissionList;
    }

    /**
     * @param $attributes
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function createPermission($attributes)
    {
        return $this->permissionRepository->create($attributes);
    }

    /**
     * @param $permissionId
     * @param $input
     * @return bool
     */
    public function updatePermission($permissionId,$input)
    {
        return $this->permissionRepository->update($permissionId,$input);
    }

    /**
     * @param $permissionId
     * @return bool
     */
    public function deletePermission($permissionId)
    {
        return $this->permissionRepository->delete($permissionId);
    }
}