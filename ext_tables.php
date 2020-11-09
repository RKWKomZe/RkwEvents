<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {



        //=================================================================
        // Register BE-Module
        //=================================================================
        if (TYPO3_MODE === 'BE') {

            /**
             * Registers a Backend Module
             */
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'RKW.' . $extKey,
                'tools',	 // Make module a submodule of 'Web'
                'eventsImport',	// Submodule key
                '',						// Position
                array(
                    'Backend' => 'show,create',
                ),
                array(
                    'access' => 'user,group',
                    'icon'   => 'EXT:' . $extKey . '/ext_icon.gif',
                    'labels' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_be.xlf',
                )
            );

        }

        //=================================================================
        // Add tables
        //=================================================================
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
            'tx_rkwevents_domain_model_event'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
            'tx_rkwevents_domain_model_eventcontact'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
            'tx_rkwevents_domain_model_eventorganizer'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
            'tx_rkwevents_domain_model_eventplace'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
            'tx_rkwevents_domain_model_eventreservation'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
            'tx_rkwevents_domain_model_eventreservationaddperson'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
            'tx_rkwevents_domain_model_eventseries'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
            'tx_rkwevents_domain_model_eventsheet'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
            'tx_rkwevents_domain_model_eventworkshop'
        );


        //=================================================================
        // Add Category
        //=================================================================
        // Add an extra categories selection field to the pages table
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
            'examples',
            'tx_rkwevents_domain_model_event',
            // Do not use the default field name ("categories") for pages, tt_content, sys_file_metadata, which is already used
            'sys_category',
            array(
                // Set a custom label
                'label' => 'LLL:EXT:examples/Resources/Private/Language/locallang.xlf:additional_categories',
                // This field should not be an exclude-field
                'exclude' => FALSE,
                // Override generic configuration, e.g. sort by title rather than by sorting
                'fieldConfiguration' => array(
                    'foreign_table_where' => ' AND sys_category.sys_language_uid IN (-1, 0) ORDER BY sys_category.title ASC',
                ),
                // string (keyword), see TCA reference for details
                'l10n_mode' => 'exclude',
                // list of keywords, see TCA reference for details
                'l10n_display' => 'hideDiff',
            )
        );

    },
    $_EXTKEY
);
