<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Routing\Registrar as RouteContract;
Route::group(['namespace' => 'Api\V1'], function (RouteContract $api) {
    // 登录
    $api->post('auth/login', 'AuthController@login');
    // 商品列表
    $api->get('shop', 'ShopController@index');
    // 检查优惠价
    $api->post('checkCoupon', 'ShopController@checkCoupon');
    // 检测用户邮箱
    $api->post('checkEmail', 'UserController@checkEmail');
    // 提交订单
    $api->post('submitOrder', 'ShopController@submitOrder');
    // API中间件
    $api->group(['middleware' => 'auth:api'], function (RouteContract $api) {
        // 获取订单详情
        $api->get('order/info/{id}', 'OrderController@info');
        // 获取支付地址
        $api->get('order/pay/{id}', 'OrderController@pay');
        // 用户相关信息
        $api->group(['prefix' => 'user'], function (RouteContract $api) {
            // 获取服务
            $api->get('service', 'UserController@getService');
            // 获取用户信息
            $api->get('info', 'UserController@getInfo');
            // 查看账单列表
            $api->get('invoices', 'UserController@invoices');
            // 重置登录密码
            $api->post('reset/password', 'UserController@resetPassword');
            // 激活预支付
            $api->post('active/prepaid', 'UserController@closePlan');
            // 重置订阅
            $api->post('reset/subscribe', 'UserController@resetSubscribe');
        });
    });
});
