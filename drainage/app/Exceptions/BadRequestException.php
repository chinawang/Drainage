<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/29
 * Time: 16:25
 */

namespace App\Exceptions;

class BadRequestException extends ErrorException
{
    /**
     * BadRequestException constructor.
     * @param string $message
     * @param string $parameter
     */
    public function __construct($message = null, $parameter = null)
    {
        $message = is_null($message) ? trans('validation.common.bad_request') : $message;
        parent::__construct(400, $message, $parameter);
    }
}