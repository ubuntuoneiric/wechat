<!DOCTYPE html>
<html>
<head>
	<mate charset="utf-8">
	<title>红包</title>
	<style type="text/css">
		#f1{
			border:1px black solid;
			width:500px;
			height:60px;
			line-height: 60px;
			text-align: center;
			margin:0px auto;
		}
		#f2{
			border:1px black solid;
			width:500px;
			height:500px;
			margin:0px auto;
		}
	</style>
</head>
<body>
	<h2 align="center">红包算法</h2>
	<form action="{{url('/domoney')}}" method="post">
		<div id="f1">
			@if(empty($data))
				总金额:<input type="text" name="total">
				人数:<input type="text" name="num">
			@else
				总金额:<input type="text" name="total" value="{{$data['total']}}">
				人数:<input type="text" name="num" value="{{$data['num']}}">
			@endif
			<input type="submit" value="发红包">
		</div>		
	</form>
	<br><br>
	<div id="f2">
		@if($list == '')

		@else
		<ul>
			@foreach($list as $v)
				<li>
					{{$v}}
				</li>
			@endforeach
		</ul>
			@if(empty($data))

			@else
				总金额:{{$data['total']}}
			@endif
		@endif
	</div>
</body>
</html>