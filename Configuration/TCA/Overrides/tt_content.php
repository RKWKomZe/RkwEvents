<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {
        //=================================================================
        // Register Plugins
        //=================================================================
        ExtensionUtility::registerPlugin(
            $extKey,
            'Pi1',
            'RKW Events'
        );

        ExtensionUtility::registerPlugin(
            $extKey,
            'Prefiltered',
            'RKW Events: Vorgefilterte Liste (flexform)'
        );

        ExtensionUtility::registerPlugin(
            $extKey,
            'Eventtitle',
            'RKW Events: Title'
        );

        ExtensionUtility::registerPlugin(
            $extKey,
            'Recommendation',
            'RKW Events: Empfehlung / Einzelauswahl'
        );

        ExtensionUtility::registerPlugin(
            $extKey,
            'Similar',
            'RKW Events: Ähnlichen Veranstaltung (Detailseite)'
        );

        ExtensionUtility::registerPlugin(
            $extKey,
            'Eventmaps',
            'RKW Events: GoogleMaps'
        );

        ExtensionUtility::registerPlugin(
            $extKey,
            'Eventinfo',
            'RKW Events: Kurzinfo'
        );

        ExtensionUtility::registerPlugin(
            $extKey,
            'Galleryone',
            'RKW Events: Gallery 1'
        );

        ExtensionUtility::registerPlugin(
            $extKey,
            'Gallerytwo',
            'RKW Events: Gallery 2'
        );

        ExtensionUtility::registerPlugin(
            $extKey,
            'Myeventreservation',
            'RKW Events: Meine RKW Veranstaltungen'
        );

        ExtensionUtility::registerPlugin(
            $extKey,
            'Seriesproposals',
            'RKW Events: Vorschläge zu einer Veranstaltung'
        );

        ExtensionUtility::registerPlugin(
            $extKey,
            'Standalonereg',
            'RKW Events: Registrierung (standalone)'
        );

        ExtensionUtility::registerPlugin(
            $extKey,
            'Cancelreg',
            'RKW Events: Von Veranstaltung abmelden (via Link)'
        );


        //=================================================================
        // Add Flexforms
        //=================================================================

        // PI1
        $pluginSignature = str_replace('_','',$extKey) . '_pi1';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
        $fileName = 'FILE:EXT:' . $extKey . '/Configuration/FlexForms/flexform_events.xml';
        // if rkw_projects is installed we have additional options available
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('rkw_projects')) {
            $fileName = 'FILE:EXT:' . $extKey . '/Configuration/FlexForms/flexform_events-projects.xml';
        }
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            $pluginSignature,
            $fileName
        );

        // Recommendation
        $pluginSignature = str_replace('_','',$extKey) . '_recommendation';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
        $fileName = 'FILE:EXT:' . $extKey . '/Configuration/FlexForms/flexform_events-recommendation.xml';
        // if rkw_projects is installed we have additional options available
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('rkw_projects')) {
            $fileName = 'FILE:EXT:' . $extKey . '/Configuration/FlexForms/flexform_events-recommendation-projects.xml';
        }
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            $pluginSignature,
            $fileName
        );

        // Standalonereg
        $pluginSignature = str_replace('_','',$extKey) . '_standalonereg';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
        $fileName = 'FILE:EXT:' . $extKey . '/Configuration/FlexForms/flexform_standalonereg.xml';
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            $pluginSignature,
            $fileName
        );

        // Prefiltered list
        $pluginSignature = str_replace('_','',$extKey) . '_prefiltered';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
        $fileName = 'FILE:EXT:' . $extKey . '/Configuration/FlexForms/flexform_events-prefiltered.xml';
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            $pluginSignature,
            $fileName
        );

    },
    'rkw_events'
);
