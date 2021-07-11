@extends('admin.layouts')
@section('css')
    <link href="/assets/global/vendor/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">用户封禁记录</h3>
            </div>
            <div class="panel-body">
                <form class="form-row">
                    <div class="form-group col-lg-3 col-sm-6">
                        <input type="text" class="form-control" name="email" value="{{Request::query('email')}}" placeholder="用户账号"/>
                    </div>
                    <div class="form-group col-lg-2 col-sm-6 btn-group">
                        <button type="submit" class="btn btn-primary">搜 索</button>
                        <a href="{{route('admin.log.ban')}}" class="btn btn-danger">{{trans('common.reset')}}</a>
                    </div>
                </form>
                <table class="text-md-center" data-toggle="table" data-mobile-responsive="true">
                    <thead class="thead-default">
                    <tr>
                        <th> #</th>
                        <th> 用户账号</th>
                        <th> 时长</th>
                        <th> 理由</th>
                        <th> 封禁时间</th>
                        <th> 最后连接时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($userBanLogs as $log)
                        <tr>
                            <td>
                                {{$log->id}}
                            </td>
                            <td>
                                @if ($log->user)
                                    @can('admin.user.index')
                                        <a href="{{route('admin.user.index', ['email'=>$log->user->email])}}" target="_blank"> {{$log->user->email}}</a>
                                    @else
                                        {{$log->user->email}}
                                    @endcan
                                @else
                                    【{{trans('common.deleted_item', ['attribute' => trans('common.account')])}}】
                                @endif
                            </td>
                            <td> {{$log->time}}分钟</td>
                            <td> {{$log->description}} </td>
                            <td> {{$log->created_at}} </td>
                            <td> {{date('Y-m-d H:i:s', $log->user->t)}} </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-sm-4">
                        共 <code>{{$userBanLogs->total()}}</code> 条记录
                    </div>
                    <div class="col-sm-8">
                        <nav class="Page navigation float-right">
                            {{$userBanLogs->links()}}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script src="/assets/global/vendor/bootstrap-table/bootstrap-table.min.js"></script>
    <script src="/assets/global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.min.js"></script>
@endsection
