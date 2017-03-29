<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/29
 * Time: 16:27
 */

namespace App\Exceptions;

class NotFoundException extends ErrorException
{
    /**
     * NotFoundException constructor.
     * @param null $message
     */
    public function __construct($message = null)
    {
        $message = is_null($message) ? trans('validation.common.not_found') : $message;
        parent::__construct(404, $message);
    }
}