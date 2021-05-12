@extends('layout.admin')
@section('title','角色信息')
@section('content')
<script src="/jquery-3.3.1.js"></script>
    <table class="table table-condensed">
        <tr>
            <td>ID</td>
            <td>角色名称</td>
            <td>目前已有账号</td>
            <td>操作</td>
        </tr>
        @foreach($data as $v)
            <tr>
                <td class="active">{{$v['role_id']}}</td>
                <td class="success">{{$v['role_name']}}</td>
                <td>
                    
                </td>
                <td class="warning">
                    <a href="{{url('admin/roledistribution')}}?id={{$v['role_id']}}" class="btn btn-success">分配用户</a>
                    <a href="" class="btn btn-info">修改</a>
                    <a href="" class="btn btn-danger">删除</a>
                </td>
            </tr>
        @endforeach
    </table>
    <script type="text/javascript">
        
    </script>
@endsection