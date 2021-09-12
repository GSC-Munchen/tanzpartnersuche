CREATE TABLE tx_tanzpartnersuche_domain_model_main (
	rel_user int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_tanzpartnersuche_domain_model_user (
	main int(11) unsigned DEFAULT '0' NOT NULL,
	username varchar(255) NOT NULL DEFAULT '',
	password varchar(255) NOT NULL DEFAULT '',
	email varchar(255) NOT NULL DEFAULT '',
	height int(11) NOT NULL DEFAULT '0',
	age int(11) NOT NULL DEFAULT '0',
	gender varchar(255) NOT NULL DEFAULT '',
	picture int(11) unsigned NOT NULL DEFAULT '0',
	rel_offer int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_tanzpartnersuche_domain_model_offer (
	user int(11) unsigned DEFAULT '0' NOT NULL,
	level varchar(255) NOT NULL DEFAULT '',
	category varchar(255) NOT NULL DEFAULT '',
	bio text NOT NULL DEFAULT '',
	role varchar(255) NOT NULL DEFAULT ''
);
