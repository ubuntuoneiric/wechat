@extends('layout.admin')
@section('title','所有粉丝')
@section('content')
    <?php
        $arr = ['1'=>"男",'2'=>"女"];
    ?>
    <table class="table table-condensed">
        <tr>
            <td>ID</td>
            <td>用户openid</td>
            <td>用户昵称</td>
            <td>用户性别</td>
            <td>用户头像</td>
            <td>城市</td>
            <td>关注时间</td>
        </tr>
        @foreach($data as $v)
            <tr>
                <td class="active">{{$v['fid']}}</td>
                <td class="success">{{$v['openid']}}</td>
                <td class="info">{{$v['nickname']}}</td>
                <td>{{$arr[$v['sex']]}}</td>
                <td>
                    <img src="{{$v['headimgurl']}}" alt="">
                </td>
                <td>{{$v['city']}}</td>
                <td>{{date("Y-m-d H:i:s",$v['subscribe_time'])}}</td>
            </tr>
        @endforeach
    </table>
@endsection