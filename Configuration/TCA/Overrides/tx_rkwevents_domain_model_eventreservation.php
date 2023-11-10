<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
            'RKW.' . $extKey,
            'tx_rkwevents_domain_model_eventreservation',
            // Do not use the default field name ("categories") for pages, tt_content, sys_file_metadata, which is already used
            'target_group',
            array(
                // Set a custom label
                'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventreservation.targetGroups',
                // This field should not be an exclude-field
                'exclude' => FALSE,
                // Override generic configuration, e.g. sort by title rather than by sorting
                'fieldConfiguration' => [
                    'foreign_table_where' => ' AND ((\'###PAGE_TSCONFIG_IDLIST###\' <> \'0\' AND FIND_IN_SET(sys_category.pid,\'###PAGE_TSCONFIG_IDLIST###\')) OR (\'###PAGE_TSCONFIG_IDLIST###\' = \'0\')) AND sys_category.sys_language_uid IN (-1, 0) ORDER BY sys_category.title ASC',
                    'readOnly' => true
                ],
                // string (keyword), see TCA reference for details
                'l10n_mode' => 'exclude',
                // list of keywords, see TCA reference for details
                'l10n_display' => 'hideDiff',
            )
        );

        //  @todo: Does not work as TCEFORM.tx_rkwevents_domain_model_eventreservation.target_group.config.treeConfig.rootUid = 147 in 50-categories.typoscript!?
        //$GLOBALS['TCA']['tx_rkwevents_domain_model_eventreservation']['columns']['target_group']['config']['treeConfig']['rootUid'] = 147;

    },
    'rkw_events'
);
