<?php
if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_rkwevents_domain_model_event', 'EXT:rkw_events/Resources/Private/Language/locallang_csh_tx_rkwevents_domain_model_event.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rkwevents_domain_model_event');
$GLOBALS['TCA']['tx_rkwevents_domain_model_event'] = array(
    'ctrl' => array(
        'title'	=> 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        //'sortby' => 'sorting',
        'default_sortby' => 'ORDER BY start DESC',
        'hideAtCopy' => true,
        'prependAtCopy' => true,
        'requestUpdate' => 'reg_required, online_event, ext_reg_link',

        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
		'searchFields' => 'title,subtitle,start,end,longitude,latitude,testimonials,description,schedule,target_group,partner,seats,costs_reg,costs_red,reg_required,reg_end,ext_reg_link,document_type,department,categories,series,logos,currency,place,online_event, online_event_access_link,external_contact,be_user,add_info,presentations, sheet,gallery1,gallery2,reservation,workshop1,workshop2,workshop3,',
        'iconfile' => 'EXT:rkw_events/Resources/Public/Icons/tx_rkwevents_domain_model_event.gif'
    ),
    'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, subtitle, start, end, description, target_group, partner, add_info, schedule, testimonials, seats, costs_reg, costs_red, costs_red_condition, costs_tax, reg_required, reg_end, ext_reg_link, document_type, department, series, currency, place, online_event,online_event_access_link, longitude, latitude, organizer, external_contact, be_user, presentations, sheet, gallery1, gallery2, reservation, workshop1, workshop2, workshop3',
    ),
    'types' => array(
        '1' => array(           
            'showitem' => '
                sys_language_uid, l10n_parent, l10n_diffsource, hidden,--palette--;;1, title, subtitle,  start, end, reg_end, document_type, series, department, organizer,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_additionaldata,
                description, target_group, partner, add_info, schedule, testimonials,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_register,
                ' .
                // seats, costs_reg, costs_red, costs_red_condition, currency, costs_tax, reg_required, online_event, online_event_access_link, ext_reg_link, survey_before, survey_after, survey_after_mail_tstamp,
                'seats, costs_reg, costs_red, costs_red_condition, currency, costs_tax, reg_required, online_event, online_event_access_link, ext_reg_link,
                
                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_place_contact,
                place, be_user, external_contact, longitude, latitude,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_gallery,
                //gallery1, gallery2, presentations, sheet,
                presentations, sheet,

                --div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_reservation,
                reservation,
				
				' .
                //--div--;LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.tab_workshop,
				// workshop1, workshop2, workshop3,

                '--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,
                starttime, endtime',
                
                
            // add RTE
            'columnsOverrides' => array(
                'description' => array (
                    'defaultExtras' => 'richtext:rte_transform[mode=ts_links]'
                ),
                'schedule' => array (
                    'defaultExtras' => 'richtext:rte_transform[mode=ts_links]'
                ),
                'testimonials' => array (
                    'defaultExtras' => 'richtext:rte_transform[mode=ts_links]'
                ),
            
            )           
            
        ),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
    'columns' => array(

        'sys_language_uid' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => array(
                    array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
                    array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
                ),
                'default' => 0
            ),
        ),
        'l10n_parent' => array(
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array(
                    array('', 0),
                ),
                'foreign_table' => 'tx_rkwevents_domain_model_event',
                'foreign_table_where' => 'AND tx_rkwevents_domain_model_event.pid=###CURRENT_PID### AND tx_rkwevents_domain_model_event.sys_language_uid IN (-1,0)',
            ),
        ),
        'l10n_diffsource' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),

        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'starttime' => array(
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        'endtime' => array(
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),

        'title' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.title',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim, required'
            ),
        ),
        'subtitle' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.subtitle',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'start' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.start',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime, required',
                'checkbox' => 0,
                'default' => 0,
            ),
        ),
        'end' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.end',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime, required',
                'checkbox' => 0,
                'default' => 0,
            ),
        ),
        'longitude' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.longitude',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'readOnly' =>1,

            ),
        ),
        'latitude' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.latitude',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'readOnly' =>1,
            ),
        ),
        // only needed for SQL-query with distance calculation!
        'distance' => array(
            'type' => 'passthrough'
        ),

        'testimonials' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.testimonials',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'wizards' => array(
                    'RTE' => array(
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif',
                        'notNewRecords'=> 1,
                        'RTEonly' => 1,
                        'module' => array(
                            'name' => 'wizard_rte',
                        ),
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                        'type' => 'script'
                    )
                )
            ),
        ),
        'description' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.description',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim, required',
                'wizards' => array(
                    'RTE' => array(
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif',
                        'notNewRecords'=> 1,
                        'RTEonly' => 1,
                        'module' => array(
                            'name' => 'wizard_rte',
                        ),
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                        'type' => 'script'
                    )
                )
            ),
        ),
        'target_group' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.target_group',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim',
            ),
        ),
        'schedule' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.schedule',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'wizards' => array(
                    'RTE' => array(
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif',
                        'notNewRecords'=> 1,
                        'RTEonly' => 1,
                        'module' => array(
                                'name' => 'wizard_rte',
                        ),
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                        'type' => 'script'
                    )
                )
            ),
        ),
        'partner' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.partner',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'wizards' => array(
                    'RTE' => array(
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif',
                        'notNewRecords'=> 1,
                        'RTEonly' => 1,
                        'module' => array(
                            'name' => 'wizard_rte',
                        ),
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.RTE',
                        'type' => 'script'
                    )
                )
            ),
        ),
        'seats' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.seats',
            'config' => array(
                'type' => 'input',
                'size' => 4,
                'eval' => 'int, required'
            )
        ),
        'costs_reg' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_reg',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim, double2, required'
            ),
        ),
        'costs_red' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_red',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim, double2'
            ),
        ),

        'costs_red_condition' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_red_condition',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'costs_tax' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_tax',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => array(
                    array('LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_tax.I.0', 0),
                    array('LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_tax.I.1', 1),
                    array('LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.costs_tax.I.2', 2),
                )
            )
        ),
        'reg_required' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.reg_required',
            'config' => array(
                'type' => 'check',
                'default' => 1
            )
        ),
        'reg_end' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.reg_end',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
            ),
        ),
        'ext_reg_link' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.ext_reg_link',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'wizards' => array(
                    '_PADDING' => 2,
                    'link' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:header_link_formlabel',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                        'module' => array(
                            'name' => 'wizard_link',
                        ),
                        'JSopenParams' => 'height=400,width=550,status=0,menubar=0,scrollbars=1',
                        'params' => Array(
                            // List of tabs to hide in link window. Allowed values are:
                            // file, mail, page, spec, folder, url
                            'blindLinkOptions' => 'mail,file,page,spec,folder',

                            // allowed extensions for file
                            //'allowedExtensions' => 'mp3,ogg',
                        )

                    )
                ),
                'softref' => 'typolink'
            ),
        ),
        'document_type' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.document_type',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwbasics_domain_model_documenttype',
                'foreign_table_where' => 'AND tx_rkwbasics_domain_model_documenttype.type = "events" AND tx_rkwbasics_domain_model_documenttype.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwbasics_domain_model_documenttype.name ASC',
                'maxitems'      => 1,
                'minitems'      => 1,
                'size'          => 5,
            ),
        ),
        'department' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.department',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwbasics_domain_model_department',
                'foreign_table_where' => 'AND tx_rkwbasics_domain_model_department.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwbasics_domain_model_department.name ASC',
                'maxitems'      => 1,
                'minitems'      => 1,
                'size'          => 5,
            ),
        ),
        'categories' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.categories',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'sys_category',
                'foreign_table_where' => ' AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(sys_category.parent,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND sys_category.sys_language_uid IN (-1, 0) ORDER BY sys_category.title ASC',
                'MM' => 'sys_category_record_mm',
                'MM_opposite_field' => 'items',
                'MM_match_fields' => array(
                    'tablenames' => 'tx_rkwevents_domain_model_event',
                    'fieldname' => 'categories',
                ),
                'size' => 10,
                'autoSizeMax' => 50,
                'maxitems' => 9999,
                'minitems'      => 0,
                /*'renderMode' => 'tree',
                'treeConfig' => array(
                    'parentField' => 'parent',
                    'appearance' => array(
                        'expandAll' => TRUE,
                        'showHeader' => TRUE,
                    ),
                ),
                */
            ),
        ),
        'series' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.series',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwevents_domain_model_eventseries',
                'foreign_table_where' => 'AND tx_rkwevents_domain_model_eventseries.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwevents_domain_model_eventseries.name ASC',
                'maxitems'      => 1,
                'size'          => 5,

            ),
        ),

        'logos' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.logos',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                array('maxitems' => 9999),
                'jpg, png, gif'
            ),
        ),

        'currency' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.currency',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'static_currencies',
                'default' => 49,
                'minitems' => 0,
                'maxitems' => 1,
                'appearance' => array(
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ),
            ),
        ),
        'place' => array(
            'displayCond' =>  'FIELD:online_event:REQ:false',
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.place',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwevents_domain_model_eventplace',
                'foreign_table_where' => 'AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwevents_domain_model_eventplace.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwevents_domain_model_eventplace.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwevents_domain_model_eventplace.name ASC, tx_rkwevents_domain_model_eventplace.city ASC',
                'maxitems'      => 1,
                'minitems' 		=>1,
                'size'          => 5,
                /*
                    'wizards' => array(
                        '_VERTICAL' => 1,
                        'edit' => array(
                            'type' => 'popup',
                            'title' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventplace.edit',
                            'icon' => 'edit2.gif',
                            'module' => array(
                                'name' => 'wizard_edit',
                            ),
                            'popup_onlyOpenIfSelected' => 1,
                            'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1'
                        ),

                        'add' => array(
                            'type' => 'script',
                            'title' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventplace.add',
                            'icon' => 'add.gif',
                            'params' => array(
                                'table' => 'tx_rkwevents_domain_model_eventplace',
                                'pid' => '###CURRENT_PID###',
                                'setValue' => 'prepend'
                            ),
                            'module' => array(
                                'name' => 'wizard_add'
                            ),
                            'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1'
                        ),
                    ),
                */
            ),
        ),
        'online_event' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.online_event',
            'config' => array(
                'type' => 'check',
                'default' => 0,
                'items' => array(
                    '1' => array(
                        '0' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.online_event.I.enabled'
                    )
                )
            )
        ),
        'online_event_access_link' => array(
            'displayCond' =>  'FIELD:online_event:REQ:true',
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.online_event_access_link',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'wizards' => array(
                    '_PADDING' => 2,
                    'link' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:header_link_formlabel',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                        'module' => array(
                            'name' => 'wizard_link',
                        ),
                        'JSopenParams' => 'height=400,width=550,status=0,menubar=0,scrollbars=1',
                        'params' => Array(
                            // List of tabs to hide in link window. Allowed values are:
                            // file, mail, page, spec, folder, url
                            'blindLinkOptions' => 'mail,file,page,spec,folder',

                            // allowed extensions for file
                            //'allowedExtensions' => 'mp3,ogg',
                        )

                    )
                ),
                'softref' => 'typolink'
            ),
        ),
        'external_contact' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.external_contact',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwevents_domain_model_eventcontact',
                'foreign_table_where' => 'AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwevents_domain_model_eventcontact.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwevents_domain_model_eventcontact.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwevents_domain_model_eventcontact.last_name ASC, tx_rkwevents_domain_model_eventcontact.company ASC, tx_rkwevents_domain_model_eventcontact.email ASC',
                'maxitems'      => 9999,
                'size'          => 5,
                'wizards' => array(
                    '_VERTICAL' => 1,
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventcontact.edit',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_edit.gif',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1'
                    ),

                    'add' => array(
                        'type' => 'script',
                        'title' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventcontact.add',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
                        'params' => array(
                            'table' => 'tx_rkwevents_domain_model_eventcontact',
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'prepend'
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1'
                    ),
                ),
            ),
        ),

		/*
		// Re-added below, if rkw_authors is active
        'internal_contact' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.internal_contact',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwauthors_domain_model_authors',
                'foreign_table_where' => 'AND tx_rkwauthors_domain_model_authors.internal = 1 AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwauthors_domain_model_authors.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwauthors_domain_model_authors.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwauthors_domain_model_authors.last_name ASC',
                'maxitems'      => 9999,
                'minitems'      => 0,
                'size'          => 5,
            ),
        ),
		*/

        'organizer' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.organizer',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwevents_domain_model_eventorganizer',
                'foreign_table_where' => 'AND tx_rkwevents_domain_model_eventorganizer.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwevents_domain_model_eventorganizer.last_name ASC, tx_rkwevents_domain_model_eventorganizer.company ASC, tx_rkwevents_domain_model_eventorganizer.email ASC',
                'maxitems'      => 9999,
                'minitems' 		=> 1,
                'size'          => 5,
                'wizards' => array(
                    '_VERTICAL' => 1,
                    'edit' => array(
                        'type' => 'popup',
                        'title' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.edit',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_edit.gif',
                        'module' => array(
                            'name' => 'wizard_edit',
                        ),
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1'
                ),

                'add' => array(
                        'type' => 'script',
                        'title' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.add',
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
                        'params' => array(
                            'table' => 'tx_rkwevents_domain_model_eventorganizer',
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'prepend'
                        ),
                        'module' => array(
                            'name' => 'wizard_add'
                        ),
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1'
                    ),
                ),
            ),
        ),

        'be_user' => array(
            'displayCond' => array(
                'AND' => array (
                    'FIELD:ext_reg_link:REQ:false',
                    'FIELD:reg_required:REQ:true'
                )
            ),
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.be_user',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'be_users',
                'foreign_table_where' => 'AND be_users.deleted = 0 AND be_users.email != "" ORDER BY be_users.username',
                'maxitems'      => 9999,
                'minitems'      => 1,
                'size'          => 5,
            ),
        ),

        'add_info' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.add_info',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'add_info',
                array(
                    'maxitems' => 9999,
                ),
                'pdf'
            ),
        ),

        'presentations' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.presentations',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'presentation',
                array(
                    'maxitems' => 9999,
                ),
                'pdf,ppt'
            ),
        ),

        'sheet' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.sheet',
            'config' => array(
                'type' => 'inline',
                'internal_type' => 'db',
                'foreign_table' => 'tx_rkwevents_domain_model_eventsheet',
                'foreign_field' => 'event',
                'foreign_sortby' => 'sorting',
                'show_thumbs' => TRUE,
                'maxitems'      => 9999,
                'size'          => 5,
                'appearance' => array(
                    'elementBrowserType' => 'db'
                ),
            ),

        ),
        'gallery1' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.gallery1',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                array(
                    'maxitems' => 9999,
                    'foreign_match_fields' => array(
                        'fieldname' => 'gallery1'
                    ),
                ),
                'jpg, png, gif'

            ),
        ),
        'gallery2' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.gallery2',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                array(
                    'maxitems' => 9999,
                    'foreign_match_fields' => array(
                        'fieldname' => 'gallery2'
                    ),
                ),
                'jpg, png, gif'
            ),
        ),
        'reservation' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.reservations',
            'config' => array(
            	'type' => 'inline',
                'foreign_table' => 'tx_rkwevents_domain_model_eventreservation',
                'foreign_field' => 'event',
                'maxitems'      => 9999,
                'size'          => 5,
                'appearance' => array(
                    'elementBrowserType' => 'db',
                    'collapseAll' => 1,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                    'enabledControls' => array(
                        'info' => true,
                        'new' => false,
                        'dragdrop' => false,
                        'sort' => false,
                        'hide' => false,
                        'delete' => true,
                        'localize' => false
                    ),
                ),
            ),
        ),

		'workshop1' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.workshop1',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_rkwevents_domain_model_eventworkshop',
				'foreign_table_where' => 'AND tx_rkwevents_domain_model_eventworkshop.deleted = 0',
				'maxitems'      => 9999,
				'minitems'      => 0,
				'size'          => 5,
			),
		),
		'workshop2' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.workshop2',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_rkwevents_domain_model_eventworkshop',
				'foreign_table_where' => 'AND tx_rkwevents_domain_model_eventworkshop.deleted = 0',
				'maxitems'      => 9999,
				'minitems'      => 0,
				'size'          => 5,
			),
		),
		'workshop3' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.workshop3',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_rkwevents_domain_model_eventworkshop',
				'foreign_table_where' => 'AND tx_rkwevents_domain_model_eventworkshop.deleted = 0',
				'maxitems'      => 9999,
				'minitems'      => 0,
				'size'          => 5,
			),
		),
        /* Re-added below, if rkw_survey is active
        'survey_before' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.survey_before',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwsurvey_domain_model_survey',
                'foreign_table_where' => 'AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwsurvey_domain_model_survey.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwsurvey_domain_model_survey.deleted = 0 AND tx_rkwsurvey_domain_model_survey.hidden = 0',
                'maxitems'      => 1,
                'minitems' 		=> 0,
                'size'          => 5,
            ),
        ),
        'survey_after' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.survey_after',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkwsurvey_domain_model_survey',
                'foreign_table_where' => 'AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwsurvey_domain_model_survey.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwsurvey_domain_model_survey.deleted = 0 AND tx_rkwsurvey_domain_model_survey.hidden = 0',
                'maxitems'      => 1,
                'minitems' 		=> 0,
                'size'          => 5,
            ),
        ),
        'survey_after_mail_tstamp' => array(
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.survey_after_mail_tstamp',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'readOnly' => 1,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        */
        'reminder_mail_tstamp' => array(
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.reminder_mail_tstamp',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'readOnly' => 1,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
    ),
);

// Extend TCA when rkw_authors is available
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('rkw_authors')) {

	$GLOBALS['TCA']['tx_rkwevents_domain_model_event']['columns']['internal_contact'] = array(
		'exclude' => 0,
		'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.internal_contact',
		'config' => array(
			'type' => 'select',
			'renderType' => 'selectMultipleSideBySide',
			'foreign_table' => 'tx_rkwauthors_domain_model_authors',
			'foreign_table_where' => 'AND tx_rkwauthors_domain_model_authors.internal = 1 AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwauthors_domain_model_authors.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwauthors_domain_model_authors.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwauthors_domain_model_authors.last_name ASC',
			'maxitems'      => 9999,
			'minitems'      => 0,
			'size'          => 5,
		),
	);
	$GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['1']['showitem'] = str_replace(', external_contact,', ', internal_contact, external_contact,', $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['1']['showitem']);
}

// Extend TCA when rkw_projects is available
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('rkw_projects')) {
    $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['columns']['project'] = array(
        'exclude' => 0,
        'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.project',
        'config' => array(
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'tx_rkwprojects_domain_model_projects',
            'foreign_table_where' => 'AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwprojects_domain_model_projects.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwprojects_domain_model_projects.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwprojects_domain_model_projects.short_name ASC',
            'maxitems'      => 1,
            'minitems'      => 0,
            'size'          => 5,
        ),
    );
    $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['1']['showitem'] = str_replace(', department,', ', department, project,', $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['1']['showitem']);
}

// Extend TCA when rkw_survey is available
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('rkw_survey')) {

    $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['columns']['survey_before'] = array(
        'exclude' => 0,
        'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.survey_before',
        'config' => array(
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'tx_rkwsurvey_domain_model_survey',
            'foreign_table_where' => 'AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwsurvey_domain_model_survey.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwsurvey_domain_model_survey.deleted = 0 AND tx_rkwsurvey_domain_model_survey.hidden = 0',
            'maxitems'      => 1,
            'minitems' 		=> 0,
            'size'          => 5,
        ),
    );

    $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['columns']['survey_after'] = array(
        'exclude' => 0,
        'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.survey_after',
        'config' => array(
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'tx_rkwsurvey_domain_model_survey',
            'foreign_table_where' => 'AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwsurvey_domain_model_survey.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwsurvey_domain_model_survey.deleted = 0 AND tx_rkwsurvey_domain_model_survey.hidden = 0',
            'maxitems'      => 1,
            'minitems' 		=> 0,
            'size'          => 5,
        ),
    );

    $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['columns']['survey_after_mail_tstamp'] = array(
        'exclude' => 1,
        'l10n_mode' => 'mergeIfNotBlank',
        'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.survey_after_mail_tstamp',
        'config' => array(
            'type' => 'input',
            'size' => 13,
            'max' => 20,
            'eval' => 'datetime',
            'checkbox' => 0,
            'default' => 0,
            'readOnly' => 1,
            'range' => array(
                'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
            ),
        ),
    );

    // @toDo: Not sure if this lines should be shown (was also commented out before in the top of the file)
    //$GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['1']['showitem'] = str_replace(', ext_reg_link,', ', ext_reg_link,survey_before,survey_after,survey_after_mail_tstamp,', $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['1']['showitem']);
}
