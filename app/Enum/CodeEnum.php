<?php
/**
 * @Author Bob (bob@bobcoder.cc)
 */

namespace App\Enum;


class CodeEnum
{
    // 成功代码
    const SUCCESS = 1;

    // 错误代码
    const ERROR = 0;

    // 服务器错误
    const SERVER_ERROR = 2;

    // 权限不足
    const NO_PERMISSION = 3;

    //未登录
    const NO_LOGIN = -1;

    public static $message = [
        self::SUCCESS => '操作成功',
        self::ERROR => '操作失败',
        self::SERVER_ERROR => '服务器错误',
        self::NO_PERMISSION => '没有%s的接口权限，请联系管理员！',
        self::NO_LOGIN => '请登陆后再操作！',
    ];

    public static function message($code)
    {
        return isset(self::$message[$code]) ? self::$message[$code] : self::$message[self::SERVER_ERROR];
    }
}
