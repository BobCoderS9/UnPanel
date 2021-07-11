@if($user->qq)
    <img src="https://q1.qlogo.cn/g?b=qq&nk={{$user->qq}}&s=640" alt="{{trans('common.avatar')}}">
@elseif(stripos(strtolower($user->email),'@qq.com') !== false)
    <img src="https://q1.qlogo.cn/g?b=qq&nk={{$user->email}}&s=640" alt="{{trans('common.avatar')}}">
@else
    <img src="/assets/images/avatar.svg" alt="{{trans('common.avatar')}}">
@endif