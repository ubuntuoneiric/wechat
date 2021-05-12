@extends('layout.admin')
@section('title','分配标签')
@section('content')
    <script src="/jquery-3.3.1.js"></script>
    <?php
    $arr = ['1'=>"男",'2'=>"女"];
    ?>

    <h2>标签:{{$label['name']}}</h2>

    <table class="table table-condensed">
        <tr>
            <td>选择</td>
            <td>ID</td>
            <td>用户openid</td>
            <td>用户昵称</td>
            <td>用户性别</td>
            <td>用户头像</td>
            <td>城市</td>
            <td>关注时间</td>
        </tr>
        @foreach($data as $v)
            <tr>
                <td>
                    <input type="checkbox">
                </td>
                <td class="active">{{$v['fid']}}</td>
                <td class="success">{{$v['openid']}}</td>
                <td class="info">{{$v['nickname']}}</td>
                <td>{{$arr[$v['sex']]}}</td>
                <td>
                    <img src="{{$v['headimgurl']}}" alt="">
                </td>
                <td>{{$v['city']}}</td>
                <td>{{date("Y-m-d H:i:s",$v['subscribe_time'])}}</td>
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
                openid += ","+$(this).parent().next().next().text();
            })
            openid = openid.substr(1);//openid集合
            label = "{{$label['sign']}}";//标签的标识

            $.ajax({
                url:"{{url('admin/createLabelUser')}}",
                type:"post",
                data:{openid:openid,label:label},
                dataType:"json",
                success:function(res){
                    alert(res.font);
                    location.href="{{url('/admin/relationlist')}}";
                }
            })
        })
    </script>
@endsection