@extends('layout.admin')
@section('title','渠道添加')
@section('content')
<div class="container">
    <form action="{{url('/admin/channeldoadd')}}" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="exampleInputEmail1">渠道名称</label>
            <input type="text" name="c_name" class="form-control" id="exampleInputEmail1" >
        </div>
        <div class="form-group">
            <label for="disabledSelect">渠道标识</label>
            <input type="text" name="c_cation" class="form-control">
        </div>
        <div class="form-group">
            <label for="disabledSelect">永久/临时</label>
            <select id="disabledSelect" class="form-control" name="c_format">
                <option value="1">永久</option>
                <option value="2">临时</option>
            </select>
        </div>
        <button type="submit" class="btn btn-default">提交</button>
    </form>
</div>
@endsection