@extends('layout.admin')
@section('title','素材添加')
@section('content')
    <h3>素材添加</h3>
    <form action="{{url('/admin/mediadoadd')}}" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="exampleInputEmail1">素材名称</label>
            <input type="text" name="m_name" class="form-control" id="exampleInputEmail1" >
        </div>
        <div class="form-group">
            <label for="disabledSelect">素材格式</label>
            <select id="disabledSelect" class="form-control" name="m_type">
                <option value="image">图片</option>
                <option value="voice">音频</option>
                <option value="video">视频</option>
            </select>
        </div>
        <div class="form-group">
            <label for="disabledSelect">永久/临时</label>
            <select id="disabledSelect" class="form-control" name="m_format">
                <option value="1">永久</option>
                <option value="2">临时</option>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleInputFile">素材</label>
            <input type="file" name="m_path">
        </div>
        <button type="submit" class="btn btn-default">提交</button>
    </form>
@endsection

