<?php
defined('TYPO3_MODE') || die('Access denied.');

$extKey = 'rkw_events';

//=================================================================
// Register Plugins
//=================================================================
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $extKey,
    'Pi1',
    'RKW Events'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $extKey,
    'Eventtitle',
    'RKW Events: Title'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $extKey,
    'Eventmaps',
    'RKW Events: GoogleMaps'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $extKey,
    'Eventinfo',
    'RKW Events: Kurzinfo'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $extKey,
    'Galleryone',
    'RKW Events: Gallery 1'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $extKey,
    'Gallerytwo',
    'RKW Events: Gallery 2'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $extKey,
    'Myeventreservation',
    'RKW Events: Meine RKW Veranstaltungen'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $extKey,
    'Seriesproposals',
    'RKW Events: Vorschläge zu einer Veranstaltung'
);

//=================================================================
// Add Flexform
//=================================================================
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

