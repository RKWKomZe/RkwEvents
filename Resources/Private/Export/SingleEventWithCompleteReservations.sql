SELECT
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
  remark,
  DATE_FORMAT(
    FROM_UNIXTIME(crdate),
    '%d.%m.%Y %H:%i:%s'
  ) as created
FROM tx_rkwevents_domain_model_eventreservation 
WHERE `event` = :event_uid
AND deleted = 0
UNION ALL
SELECT 
  event_resadd.salutation,
  event_resadd.first_name,
  event_resadd.last_name,
  event_res.company,
  event_res.address,
  event_res.zip,
  event_res.city,
  event_res.phone,
  event_res.mobile,
  event_res.fax,
  event_res.email, 
  event_res.remark,
  DATE_FORMAT(
    FROM_UNIXTIME(event_resadd.crdate),
    '%d.%m.%Y %H:%i:%s'
  ) as created
FROM tx_rkwevents_domain_model_eventreservation as event_res
INNER JOIN tx_rkwevents_domain_model_eventreservationaddperson as event_resadd
ON event_res.uid = event_resadd.event_reservation AND event_res.`event` = :event_uid
AND event_res.deleted = 0