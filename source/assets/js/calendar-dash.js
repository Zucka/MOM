$('#fullCalendar').fullCalendar({
	defaultView: 'agendaDay',
	aspectRatio: 1.35,
	header: {
		left: '',
		center: '',
		right: 'today,prev,next'
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
	
	events: {
        url: "ajax/json-events-dash.php",
        data: {
            uid: userID
        },
        error: function() {
            alert('there was an error while fetching events!');
        }
	},

    eventRender: function (event, element) {
        element.tooltip({
        	html: true,
        	container: 'body',
            title: event.title  + '<br />Starts at: ' + event.startTime + '<br />Ends at: ' + event.endTime + '<br />' + event.description,
            placement: 'bottom',
        });
    }
});