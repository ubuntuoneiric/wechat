@extends('layout.admin')
@section('content')
<script src="/jquery-3.3.1.js"></script>
<link rel="icon" href="https://jscdn.com.cn/highcharts/images/favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://code.highcharts.com.cn/highcharts/highcharts.js"></script>
<script src="https://code.highcharts.com.cn/highcharts/highcharts-more.js"></script>
<script src="https://code.highcharts.com.cn/highcharts/modules/exporting.js"></script>
<script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>

    城市名: <input type="text" name="city">
           <input type="button" value="查看" id="sub">

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script>
    /**
     * 这是进页面默认的显示北京的天气
     */
    $.ajax({
        url:"{{url('/admin/weather')}}",
        data:{city:"北京"},
        dataType:'json',
        success:function(data){
            getWeather(data.result);//result是所有有关天气的数据都在result的键值的值里
        }

    })
    /*查看天气的点击事件*/
    $("#sub").click(function(){
        var city = $('[name="city"]').val();
        if(city == ""){
            alert("请填写城市");
            return;
        }
        $.ajax({
            url:"{{url('/admin/weather')}}",
            data:{city:city},
            dataType:'json',
            success:function(data){
                console.log(data);
                getWeather(data.result);//result是所有有关天气的数据都在result的键值的值里
            }

        })
    })
    /**
     * 封装一个生成天气统计图的方法
     */
    function getWeather(weatherData)
    {
        /*
        * 需要修改的有两处数据,格式如下
        * categories=["星期一","星期二","星期三"]
        * data = [
                    [-9.7, 9.4],
                    [-8.7, 6.5],
                    [-3.5, 9.4],
                    [-1.4, 19.9],
                    [0.0, 22.6],
                    [2.9, 29.5],
                    [9.2, 30.7],
                    [7.3, 26.5],
                    [4.4, 18.0],
                    [-3.1, 11.4],
                    [-5.2, 10.4],
                    [-13.5, 9.8]
                ]
           现在需要做的是把这两个数据用ajax返回的数据填充上*/
        var categories = [];//y轴的日期格式
        var data = [];      //x轴对应的温度
        $.each(weatherData,function (i,v) {
            /*第一个参数的处理*/
            categories.push(v.days)
            /*第二个参数的处理*/
            var arr = [parseInt(v.temp_low),parseInt(v.temp_high)];
            data.push(arr)
        })

        var chart = Highcharts.chart('container', {
            chart: {
                type: 'columnrange', // columnrange 依赖 highcharts-more.js
                inverted: true
            },
            title: {
                text: '最近一周的气温变化'
            },
            subtitle: {
                text: weatherData[0]['citynm']
            },
            xAxis: {
                categories: categories
            },
            yAxis: {
                title: {
                    text: '温度 ( °C )'
                }
            },
            tooltip: {
                valueSuffix: '°C'
            },
            plotOptions: {
                columnrange: {
                    dataLabels: {
                        enabled: true,
                        formatter: function () {
                            return this.y + '°C';
                        }
                    }
                }
            },
            legend: {
                enabled: false
            },
            series: [{
                name: '温度',
                data: data
            }]
        });
    }

</script>
@endsection