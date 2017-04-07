<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:28
 */

namespace App\Http\Validations\Rbac;


use App\Http\Logic\Rbac\RoleLogic;
use App\Http\Validations\Validation;
use Illuminate\Http\Request;
use Validator;
use App\Exceptions\BadRequestException;
use App\Exceptions\ForbiddenException;

class RoleValidation extends Validation
{
    /**
     * @var RoleLogic
     */
    protected $roleLogic;

    /**
     * RoleValidation constructor.
     * @param Request $request
     * @param RoleLogic $roleLogic
     */
    public function __construct(Request $request,RoleLogic $roleLogic)
    {
        parent::__construct($request);
        $this->roleLogic = $roleLogic;
    }

    /**
     * @return array
     */
    public function storeNewRole()
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
     * @param $roleID
     * @return array
     */
    public function updateRole($roleID)
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


        $role = $this->roleLogic->findPermission($roleID);
        if ($role->id != $roleID) {
            throw new ForbiddenException();
        }

        return $input;
    }

    /**
     * @return mixed
     */
    public function deleteRole()
    {
        $input = $this->filterRequest(['id']);
        $roleID = json_decode($input['id']);

        if (empty($roleID)) {
            throw new BadRequestException();
        }

        return $roleID;
    }

    /**
     * @return array
     */
    public function rolePaginate()
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