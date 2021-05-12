@extends('layout.admin')
@section('title','账号绑定')
@section('content')
    <div class="container">
        <form action="{{url('/admin/doauth')}}" method="post">
            <div class="form-group">
                <label for="exampleInputEmail1">账号</label>
                <input type="text" name="name" class="form-control" id="exampleInputEmail1" >
            </div>
            <div class="form-group">
                <label for="disabledSelect">密码</label>
                <input type="text" name="pwd" class="form-control">
            </div>
            <button type="submit" class="btn btn-default">绑定</button>
        </form>
    </div>
@endsection