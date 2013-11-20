/*lave en database e.g.: CREATE DATABASE smartparentalcontrol;
også kør det følgende
*/


CREATE TABLE control_system
(
    CSId BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
    username VARCHAR(30) NOT NULL UNIQUE,
    password  VARCHAR(50) NOT NULL,
	email VARCHAR(30),
	phoneNo INTEGER,
    PRIMARY KEY(CSId)    
);


CREATE TABLE profile
(
    PId BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
	CSId BIGINT UNSIGNED NOT NULL,
	name VARCHAR(30),
	points DOUBLE DEFAULT 0,
	PRIMARY KEY(PId),
	FOREIGN KEY (CSId) REFERENCES control_system(CSId)	
);

CREATE TABLE controller
(
    CSerieNo BIGINT UNSIGNED NOT NULL UNIQUE,
	CSId BIGINT UNSIGNED NOT NULL,
	name VARCHAR(30) NOT NULL,
	location VARCHAR(30),
	status  ENUM('!', 'GREEN', 'RED') DEFAULT '!',
	PRIMARY KEY(CSerieNo),
	FOREIGN KEY (CSId) REFERENCES control_system(CSId)	
);

CREATE TABLE tag
(
    TSerieNo BIGINT UNSIGNED NOT NULL UNIQUE,
	CSId BIGINT UNSIGNED NOT NULL,
	profileId BIGINT UNSIGNED,
	name VARCHAR(30),
	active BOOLEAN DEFAULT TRUE,
	PRIMARY KEY(TSerieNo),
	FOREIGN KEY (CSId) REFERENCES control_system(CSId),
	FOREIGN KEY (profileId) REFERENCES profile(PId) ON DELETE CASCADE
);



CREATE TABLE chores
(
	CId BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
	CSId BIGINT UNSIGNED NOT NULL,
    name VARCHAR(30) NOT NULL,
	description VARCHAR(50),
	defaultPoints DOUBLE UNSIGNED DEFAULT 0,
	PRIMARY KEY(CId),
	FOREIGN KEY (CSId) REFERENCES control_system(CSId)
);

CREATE TABLE rules
(
	RId BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
	CSId BIGINT UNSIGNED NOT NULL,
	name VARCHAR(30) NOT NULL,
	profileId BIGINT UNSIGNED,
	isPermission BOOLEAN DEFAULT FALSE,
	PRIMARY KEY(RId),
	FOREIGN KEY (CSId) REFERENCES control_system(CSId),
	FOREIGN KEY (profileId) REFERENCES profile(PId)	
);

CREATE TABLE action
(
	AId BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
	RId BIGINT UNSIGNED NOT NULL,
	name VARCHAR(30) NOT NULL,
	points DOUBLE,
	controllerId BIGINT UNSIGNED,
	PRIMARY KEY(AId),
	FOREIGN KEY (controllerId) REFERENCES controller(CSerieNo),
	FOREIGN KEY(RId) REFERENCES rules(RId)
);
CREATE TABLE rCondition
(
	condId BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
	RId BIGINT UNSIGNED NOT NULL,
	name VARCHAR(30) NOT NULL,
	controllerId BIGINT UNSIGNED NOT NULL,
	FOREIGN KEY (controllerId) REFERENCES controller(CSerieNo),
	FOREIGN KEY(RId) REFERENCES rules(RId),
	PRIMARY KEY(condId)
);


CREATE TABLE cond_timeperiod
(
	condTimepId BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
	condId BIGINT UNSIGNED,  
	timeFrom DATETIME NOT NULL,
	timeTo DATETIME NOT NULL,
	weekdays SET ('monday','tuesday','wednesday','thursday','friday','saturday','sunday'),
	weekly BOOLEAN DEFAULT FALSE,
	ndWeekly BOOLEAN DEFAULT FALSE,
	rdWeekly BOOLEAN DEFAULT FALSE,
	firstInMonth BOOLEAN DEFAULT FALSE,
	lastInMonth BOOLEAN DEFAULT FALSE,
	weekNumber TINYINT,
	PRIMARY KEY(condTimepId),
	FOREIGN KEY (condId) REFERENCES rCondition(condId)
);


CREATE TABLE cond_timestamp
(
	condTimesId BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
	condId BIGINT UNSIGNED NOT NULL,
	onTimestamp TIMESTAMP NOT NULL,
	PRIMARY KEY(condTimesId),
	FOREIGN KEY (condId) REFERENCES rCondition(condId)
);

CREATE TABLE controller_used_by_tag
(
    TSerieNo BIGINT UNSIGNED NOT NULL,
	CSerieNo BIGINT UNSIGNED NOT NULL,
	starttime TIMESTAMP, 
	endtime TIMESTAMP, 
	FOREIGN KEY(TSerieNo) REFERENCES tag(TSerieNo),	
	FOREIGN KEY(CSerieNo) REFERENCES controller(CSerieNo),
	PRIMARY KEY(TSerieNo, CSerieNo, starttime)
);

CREATE TABLE profile_has_rules
(
    PId BIGINT UNSIGNED NOT NULL,
	RId BIGINT UNSIGNED NOT NULL,
	validFromTime TIMESTAMP,
	FOREIGN KEY(PId) REFERENCES profile(PId),	
	FOREIGN KEY(RId) REFERENCES rules(RId),
	PRIMARY KEY(PId, RId)
);

CREATE TABLE profile_did_chores
(
    PId BIGINT UNSIGNED NOT NULL,
	CId BIGINT UNSIGNED NOT NULL,
	actualPoints DOUBLE,
	timeOfCreation TIMESTAMP,
	FOREIGN KEY(PId) REFERENCES profile(PId),	
	FOREIGN KEY(CId) REFERENCES chores(CId),
	PRIMARY KEY(PId, CId, timeOfCreation)
);