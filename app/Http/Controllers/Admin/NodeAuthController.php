<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\NodeAuth;
use Exception;
use Response;
use Str;

class NodeAuthController extends Controller
{
    // 节点授权列表
    public function index()
    {
        return view('admin.node.auth', ['authorizations' => NodeAuth::orderBy('node_id')->paginate()->appends(request('page'))]);
    }

    // 添加节点授权
    public function store()
    {
        $nodes = Node::whereStatus(1)->doesntHave('auth')->orderBy('id')->get();

        if ($nodes->isEmpty()) {
            return Response::json(['status' => 'success', 'message' => '没有需要生成授权的节点']);
        }
        $nodes->each(function ($node) {
            $node->auth()->create(['key' => Str::random(), 'secret' => Str::random(8)]);
        });

        return Response::json(['status' => 'success', 'message' => trans('common.generate_item', ['attribute' => trans('common.success')])]);
    }

    // 重置节点授权
    public function update(NodeAuth $auth)
    {
        if ($auth->update(['key' => Str::random(), 'secret' => Str::random(8)])) {
            return Response::json(['status' => 'success', 'message' => '操作成功']);
        }

        return Response::json(['status' => 'fail', 'message' => '操作失败']);
    }

    // 删除节点授权
    public function destroy(NodeAuth $auth)
    {
        try {
            $auth->delete();
        } catch (Exception $e) {
            return Response::json(['status' => 'fail', 'message' => '错误：'.var_export($e, true)]);
        }

        return Response::json(['status' => 'success', 'message' => '操作成功']);
    }
}
