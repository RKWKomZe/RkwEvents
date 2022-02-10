<?php
return [
	'ctrl' => [
        //'adminOnly' => 1,
        'title'	=> 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservation',
        'label' => 'last_name',
        'label_alt' => 'first_name',
        'label_alt_force' => true,
        'default_sortby' => 'ORDER BY last_name ASC',
        'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => true,

		'delete' => 'deleted',
		'enablecolumns' => [
		],
		'searchFields' => 'event,remark,add_person,fe_user,salutation,first_name,last_name,company,address,zip,city,phone,mobile,email,server_host,show_pid',
		'iconfile' => 'EXT:rkw_events/Resources/Public/Icons/tx_rkwevents_domain_model_eventreservation.gif'
	],
	'interface' => [
		'showRecordFieldList' => 'event, fe_user, salutation, first_name, last_name, company, address, zip, city, phone, mobile, email, remark, add_person, server_host, show_pid,',
	],
	'types' => [
		'1' => ['showitem' => 'event, fe_user, salutation, first_name, last_name, company, address, zip, city, phone, mobile, email, remark, add_person, server_host, show_pid'],
	],
	'palettes' => [
		'1' => ['showitem' => ''],
	],
	'columns' => [

		'remark' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservation.remark',
			'config' => [
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			],
		],
        'add_person' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservation.add_person',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_rkwevents_domain_model_eventreservationaddperson',
                'foreign_field' => 'event_reservation',
                'maxitems'      => 4,
                'size'          => 5,
                'appearance' => [
                    'elementBrowserType' => 'db',
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                    'enabledControls' => [
                        'info' => true,
                        'new' => false,
                        'dragdrop' => false,
                        'sort' => false,
                        'hide' => false,
                        'delete' => true,
                        'localize' => false
                    ],
                ],
            ],
        ],
		'fe_user' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservation.fe_user',
			'config' => [
				'type' => 'select',
                'renderType' => 'selectSingle',
				'foreign_table' => 'fe_users',
				'foreign_table_where' => 'AND fe_users.deleted = 0  AND fe_users.disable = 0 AND fe_users.email <> "" ORDER BY fe_users.username',
				'minitems' => 1,
				'maxitems' => 1,
			],
		],
        'event' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
		'salutation' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddress.salutation',
			'config' => [
				'type' => 'select',
                'renderType' => 'selectSingle',
				'items' => [
					['LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddress.salutation.I.99', 99],
					['LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddress.salutation.I.0', 0],
					['LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddress.salutation.I.1', 1],
				],
				'default' => 99,
				'size' => 1,
				'maxitems' => 1,
			],
		],
		'first_name' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddress.first_name',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim, required'
			],
		],
		'last_name' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddress.last_name',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim, required'
			],
		],
		'company' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddress.company',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'address' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddress.address',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim, required'
			],
		],
		'zip' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddress.zip',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim, required'
			],
		],
		'city' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddress.city',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim, required'
			],
		],
		'phone' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddress.phone',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
        'mobile' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddress.mobile',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
		'fax' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddress.fax',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'email' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservationaddress.email',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,email'
			],
		],
		'server_host' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservation.server_host',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'max' => 255,
				'readOnly' => 1,
			],
		],
        'show_pid' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservation.show_pid',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'readOnly' => 1,
            ],
        ],
	],
];
