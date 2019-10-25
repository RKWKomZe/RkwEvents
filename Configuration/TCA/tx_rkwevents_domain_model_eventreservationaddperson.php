<?php
return [
	'ctrl' => [
        'adminOnly' => 1,
        'title'	=> 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddperson',
		'label' => 'last_name',
		'label_alt' => 'first_name',
		'label_alt_force' => TRUE,
        'default_sortby' => 'ORDER BY last_name ASC',
        'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'delete' => 'deleted',
		'enablecolumns' => [

		],
		'searchFields' => 'event_reservation, salutation,first_name,last_name,',
		'iconfile' => 'EXT:rkw_events/Resources/Public/Icons/tx_rkwevents_domain_model_eventreservationaddperson.gif'
	],
	'interface' => [
		'showRecordFieldList' => 'event_reservation, salutation, first_name, last_name',
	],
	'types' => [
		'1' => ['showitem' => 'event_reservation, salutation, first_name, last_name'],
	],
	'palettes' => [
		'1' => ['showitem' => ''],
	],
	'columns' => [

        'event_reservation' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],

        'salutation' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddperson.salutation',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddperson.salutation.I.99', 99],
                    ['LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddperson.salutation.I.0', 0],
                    ['LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddperson.salutation.I.1', 1],
                ],
                'size' => 1,
                'maxitems' => 1,
                'eval' => ''
            ],
        ],
		'first_name' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddperson.first_name',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'last_name' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddperson.last_name',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
	],
];
