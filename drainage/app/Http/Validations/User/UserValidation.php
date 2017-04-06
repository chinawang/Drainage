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
            'name'  => ['required', 'string'],
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



}