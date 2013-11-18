<h1>Devices</h1>

<h3 class="headder-devices">Controller</h3><a href="?page=addController"><button type="button" class="btn btn-devices"><span class="glyphicon glyphicon-plus"></span> Add Controller</button></a>
<table id="controller" class="tablesorter">
	<thead>
		<tr>
			<th>Status</th><th>Name</th><th>Location</th><th>Last User, Time</th><th>Details</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>1</td><td>2</td><td>1</td><td>2</td><td>1</td>
		</tr>
		<tr>
			<td>2</td><td>1</td><td>2</td><td>1</td><td>2</td>
		</tr>
	</tbody>	
</table>

<h3 class="headder-devices">Tag</h3><a href="?page=addTag"><button type="button" class="btn btn-devices"><span class="glyphicon glyphicon-plus"></span> Add Tag</button></a>
<table id="tag" class="tablesorter">
	<thead>
		<tr>
			<th>Active</th><th>User</th><th>Tag Name</th><th>Last Used, time</th><th>Details</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>1</td><td>1</td><td>1</td><td>1</td><td>1</td>
		</tr>
		<tr>
			<td>2</td><td>1</td><td>2</td><td>1</td><td>2</td>
		</tr>
	</tbody>
</table>

<script>
$(document).ready(function() 
    { 
        $("#controller").tablesorter(); 
		$("#tag").tablesorter(); 
    } 
); 
</script>