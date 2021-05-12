@extends('layout.admin')
@section('title','角色添加')
@section('content')
<h3>角色添加</h3>
<form action="{{url('/admin/doroleadd')}}" method="post">
    <div class="form-group">
        <label for="exampleInputEmail1">角色名称</label>
        <input type="text" name="role_name" class="form-control" id="exampleInputEmail1" >
    </div>
    <button type="submit" class="btn btn-default">提交</button>
</form>
@endsection