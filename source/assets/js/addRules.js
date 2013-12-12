function timeFromTo() {
	$('.formStartTime').datetimepicker({
		format: "hh:ii",
		linkField: "startTime",
		linkFormat: "yyyy-mm-dd hh:ii",
		language:  'da',
		todayBtn:  0,
		autoclose: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		pickerPosition: 'bottom-left',
		minuteStep: 5
	});
	$('.formEndTime').datetimepicker({
		format: "hh:ii",
		linkField: "endTime",
		linkFormat: "yyyy-mm-dd hh:ii",
		language:  'da',
		todayBtn:  0,
		autoclose: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		pickerPosition: 'bottom-left',
		forceParse: 0,
		minuteStep: 5
	});
	$('.formStartTime').datetimepicker().on('hide', function(e){
	    $('.formEndTime').datetimepicker('setStartDate', e.date);
	});
	$('.formEndTime').datetimepicker().on('hide', function(e){
	    $('.formStartTime').datetimepicker('setEndDate', e.date);
	});
}
function timeAT() {
	$('.formATTime').datetimepicker({
		format: "hh:ii",
		linkField: "timeATHidden",
		linkFormat: "hh:ii",
		language:  'da',
		todayBtn:  0,
		autoclose: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		pickerPosition: 'bottom-left',
		minuteStep: 5
	});
}
function specificTime() {
	$('.formSpecificTime').datetimepicker({
		startDate: new Date(), // Add 10 minutes
		format: "dd MM yyyy hh:ii",
		linkField: "timeSPCTHidden",
		linkFormat: "yyyy-mm-dd hh:ii",
		language:  'da',
		todayBtn:  1,
		autoclose: 1,
		startView: 2,
		minView: 0,
		maxView: 3,
		pickerPosition: 'bottom-left',
		minuteStep: 5
	});
}
function dateFromTo() {
	$('.formStartRepeatOn').datetimepicker({
		startDate: new Date(), // Add 10 minutes
		format: "dd MM yyyy",
		linkField: "startRepeatOn",
		linkFormat: "yyyy-mm-dd",
		language:  'da',
		weekStart: 1,
		todayBtn:  0,
		autoclose: 1,
		todayHighlight: 1,
		minView: 2,
		startView: 2,
		pickerPosition: 'bottom-left',
		forceParse: 0,
		minuteStep: 5
	});
	$('.formEndRepeatOn').datetimepicker({
		startDate: new Date(), // Add 10 minutes
		format: "dd MM yyyy",
		linkField: "endRepeatOn",
		linkFormat: "yyyy-mm-dd",
		language:  'da',
		weekStart: 1,
		autoclose: 1,
		minView: 2,
		startView: 2,
		pickerPosition: 'bottom-left',
		forceParse: 0,
		minuteStep: 5
	});
	$('.formStartRepeatOn').datetimepicker().on('hide', function(e){
	    $('.formEndRepeatOn').datetimepicker('setStartDate', e.date);
	});
	$('.formEndRepeatOn').datetimepicker().on('hide', function(e){
	    $('.formStartRepeatOn').datetimepicker('setEndDate', e.date);
	});
}