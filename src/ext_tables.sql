CREATE TABLE tx_tanzpartnersuche_domain_model_tanzpartnersuche (
	username varchar(255) NOT NULL DEFAULT '',
	password varchar(255) NOT NULL DEFAULT '',
	email varchar(255) NOT NULL DEFAULT '',
	height int(11) NOT NULL DEFAULT '0',
	age int(11) NOT NULL DEFAULT '0',
	gender varchar(255) NOT NULL DEFAULT '',
	picture int(11) unsigned NOT NULL DEFAULT '0',
	level varchar(255) NOT NULL DEFAULT '',
	category varchar(255) NOT NULL DEFAULT '',
	bio text NOT NULL DEFAULT '',
	role varchar(255) NOT NULL DEFAULT '',
	verificationcode varchar(255) NOT NULL DEFAULT '',
	loggedin varchar(255) NOT NULL DEFAULT ''
);
