$(document).ready(function(){

		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
	$('.fullCalendar').fullCalendar({
		defaultView: 'month',
		aspectRatio: 1.35,
		header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
		weekends: true,
		weekNumbers: true,
		weekNumberCalculation: 'iso',
		weekNumberTitle: 'Week ',
		editable: false,
		disableDragging: false,
		disableResizing: false,

		allDayDefault: false,

		allDaySlot: false,
		firstDay: 1,

		defaultEventMinutes: 60,

			events: [
				{
					title: 'Johan is Blocked',
					start: new Date(y, m, 1)
				},
				{
					title: 'Asger is Blocked',
					start: new Date(y, m, d-5),
					end: new Date(y, m, d-2)
				},
				{
					id: 999,
					title: 'Wii is allowed for Hermann',
					start: new Date(y, m, d-3, 16, 0),
					allDay: false
				},
				{
					id: 999,
					title: 'Playstation is denied for Asger',
					start: new Date(y, m, d+4, 16, 0),
					allDay: false
				},
				{
					title: 'Unlimited points for Lars',
					start: new Date(y, m, d, 10, 30),
					allDay: false
				},
				{
					title: 'Tv is bloacked',
					start: new Date(y, m, d, 12, 0),
					end: new Date(y, m, d, 14, 0),
					allDay: false
				},
				{
					title: 'Johan is denied access to TV',
					start: new Date(y, m, d+1, 19, 0),
					end: new Date(y, m, d+1, 22, 30),
					allDay: false
				}
			],
		// events: {
		// 	url: "ajax/json-events-dash.php",
		// 	data: {
		// 		uid: userID
		// 	},
		// 	error: function() {
		// 		alert('there was an error while fetching events!');
		// 	}
		// },

		// eventRender: function (event, element) {
		// 	element.tooltip({
		// 		html: true,
		// 		container: 'body',
		// 		title: event.title  + '<br />Starts at: ' + event.startTime + '<br />Ends at: ' + event.endTime + '<br />' + event.description,
		// 		placement: 'bottom',
		// 	});
		// }
	});
});