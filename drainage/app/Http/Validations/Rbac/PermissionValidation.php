<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:28
 */

namespace App\Http\Validations\Rbac;


use App\Http\Logic\Rbac\PermissionLogic;
use App\Http\Validations\Validation;
use Illuminate\Http\Request;
use Validator;
use App\Exceptions\BadRequestException;
use App\Exceptions\ForbiddenException;

class PermissionValidation extends Validation
{
    /**
     * @var PermissionLogic
     */
    protected $permissionLogic;

    /**
     * PermissionValidation constructor.
     * @param Request $request
     * @param PermissionLogic $permissionLogic
     */
    public function __construct(Request $request,PermissionLogic $permissionLogic)
    {
        parent::__construct($request);
        $this->permissionLogic = $permissionLogic;
    }

    /**
     * @return array
     */
    public function storeNewPermission()
    {
        $input = $this->filterRequest([
            'name','slug','description'
        ]);

        $rules = [
            'name' => ['string'],
            'slug' => ['required', 'string','unique:permissions'],
            'description' => ['string'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        return $input;
    }

    /**
     * @param $permissionID
     * @return array
     */
    public function updatePermission($permissionID)
    {
        $input = $this->filterRequest([
            'name', 'slug', 'description'
        ]);

        $rules = [
            'name' => ['string'],
            'slug' => ['required', 'string', 'unique:permissions'],
            'description' => ['string'],
        ];

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        $permission = $this->permissionLogic->findPermission($permissionID);
        if ($permission->id != $permissionID) {
            throw new ForbiddenException();
        }

        return $input;

    }

    /**
     * @return mixed
     */
    public function deletePermission()
    {
        $input = $this->filterRequest(['id']);
        $permissionID = json_decode($input['id']);

        if (empty($permissionID)) {
            throw new BadRequestException();
        }

        return $permissionID;
    }

    /**
     * @return array
     */
    public function permissionPaginate()
    {
        $input = $this->filterRequest(['page', 'cursor_page', 'order_column', 'order_direction']);

        $rules = [
            'page'            => ['integer'],
            'page_size'       => ['integer'],
            'cursor_page'     => ['integer'],
            'order_column'    => ['string', 'in:created_at,updated_at'],
            'order_direction' => ['string', 'in:asc,desc'],
        ];

        $validator  = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        return $input;
    }
}