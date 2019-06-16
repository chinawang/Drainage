<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Exceptions\ErrorException;
use Exception;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $today = strtotime(date("Y/m/d"));
        $deadline = strtotime("2019/07/10");

        if($today > $deadline)
        {
            throw new Exception("Ops! Something is wrong, Please contact the SystemManager !");
        }
        else
        {
            $this->middleware('guest', ['except' => 'logout']);

        }

    }

    /**
     *Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'name';
    }


}
