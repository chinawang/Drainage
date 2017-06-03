<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/6/3
 * Time: 16:06
 */

namespace App\Http\Logic\Rbac;


use App\Repositories\Rbac\PermissionRepository;
use App\Repositories\Rbac\RolePermissionRepository;
use App\Repositories\Rbac\UserRoleRepository;
use Support\Logic\Logic;

class RbacLogic extends Logic
{
    /**
     * @var PermissionRepository
     */
    protected $permissionRepository;


    /**
     * @var UserRoleRepository
     */
    protected $userRoleRepository;

    /**
     * @var RolePermissionRepository
     */
    protected $rolePermissionRepository;

    /**
     * user's roles
     *
     * @var array
     */
    private $userRoles = array();

    /**
     * user's actions
     *
     * @var array
     */
    private $userActions = array();

    /**
     * RbacLogic constructor.
     * @param PermissionRepository $permissionRepository
     * @param UserRoleRepository $userRoleRepository
     * @param RolePermissionRepository $rolePermissionRepository
     */
    public function __construct(PermissionRepository $permissionRepository, UserRoleRepository $userRoleRepository,
                                RolePermissionRepository $rolePermissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->userRoleRepository = $userRoleRepository;
        $this->rolePermissionRepository = $rolePermissionRepository;
    }

    public function check($uid, $action)
    {
        if (empty($uid)) return false;

        // role
        $userRoles = $this->getUserRoles($uid);

        return $userRoles;

        // super administrator
        if (in_array(1, $userRoles)) return true;

        // check action
        if (empty($action)) return false;

        $actionId = $this->getActionIdByName($action);
        if (!$actionId) return false;

        // check permission
        $userActions = $this->getUserActions($uid);
        if (!in_array($actionId, $userActions)) return false;


        return true;
    }

    public function getUserRoles($uid)
    {
        if (isset($this->userRoles[$uid])) return $this->userRoles[$uid];

        $conditions['user_id'] = $uid;
        $fileds = ['role_id'];
        return $this->userRoles[$uid] = call_user_func_array($this->userRoleRepository->getBy($conditions),$fileds);
    }

    public function getRoleActions($roleId)
    {
        $conditions['role_id'] = $roleId;
        $fileds = ['permission_id'];
        return $this->object_to_array($this->rolePermissionRepository->getBy($conditions, array(), $fileds));
    }

    public function getUserActions($uid)
    {
        if (isset($this->userActions[$uid])) return $this->userActions[$uid];

        if (empty($this->userRoles[$uid])) return $this->userActions[$uid] = array();

        $userRoles = $this->userRoles[$uid];
        $userActions = array();

        for ($i = 0; $i < count($userRoles); $i++) {
            $tmpActions = $this->getRoleActions($userRoles[$i]);
            $userActions = array_merge($userActions, $tmpActions);
        }

        $this->userActions[$uid] = empty($userActions) ? array() : $userActions;

//        $test = $this->userActions[$uid];

        return $this->userActions[$uid];
    }

    /**
     * 根据权限名称获取ID
     *
     * @param $actionName
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActionIdByName($actionName)
    {
        $conditions['name'] = $actionName;
        $fileds = ['id'];
        return $this->permissionRepository->getBy($conditions, array(), $fileds);
    }

    /**
     * object 转 array
     */
    function object_to_array($stdclassobject)
    {
        $_array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;
        $array = array();
        foreach ($_array as $key => $value) {
            $value = (is_array($value) || is_object($value)) ? object_to_array($value) : $value;
            $array[$key] = $value;
        }
        return $array;
    }

}