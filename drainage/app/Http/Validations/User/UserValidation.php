<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:23
 */

namespace App\Http\Validations\User;


use App\Exceptions\BadRequestException;
use App\Exceptions\ForbiddenException;
use App\Http\Logic\User\UserLogic;
use App\Http\Validations\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserValidation extends Validation
{
    /**
     * @var UserLogic
     */
    protected $userLogic;

    /**
     * UserValidation constructor.
     * @param Request $request
     * @param UserLogic $userLogic
     */
    public function __construct(Request $request,UserLogic $userLogic)
    {
        parent::__construct($request);
        $this->userLogic = $userLogic;
    }

    /**
     * @return array
     */
    public function storeNewUser()
    {
        $input = $this->filterRequest([
            'name','employee_number','realname','office','contact','password'
        ]);

        $rules = [
            'name'  => ['required', 'string','unique:users'],
            'employee_number' => ['string'],
            'realname' => ['string'],
            'office' => ['string'],
            'contact' => ['string'],
            'password' => ['required', 'string'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        return $input;
    }

    /**
     * @param $userID
     * @return array
     */
    public function updateUser($userID)
    {
        $input = $this->filterRequest([
            'employee_number','realname','office','contact'
        ]);

        $rules = [
            'employee_number' => ['string'],
            'realname' => ['string'],
            'office' => ['string'],
            'contact' => ['string'],
            'password' => ['string', 'min:6'],
        ];

        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        $user = $this->userLogic->findUser($userID);
        if($user->id != $userID) {
            throw new ForbiddenException();
        }

        return $input;
    }

    /**
     * @return mixed
     */
    public function deleteUser()
    {
        $input = $this->filterRequest(['id']);
        $userID = json_decode($input['id']);

        if (empty($userID)) {
            throw new BadRequestException();
        }

        return $userID;
    }

    /**
     * @return array
     */
    public function userPaginate()
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

    /**
     * @param $userID
     * @return array
     */
    public function resetPassword($userID)
    {
        $input = $this->filterRequest(['newPassword']);

        $rules = [
            'newPassword' => ['required', 'string', 'min:1'],
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        $user = $this->userLogic->findUser($userID);
        if($user->id != $userID) {
            throw new ForbiddenException();
        }

        return $input;
    }

}