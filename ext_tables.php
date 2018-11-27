<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'RKW Events'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Eventmaps',
	'RKW Events: GoogleMaps'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Eventinfo',
	'RKW Events: Kurzinfo'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Galleryone',
	'RKW Events: Gallery 1'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Gallerytwo',
	'RKW Events: Gallery 2'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Myeventreservation',
	'RKW Events: Meine RKW Veranstaltungen'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Seriesproposals',
	'RKW Events: Vorschläge zu einer Veranstaltung'
);


if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'RKW.' . $_EXTKEY,
		'tools',	 // Make module a submodule of 'Web'
		'eventsImport',	// Submodule key
		'',						// Position
		array(
				'Backend' => 'show,create',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_be.xlf',
		)
	);

}


//=================================================================
// General stuff
//=================================================================
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'RKW Events');

/**
 * Flexform
 */
$pluginSignature = str_replace('_','',$_EXTKEY) . '_pi1';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
$fileName = 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_events.xml';

// if rkw_projects is installed we have additional options available
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('rkw_projects')) {
    $fileName = 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_events-projects.xml';
}
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, $fileName);
