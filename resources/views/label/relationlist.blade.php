@extends('layout.admin')
@section('title','关联信息')
@section('content')
    <script src="/jquery-3.3.1.js"></script>
    <table class="table table-condensed">
        <tr>
            <td>ID</td>
            <td>openid</td>
            <td>标签标识</td>
        </tr>
        @foreach($data as $v)
            <tr>
                <td class="active">{{$v->rid}}</td>
                <td class="success">{{$v->uid}}</td>
                <td>{{$v->lid}}</td>
            </tr>
        @endforeach
    </table>
@endsection