@extends('layout.admin')
@section('title','自定义菜单添加')
@section('content')
<h3>自定义菜单添加</h3>
<form action="{{url('/admin/menudoadd')}}" method="post">
    <div class="form-group">
        <label for="disabledSelect">菜单类型</label>
        <select id="disabledSelect" class="form-control" name="type">
            <option value="click">点击</option>
            <option value="view">跳转</option>
        </select>
    </div>
    <div class="form-group">
        <label for="disabledSelect">菜单分类</label>
        <select id="disabledSelect" class="form-control" name="parent_id">
            <option value="">请选择</option>
            <option value="0">添加新的一级分类</option>
            @foreach($data as $v)
                <option value="{{$v['id']}}">{{$v['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">菜单名称</label>
        <input type="text" name="name" class="form-control" id="exampleInputEmail1" >
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">菜单标识key</label>
        <input type="text" name="content" class="form-control" id="exampleInputEmail1" >
    </div>
    <button type="submit" class="btn btn-default">提交</button>
</form>
@endsection