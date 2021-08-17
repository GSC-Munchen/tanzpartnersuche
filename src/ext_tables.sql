CREATE TABLE tx_tanzpartnersuche_domain_model_main (
	relation_user int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_tanzpartnersuche_domain_model_user (
	main int(11) unsigned DEFAULT '0' NOT NULL,
	username varchar(255) NOT NULL DEFAULT '',
	email varchar(255) NOT NULL DEFAULT '',
	password varchar(255) NOT NULL DEFAULT ''
);
