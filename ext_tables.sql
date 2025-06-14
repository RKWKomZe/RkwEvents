#
# Table structure for table 'tx_rkwevents_domain_model_eventseries'
#
CREATE TABLE tx_rkwevents_domain_model_eventseries
(

    uid                     int(11) NOT NULL auto_increment,
    pid                     int(11) DEFAULT '0' NOT NULL,

    title                   varchar(255) DEFAULT '' NOT NULL,
    url_override            tinyint(4) unsigned DEFAULT '0' NOT NULL,
    subtitle                varchar(255) DEFAULT '' NOT NULL,
    keywords                text,
    testimonials            text NOT NULL,
    description             text NOT NULL,
    description2            text NOT NULL,
    target_learning         text NOT NULL,
    target_group            text NOT NULL,
    schedule                text NOT NULL,
    partner                 text NOT NULL,
    header_image            int(11) unsigned DEFAULT '0',
    project                 varchar(255) DEFAULT '' NOT NULL,
    organizer               varchar(255) DEFAULT '' NOT NULL,
    add_info                varchar(255) DEFAULT '' NOT NULL,
    recommended_events      varchar(255) DEFAULT '' NOT NULL,
    recommended_links       varchar(255) DEFAULT '' NOT NULL,
    document_type           varchar(255) DEFAULT '' NOT NULL,
    department              varchar(255) DEFAULT '' NOT NULL,
    categories              varchar(255) DEFAULT '' NOT NULL,
    categories_displayed    varchar(255) DEFAULT '' NOT NULL,
    reg_inhouse             tinyint(1) unsigned DEFAULT '0' NOT NULL,
    backend_user_exclusive  tinyint(1) unsigned DEFAULT '0' NOT NULL,
    disable_reminder_mail   tinyint(1) unsigned DEFAULT '0' NOT NULL,
	event_start_date		int(11) unsigned DEFAULT '0' NOT NULL,

    event                   varchar(255) DEFAULT '' NOT NULL,

    tstamp                  int(11) unsigned DEFAULT '0' NOT NULL,
    crdate                  int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id               int(11) unsigned DEFAULT '0' NOT NULL,
    deleted                 tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden                  tinyint(4) unsigned DEFAULT '0' NOT NULL,

    sys_language_uid        int(11) DEFAULT '0' NOT NULL,
    l10n_parent             int(11) DEFAULT '0' NOT NULL,
    l10n_diffsource         mediumblob,

    PRIMARY KEY (uid),
    KEY              parent (pid),
    KEY language (l10n_parent,sys_language_uid)

);



#
# Table structure for table 'tx_rkwevents_domain_model_event'
#
CREATE TABLE tx_rkwevents_domain_model_event
(

	uid                      int(11) NOT NULL auto_increment,
	pid                      int(11) DEFAULT '0' NOT NULL,

    /*

    subtitle                 varchar(255) DEFAULT '' NOT NULL,
    keywords                 text,
    testimonials             text NOT NULL,
    description              text NOT NULL,
    description2             text NOT NULL,
    target_learning          text NOT NULL,
    target_group             text NOT NULL,
    schedule                 text NOT NULL,
    partner                  text NOT NULL,
    header_image             int(11) unsigned DEFAULT '0',
    project                  varchar(255) DEFAULT '' NOT NULL,
    organizer                varchar(255) DEFAULT '' NOT NULL,
    add_info                 varchar(255) DEFAULT '' NOT NULL,
    recommended_events       varchar(255) DEFAULT '' NOT NULL,
    recommended_links        varchar(255) DEFAULT '' NOT NULL,
    document_type            varchar(255) DEFAULT '' NOT NULL,
    department               varchar(255) DEFAULT '' NOT NULL,
    categories               varchar(255) DEFAULT '' NOT NULL,
    categories_displayed     varchar(255) DEFAULT '' NOT NULL,
    reg_inhouse              tinyint(1) unsigned DEFAULT '0' NOT NULL,
    backend_user_exclusive   tinyint(1) unsigned DEFAULT '0' NOT NULL,
     */

    /* still needed as slug uri helper */
    title                    varchar(255) DEFAULT '' NOT NULL,

    record_type              varchar(255) DEFAULT '\RKW\RkwEvents\Domain\Model\EventScheduled' NOT NULL,
    code                     varchar(255) DEFAULT '' NOT NULL,
    start                    int(11) unsigned DEFAULT '0' NOT NULL,
    end                      int(11) unsigned DEFAULT '0' NOT NULL,
    show_time                tinyint(1) unsigned DEFAULT '1' NOT NULL,
    longitude                varchar(255) DEFAULT '' NOT NULL,
    latitude                 varchar(255) DEFAULT '' NOT NULL,
    seats                    int(11) DEFAULT '0' NOT NULL,
    costs_unknown            tinyint(1) unsigned DEFAULT '0' NOT NULL,
    costs_reg                varchar(255) DEFAULT '' NOT NULL,
    costs_red                varchar(255) DEFAULT '' NOT NULL,
    costs_red_condition      varchar(255) DEFAULT '' NOT NULL,
    costs_red_link           varchar(255) DEFAULT '' NOT NULL,
    costs_tax                tinyint(1) unsigned DEFAULT '0' NOT NULL,
    reg_required             tinyint(1) unsigned DEFAULT '0' NOT NULL,
    reg_single               tinyint(1) unsigned DEFAULT '0' NOT NULL,
    reg_end                  int(11) unsigned DEFAULT '0' NOT NULL,
    cancel_end               int(11) unsigned DEFAULT '0' NOT NULL,
    ext_reg_link             varchar(255) DEFAULT '' NOT NULL,
    ext_show_link            varchar(255) DEFAULT '' NOT NULL,
    ext_cancel_info          varchar(255) DEFAULT '' NOT NULL,
    ext_cancel_link          varchar(255) DEFAULT '' NOT NULL,
    series                   varchar(255) DEFAULT '' NOT NULL,
    logos                    varchar(255) DEFAULT '' NOT NULL,
    currency                 int(11) unsigned DEFAULT '0',
    eligibility              tinyint(1) unsigned DEFAULT '0' NOT NULL,
    eligibility_link         varchar(255) DEFAULT '' NOT NULL,
    extended_network		 		 tinyint(1) unsigned DEFAULT '0' NOT NULL,
    online_event             tinyint(1) unsigned DEFAULT '0' NOT NULL,
    online_event_access_link varchar(255) DEFAULT '' NOT NULL,
    register_add_information text NOT NULL,
    place_unknown            tinyint(1) unsigned DEFAULT '0' NOT NULL,
    place                    varchar(255) DEFAULT '' NOT NULL,
    external_contact         varchar(255) DEFAULT '' NOT NULL,
    internal_contact         varchar(255) DEFAULT '' NOT NULL,
    be_user                  varchar(255) DEFAULT '' NOT NULL,

    presentations            varchar(255) DEFAULT '' NOT NULL,
    sheet                    varchar(255) DEFAULT '' NOT NULL,
    gallery1                 int(11) unsigned DEFAULT '0',
    gallery2                 int(11) unsigned DEFAULT '0',
    reservation              varchar(255) DEFAULT '' NOT NULL,
    workshop                 varchar(255) DEFAULT '' NOT NULL,
    workshop_select_type     tinyint(1) unsigned DEFAULT '0' NOT NULL,
    workshop_select_req      int(11) unsigned DEFAULT '0' NOT NULL,
    reminder_mail_tstamp     int(11) unsigned DEFAULT '0' NOT NULL,
    survey_before            varchar(255) DEFAULT '' NOT NULL,
    survey_after             varchar(255) DEFAULT '' NOT NULL,
    survey_after_mail_tstamp int(11) unsigned DEFAULT '0' NOT NULL,

    trainer                  varchar(255) DEFAULT '' NOT NULL,
    additional_tile_flag     varchar(255) DEFAULT '' NOT NULL,
    custom_privacy_consent_show	int(11) unsigned DEFAULT '0' NOT NULL,
    custom_privacy_consent   text,
    custom_field_show		     int(11) unsigned DEFAULT '0' NOT NULL,
    custom_field_mandatory	 int(11) unsigned DEFAULT '0' NOT NULL,
    custom_field_label       varchar(255) DEFAULT '' NOT NULL,
    custom_field_placeholder varchar(255) DEFAULT '' NOT NULL,
    custom_field_type        tinyint(1) unsigned DEFAULT '0' NOT NULL,
    custom_field_full_width  tinyint(1) unsigned DEFAULT '0' NOT NULL,

    tstamp                   int(11) unsigned DEFAULT '0' NOT NULL,
    crdate                   int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id                int(11) unsigned DEFAULT '0' NOT NULL,
    deleted                  tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden                   tinyint(4) unsigned DEFAULT '0' NOT NULL,
    starttime                int(11) unsigned DEFAULT '0' NOT NULL,
    endtime                  int(11) unsigned DEFAULT '0' NOT NULL,
    sorting                  int(11) unsigned DEFAULT '0' NOT NULL,

    sys_language_uid         int(11) DEFAULT '0' NOT NULL,
    l10n_parent              int(11) DEFAULT '0' NOT NULL,
    l10n_diffsource          mediumblob,

    PRIMARY KEY (uid),
    KEY                      parent (pid),
    KEY language (l10n_parent,sys_language_uid)

);



#
# Table structure for table 'tx_rkwevents_domain_model_eventplace'
#
CREATE TABLE tx_rkwevents_domain_model_eventplace
(

	uid                     int(11) NOT NULL auto_increment,
	pid                     int(11) DEFAULT '0' NOT NULL,

	name                    varchar(255) DEFAULT '' NOT NULL,
	short                   text                    NOT NULL,
	address                 varchar(255) DEFAULT '' NOT NULL,
	zip                     varchar(255) DEFAULT '' NOT NULL,
	city                    varchar(255) DEFAULT '' NOT NULL,
	country                 int(11) unsigned DEFAULT '0',
	driving_directions_link varchar(255) DEFAULT '' NOT NULL,
	longitude               varchar(255) DEFAULT '' NOT NULL,
	latitude                varchar(255) DEFAULT '' NOT NULL,

	tstamp                  int(11) unsigned DEFAULT '0' NOT NULL,
	crdate                  int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id               int(11) unsigned DEFAULT '0' NOT NULL,
	deleted                 tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden                  tinyint(4) unsigned DEFAULT '0' NOT NULL,

	sys_language_uid        int(11) DEFAULT '0' NOT NULL,
	l10n_parent             int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource         mediumblob,

	PRIMARY KEY (uid),
	KEY                     parent (pid),
	KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_rkwevents_domain_model_eventcontact'
#
CREATE TABLE tx_rkwevents_domain_model_eventcontact
(

	uid                        int(11) NOT NULL auto_increment,
	pid                        int(11) DEFAULT '0' NOT NULL,

	salutation                 int(11) DEFAULT '99' NOT NULL,
	first_name                 varchar(255) DEFAULT '' NOT NULL,
	last_name                  varchar(255) DEFAULT '' NOT NULL,
	company                    varchar(255) DEFAULT '' NOT NULL,
	address                    varchar(255) DEFAULT '' NOT NULL,
	zip                        varchar(255) DEFAULT '' NOT NULL,
	city                       varchar(255) DEFAULT '' NOT NULL,
	telephone                  varchar(255) DEFAULT '' NOT NULL,
	fax                        varchar(255) DEFAULT '' NOT NULL,
	email                      varchar(255) DEFAULT '' NOT NULL,
	lang                       int(11) unsigned DEFAULT '0',
	recipient_of_personal_data tinyint(4) unsigned DEFAULT '0' NOT NULL,

	tstamp                     int(11) unsigned DEFAULT '0' NOT NULL,
	crdate                     int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id                  int(11) unsigned DEFAULT '0' NOT NULL,
	deleted                    tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden                     tinyint(4) unsigned DEFAULT '0' NOT NULL,

	sys_language_uid           int(11) DEFAULT '0' NOT NULL,
	l10n_parent                int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource            mediumblob,

	PRIMARY KEY (uid),
	KEY                        parent (pid),
	KEY language (l10n_parent,sys_language_uid)

);



#
# Table structure for table 'tx_rkwevents_domain_model_eventorganizer'
#
CREATE TABLE tx_rkwevents_domain_model_eventorganizer
(

	uid              int(11) NOT NULL auto_increment,
	pid              int(11) DEFAULT '0' NOT NULL,

	salutation       int(11) DEFAULT '99' NOT NULL,
	first_name       varchar(255) DEFAULT '' NOT NULL,
	last_name        varchar(255) DEFAULT '' NOT NULL,
	company          varchar(255) DEFAULT '' NOT NULL,
	address          varchar(255) DEFAULT '' NOT NULL,
	zip              varchar(255) DEFAULT '' NOT NULL,
	city             varchar(255) DEFAULT '' NOT NULL,
	phone            varchar(255) DEFAULT '' NOT NULL,
	fax              varchar(255) DEFAULT '' NOT NULL,
	email            varchar(255) DEFAULT '' NOT NULL,
	show_pid         int(11) DEFAULT '0' NOT NULL,

	tstamp           int(11) unsigned DEFAULT '0' NOT NULL,
	crdate           int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id        int(11) unsigned DEFAULT '0' NOT NULL,
	deleted          tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden           tinyint(4) unsigned DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent      int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource  mediumblob,

	PRIMARY KEY (uid),
	KEY              parent (pid),
	KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_rkwevents_domain_model_eventsheet'
#
CREATE TABLE tx_rkwevents_domain_model_eventsheet
(

	uid              int(11) NOT NULL auto_increment,
	pid              int(11) DEFAULT '0' NOT NULL,
	event            int(11) unsigned DEFAULT '0' NOT NULL,

	title            varchar(255) DEFAULT '' NOT NULL,
	html             text                    NOT NULL,

	tstamp           int(11) unsigned DEFAULT '0' NOT NULL,
	crdate           int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id        int(11) unsigned DEFAULT '0' NOT NULL,
	deleted          tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden           tinyint(4) unsigned DEFAULT '0' NOT NULL,
	sorting          int(11) unsigned DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent      int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource  mediumblob,

	PRIMARY KEY (uid),
	KEY              parent (pid),
	KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_rkwevents_domain_model_eventreservation'
#
CREATE TABLE tx_rkwevents_domain_model_eventreservation
(

	uid                 int(11) NOT NULL auto_increment,
	pid                 int(11) DEFAULT '0' NOT NULL,

	event               int(11) unsigned DEFAULT '0' NOT NULL,

	remark              text NOT NULL,
	valid_until         int(11) unsigned DEFAULT '0' NOT NULL,
	add_person          varchar(255) DEFAULT '' NOT NULL,
	fe_user             varchar(255) DEFAULT '' NOT NULL,
	salutation          int(11) DEFAULT '99' NOT NULL,
	first_name          varchar(255) DEFAULT '' NOT NULL,
	last_name           varchar(255) DEFAULT '' NOT NULL,
	company             varchar(255) DEFAULT '' NOT NULL,
	company_role        varchar(255) DEFAULT '' NOT NULL,
	address             varchar(255) DEFAULT '' NOT NULL,
	zip                 varchar(255) DEFAULT '' NOT NULL,
	city                varchar(255) DEFAULT '' NOT NULL,
	phone               varchar(255) DEFAULT '' NOT NULL,
	mobile              varchar(255) DEFAULT '' NOT NULL,
	fax                 varchar(255) DEFAULT '' NOT NULL,
	email               varchar(255) DEFAULT '' NOT NULL,
	server_host         varchar(255) DEFAULT '' NOT NULL,
	show_pid            int(11) unsigned DEFAULT '0' NOT NULL,
	participate_dinner  tinyint(1) unsigned DEFAULT '0' NOT NULL,
	participate_meeting tinyint(1) unsigned DEFAULT '0' NOT NULL,
    subscribe_newsletter tinyint(1) unsigned DEFAULT '0' NOT NULL,
	target_group        varchar(255) DEFAULT '' NOT NULL,
	custom_field        text NOT NULL,
    cancel_reg_hash     varchar(255) DEFAULT '' NOT NULL,
	workshop_register   varchar(255) DEFAULT '' NOT NULL,

	tstamp              int(11) unsigned DEFAULT '0' NOT NULL,
	crdate              int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id           int(11) unsigned DEFAULT '0' NOT NULL,
	deleted             tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY                 parent (pid)

);

#
# Table structure for table 'tx_rkwevents_domain_model_eventreservationaddperson'
#
CREATE TABLE tx_rkwevents_domain_model_eventreservationaddperson
(

	uid               int(11) NOT NULL auto_increment,
	pid               int(11) DEFAULT '0' NOT NULL,

	event_reservation int(11) unsigned DEFAULT '0' NOT NULL,

	salutation        int(11) DEFAULT '99' NOT NULL,
	first_name        varchar(255) DEFAULT '' NOT NULL,
	last_name         varchar(255) DEFAULT '' NOT NULL,

	tstamp            int(11) unsigned DEFAULT '0' NOT NULL,
	crdate            int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id         int(11) unsigned DEFAULT '0' NOT NULL,
	deleted           tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY               parent (pid)

);

#
# Table structure for table 'tx_rkwevents_domain_model_eventworkshop'
#
CREATE TABLE tx_rkwevents_domain_model_eventworkshop
(

	uid                       int(11) NOT NULL auto_increment,
	pid                       int(11) DEFAULT '0' NOT NULL,

	title                     varchar(255) DEFAULT '' NOT NULL,
	start                     int(11) unsigned DEFAULT '0' NOT NULL,
	end                       int(11) unsigned DEFAULT '0' NOT NULL,
	description               text                    NOT NULL,
	type                      varchar(255) DEFAULT '' NOT NULL,
	previous_experience       varchar(255) DEFAULT '' NOT NULL,
	objective                 varchar(255) DEFAULT '' NOT NULL,
	speaker                   varchar(255) DEFAULT '' NOT NULL,
	available_seats           int(11) DEFAULT '0' NOT NULL,
	reg_required              tinyint(1) unsigned DEFAULT '0' NOT NULL,
	costs                     varchar(255) DEFAULT '' NOT NULL,
	registered_frontend_users varchar(255) DEFAULT '' NOT NULL,

	tstamp                    int(11) unsigned DEFAULT '0' NOT NULL,
	crdate                    int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id                 int(11) unsigned DEFAULT '0' NOT NULL,
	deleted                   tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden                    tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime                 int(11) unsigned DEFAULT '0' NOT NULL,
	endtime                   int(11) unsigned DEFAULT '0' NOT NULL,
	sorting                   int(11) unsigned DEFAULT '0' NOT NULL,
	sys_language_uid          int(11) DEFAULT '0' NOT NULL,
	l10n_parent               int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource           mediumblob,

	PRIMARY KEY (uid),
	KEY                       parent (pid),
	KEY language (l10n_parent,sys_language_uid)

);

