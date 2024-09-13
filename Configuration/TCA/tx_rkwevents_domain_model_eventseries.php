<?php

use RKW\RkwEvents\Utility\TCA;

return [
	'ctrl' => [
		'title'	=> 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries',
        'label' => 'title',
        'label_userFunc' => TCA::class . '->eventSeriesTitle',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => true,
        'default_sortby' => 'ORDER BY title ASC',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden',
		],
		'searchFields' => 'title,subtitle,keywords,description,description2,target_learning,target_group,reg_inhouse,schedule,partner,testimonials,backend_user_exclusive,additional_tile_flag,recommended_links,header_image,add_info,recommended_events,document_type,department,categories,categories_displayed,organizer,event',
		'iconfile' => 'EXT:rkw_events/Resources/Public/Icons/tx_rkwevents_domain_model_eventseries.gif'
	],
	'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, subtitle, keywords, description, description2, target_learning, target_group, reg_inhouse, schedule, partner, testimonials, backend_user_exclusive, additional_tile_flag, recommended_links, header_image, add_info, recommended_events, document_type, department, categories, categories_displayed, organizer, event',
	],
	'types' => [
		'1' => [
            'showitem' => '

            --palette--;;title, subtitle, event,

            --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.tab_basicdata,
            reg_inhouse, document_type, department, categories, categories_displayed, organizer,

            --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.tab_additionaldata,
            keywords, description, description2, target_learning, target_group, schedule, partner, testimonials, add_info,

             --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.tab_other,
            header_image, additional_tile_flag, recommended_events, recommended_links, backend_user_exclusive,

            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
            --palette--;;dataFields, --palette--;;systemFields,

            '
        ],
	],
	'palettes' => [
		'1' => ['showitem' => ''],
        'title' => [
            'showitem' => 'title, url_override',
        ],
        'dataFields' => [
            'showitem' => 'hidden, sys_language_uid, l10n_parent, l10n_diffsource',
        ],
        'systemFields' => [
            'showitem' => 'starttime, endtime',
            'isHiddenPalette' => true,
        ],
	],
	'columns' => [

		'sys_language_uid' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
			'config' => [
				'type' => 'select',
                'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => [
					['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages', -1],
					['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0],
				],
				'default' => 0
			],
		],
		'l10n_parent' => [
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
			'config' => [
				'type' => 'select',
                'renderType' => 'selectSingle',
				'items' => [
					['', 0],
				],
				'foreign_table' => 'tx_rkwevents_domain_model_eventseries',
				'foreign_table_where' => 'AND tx_rkwevents_domain_model_eventseries.pid=###CURRENT_PID### AND tx_rkwevents_domain_model_eventseries.sys_language_uid IN (-1,0)',
			],
		],
		'l10n_diffsource' => [
			'config' => [
				'type' => 'passthrough',
			],
		],

		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
			'config' => [
				'type' => 'check',
			],
		],

        'title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim, required'
            ],
            'onChange' => 'reload'
        ],
        'url_override' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.url_override',
            'config' => [
                'type' => 'check',
            ],
        ],
        'subtitle' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.subtitle',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'keywords' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.keywords',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3
            ]
        ],
        'description' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim, required',
                'fieldControl'  => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'enableRichtext' => true,
            ],
        ],
        'description2' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.description2',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'fieldControl'  => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'enableRichtext' => true,
            ],
        ],
        'target_learning' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.target_learning',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'fieldControl'  => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'enableRichtext' => true,
            ],
        ],
        'target_group' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.target_group',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim',
            ],
        ],
        'reg_inhouse' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.reg_inhouse',
            'config' => [
                'type' => 'check',
                'default' => 0
            ],
            //'displayCond' => 'FIELD:reg_required:REQ:true',
        ],
        'schedule' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.schedule',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'fieldControl'  => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'enableRichtext' => true,
            ],
        ],
        'partner' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.partner',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'fieldControl'  => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'enableRichtext' => true,
            ],
        ],
        'testimonials' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.testimonials',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'fieldControl'  => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'enableRichtext' => true,
            ],
        ],
        'backend_user_exclusive' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.backend_user_exclusive',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ]
        ],
        'recommended_links' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.recommended_links',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'maxitems' => 5,
                'minitems' => 0,
                'size' => 5,
                'default' => 0,
                'suggestOptions' => [
                    'default' => [
                        'additionalSearchFields' => 'nav_title, alias',
                        'addWhere' => 'AND pages.doktype = 1'
                    ]
                ],
            ]
        ],
        'header_image' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.header_image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'maxitems' => 1,
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                    --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                            ],
                        ],
                    ],
                    'appearance' => [
                        'collapseAll' => 1
                    ]
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'add_info' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.add_info',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'add_info',
                [
                    'maxitems' => 9999,
                ],
                'pdf'
            ),
        ],
        'recommended_events' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.recommended_events',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwevents_domain_model_event',
                'foreign_table_where' => 'AND tx_rkwevents_domain_model_event.uid!=###THIS_UID### AND tx_rkwevents_domain_model_event.pid=###CURRENT_PID### AND tx_rkwevents_domain_model_event.sys_language_uid = ###REC_FIELD_sys_language_uid### AND ( (tx_rkwevents_domain_model_event.start = 0 OR tx_rkwevents_domain_model_event.start > unix_timestamp(now())) OR (tx_rkwevents_domain_model_event.end > unix_timestamp(now())) ) ORDER BY tx_rkwevents_domain_model_event.start DESC',
                'itemsProcFunc' => 'RKW\\RkwEvents\\UserFunctions\\TcaProcFunc->getEventTitle',
                'maxitems'      => 99,
                'size'          => 5,
            ],
        ],
        'document_type' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.document_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwbasics_domain_model_documenttype',
                'foreign_table_where' => ' AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwbasics_domain_model_documenttype.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwbasics_domain_model_documenttype.type = "events" AND tx_rkwbasics_domain_model_documenttype.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwbasics_domain_model_documenttype.name ASC',
                'maxitems'      => 1,
                'minitems'      => 1,
                'size'          => 5,
            ],
        ],
        'department' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.department',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwbasics_domain_model_department',
                'foreign_table_where' => 'AND tx_rkwbasics_domain_model_department.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwbasics_domain_model_department.name ASC',
                'maxitems'      => 1,
                'minitems'      => 1,
                'size'          => 5,
            ],
        ],
        'categories_displayed' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.categories_displayed',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'itemsProcFunc' => 'RKW\\RkwEvents\\UserFunctions\\TcaProcFunc->getSelectedCategories',
                'maxitems'      => 1,
                'size'          => 5,

            ],
        ],
        'organizer' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.organizer',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwevents_domain_model_eventorganizer',
                'foreign_table_where' => 'AND tx_rkwevents_domain_model_eventorganizer.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwevents_domain_model_eventorganizer.last_name ASC, tx_rkwevents_domain_model_eventorganizer.company ASC, tx_rkwevents_domain_model_eventorganizer.email ASC',
                'maxitems'      => 1,
                'minitems' 		=> 1,
                'size'          => 5,
                'fieldControl'  => [
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'editPopup' => [
                        'disabled' => false,
                    ]
                ]
            ],
        ],
        'event' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.event',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_rkwevents_domain_model_event',
                'foreign_table_where' => 'AND tx_rkwevents_domain_model_event.deleted = 0',
                'foreign_field' => 'series',
                //'foreign_sortby' => 'sorting',
                'foreign_default_sortby' => 'start',
                'maxitems'      => 9999,
                'minitems'      => 0,
                'size'          => 5,
            ],
        ],
	],
];
