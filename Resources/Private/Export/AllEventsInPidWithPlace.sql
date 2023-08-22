SELECT event_table.uid, event_table.record_type, event_table.hidden, event_table.deleted,
       event_table.backend_user_exclusive, event_table.title, event_table.title, event_table.subtitle,
       event_table.code, DATE_FORMAT(FROM_UNIXTIME(event_table.start), '%d.%m.%Y') AS 'start_formatted',
       DATE_FORMAT(FROM_UNIXTIME(event_table.end), '%d.%m.%Y') AS 'end_formatted', event_table.costs_reg,
  		 event_table.costs_red, event_table.costs_red_condition, event_place_table.name, event_place_table.address,
  		 event_place_table.zip, event_place_table.city, event_table.target_group, event_table.target_learning, event_table.trainer,
			 CONCAT('https://www.rkw-bw.de/veranstaltungen/details/rkw-events/show/placeholder-', event_table.uid) as pseudo_link
FROM tx_rkwevents_domain_model_event as event_table
			 LEFT JOIN tx_rkwevents_domain_model_eventplace as event_place_table
						 ON event_table.place = event_place_table.uid
WHERE event_table.pid = 5088
