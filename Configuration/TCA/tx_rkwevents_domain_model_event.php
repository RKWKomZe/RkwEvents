<?php

use RKW\RkwEvents\Utility\TCA;

return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event',
        'label' => 'title',
        'label_userFunc' => TCA::class . '->eventTitle',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        //'sortby' => 'sorting',
        'default_sortby' => 'ORDER BY start DESC',
        'hideAtCopy' => true,
        'prependAtCopy' => true,
        //'requestUpdate' => 'reg_required, online_event, ext_reg_link',
        'type' => 'record_type',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
		'searchFields' => 'title,subtitle,keywords,start,end,show_time,longitude,latitude,testimonials,description,description2,schedule,target_group,target_learning,partner,seats,costs_unknown,costs_reg,costs_red,reg_required,reg_single,reg_end,cancel_end,ext_reg_link,ext_cancel_info,ext_cancel_link,document_type,department,series,logos,currency,place_unknown,place,online_event,online_event_access_link,register_add_information,external_contact,be_user,add_info,presentations, sheet,gallery1,gallery2,reservation,workshop1,workshop2,workshop3,code,trainer,eligibility, eligibility_link, additional_tile_flag, categories, categories_displayed, recommended_events, recommended_links, header_image, backend_user_exclusive',
        'iconfile' => 'EXT:rkw_events/Resources/Public/Icons/tx_rkwevents_domain_model_event.gif'
    ],
    'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, subtitle, keywords, start, end, show_time, description, target_group, target_learning, partner, add_info, schedule, testimonials, seats, costs_unknown, costs_reg, costs_red, costs_red_condition, costs_red_link, costs_tax, reg_required, reg_single, reg_end, cancel_end, ext_reg_link, ext_cancel_info, ext_cancel_link, document_type, department, series, currency, place_unknown, place, online_event,online_event_access_link, register_add_information, longitude, latitude, organizer, external_contact, be_user, presentations, sheet, gallery1, gallery2, reservation, workshop1, workshop2, workshop3, code, trainer, eligibility, eligibility_link, additional_tile_flag, recommended_events, recommended_links, header_image, backend_user_exclusive',
    ],
    'types' => [
        '\RKW\RkwEvents\Domain\Model\EventScheduled' => [
            'showitem' => '
                record_type,--palette--;;1, --palette--;;startEnd, title, subtitle, document_type, series, department, organizer, categories, categories_displayed,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_additionaldata,
                code, trainer, keywords, description, description2, target_group, target_learning, partner, add_info, schedule, testimonials,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_register,
                seats, --palette--;;registrationRequired, reg_end, costs_unknown, costs_tax, costs_reg, --palette--;;costsReduced, costs_red_link, currency, eligibility, eligibility_link, ext_reg_link,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_cancel,
                cancel_end, --palette--;;externalCancel,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_place_contact,
                online_event, online_event_access_link, place_unknown, place, be_user, external_contact, longitude, latitude,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_gallery,
                presentations, sheet,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_reservation,
                reservation,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_other,
                header_image, additional_tile_flag, register_add_information, recommended_events, recommended_links, backend_user_exclusive,
		
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                --palette--;;dataFields, --palette--;;systemFields,
            ',
        ],
        '\RKW\RkwEvents\Domain\Model\EventAnnouncement' => [
            'showitem' => '
                record_type,--palette--;;1, title, subtitle, document_type, series, department, organizer, categories, categories_displayed,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_additionaldata,
                code, trainer, description, description2, target_group, target_learning, partner, add_info, schedule, testimonials,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_register,
                seats, --palette--;;registrationRequired, reg_end, costs_unknown, costs_tax, costs_reg, --palette--;;costsReduced, costs_red_link, currency, eligibility, eligibility_link, ext_reg_link,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_cancel,
                cancel_end, --palette--;;externalCancel,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_place_contact,
                online_event, online_event_access_link, place_unknown, place, be_user, external_contact, longitude, latitude,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_gallery,
                presentations, sheet,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_reservation,
                reservation,

                 --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_other,
                header_image, additional_tile_flag, register_add_information, recommended_events, recommended_links, backend_user_exclusive,

                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                --palette--;;dataFields, --palette--;;systemFields,
            ',

            'columnsOverrides' => [
                // announcement special values
                'place' => [
                    'config' => [
                        'minitems' => 0
                    ]
                ],
            ]
        ],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
        'dataFields' => [
            'showitem' => 'hidden, sys_language_uid, l10n_parent, l10n_diffsource',
        ],
        'startEnd' => [
            'showitem' => 'start, end, show_time',
        ],
        'registrationRequired' => [
            'showitem' => 'reg_required, reg_single',
        ],
        'costsReduced' => [
            'showitem' => 'costs_red, costs_red_condition',
        ],
        'externalCancel' => [
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.externalCancel',
            'showitem' => 'ext_cancel_info, ext_cancel_link',
        ],
        'systemFields' => [
            'showitem' => 'starttime, endtime',
            'isHiddenPalette' => true,
        ],
    ],
    'columns' => [

        'record_type' => [
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.recordType',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.recordType.default', '\RKW\RkwEvents\Domain\Model\EventScheduled'],
                    ['LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.recordType.announcement', '\RKW\RkwEvents\Domain\Model\EventAnnouncement'],
                ],
                'default' => '\RKW\RkwEvents\Domain\Model\EventScheduled'
            ],
        ],

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
                'foreign_table' => 'tx_rkwevents_domain_model_event',
                'foreign_table_where' => 'AND tx_rkwevents_domain_model_event.pid=###CURRENT_PID### AND tx_rkwevents_domain_model_event.sys_language_uid IN (-1,0)',
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
        'starttime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim, required'
            ],
            'onChange' => 'reload'
        ],
        'subtitle' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.subtitle',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'keywords' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.keywords',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3
            ]
        ],
        'start' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.start',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime, required',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],
        'end' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.end',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime, required, RKW\\RkwEvents\\Evaluation\\EventEndDate',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],
        'show_time' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.show_time',
            'config' => [
                'type' => 'check',
                'default' => 1
            ],
        ],
        'longitude' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.longitude',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'readOnly' =>1,

            ],
        ],
        'latitude' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.latitude',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'readOnly' =>1,
            ],
        ],
        // only needed for SQL-query with distance calculation!
        'distance' => [
            'type' => 'passthrough'
        ],

        'testimonials' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.testimonials',
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
        'description' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.description',
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
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.description2',
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
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.target_learning',
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
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.target_group',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim',
            ],
        ],
        'schedule' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.schedule',
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
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.partner',
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
        'seats' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.seats',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int, required'
            ],
        ],
        'costs_unknown' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_unknown',
            'config' => [
                'type' => 'check',
                'default' => 1
            ],
            'onChange' => 'reload',
            'displayCond' => 'FIELD:record_type:=:\RKW\RkwEvents\Domain\Model\EventAnnouncement',
        ],
        'costs_reg' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_reg',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim, double2, required'
            ],
            // show always if record_type is "EventScheduled"
            // but hide if record_type is "EventAnnouncement" and "costs_unknown" is true
            'displayCond' => [
                'OR' => [
                    'FIELD:costs_unknown:REQ:false',
                    'FIELD:record_type:=:\RKW\RkwEvents\Domain\Model\EventScheduled',
                ],
            ]

        ],
        'costs_red' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_red',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim, double2'
            ],
            // show always if record_type is "EventScheduled"
            // but hide if record_type is "EventAnnouncement" and "costs_unknown" is true
            'displayCond' => [
                'OR' => [
                    'FIELD:costs_unknown:REQ:false',
                    'FIELD:record_type:=:\RKW\RkwEvents\Domain\Model\EventScheduled',
                ],
            ]
        ],
        'costs_red_condition' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_red_condition',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
            // show always if record_type is "EventScheduled"
            // but hide if record_type is "EventAnnouncement" and "costs_unknown" is true
            'displayCond' => [
                'OR' => [
                    'FIELD:costs_unknown:REQ:false',
                    'FIELD:record_type:=:\RKW\RkwEvents\Domain\Model\EventScheduled',
                ],
            ]
        ],
        'costs_red_link' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_red_link',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 30,
                'eval' => 'trim',
                'softref' => 'typolink'
            ],
        ],
        'costs_tax' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_tax',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_tax.I.0', 0],
                    ['LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_tax.I.1', 1],
                    ['LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_tax.I.2', 2],
                ],
            ],
            // show always if record_type is "EventScheduled"
            // but hide if record_type is "EventAnnouncement" and "costs_unknown" is true
            'displayCond' => [
                'OR' => [
                    'FIELD:costs_unknown:REQ:false',
                    'FIELD:record_type:=:\RKW\RkwEvents\Domain\Model\EventScheduled',
                ],
            ]
        ],
        'reg_required' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.reg_required',
            'config' => [
                'type' => 'check',
                'default' => 1
            ],
            'onChange' => 'reload'
        ],
        'reg_single' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.reg_single',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'reg_end' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.reg_end',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
            ],
            'displayCond' => 'FIELD:reg_required:REQ:true',
        ],
        'cancel_end' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.cancel_end',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
            ],
            'onChange' => 'reload'
        ],
        'ext_reg_link' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.ext_reg_link',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 30,
                'eval' => 'trim',
                'softref' => 'typolink'
            ],
            'onChange' => 'reload'
        ],
        'ext_cancel_info' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.ext_cancel_info',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim',
            ],
            'displayCond' => 'FIELD:cancel_end:!=:0'
        ],
        'ext_cancel_link' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.ext_cancel_link',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 30,
            ],
            'displayCond' => 'FIELD:cancel_end:!=:0'
        ],
        'document_type' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.document_type',
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
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.department',
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
        /*
        'categories' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.categories',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'sys_category',
                'foreign_table_where' => ' AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(sys_category.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND sys_category.sys_language_uid IN (-1, 0) ORDER BY sys_category.title ASC',
                'MM' => 'sys_category_record_mm',
                'MM_opposite_field' => 'items',
                'MM_match_fields' => [
                    'tablenames' => 'tx_rkwevents_domain_model_event',
                    'fieldname' => 'categories',
                ],
                'size' => 10,
                'autoSizeMax' => 50,
                'maxitems' => 9999,
                'minitems'      => 0,
            ],
        ],
        */
        'categories_displayed' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.categories_displayed',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'itemsProcFunc' => 'RKW\\RkwEvents\\UserFunctions\\TcaProcFunc->getSelectedCategories',
                'maxitems'      => 1,
                'size'          => 5,

            ],
        ],
        'series' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.series',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwevents_domain_model_eventseries',
                'foreign_table_where' => 'AND tx_rkwevents_domain_model_eventseries.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwevents_domain_model_eventseries.name ASC',
                'maxitems'      => 1,
                'size'          => 5,

            ],
        ],

        'logos' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.logos',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                ['maxitems' => 9999],
                'jpg, png, gif'
            ),
        ],

        'currency' => [
            'displayCond' => 'FIELD:costs_unknown:REQ:false',
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.currency',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'static_currencies',
                'default' => 49,
                'minitems' => 0,
                'maxitems' => 1,
                'appearance' => [
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],
        ],
        'place_unknown' => [
            'displayCond' => 'FIELD:online_event:REQ:false',
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.place_unknown',
            'config' => [
                'type' => 'check',
                'default' => 0,
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.place_unknown.I.enabled'
                    ],
                ],
            ],
            'onChange' => 'reload'
        ],
        'place' => [
            'displayCond' => [
                'AND' => [
                    'FIELD:online_event:REQ:false',
                    'FIELD:place_unknown:REQ:false'
                ],
            ],
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.place',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwevents_domain_model_eventplace',
                'foreign_table_where' => 'AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwevents_domain_model_eventplace.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwevents_domain_model_eventplace.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwevents_domain_model_eventplace.name ASC, tx_rkwevents_domain_model_eventplace.city ASC',
                'maxitems'      => 1,
                'minitems' 		=> 1,
                'size'          => 5,
            ],
        ],
        'online_event' => [
            'displayCond' => 'FIELD:place_unknown:REQ:false',
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.online_event',
            'config' => [
                'type' => 'check',
                'default' => 0,
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.online_event.I.enabled'
                    ],
                ],
            ],
            'onChange' => 'reload'
        ],
        'online_event_access_link' => [
            'displayCond' => 'FIELD:online_event:REQ:true',
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.online_event_access_link',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 30,
                'eval' => 'trim',
                'softref' => 'typolink'
            ],
        ],
        'register_add_information' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.register_add_information',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim',
            ],
        ],
        'external_contact' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.external_contact',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwevents_domain_model_eventcontact',
                'foreign_table_where' => 'AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwevents_domain_model_eventcontact.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwevents_domain_model_eventcontact.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwevents_domain_model_eventcontact.last_name ASC, tx_rkwevents_domain_model_eventcontact.company ASC, tx_rkwevents_domain_model_eventcontact.email ASC',
                'maxitems'      => 9999,
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
        'organizer' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.organizer',
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

        'be_user' => [
            'displayCond' => [
                'AND' => [
                    'FIELD:ext_reg_link:REQ:false',
                    'FIELD:reg_required:REQ:true'
                ],
            ],
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.be_user',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'be_users',
                'foreign_table_where' => 'AND be_users.deleted = 0 AND be_users.email != "" ORDER BY be_users.username',
                'maxitems'      => 9999,
                'minitems'      => 1,
                'size'          => 5,
            ],
        ],

        'add_info' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.add_info',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'add_info',
                [
                    'maxitems' => 9999,
                ],
                'pdf'
            ),
        ],

        'presentations' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.presentations',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'presentation',
                [
                    'maxitems' => 9999,
                ],
                'pdf,ppt'
            ),
        ],

        'sheet' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.sheet',
            'config' => [
                'type' => 'inline',
                'internal_type' => 'db',
                'foreign_table' => 'tx_rkwevents_domain_model_eventsheet',
                'foreign_field' => 'event',
                'foreign_sortby' => 'sorting',
                'show_thumbs' => true,
                'maxitems'      => 9999,
                'size'          => 5,
                'appearance' => [
                    'elementBrowserType' => 'db'
                ],
            ],

        ],
        'gallery1' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.gallery1',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'maxitems' => 9999,
                    'foreign_match_fields' => [
                        'fieldname' => 'gallery1'
                    ],
                ],
                'jpg, png, gif'
            ),
        ],
        'gallery2' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.gallery2',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'maxitems' => 9999,
                    'foreign_match_fields' => [
                        'fieldname' => 'gallery2'
                    ],
                ],
                'jpg, png, gif'
            ),
        ],
        'reservation' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.reservations',
            'config' => [
            	'type' => 'inline',
                'foreign_table' => 'tx_rkwevents_domain_model_eventreservation',
                'foreign_field' => 'event',
                'maxitems'      => 9999,
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

		'workshop1' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.workshop1',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_rkwevents_domain_model_eventworkshop',
				'foreign_table_where' => 'AND tx_rkwevents_domain_model_eventworkshop.deleted = 0',
				'maxitems'      => 9999,
				'minitems'      => 0,
				'size'          => 5,
			],
		],
		'workshop2' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.workshop2',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_rkwevents_domain_model_eventworkshop',
				'foreign_table_where' => 'AND tx_rkwevents_domain_model_eventworkshop.deleted = 0',
				'maxitems'      => 9999,
				'minitems'      => 0,
				'size'          => 5,
			],
		],
		'workshop3' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.workshop3',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_rkwevents_domain_model_eventworkshop',
				'foreign_table_where' => 'AND tx_rkwevents_domain_model_eventworkshop.deleted = 0',
				'maxitems'      => 9999,
				'minitems'      => 0,
				'size'          => 5,
			],
		],
        'reminder_mail_tstamp' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.reminder_mail_tstamp',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'readOnly' => 1,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'code' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.code',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'trainer' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.trainer',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'eligibility' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.eligibility',
            'config' => [
                'type' => 'check',
                'default' => 1
            ],
        ],
        'eligibility_link' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.eligibility_link',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 30,
                'eval' => 'trim',
                'softref' => 'typolink'
            ],
        ],
        'additional_tile_flag' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.additional_tile_flag',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim'
            ],
        ],
        'recommended_events' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.recommended_events',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwevents_domain_model_event',
                'foreign_table_where' => 'AND tx_rkwevents_domain_model_event.uid!=###THIS_UID### AND tx_rkwevents_domain_model_event.pid=###CURRENT_PID### AND tx_rkwevents_domain_model_event.sys_language_uid = ###REC_FIELD_sys_language_uid### AND ( (tx_rkwevents_domain_model_event.start = 0 OR tx_rkwevents_domain_model_event.start > unix_timestamp(now())) OR (tx_rkwevents_domain_model_event.end > unix_timestamp(now())) ) ORDER BY tx_rkwevents_domain_model_event.start DESC',
                'maxitems'      => 99,
                'size'          => 5,
            ],
        ],
        'recommended_links' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.recommended_links',
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
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.header_image',
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
        'backend_user_exclusive' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.backend_user_exclusive',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ]
        ],
    ],
];
