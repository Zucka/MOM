
var actionSelected ="";
var conditionSelected = "";
function actionNameSelect () {
	$( "#actionName option:selected" ).each(function() {
		dateFromTo();
		timeFromTo();
		timeAT();
		specificTime();
		actionSelected = $( this ).text();
		switch (actionSelected) {
			case 'Block user':{ 					setVisibility (0, 0, 0, 0, 1, 1); }break;
			case 'Add points':{ 					setVisibility (0, 0, 1, 0, 1, 1); }break;
			case 'Delete points':{ 					setVisibility (0, 0, 1, 0, 1, 1); }break;
			case 'Set maximum of point':{   		setVisibility (0, 0, 1, 0, 1, 0); }break;
			case 'Unlimited time':{ 				setVisibility (0, 0, 0, 0, 1, 1); }break;
			case 'Access any controller':{  		setVisibility (0, 0, 0, 0, 1, 1); }break;
			case 'Cannot access any controller':{   setVisibility (0, 0, 0, 0, 1, 1); }break;
			case 'Access controller':{  			setVisibility (0, 1, 0, 0, 1, 1); }break;
			case 'Cannot access controller':{   	setVisibility (0, 1, 0, 0, 1, 1); }break;
			case 'Access controller if':{  			setVisibility (0, 1, 0, 1, 0, 1); }break;
			case 'Cannot access controller if':{   	setVisibility (0, 1, 0, 1, 0, 1); }break;
			default: break;
		}
	});
}
function setVisibility (condition, controllerName, amountOfPoints, controllerNameIf, userSelect, repeatEach) {
	changeStateOfID("#conditionNameSelect",(condition 		? "add" : "remove"));
	changeStateOfID("#controllerName"	, (controllerName 	? "add" : "remove"));
	changeStateOfID("#amountOfPoints"	, (amountOfPoints 	? "add" : "remove"));
	changeStateOfID("#controllerNameIf"	, (controllerNameIf ? "add" : "remove"));
	changeStateOfID("#systemUser"		, (userSelect 		? "add" : "remove"));
	changeStateOfID("#repeatEach"		, (repeatEach 		? "add" : "remove"));
	changeStateOfID("#submitBtn"		, "add");
	repeatWeeklySelect ();
}
function conditionSelectChange () {
	$( "#condNameSelect option" ).each(function() {
		$( this ).attr("disabled","disabled");
	});
}
function repeatWeeklySelect () {
	$( "#repeatEach option:selected" ).each(function() {
		var repeatOptionSelected = $( this ).attr("value");
		// Reset form systemUser
		console.log(actionSelected);
		console.log(repeatOptionSelected);
		disableNoRepeat(false);
		changeStateOfID(	"#repeatBetween", 'add');
		changeStateOfID(	"#betweenTime"	, 'add');
		changeStateOfID(	"#ruleName"		, 'add');
		changeStateOfChkBox("#repeatDays"	, 'add');
		changeStateOfID(	"#selectWeekNo"	, 'remove');
		changeStateOfID(	"#ATTime"		, 'remove');
		changeStateOfID(	"#SpecificTime"	, 'remove');
		changeStateOfID(	"#specificDay"	, 'remove');
		if (repeatOptionSelected == "sWeek") {
			changeStateOfID("#selectWeekNo"	, 'add');
			changeStateOfID("#repeatBetween", 'remove');
		} 
		else if (repeatOptionSelected == "noRepeat" && actionSelected == 'Block user') {
			changeStateOfID("#SpecificTime"	, 'add');
			changeStateOfID("#repeatBetween", 'remove');
			changeStateOfID("#betweenTime"	, 'remove');
			changeStateOfChkBox("#repeatDays",'remove');
		}
		else if (repeatOptionSelected == "noRepeat") {
			changeStateOfChkBox("#repeatDays",'remove');
		}
		else if (repeatOptionSelected == "primo" || repeatOptionSelected ==  "ultimo" ) {
			changeStateOfID("#repeatBetween", 'add');
			changeStateOfID("#specificDay"	, 'add');
			changeStateOfChkBox("#repeatDays",'remove');
		}
		else {
		}
		if (actionSelected == 'Add points' || actionSelected == 'Delete points') {
			changeStateOfID("#ATTime"		, 'add');
			changeStateOfID("#betweenTime"	, 'remove');
			disableNoRepeat(true);
		}
		else if ($('#repeatEach').find("option[value='noRepeat']").attr("selected") == "selected") {
			$('#repeatEach').find("option[value='noRepeat']").removeAttr("selected","selected");
		}
		else if (actionSelected == 'Set maximum of point') {
			changeStateOfID(	"#specificDay"	, 'remove');
			changeStateOfID(	"#repeatBetween", 'remove');
			changeStateOfID(	"#betweenTime"	, 'remove');
			changeStateOfChkBox("#repeatDays"	, 'remove');
			changeStateOfID(	"#selectWeekNo"	, 'remove');
			changeStateOfID(	"#ATTime"		, 'remove');
			changeStateOfID(	"#SpecificTime"	, 'add');

		}
	});
}
function changeStateOfAll(changeTo) {
	changeStateOfID(	"#repeatBetween", changeTo);
	changeStateOfID(	"#betweenTime"	, changeTo);
	changeStateOfID(	"#selectWeekNo"	, changeTo);
	changeStateOfID(	"#ATTime"		, changeTo);
	changeStateOfID(	"#SpecificTime"	, changeTo);
	changeStateOfID(	"#specificDay"	, changeTo);
	changeStateOfChkBox("#repeatDays"	, changeTo);

}
function disableNoRepeat(state) {
	if (state) {
		$('#repeatEach').find("option[value='noRepeat']").attr("disabled","disabled");
	} else {
		$('#repeatEach').find("option[value='noRepeat']").removeAttr("disabled","disabled");
	};
}
function changeStateOfID(id, changeTo) {
	if (changeTo == 'remove') {
		$(id).css("display", "none");
		$(id).attr("disabled","disabled");
	} else {
		$(id).css("display", "");
		$(id).removeAttr("disabled","disabled");
	};
}
function changeStateOfChkBox(id, changeTo) {
	if (changeTo == 'remove') {
		$(id).css("display", "none");
		$(id).children('.col-sm-1').children('.checkbox-inline').children('input').attr("disabled","disabled");
	} else {
		$(id).css("display", "");
		$(id).children('.col-sm-1').children('.checkbox-inline').children('input').removeAttr("disabled","disabled");
	};
}

function timeFromTo() {
	$('.formStartTime').datetimepicker({
		format: "hh:ii",
		linkField: "startTime",
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
	$('.formEndTime').datetimepicker({
		format: "hh:ii",
		linkField: "endTime",
		linkFormat: "hh:ii",
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