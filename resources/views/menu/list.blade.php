@extends('layout.admin')
@section('title','自定义菜单信息展示')
@section('content')
    <script src="/jquery-3.3.1.js"></script>
    <table class="table table-condensed">
        <tr>
            <td>ID</td>
            <td>菜单名称</td>
            <td>菜单格式</td>
            <td>菜单内容</td>
            <td>添加时间</td>
            <td>操作</td>
        </tr>
        @foreach($data as $v)
            <tr>
                <td class="active">{{$v['id']}}</td>
                <td class="success">{{str_repeat("---",$v['level']*3).$v['name']}}</td>
                <td class="warning">{{$v['type']}}</td>
                <td class="danger">{{$v['content']}}</td>
                <td class="info">{{date('Y-m-d H:i:s',$v['create_time'])}}</td>
                <td class="warning">
                    <a href="">修改</a>|
                    <a href="">删除</a>
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6">
                <a href="{{url('/admin/menuadd')}}">添加菜单</a>
                &nbsp;&nbsp;
                <input type="button" value="一键生成微信菜单" id="create_menu">
            </td>
        </tr>
    </table>
    <script>
        /*一键生成的点击事件*/
        $("#create_menu").click(function(){
            location.href="/admin/createMenu";
        })
    </script>
@endsection