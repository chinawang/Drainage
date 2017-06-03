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
    public function __construct(PermissionRepository $permissionRepository,UserRoleRepository $userRoleRepository,
                                RolePermissionRepository $rolePermissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->userRoleRepository = $userRoleRepository;
        $this->rolePermissionRepository = $rolePermissionRepository;
    }

    public function check($uid,$action)
    {
        if (empty($uid)) return false;

        // role
        $userRoles = $this->getUserRoles($uid);

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
        return $this->userRoles[$uid] = $this->object_to_array($this->userRoleRepository->getBy($conditions,array(),$fileds));
    }

    public function getRoleActions($roleId)
    {
        $conditions['role_id'] = $roleId;
        $fileds = ['permission_id'];
        return $this->object_to_array($this->rolePermissionRepository->getBy($conditions,array(),$fileds));
    }

    public function getUserActions($uid)
    {
        if (isset($this->userActions[$uid])) return $this->userActions[$uid];

        if (empty($this->userRoles[$uid])) return $this->userActions[$uid] = array();

        $userRoles = $this->userRoles[$uid];
        $userActions = array();

        for($i = 0 ; $i < count($userRoles) ; $i++)
        {
            $tmpActions = $this->getRoleActions($userRoles[$i]);
            $userActions = array_merge($userActions,$tmpActions);
        }

        return $this->userActions[$uid] = empty($userActions) ? array() : $userActions;
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
        return $this->permissionRepository->getBy($conditions,array(),$fileds);
    }

    /**
     * object 转 array
     */
    function object_to_array($obj){
        $_arr = is_object($obj)? get_object_vars($obj) : $obj;
        foreach ($_arr as $key => $val) {
            $val = (is_array($val)) || is_object($val) ? object_to_array($val) : $val;
            $arr[$key] = $val;
        }

        return $arr;
    }

}