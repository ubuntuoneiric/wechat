@extends('layout.admin')
@section('title','所有标签')
@section('content')
    <script src="/jquery-3.3.1.js"></script>
    <table class="table table-condensed">
        <tr>
            <td>ID</td>
            <td>标签名称</td>
            <td>标签标识</td>
            <td>操作</td>
        </tr>
        @foreach($data as $v)
            <tr>
                <td class="active">{{$v['lid']}}</td>
                <td class="success">{{$v['name']}}</td>
                <td>{{$v['sign']}}</td>
                <td class="warning">
                    <a href="{{url('admin/fansDistribution')}}?id={{$v['lid']}}" class="btn btn-success">分配用户</a>
                    <a href="" class="btn btn-info">修改</a>
                    <a href="" class="btn btn-danger">删除</a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection