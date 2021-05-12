@extends('layout.admin')
@section('title','消息回复')
@section('content')
    <script src="/jquery-3.3.1.js"></script>
    <div class="container">
        <form action="{{url('admin/replydoadd')}}" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="disabledSelect">回复方式</label>
                <select id="disabledSelect" class="form-control" name="type">
                    <option value="1">文本</option>
                    <option value="2">图片</option>
                </select>
            </div>
            <div class="form-group" id="f1">
                <label for="exampleInputEmail1">文本内容</label>
                <input type="text" name="content" class="form-control" id="exampleInputEmail1">
            </div>
            <!--图片模式-->
            <div class="form-group" id="f3" style="display: none">
                <input type="button" value="前往素材库" id="clickMedia">
            </div>
            <table id="stu" class="table table-condensed"></table>
            <div class="form-group" id="f2" style="display: none">
                <label for="exampleInputEmail1">回复图片上传</label>
                <input type="file" name="content" >
            </div>

            <button type="submit" class="btn btn-default">提交</button>
        </form>
    </div>

    <script>
        //下拉框的内容改变事件
        $("#disabledSelect").change(function(){
            var method = $(this).val();
            if(method == "1"){
                $('#f1').show();
                $('#f2').hide();
                $('#f3').hide();
            }else{
                $('#f1').hide();
                $('#f2').show();
                $('#f3').show();
            }
        })
        //素材库点击事件
        $("#clickMedia").click(function(){
            $.ajax({
                url:"{{url('/admin/mediareplylist')}}",
                type:"post",
                dataType:"json",
                success:function(data){
                    var html = '';
                    for(var i = 0 ;  i<data.length; i++){
                        html += '<tr><td><input type="radio" name="media"></td><td>'+data[i]['id']+'</td><td>'+data[i]['m_name']+'</td><td><img src="{{asset('storage')}}'+"/"+data[i]['m_path']+'" width="50"></td></td></tr>';
                    }
                    $('#stu').html(html);
                }
            })
        })
    </script>
@endsection