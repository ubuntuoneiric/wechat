<!DOCTYPE html>
<html>
<head>
	<mate charset="utf-8">
	<title>许愿墙</title>
	<script src="/jquery-3.3.1.js"></script>
</head>
<body>
	<form action="{{url('/admin/dowish')}}" method="post">
		<textarea name="content"></textarea><br>
		<input type="submit" value="许愿">
	</form>
	
	<table id="table" border="1">
		@foreach($data as $v)
			<tr>
				<td>{{$v['name']}}</td>
				<td>
					<img src="{{$v['header']}}" width="80">	
				</td>
				<td>{{$v['content']}}</td>
			</tr>
		@endforeach
	</table>
</body>
<script type="text/javascript">
	var content = $("#wish").val();
	$('#xxoo').click(function(){
		// $.ajax({
		// 	url:"{{url('/admin/dowish')}}",
		// 	type:"post",
		// 	data:{content:content},
		// 	dataType:"json",
		// 	success:function(res){
		// 		alert(res.font);
		// 	}
		// })
	})

	// $.ajax({
	// 		url:"{{url('/admin/wishlist')}}",
	// 		type:"post",
	// 		dataType:"json",
	// 		success:function(data){
	// 			alert(1)
	// 			var html = "";
	// 			for (var i = 0; i <= data.length; i++) {
	// 				html += "<tr><td>".data[i]['name']."</td><td><img src='".data[i]['header']."'></td><td>".data[i]['content']."</td></tr>";
	// 			}
	// 			$("#table").html(html);
	// 		}
	// })
</script>
</html>