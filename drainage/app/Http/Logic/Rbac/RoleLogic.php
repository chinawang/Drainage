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
        $conditions = [
            'delete_process' => 0,
            'id' => $roleId
        ];
        $role = $this->roleRepository->findBy($conditions);
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
        $conditions = ['delete_process' => 0];
        $roleList = $this->roleRepository->getPaginate($conditions,$pageSize,$orderColumn,$orderDirection,$cursorPage);
        return $roleList;
    }

    /**
     * @param $roleIDs
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRolesByIDs($roleIDs)
    {
        $roleList = $this->roleRepository->get($roleIDs);
        return $roleList;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRoles()
    {
        $conditions = [
            'delete_process' => 0
        ];
        $roleList = $this->roleRepository->getBy($conditions);
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