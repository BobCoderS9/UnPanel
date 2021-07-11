<?php


namespace App\Http\Controllers\Api\V1;


use App\Enum\CodeEnum;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{

    /**
     * send error json string
     * @param int $code
     * @param string $message
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function sendSuccess($message = '')
    {
        $method = request()->input('method');
        $callback = request()->input('callback');

        if ($method === 'jsonp' && $callback)
            return Response()->jsonp($callback, ['code' =>  CodeEnum::SUCCESS, 'msg' => $message ? $message : CodeEnum::message( CodeEnum::SUCCESS)]);

        $headers = ['content-type' => 'application/json'];
        return Response()->json(['code' =>  CodeEnum::SUCCESS, 'msg' => $message ? $message : CodeEnum::message( CodeEnum::SUCCESS)])
            ->withHeaders($headers);
    }

    /**
     * send error json string
     * @param string $message
     * @param int $code
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function sendError($message = '', $code = CodeEnum::ERROR)
    {
        $method = request()->input('method');
        $callback = request()->input('callback');

        if ($method === 'jsonp' && $callback)
            return Response()->jsonp($callback, ['code' => $code, 'msg' => $message ? $message : CodeEnum::message($code)]);

        $headers = ['content-type' => 'application/json'];
        return Response()->json(['code' => $code, 'msg' => $message ? $message : CodeEnum::message($code)])
            ->withHeaders($headers);
    }

    /**
     * send success json string
     * @param array $data
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function sendJson($data = [])
    {
        $method = request()->input('method');
        $callback = request()->input('callback');

        if ($method === 'jsonp' && $callback)
            return Response()->jsonp($callback, ['code' => CodeEnum::SUCCESS, 'data' => $data, 'msg' => CodeEnum::message(CodeEnum::SUCCESS)]);

        $headers = ['content-type' => 'application/json'];
        return Response()->json(['code' => CodeEnum::SUCCESS, 'data' => $data, 'msg' => CodeEnum::message(CodeEnum::SUCCESS)])
            ->withHeaders($headers);
    }
}
