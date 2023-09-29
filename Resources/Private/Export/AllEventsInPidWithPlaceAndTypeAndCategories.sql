SELECT event_table.uid, event_table.record_type, event_type_table.name, event_table.hidden, event_table.deleted,
       event_table.backend_user_exclusive, event_table.title, event_table.title, event_table.subtitle,
       event_table.code, DATE_FORMAT(FROM_UNIXTIME(event_table.start), '%d.%m.%Y') AS 'start_formatted',
       DATE_FORMAT(FROM_UNIXTIME(event_table.end), '%d.%m.%Y') AS 'end_formatted', event_table.costs_reg,
  		 event_table.costs_red, event_table.costs_red_condition, event_place_table.name, event_place_table.address,
  		 event_place_table.zip, event_place_table.city, event_table.target_group, event_table.target_learning, event_table.trainer,
			 GROUP_CONCAT(sys_category.title ORDER BY sys_category.title ASC SEPARATOR ','  )  as 'categories',
			 CONCAT('https://www.rkw-bw.de/veranstaltungen/details/rkw-events/show/placeholder-', event_table.uid) as pseudo_link
FROM tx_rkwevents_domain_model_event as event_table
			 LEFT JOIN tx_rkwevents_domain_model_eventplace as event_place_table
						 ON event_table.place = event_place_table.uid
			 LEFT JOIN tx_rkwbasics_domain_model_documenttype as event_type_table
						 ON event_table.document_type = event_type_table.uid
			 LEFT JOIN sys_category_record_mm
						 ON sys_category_record_mm.tablenames = 'tx_rkwevents_domain_model_event'
						      AND sys_category_record_mm.uid_foreign = event_table.uid
									AND sys_category_record_mm.fieldname = 'categories'
			LEFT JOIN sys_category
				ON sys_category.uid = sys_category_record_mm.uid_local
WHERE event_table.pid = 5088
GROUP BY event_table.uid
