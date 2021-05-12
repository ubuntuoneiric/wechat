@extends('layout.admin')
@section('title','素材展示')
@section('content')
<a href="" class="btn btn-primary">图片</a>
<a href="" class="btn btn-success">音频</a>
<a href="" class="btn btn-danger">视频</a>

<?php
    $arr = [
        'image'=>"图片",
        'voice'=>"音频",
        'video'=>"视频",
    ];
?>
<table class="table table-condensed">
    <tr>
        <td>ID</td>
        <td>素材名称</td>
        <td>素材内容</td>
        <td>素材格式</td>
        <td>添加时间</td>
        <td>永久/临时</td>
        <td>微信素材ID</td>
        <td>操作</td>
    </tr>
    @foreach($data as $v)
    <tr>
        <td class="active">{{$v->id}}</td>
        <td class="success">{{$v->m_name}}</td>
        <td width="100" height="100" class="warning">
            @if($v->m_type == "image")
                <img src="{{asset('storage').'/'.$v->m_path}}" width="80">
            @elseif($v->m_type == "voice")
                <audio width="100" height="100" controls>
                    <source src="{{asset('storage').'/'.$v->m_path}}">
                </audio>
            @else
                <video width="50" height="50" controls>
                    <source src="{{asset('storage').'/'.$v->m_path}}">
                </video>
            @endif
        </td>
        <td class="danger">{{$arr[$v->m_type]}}</td>
        <td class="info">{{date('Y-m-d H:i:s',$v->create_time)}}</td>
        <td class="active">
            @if($v->m_format == "1")
                永久
            @else
                临时
            @endif
        </td>
        <td class="success">{{$v->media_id}}</td>
        <td class="warning">
            @if($v->m_format == "1")
                <a href="{{url('/admin/deletemedia')}}?media_id={{$v->media_id}}" class="btn btn-danger">永久删除</a>
            @elseif(time()<$v->create_time+3*24*60*60)
                <b style="color:red">过期时间:{{date('Y-m-d H:i:s',$v->create_time+3*24*60*60)}}</b>
            @else
                <b style="color:red">已过期</b>
            @endif
        </td>
    </tr>
    @endforeach
    <tr>
        <td colspan="5">{{$data->links()}}</td>
    </tr>
</table>
@endsection