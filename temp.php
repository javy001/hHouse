<?php
date_default_timezone_set('America/Los_Angeles');
$db = new mysqli('ec2-54-68-139-60.us-west-2.compute.amazonaws.com', 'javierq', 'abcd1234');


$sql = "
SELECT date_key, temp 
from read_port.clean_temp
order by 1
";

$data = $db->query($sql);
if($data){
	while($row=$data->fetch_assoc()){
		$dk = strtotime($row['date_key'])*1000;
		$chart_data[] = array($dk,floatval($row['temp']));
	}
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>temp</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
	<script src="https://code.highcharts.com/stock/highstock.js"></script>
	<style type="text/css">
		.chart{
			height: 250px;
		}
		body{
			background-color: #f2f2f2;
		}
		.container1{
			padding-top: 25px;
			padding-left: 50px;
			padding-right: 50px;
		}
		.card{
			background: rgb(255, 255, 255);
			box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 2px 0px;
		    border-bottom: 1px solid rgb(156, 154, 154);
		    font: system-ui, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif;
		    padding-top: 16px;
		    padding-bottom: 16px;
		    padding-left: 16px;
		    padding-right: 16px;
			}
		.buffer{
			height: 25px;
		}
		h3{
			text-align: center;
		}
	</style>

	<script type="text/javascript">
		$(document).ready(function(){
			var chart_data = <?php echo json_encode($chart_data); ?>;
			console.log(chart_data);

			var color1 = '#f53c5c';
			var color2 = '#3c5cf5';

			function draw_chart(container,title,data,decimals=4,legend=false,ticks=7){
				Highcharts.chart(container,{
					chart: {
						type: 'line'
					},
					credits: {
						enabled: false
					},
					xAxis: [{
						type: 	'datetime',
								//tickInterval:  ticks * 24 * 3600 * 1000,
								startOnTick: true,
								startOfWeek: 0
					}],
					yAxis: {
						title: {
							text: ''
						}
					},
					plotOptions: {
						line: {
							marker: {
								enabled: false
								}
							}
					},
					title: {
						text: title
					},
					tooltip : {
							valueDecimals: decimals,
                            shared : true,
                            split: true,
                            xDateFormat: "%A, %b %e %H:00"
	                 },
	                 legend: {
	                 	enabled: legend
	                 },
					series: data
				});
			}
			
			draw_chart('chart','Average Temperature',[{data: chart_data}]);
			
			
			
		});
		
	</script>
</head>
<body>
	<div class="container1">
		<div class="col-md-12 card">
			<div class="chart" id="chart"></div>
		</div>
	</div>

</body>
</html>