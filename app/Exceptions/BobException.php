<?php

namespace App\Exceptions;

use App\Enum\CodeEnum;
use Exception;

class BobException extends Exception
{
    /**
     * @var string
     */
    protected $message;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var int
     */
    protected $StatusCode;

    /**
     * BobException constructor.
     * @param string $message
     * @param int $code
     * @param int $StatusCode
     */
    public function __construct($message = '', $code = 0, $StatusCode = 400)
    {
        $this->message = $message;
        $this->code = $code;
        $this->StatusCode = $StatusCode;
    }

    public function report()
    {

    }

    /**
     * 将异常渲染至 HTTP 响应值中。
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     * @author Bob(bobcoder@qq.com)
     */
    public function render($request)
    {
        $code = $this->getCode() ?? CodeEnum::ERROR;    //状态码
        $message = $this->getMessage() ?? CodeEnum::$message[$code];    //返回信息

        return response()->json([
            'code' => $this->getCode() ?? CodeEnum::ERROR,
            'msg' => $message
        ])
            ->setStatusCode($this->StatusCode);
    }
}
