<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/29
 * Time: 16:26
 */

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrorException extends HttpException
{
    /**
     * Error Code.
     * @var string
     */
    private $parameter;

    /**
     * ErrorException constructor.
     * @param string $statusCode
     * @param string $message
     * @param string $parameter
     */
    public function __construct($statusCode, $message = null, $parameter = null)
    {
        $this->parameter = $parameter;

        parent::__construct($statusCode, $message);
    }

    /**
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }
}