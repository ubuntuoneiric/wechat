@extends('layout.admin')
@section('title','分配角色')
@section('content')
    <script src="/jquery-3.3.1.js"></script>
    <?php
    $arr = ['1'=>"男",'2'=>"女"];
    ?>

    <h2>角色:{{$role['role_name']}}</h2>

    <table class="table table-condensed">
        <tr>
        	<td>选择</td>
            <td>ID</td>
            <td>账户昵称</td>
            <td>账户openid</td>
        </tr>
        @foreach($data as $v)
            <tr>
            	<td>
                    <input type="checkbox">
                </td>
                <td class="active">{{$v['id']}}</td>
                <td class="info">{{$v['name']}}</td>
                <td class="success">{{$v['openid']}}</td>  
            </tr>
        @endforeach
        <tr>
            <td colspan="6">
                <input type="button" value="添加标签" class="btn btn-success" id="add">
            </td>
        </tr>
    </table>

    <script>
        /*添加点击事件*/
        $(document).on('click','#add',function(){
            var openid = '';
            $("input[type='checkbox']:checked").each(function(){
                openid += ","+$(this).parent().next().next().next().text();
            })
            openid = openid.substr(1);//openid集合
            role = "{{$role['role_id']}}";//角色的标识

            $.ajax({
                url:"{{url('/admin/dodistribution')}}",
                type:"post",
                data:{openid:openid,role:role},
                dataType:"json",
                success:function(res){
                    alert(res.font);
                }
            })
        })
    </script>
@endsection