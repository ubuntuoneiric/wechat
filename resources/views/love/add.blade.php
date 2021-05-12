<!DOCTYPE html>
<html>
<head>
	
	<title>添加</title>
</head>
<body>
	<?php 
		$arr = [1=>'男',2=>'女'];
	 ?>
	<h1 align="center">是否匿名表白</h1>
	<form action="{{url('/lovedoadd')}}" method="post">
		<center>
			<input type="radio" name="is_name" value="1">是
			<input type="radio" name="is_name" value="2">否<br>
			<hr>
			<table>
				<tr>
					<td>请选择</td>
					<td>ID</td>
					<td>昵称</td>
					<td>性别</td>
					<td>头像</td>
					<td>表白内容</td>
				</tr>
				@foreach($fans as $v)
				<tr>
					<td>
						<input type="checkbox" name="check[]" value="{{$v['openid']}}">
					</td>
					<td>{{$v['fid']}}</td>
					<td>{{$v['nickname']}}</td>
					<td>{{$arr[$v['sex']]}}</td>
					<td>
						<img src="{{$v['headimgurl']}}">
					</td>
					<td>
						<input type="text" name="{{$v['openid']}}">
					</td>
				</tr>
				@endforeach
			</table>
			<input type="submit" value="表白">
		</center>
	</form>
</body>
</html>