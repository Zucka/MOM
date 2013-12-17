<?php
	

?>



<!DOCTYPE html>
<head>
	<title>Calendar</title>
	<!-- SITE SPECIFIC STYLE -->
	<link rel="stylesheet" href="//cdn.oesmith.co.uk/morris-0.4.3.min.css">
	<!-- SITE SPECIFIC JS -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	<script src="//cdn.oesmith.co.uk/morris-0.4.3.min.js"></script>
    <script type="text/javascript">
		$(document).ready(function() { 
			$.ajax({
				url: 'ajax/json-charts.php?chart=pointPrDay',
				success: function(data) {
					var $graph = data;
					var obj = $.parseJSON($graph);
					Morris.Bar({
						element: 'pointPrDayBar',
						data: obj,
						xkey: 'device',
						ykeys: ['geekbench'],
						labels: ['Geekbench'],
						barRatio: 0.4,
						xLabelAngle: 35,
						hideHover: 'auto'
					});
				}
			});
			$.ajax({
				url: 'ajax/json-charts1.php?chart=useStatistics&PId=1',
				success: function(data) {
					var $graph = data;
					var obj = $.parseJSON($graph);
					Morris.Donut({
					  element: 'morris-chart-donut',
					  data: obj,
					  formatter: function (y) { return y + "%" ;}
					});
				}
			});
		})
	</script>
</head>
<body>
	<div class="container">
      <div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h2>Charts:</h2>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Points spent pr. day</h3>
					</div>
					<div class="panel-body">
						<div id="pointPrDayBar"></div>
					</div>
				</div>
			</div>
		</div><!-- /.row -->

		<div class="row">
			<div class="col-lg-4">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Most active day</h3>
					</div>
					<div class="panel-body">
						<div id="morris-chart-donut"></div>
						<div class="text-right">
							<a href="#">View Details <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Total points spent</h3>
					</div>
					<div class="panel-body">
						<div id="morris-chart-line"></div>
						<div class="text-right">
							<a href="#">View Details <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Points spent pr. user</h3>
					</div>
					<div class="panel-body">
						<div id="morris-chart-bar"></div>
						<div class="text-right">
							<a href="#">View Details <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.row -->
      </div><!-- /#page-wrapper -->
    </div> <!-- /container -->
</body>

    <script src="assets/js/chart-data.js"></script>	
</html>