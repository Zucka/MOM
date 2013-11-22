parentcontrolsql => indeholder kun sql til at lave tabellen databasen i MySQL
sqlHelper => bruges til at tilgå databasen, alt input til funktionerne bør være strings
configDB => indeholder navnene på databasens tablerner og koloner. Derudover er config setting også der i.
smartparentalcontrol-sqlDBwithoutTestdata => expoteret fra mysql(med test system username:'user1'og password:'password')
smartparentalcontrol-sqlDBwithTestdata=> expoteret fra mysql
21-11-13-phpMyAdmin-withTestData => den der bruges pr. 21-11-13


how to use the the following addNewRuleToDB
	/*$cond1 = new Condition(null,'true',null, null);
	$cond2 = new Condition(null,'time',null, 123, array('onTimestamp'=> now()));
	$cond3 = new Condition(null,'Timeperiode',null, 123, 
		array('timeFrom'=> mktime(12,00)
		,'timeTo' =>mktime(13,00)
		,'weekdays' => 'monday'
		,'weekly'=> true
		,'ndWeekly' => false
		,'rdWeekly' => null
		,'firstInMonth' => null
		,'lastInMonth'=> null
		,'weekNumber'=> 52));
	
	$act1 = new Action(1,'Access unlimited');
	$act2 = new Action(1,'Add points',null,null, 23);
	$act3 = new Action(1,'Access controller',null, 123);
	
	$rule = new Rules(1, "first rule");
	$ruleId = addNewRuleToDB($rule, array($cond1, $cond2, $cond3), array($act1, $act2, $act3));
	
	//1 and 2 are from the current testpersons
	addRuleToProfile(1 ,$ruleId);
	addRuleToProfile(2 ,$ruleId);*/
	
	*/