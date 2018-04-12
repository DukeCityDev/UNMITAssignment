USE UNMProject;

DROP TABLE IF EXISTS scheduleItem;
DROP TABLE IF EXISTS user;

CREATE TABLE user(
	userId INT UNSIGNED AUTO_INCREMENT NOT NULL, -- PRIMARY KEY
	userHash CHAR(128) NOT NULL, -- hash for password
	userSalt CHAR(64) NOT NULL, -- salt for password,
	userEmail CHAR(160) NOT NULL, -- user's email
  userFirstName CHAR(24) NOT NULL, -- user's first name
  userLastName CHAR(24) NOT NULL, -- user's last name
	userUsername VARCHAR(24) UNIQUE NOT NULL, -- user's username
	-- PRIMARY KEY AND FOREIGN KEY
	PRIMARY KEY (userId)
);

CREATE TABLE scheduleItem(
	scheduleItemId INT UNSIGNED AUTO_INCREMENT  NOT NULL,
	scheduleItemDescription VARCHAR(1000) NOT NULL,
	scheduleItemName VARCHAR(24),
	scheduleItemStartTime VARCHAR(30) NOT NULL,
	scheduleItemEndTime VARCHAR(30) NOT NULL,
	scheduleItemUserId INT UNSIGNED NOT NULL,

	PRIMARY KEY (scheduleItemId),
	FOREIGN KEY (scheduleItemUserId) REFERENCES user(userId)
);

