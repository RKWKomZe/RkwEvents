<?php



// Extend TCA when rkw_projects is available
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('rkw_projects')) {
    $GLOBALS['TCA']['tx_rkwevents_domain_model_eventseries']['columns']['project'] = [
        'exclude' => 0,
        'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.project',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'tx_rkwprojects_domain_model_projects',
            'foreign_table_where' => 'AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(tx_rkwprojects_domain_model_projects.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND tx_rkwprojects_domain_model_projects.sys_language_uid = ###REC_FIELD_sys_language_uid### ORDER BY tx_rkwprojects_domain_model_projects.short_name ASC',
            'maxitems'      => 1,
            'minitems'      => 0,
            'size'          => 5,
        ],
    ];
    //$GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['\RKW\RkwEvents\Domain\Model\EventScheduled']['showitem'] = str_replace(', department,', ', department, project,', $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['\RKW\RkwEvents\Domain\Model\EventScheduled']['showitem']);
    //$GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['\RKW\RkwEvents\Domain\Model\EventAnnouncement']['showitem'] = str_replace(', department,', ', department, project,', $GLOBALS['TCA']['tx_rkwevents_domain_model_event']['types']['\RKW\RkwEvents\Domain\Model\EventAnnouncement']['showitem']);
}

//=================================================================
// Add Category
//=================================================================
// Add an extra categories selection field to the pages table
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
    'examples',
    'tx_rkwevents_domain_model_eventseries',
    // Do not use the default field name ("categories") for pages, tt_content, sys_file_metadata, which is already used
    'categories',
    array(
        // Set a custom label
        'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.categories',
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

