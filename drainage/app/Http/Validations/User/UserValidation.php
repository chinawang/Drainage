<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/30
 * Time: 20:23
 */

namespace App\Http\Validations\User;


use App\Http\Logic\User\UserLogic;
use App\Http\Validations\Validation;
use Illuminate\Validation\Validator;

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
            'password' => ['required', 'string', 'min:6'],
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
            'employee_number','realname','office','contact','password'
        ]);

        $rules = [
            'employee_number' => ['string'],
            'realname' => ['string'],
            'office' => ['string'],
            'contact' => ['string'],
            'password' => ['required', 'string', 'min:6'],
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
     * @param $userID
     * @return array
     */
    public function resetPassword($userID)
    {
        $input = $this->filterRequest(['old', 'new']);

        $rules = [
            'old' => ['required', 'string'],
            'new' => ['required', 'string', 'min:6'],
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            throw new BadRequestException($validator->errors());
        }

        $user = $this->userLogic->findUser($userID);

        if (! Hash::check($input['old'], $user->password)) {
            throw new ForbiddenException(trans('old_password_error'));
        }


        return $input;
    }

}