@extends('layout.admin')
@section('title','标签添加')
@section('content')
    <h3>标签添加</h3>
    <form action="{{url('/admin/labeldoadd')}}" method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">标签名称</label>
            <input type="text" name="name" class="form-control" id="exampleInputEmail1" >
        </div>
        <button type="submit" class="btn btn-default">提交</button>
    </form>
@endsection