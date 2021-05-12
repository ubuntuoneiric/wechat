@extends('layout.admin')
@section('title','群发消息设置')
@section('content')
    <script src="/jquery-3.3.1.js"></script>
    <h3>群发消息设置</h3>
    <form action="{{url('/admin/massdoadd')}}" method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">消息内容</label>
            <input type="text" name="content" class="form-control" id="exampleInputEmail1" >
        </div>
        <div class="form-group">
            <label for="disabledSelect">发送方式</label>
            <select id="method" class="form-control" name="type">
                <option value="1">通过标签发送</option>
                <option value="2">所有人发送</option>
                <option value="3">批量用户发送</option>
            </select>
        </div>
        <!--标签-->
        <div class="form-group" id="f1">
            <label for="disabledSelect">请选择标签</label>
            <select id="disabled" class="form-control" name="label">
                @foreach($label as $v)
                    <option value="{{$v->sign}}">{{$v->name}}</option>
                @endforeach
            </select>
        </div>
        <!--标签-->
        <!--批量-->
        <?php $arr = ['1'=>"男",'2'=>"女"];?>
        <table class="table table-condensed" id="f2" style="display: none">
            <tr>
                <td>
                    <input type="checkbox" id="checkAll">请选择
                </td>
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
                        <input type="checkbox" name="check[]" value="{{$v['openid']}}" class="checkfans">
                    </td>
                    <td class="active">{{$v['fid']}}</td>
                    <td class="success">{{$v['openid']}}</td>
                    <td class="info">{{$v['nickname']}}</td>
                    <td>{{$arr[$v['sex']]}}</td>
                    <td>
                        <img src="{{$v['headimgurl']}}" width="80">
                    </td>
                    <td>{{$v['city']}}</td>
                    <td>{{date("Y-m-d H:i:s",$v['subscribe_time'])}}</td>
                </tr>
            @endforeach
        </table>
        <!--批量-->
        <input type="submit" value="发送消息" class="btn btn-danger">
    </form>
    <script>
        /*发送方式改变事件*/
        $("#method").change(function(){
            var method = $(this).val();
            if(method == 1){
                $("#f1").show();
                $("#f2").hide();
            }
            if(method == 2){
                $("#f1").hide();
                $("#f2").hide();
            }
            if(method == 3){
                $("#f1").hide();
                $("#f2").show();
            }
            //提交openid
            /*全选和反选*/
            $(document).on('click','#checkAll',function(){
                var au = $("#checkAll").prop('checked');
                if(au){
                    $(".checkfans").prop('checked',true);
                }else{
                    $(".checkfans").prop('checked',false);
                }
            })
        })
    </script>
@endsection