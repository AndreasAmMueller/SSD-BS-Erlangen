DROP TABLE IF EXISTS duties;
DROP TABLE IF EXISTS attendences;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
	  usr_id             INT(11)       NOT NULL  AUTO_INCREMENT
	, usr_email          VARCHAR(100)  NOT NULL
	, usr_name           VARCHAR(100)  NOT NULL
	, usr_password       VARCHAR(255)  NOT NULL
	, usr_mobile         VARCHAR(30)
	, usr_class          VARCHAR(20)
	, usr_room           VARCHAR(20)
	, usr_qualification  TEXT
	, PRIMARY KEY (usr_id)
	, UNIQUE KEY (usr_email)
);

CREATE TABLE attendences (
	  att_user  INT(11)  NOT NULL
	, att_week  INT(3)   NOT NULL
	, att_year  INT(5)   NOT NULL
	, att_mon   BIT(1)
	, att_tue   BIT(1)
	, att_wed   BIT(1)
	, att_thu   BIT(1)
	, att_fri   BIT(1)
	, PRIMARY KEY (att_user, att_week)
	, CONSTRAINT att_user_fk FOREIGN KEY (att_user) REFERENCES users(usr_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE duties (
	  dut_user  INT(11)  NOT NULL
	, dut_week  INT(3)   NOT NULL
	, dut_year  INT(5)   NOT NULL
	, dut_mon   BIT(1)
	, dut_tue   BIT(1)
	, dut_wed   BIT(1)
	, dut_thu   BIT(1)
	, dut_fri   BIT(1)
	, PRIMARY KEY (dut_user, dut_week)
	, CONSTRAINT dut_user_fk FOREIGN KEY (dut_user) REFERENCES users(usr_id) ON DELETE CASCADE ON UPDATE CASCADE
);