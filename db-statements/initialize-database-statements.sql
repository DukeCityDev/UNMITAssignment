DROP TABLE IF EXISTS user;

CREATE TABLE user(
	userId INT UNSIGNED AUTO_INCREMENT NOT NULL, -- PRIMARY KEY
	userHash CHAR(128) NOT NULL, -- hash for password
	userSalt CHAR(64) NOT NULL, -- salt for password
  userFirstName CHAR(32) NOT NULL, -- user first name
  userLastName CHAR(32) NOT NULL, -- user last name
	userUsername VARCHAR(24) UNIQUE NOT NULL, -- username
	-- PRIMARY KEY AND FOREIGN KEY
	PRIMARY KEY (userId)
);

CREATE TABLE scheduleItem(
	scheduleItemId INT AUTO_INCREMENT UNSIGNED NOT NULL,
	scheduleItemDesciption VARCHAR(1000) NOT NULL,
	scheduleItemName VARCHAR(24),
	scheduleItemStartTime DATETIME NOT NULL,
	scheduleItemEndTime DATETIME NOT NULL,
	userId INT UNSIGNED NOT NULL,
	dateTimePosted DATETIME NOT NULL,

	PRIMARY KEY (scheduleItemId),
	FOREIGN KEY (userId) REFERENCES user(userId)
);

