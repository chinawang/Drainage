<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:13
 */

namespace App\Http\Logic\Rbac;


use App\Repositories\Rbac\RoleRepository;
use Support\Logic\Logic;

class RoleLogic extends Logic
{
    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * RoleLogic constructor.
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param $roleId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findRole($roleId)
    {
        $role = $this->roleRepository->find($roleId);
        return $role;
    }

    /**
     * @param $pageSize
     * @param $orderColumn
     * @param $orderDirection
     * @param null $cursorPage
     * @return mixed
     */
    public function getRoles($pageSize, $orderColumn, $orderDirection, $cursorPage = null)
    {
        //$conditions = ['delete_process' => 0];
        $roleList = $this->roleRepository->getPaginate($pageSize,$orderColumn,$orderDirection,$cursorPage);
        return $roleList;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRoles()
    {
        $roleList = $this->roleRepository->all();
        return $roleList;
    }

    /**
     * @param $attributes
     * @return bool|\Illuminate\Database\Eloquent\Model
     */
    public function createRole($attributes)
    {
        return $this->roleRepository->create($attributes);
    }

    /**
     * @param $roleId
     * @param $input
     * @return bool
     */
    public function updateRole($roleId,$input)
    {
        return $this->roleRepository->update($roleId,$input);
    }

    /**
     * @param $roleId
     * @return bool
     */
    public function deleteRole($roleId)
    {
        return $this->roleRepository->delete($roleId);
    }

}