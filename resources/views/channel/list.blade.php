@extends('layout.admin')
@section('title','渠道展示')
@section('content')
<h1>渠道展示</h1>
<table class="table table-condensed">
    <tr>
        <td>ID</td>
        <td>渠道名称</td>
        <td>渠道标识</td>
        <td>关注人数</td>
        <td>添加时间</td>
        <td>二维码</td>
        <td>操作</td>
    </tr>
    @foreach($data as $v)
        <tr>
            <td class="active">{{$v->id}}</td>
            <td class="success">{{$v->c_name}}</td>
            <td class="warning">{{$v->c_cation}}</td>
            <td class="danger">{{$v->num}}</td>
            <td class="info">{{date('Y-m-d H:i:s',$v->create_time)}}</td>
            <td class="danger">
                <img src="{{asset('/qrcode/'.$v->qrcode)}}" width="80" class="img">
            </td>
            <td class="success">
                <a href="" class="btn btn-danger">永久删除</a>
            </td>
        </tr>
    @endforeach
</table>
<div class="bg" style="display:none;background: #ccc;width:100%;height:100%;position:absolute;top:0;left:0;opacity:0.8;text-align:center;padding-top:10%">
    <div class="close_img" style="padding-left:20%">
        <b>关闭</b>
    </div>
    <img src="" style="width:300px" class="bg_img">
</div>
<script src="/jquery-3.3.1.js"></script>
<script>
    //点击事件
    $(document).on('click','.img',function(){
        $(".bg").show();//背景层显示
        var src = $(this).attr('src');//获取当前的图片路径
        $(".bg_img").attr('src',src);
    })
    //点击关闭按钮
    $(document).on('click','.close_img',function(){
        $('.bg').hide();
    })
</script>
@endsection