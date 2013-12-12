import MySQLdb
import MySQLdb.cursors
import datetime
from time import sleep,time
from math import floor
import calendar
weekday = {
	1:'monday',
	2:'tuesday',
	3:'wednesday',
	4:'thursday',
	5:'friday',
	6:'saturday',
	7:'sunday' 
}

db = MySQLdb.connect(host = "blade3.s-et.aau.dk",
					 user = "spc",
					 passwd = "tts37ent",
					 db="smartparentalcontrol",
					 cursorclass=MySQLdb.cursors.DictCursor)
cur = db.cursor()

def firstDayInMonth(day,month,year):
	month_range = calendar.monthrange(year,month)
	date = datetime.date(year,month,1)
	delta = ((day - 1) - month_range[0]) % 7
	return date + datetime.timedelta(days=delta)

def lastDayInMonth(day,month,year):
	month_range = calendar.monthrange(year,month)
	date = datetime.date(year,month,month_range[1])
	delta = (date.weekday() - (day - 1)) % 7
	return date - datetime.timedelta(days=delta)


def shouldActivate(rule):
	timeFrom = rule['timeFrom']
	timeTo = rule['timeTo']
	now = datetime.datetime.now()
	repeatable = rule['weekly'] == 1 or rule['ndWeekly'] == 1 or rule['rdWeekly'] == 1 or rule['firstInMonth'] == 1 or rule['lastInMonth'] == 1 or rule['weekdays'] is not None
	repeatWeekMonth = rule['weekly'] == 1 or rule['ndWeekly'] == 1 or rule['rdWeekly'] == 1 or rule['firstInMonth'] == 1 or rule['lastInMonth'] == 1
	activateDate = (now.date() >= timeFrom.date() and now.date() <= timeTo.date())
	#print 'timeFrom: '+str(timeFrom.time())
	#print 'timeTo: '+str(timeTo.time())
	#print 'now: '+str(now.time())
	#print 'now >= timeFrom: '+str(now >= timeFrom)
	#print 'now <= timeTo: '+str(now <= timeTo)
	#print 'activateDate: '+str(activateDate)
	activateTime = now.time() >= timeFrom.time() and now.time() <= timeTo.time()
	#if there are any weekdays specified are we on one of them?
	activateWeekday = False
	currentWeekday = weekday[datetime.datetime.today().isoweekday()]
	if rule['weekdays'] is not None:
		for day in rule['weekdays'].split(','):
			if currentWeekday == day:
				activateWeekday = True

	#check if we should activate based on weekly/every 2nd week/every 3rd week
	deltaWeek = floor((now-timeFrom).days / 7)
	activateWeek = False
	if rule['weekly'] == 1 :
		activateWeek = True
	elif rule['ndWeekly'] == 1: 
		activateWeek = deltaWeek % 2 == 0
	elif rule['rdWeekly'] == 1: #The rule has some weekly recurrence enabled
		activateWeek = deltaWeek % 3 == 0


	#check if we should activate based on firstinmonth/lastinmonth
	activateFirstLastInMonth = False
	if rule['firstInMonth'] == 1:
		if activateWeekday:
			if now.date() == firstDayInMonth(rule['weekdays'][0],now.month,now.year):
				activateFirstLastInMonth = True
	if rule['lastInMonth'] == 1:
		if activateWeekday:
			if now.date() == lastDayInMonth(rule['weekdays'][0],now.month,now.year):
				activateFirstLastInMonth = True



	#print 'activateDate: '+str(activateDate)
	#print 'activateWeekday: '2014-06-26 00:00:00+str(activateWeekday)
	#print 'activateWeek: '+str(activateWeek)
	#print 'activateFirstLastInMonth: '+str(activateFirstLastInMonth)
	#print 'activateTime: '+str(activateTime)

	if (activateWeekday and not repeatWeekMonth and activateDate and activateTime):
		print 'Rule activated because of weekday and time'
		return True
	if (activateWeek and activateDate and activateTime):
		print 'Rule activated because of week and time'
		return True
	if (activateFirstLastInMonth and activateDate and activateTime):
		print 'Rule activated because of weekday and time'
		return True
	if (activateDate and activateTime and not repeatable):
		print 'Rule activated because of date and time'
		return True

	return False

def AddPoints(rule):
	pointsToAdd = rule['points']
	if pointsToAdd is None and pointsToAdd > 0:
		print 'Add points must be a possitive integer, it was either null or <= 0, skipping this rule'
		return
	db.autocommit(False)
	try:
		cur.execute("SELECT points from profile WHERE PId='{0}'".format(rule['PId']))
		row = cur.fetchone()
		points = int(row['points'])+pointsToAdd
		cur.execute("UPDATE profile SET points={0} WHERE PId='{1}'".format(points,rule['PId']))
		db.commit()
		#print 'profile points: '+str(row['points'])
		#print 'Added {0} points to profile nr {1} with new total {2}'.format(pointsToAdd,rule['PId'],points)
	except:
		db.rollback()
	db.autocommit(True)
	return

#check rules that have specific actions, so far only checks the 'Add points' action
def checkRules():
	print "Checking rules"
	cur.execute("SELECT * FROM rule,rcondition,cond_timeperiod,action,profile_has_rule WHERE rule.RId=rcondition.RId AND rule.RId=action.RId AND rule.RId=profile_has_rule.RId AND rcondition.condId=cond_timeperiod.condId AND rcondition.name='Timeperiod' AND action.name='Add points'")
	rules = cur.fetchall()
	for rule in rules:
		if shouldActivate(rule):
			if rule['action.name'] == 'Add points': #add more ifs here for doing more actions
				AddPoints(rule)
			print 'rule nr '+str(rule['RId'])+' with condition '+str(rule['condId'])+' was activated\n'


while True:
	now = time()
	checkRules()
	print 'checking rules took {0} milliseconds'.format(str((time()-now)*1000))
	sleep(60)