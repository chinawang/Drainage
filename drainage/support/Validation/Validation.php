<?php
/**
 * Created by PhpStorm.
 * User: wangyx
 * Date: 2017/3/29
 * Time: 13:26
 */

use App\Exceptions\BadRequestException;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class Validation
 * @package App\Http\Validations
 * @property \Illuminate\Database\Eloquent\Model $user
 */
class Validation
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Validator constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 过滤请求参数
     *
     * @param array $keys
     * @param bool $trim
     * @return array
     */
    public function filterRequest(array $keys, $trim = true)
    {
        $input = $this->request->only($keys);

        if ( $trim ) {
            array_walk($input, function(&$value) {
                if ( is_string($value) ) {
                    $value = trim($value);
                }
            });
        }

        return array_filter($input, function($value) {
            return ! is_null($value);
        });
    }

    /**
     * Create a new Validator instance.
     *
     * @param  array   $input
     * @param  array   $rules
     * @param  string  $namespace
     * @return \Illuminate\Validation\Validator
     */
    public function validator(array $input, array $rules, $namespace = null)
    {
        /** @var Validator $validator */
        $validator = $this->getValidationFactory()->make($input, $rules);

        if ( $validator->fails() ) {
            $errorAttribute = $validator->getErrorAttribute();
            if ( !is_null($namespace) ) $errorAttribute = $namespace.'.'.$errorAttribute;
            throw new BadRequestException(null, $errorAttribute);
        }

        return $validator;
    }

    /**
     * Format datetime.
     *
     * @param string $datetime
     * @param string $format
     * @return string
     */
    public function formatDatetime($datetime, $format = 'Y-m-d H:i:s')
    {
        return Carbon::parse($datetime)->format($format);
    }

    /**
     * Get a validation factory instance.
     *
     * @return \Illuminate\Contracts\Validation\Factory
     */
    protected function getValidationFactory()
    {
        return app('validator');
    }

}