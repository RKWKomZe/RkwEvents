/* drop temporary table, if it already exists */
DROP TABLE IF EXISTS
  temp_tx_rkwevents_domain_model_eventreservation;

/* create temporary table to be able to add additional persons to separate rows and export result set to .csv and other formats */
CREATE TABLE temp_tx_rkwevents_domain_model_eventreservation(
  reservation_uid INT(11) NOT NULL,
  EVENT INT(11) UNSIGNED DEFAULT '0' NOT NULL,
  remark TEXT NOT NULL,
  valid_until INT(11) UNSIGNED DEFAULT '0' NOT NULL,
  add_person VARCHAR(255) DEFAULT '' NOT NULL,
  fe_user VARCHAR(255) DEFAULT '' NOT NULL,
  salutation INT(11) DEFAULT '99' NOT NULL,
  first_name VARCHAR(255) DEFAULT '' NOT NULL,
  last_name VARCHAR(255) DEFAULT '' NOT NULL,
  company VARCHAR(255) DEFAULT '' NOT NULL,
  address VARCHAR(255) DEFAULT '' NOT NULL,
  zip VARCHAR(255) DEFAULT '' NOT NULL,
  city VARCHAR(255) DEFAULT '' NOT NULL,
  phone VARCHAR(255) DEFAULT '' NOT NULL,
  mobile VARCHAR(255) DEFAULT '' NOT NULL,
  fax VARCHAR(255) DEFAULT '' NOT NULL,
  email VARCHAR(255) DEFAULT '' NOT NULL,
  crdate VARCHAR(255) DEFAULT '' NOT NULL
);

/* insert values from table tx_rkwevents_domain_model_eventreservation, where event = :event_uid */
INSERT
INTO
  temp_tx_rkwevents_domain_model_eventreservation(
    reservation_uid,
    EVENT,
    remark,
    add_person,
    fe_user,
    salutation,
    first_name,
    last_name,
    company,
    address,
    zip,
    city,
    phone,
    mobile,
    fax,
    email,
    crdate
  )
SELECT
  uid,
  :event_uid,
  remark,
  add_person,
  fe_user,
  salutation,
  first_name,
  last_name,
  company,
  address,
  zip,
  city,
  phone,
  mobile,
  fax,
  email,
  DATE_FORMAT(
    FROM_UNIXTIME(crdate),
    '%d.%m.%Y %H:%i:%s'
  )
FROM
  tx_rkwevents_domain_model_eventreservation
WHERE
  tx_rkwevents_domain_model_eventreservation.event = :event_uid;

/* insert values from table tx_rkwevents_domain_model_eventreservationaddperson, if existing reservations contain more than one person. */
INSERT
INTO
  temp_tx_rkwevents_domain_model_eventreservation(
    reservation_uid,
    salutation,
    first_name,
    last_name,
    crdate
  )
SELECT
  event_reservation,
  salutation,
  first_name,
  last_name,
  DATE_FORMAT(
    FROM_UNIXTIME(crdate),
    '%d.%m.%Y %H:%i:%s'
  )
FROM
  tx_rkwevents_domain_model_eventreservationaddperson
WHERE
  tx_rkwevents_domain_model_eventreservationaddperson.event_reservation IN(
  SELECT
    uid
  FROM
    tx_rkwevents_domain_model_eventreservation
  WHERE
    tx_rkwevents_domain_model_eventreservation.event = :event_uid
);

/* select ordered result from temporary table to allow export */
SELECT
  *
FROM
  temp_tx_rkwevents_domain_model_eventreservation
ORDER BY
  reservation_uid,
  add_person DESC;
