@extends('layout.admin')
@section('title','题目添加')
@section('content')
    <div class="container">
        <form action="{{url('/admin/questiondoadd')}}" method="post">
            <div class="form-group">
                <label for="exampleInputEmail1">题目名称</label>
                <input type="text" name="title" class="form-control" id="exampleInputEmail1" >
            </div>
            <div class="form-group">
                <label for="disabledSelect">答案A</label>
                <input type="text" name="A" class="form-control">
            </div>
            <div class="form-group">
                <label for="disabledSelect">答案B</label>
                <input type="text" name="B" class="form-control">
            </div>
            <div class="form-group">
                <label for="disabledSelect">正确答案</label>
                <input type="text" name="right" class="form-control">
            </div>

            <button type="submit" class="btn btn-default">提交</button>
        </form>
    </div>
@endsection