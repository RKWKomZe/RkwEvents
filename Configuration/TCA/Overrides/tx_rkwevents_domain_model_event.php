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
