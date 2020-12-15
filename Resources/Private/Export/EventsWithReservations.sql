SELECT
	event_list.uid,
	event_list.pid,
	event_list.title,
	event_list.subtitle,
    department.name,
	doc_type.name,
	DATE_FORMAT(FROM_UNIXTIME(event_list.start), '%d.%m.%Y') AS 'start_formatted',
	DATE_FORMAT(FROM_UNIXTIME(event_list.end), '%d.%m.%Y') AS 'end_formatted',
	event_list.reg_required,
	event_list.ext_reg_link,
	event_list.online_event,
(
    SELECT COUNT(event_res.uid)
    FROM `tx_rkwevents_domain_model_eventreservation` as event_res
    WHERE event_res.event = event_list.uid
   		AND event_res.deleted = 0
) as reservation_count,
(
 	SELECT COUNT(event_res.uid)
 	FROM tx_rkwevents_domain_model_eventreservation as event_res
	RIGHT JOIN tx_rkwevents_domain_model_eventreservationaddperson as event_res_add
	ON event_res.uid = event_res_add.event_reservation
   	WHERE event_res.event = event_list.uid
   		AND event_res.deleted = 0
   		AND event_res_add.deleted = 0
 ) as reservation_add_count
FROM tx_rkwevents_domain_model_event as event_list
LEFT JOIN tx_rkwbasics_domain_model_documenttype as doc_type
ON event_list.document_type = doc_type.uid
LEFT JOIN tx_rkwbasics_domain_model_department as department
ON event_list.department = department.uid
WHERE event_list.pid = 2986
	AND DATE_FORMAT(FROM_UNIXTIME(event_list.start), '%Y') >= 2019
	AND DATE_FORMAT(FROM_UNIXTIME(event_list.start), '%Y') < 2021
	AND event_list.deleted = 0
	AND event_list.hidden = 0
	ORDER BY event_list.start