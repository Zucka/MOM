
<div class="col-md-4 dashboardContainer">
	<div class="help">
		<h2>Help</h2>
		Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed auctor, mauris in venenatis egestas, est justo tristique arcu, ac faucibus nulla metus eget metus. Quisque vel molestie lorem, nec bibendum orci. Nunc quis hendrerit nibh. Nam fringilla, augue id consequat hendrerit, neque massa posuere erat, ut egestas quam orci non ipsum. Vivamus nec ipsum urna. Curabitur vitae nisi semper, pulvinar lorem a, luctus erat. Suspendisse feugiat arcu id nulla lobortis consectetur. Donec lectus enim, commodo id vulputate sed, tincidunt et purus. 
	</div>
</div>
<div class="col-md-4 dashboardContainer">
	<div class="userLastUsedController">
		<h2>User Last used Controller</h2>
		<?php
		echo '
		<table id="userLastUsedControllerTable" class="tablesorter">
			<thead>
				<tr>
					<th>User</th><th>Controller</th><th>From</th><th>To</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Johan Sørensen</td><td>TV</td><td>2013-12-13 00:00:00</td><td>2013-12-13 00:06:00</td>
				</tr>
				<tr>
					<td>Allan Hansen</td><td>Wii</td><td>2013-01-13 00:00:00</td><td>2013-01-13 00:06:00</td>
				</tr>
				<tr>
					<td>Hermann Hansen</td><td>Playstation</td><td>2013-01-13 00:00:00</td><td>2013-01-13 00:06:00</td>
				</tr>
			</tbody>
		</table>
		';
		?>
	</div>
</div>
<div class="col-md-4 dashboardContainer">
	<div class="lastUsedControllers">
		<h2>Last used Controllers</h2>
		<?php
		echo '
		<table id="userLastUsedControllerTable" class="tablesorter">
			<thead>
				<tr>
					<th>Controller</th><th>User</th><th>From</th><th>To</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>TV</td><td>Johan Sørensen</td><td>2013-12-13 06:30:00</td><td>2013-12-13 00:06:00</td>
				</tr>
				<tr>
					<td>TV</td><td>Johan Sørensen</td><td>2013-12-13 08:34:00</td><td>2013-12-13 08:55:00</td>
				</tr>
				<tr>
					<td>TV</td><td>Johan Sørensen</td><td>2013-12-13 09:20:00</td><td>2013-12-13 10:20:00</td>
				</tr>
			</tbody>
		</table>
		';
		?>
	</div>
</div>

<div class="col-md-6 dashboardContainer">
	<div class="empty">
		<h2>empty</h2>
		<button type="button" class="btn btn-default">Add to dashboard</button>
	</div>
</div>
<div class="col-md-6 dashboardContainer">
	<div class="calendar">
		<h2>Calendar</h2>
	</div>
</div>