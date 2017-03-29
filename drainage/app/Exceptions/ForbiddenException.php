<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/29
 * Time: 16:26
 */

namespace App\Exceptions;


class ForbiddenException extends ErrorException
{
    /**
     * ForbiddenException constructor.
     * @param $message
     */
    public function __construct($message = null)
    {
        $message = is_null($message) ? trans('validation.common.forbidden') : $message;
        parent::__construct(403, $message);
    }
}