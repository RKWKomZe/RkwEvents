<?php

return [
    'ctrl' => [
        'hideTable' => 1,
        'title'	=> 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event',
        'label' => 'start',
        'label_userFunc' => RKW\RkwEvents\UserFunctions\TcaLabel::class . '->eventTitle',
        // "label_alt" with "title" is also possible instead of using "label_userFunc" (but shows only the "slug")
        //'label_alt' => 'title, place',
        //'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        //'sortby' => 'sorting',
        'default_sortby' => 'ORDER BY start ASC',
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
		'searchFields' => 'title,start,end,show_time,longitude,latitude,seats,costs_unknown,costs_reg,costs_red,reg_required,reg_single,reg_end,cancel_end,ext_show_link,ext_reg_link,ext_cancel_info,ext_cancel_link,series,logos,currency,place_unknown,place,online_event,online_event_access_link,register_add_information,additional_tile_flag,external_contact,be_user,presentations,sheet,gallery1,gallery2,reservation,code,trainer,eligibility,eligibility_link,workshop_select_type,workshop_select_req,workshop,extended_network,custom_privacy_consent_show,custom_privacy_consent,custom_field_show,custom_field_mandatory,custom_field_label,custom_field_placeholder,custom_field_type,custom_field_full_width',
        'iconfile' => 'EXT:rkw_events/Resources/Public/Icons/tx_rkwevents_domain_model_event.gif'
    ],
    'types' => [
        '\RKW\RkwEvents\Domain\Model\EventScheduled' => [
            'showitem' => '
                record_type,--palette--;;1, --palette--;;startEnd, series,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_additionaldata,
                code, trainer,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_register,
                seats, --palette--;;registrationRequired, reg_end, costs_unknown, costs_tax, costs_reg, --palette--;;costsReduced, costs_red_link, currency, eligibility, eligibility_link, ext_reg_link, workshop_select_type, workshop_select_req, workshop,--palette--;;customField1,--palette--;;customField2,--palette--;;customField3,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_consent,
                extended_network, custom_privacy_consent_show, custom_privacy_consent,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_cancel,
                cancel_end, --palette--;;externalCancel,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_place_contact,
                online_event, online_event_access_link, register_add_information, place_unknown, place, be_user, external_contact, longitude, latitude,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_gallery,
                presentations, sheet,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_reservation,
                reservation,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_other,
                ext_show_link, additional_tile_flag, title,

                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                --palette--;;dataFields, --palette--;;systemFields,
            ',
        ],
        '\RKW\RkwEvents\Domain\Model\EventAnnouncement' => [
            'showitem' => '
                record_type,--palette--;;1, series,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_additionaldata,
                code, trainer,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_register,
                seats, --palette--;;registrationRequired, reg_end, costs_unknown, costs_tax, costs_reg, --palette--;;costsReduced, costs_red_link, currency, eligibility, eligibility_link, ext_reg_link, workshop_select_type, workshop_select_req, workshop,--palette--;;customField1,--palette--;;customField2,--palette--;;customField3,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_consent,
                extended_network, custom_privacy_consent_show, custom_privacy_consent,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_cancel,
                cancel_end, --palette--;;externalCancel,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_place_contact,
                online_event, online_event_access_link, register_add_information, place_unknown, place, be_user, external_contact, longitude, latitude,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_gallery,
                presentations, sheet,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_reservation,
                reservation,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_other,
                ext_show_link, additional_tile_flag, title,

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
            'showitem' => 'title, starttime, endtime',
            'isHiddenPalette' => true,
        ],
        'customField1' => [
            'showitem' => 'custom_field_show, custom_field_mandatory',
        ],
        'customField2' => [
            'showitem' => 'custom_field_type, custom_field_full_width',
        ],
        'customField3' => [
            'showitem' => 'custom_field_label, custom_field_placeholder',
        ]
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
            ],
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
            'config' => [
                'type' => 'passthrough'
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
            // @toDo: MIGRATION ISSUE
            //'displayCond' => 'FIELD:record_type:=:\RKW\RkwEvents\Domain\Model\EventAnnouncement',
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
                    // @toDo: MIGRATION ISSUE
                    //'FIELD:record_type:=:\RKW\RkwEvents\Domain\Model\EventScheduled',
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
                    // @toDo: MIGRATION ISSUE
                    //'FIELD:record_type:=:\RKW\RkwEvents\Domain\Model\EventScheduled',
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
                    // @toDo: MIGRATION ISSUE
                    //'FIELD:record_type:=:\RKW\RkwEvents\Domain\Model\EventScheduled',
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
                    // @toDo: MIGRATION ISSUE
                    //'FIELD:record_type:=:\RKW\RkwEvents\Domain\Model\EventScheduled',
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
        'ext_show_link' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.ext_show_link',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 30,
                'eval' => 'trim',
                'softref' => 'typolink'
            ],
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
                'foreign_table_where' => 'AND be_users.deleted = 0 AND be_users.disable = 0 AND be_users.email != "" ORDER BY be_users.username',
                'maxitems'      => 9999,
                'minitems'      => 1,
                'size'          => 5,
            ],
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

        'workshop' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.workshop',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_rkwevents_domain_model_eventworkshop',
                'foreign_table_where' => 'AND tx_rkwevents_domain_model_eventworkshop.deleted = 0',
                'maxitems'      => 9999,
                'minitems'      => 0,
                'size'          => 5,
                'appearance' => [
                    'collapseAll' => true
                ]
            ],
        ],
        'workshop_select_type' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.workshop_select_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.workshop_select_type.check', '0'],
                    ['LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.workshop_select_type.radio', '1'],
                ],
            ],
        ],
        'workshop_select_req' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.workshop_select_req',
            'config' => [
                'type' => 'check',
                'default' => 0
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
        'extended_network' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.extended_network',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ]
        ],
        'custom_privacy_consent_show' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.custom_privacy_consent_show',
            'config' => [
                'type' => 'check',
            ],
            'onChange' => 'reload',
        ],
        'custom_privacy_consent' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.custom_privacy_consent',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'enableRichtext' => true,
            ],
            'displayCond' => 'FIELD:custom_privacy_consent_show:REQ:true',
        ],
        'custom_field_show' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.custom_field_show',
            'config' => [
                'type' => 'check',
            ],
            'onChange' => 'reload',
        ],
        'custom_field_mandatory' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.custom_field_mandatory',
            'config' => [
                'type' => 'check',
            ],
            'displayCond' => 'FIELD:custom_field_show:REQ:true',
        ],
        'custom_field_label' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.custom_field_label',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim, required'
            ],
            'displayCond' => 'FIELD:custom_field_show:REQ:true',
        ],
        'custom_field_placeholder' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.custom_field_placeholder',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
            'displayCond' => 'FIELD:custom_field_show:REQ:true',
        ],
        'custom_field_type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.custom_field_type',
            'config' => [
                'type' => 'check',
            ],
            'displayCond' => 'FIELD:custom_field_show:REQ:true',
        ],
        'custom_field_full_width' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.custom_field_full_width',
            'config' => [
                'type' => 'check',
            ],
            'displayCond' => 'FIELD:custom_field_show:REQ:true',
        ],
        'series' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.series',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_rkwevents_domain_model_eventseries',
                'foreign_table_where' => 'AND tx_rkwevents_domain_model_eventseries.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwevents_domain_model_eventseries.title ASC',
                'maxitems'      => 1,
            ],
        ],
    ],
];
