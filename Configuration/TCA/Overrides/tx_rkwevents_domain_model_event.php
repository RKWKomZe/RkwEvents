<?php


// Extend TCA when rkw_authors is available
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('rkw_authors')) {

	$GLOBALS['TCA']['tx_rkwevents_domain_model_event']['columns']['internal_contact'] = [
		'exclude' => 0,
		'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.internal_contact',
		'config' => [
			'type' => 'select',
			'renderType' => 'selectMultipleSideBySide',
			'foreign_table' => 'tx_rkwauthors_domain_model_authors',
			'foreign_table_where' => 'AND tx_rkwauthors_domain_model_authors.internal = 1 AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwauthors_domain_model_authors.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwauthors_domain_model_authors.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwauthors_domain_model_authors.last_name ASC',
			'maxitems'      => 9999,
			'minitems'      => 0,
			'size'          => 5,
		],
	];
	$GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['\RKW\RkwEvents\Domain\Model\EventScheduled']['showitem'] = str_replace(', external_contact,', ', internal_contact, external_contact,', $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['\RKW\RkwEvents\Domain\Model\EventScheduled']['showitem']);
    $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['\RKW\RkwEvents\Domain\Model\EventAnnouncement']['showitem'] = str_replace(', external_contact,', ', internal_contact, external_contact,', $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['\RKW\RkwEvents\Domain\Model\EventAnnouncement']['showitem']);
}

// Extend TCA when rkw_survey is available
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('rkw_survey')) {

    $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['columns']['survey_before'] = [
        'exclude' => 0,
        'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.survey_before',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'tx_rkwsurvey_domain_model_survey',
            'foreign_table_where' => 'AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwsurvey_domain_model_survey.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwsurvey_domain_model_survey.deleted = 0 AND tx_rkwsurvey_domain_model_survey.hidden = 0',
            'maxitems'      => 1,
            'minitems' 		=> 0,
            'size'          => 5,
        ],
    ];

    $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['columns']['survey_after'] = [
        'exclude' => 0,
        'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.survey_after',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'tx_rkwsurvey_domain_model_survey',
            'foreign_table_where' => 'AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwsurvey_domain_model_survey.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwsurvey_domain_model_survey.deleted = 0 AND tx_rkwsurvey_domain_model_survey.hidden = 0',
            'maxitems'      => 1,
            'minitems' 		=> 0,
            'size'          => 5,
        ],
    ];

    $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['columns']['survey_after_mail_tstamp'] = [
        'exclude' => 1,
        'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_event.survey_after_mail_tstamp',
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
    ];

    // @todo Not sure if this lines should be shown (was also commented out before in the top of the file],
    //$GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['\RKW\RkwEvents\Domain\Model\EventScheduled']['showitem'] = str_replace(', ext_reg_link,', ', ext_reg_link,survey_before,survey_after,survey_after_mail_tstamp,', $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['\RKW\RkwEvents\Domain\Model\EventScheduled']['showitem']);
}



//=================================================================
// Add Category
//=================================================================
// Add an extra categories selection field to the pages table
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
    'examples',
    'tx_rkwevents_domain_model_event',
    // Do not use the default field name ("categories") for pages, tt_content, sys_file_metadata, which is already used
    'categories',
    array(
        // Set a custom label
        'label' => 'LLL:EXT:examples/Resources/Private/Language/locallang.xlf:additional_categories',
        // This field should not be an exclude-field
        'exclude' => FALSE,
        // Override generic configuration, e.g. sort by title rather than by sorting
        'fieldConfiguration' => array(
            'foreign_table_where' => ' AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(sys_category.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND sys_category.sys_language_uid IN (-1, 0) ORDER BY sys_category.title ASC',
        ),
        // string (keyword), see TCA reference for details
        'l10n_mode' => 'exclude',
        // list of keywords, see TCA reference for details
        'l10n_display' => 'hideDiff',
    )
);

