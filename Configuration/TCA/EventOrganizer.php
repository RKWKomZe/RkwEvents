<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_rkwevents_domain_model_eventorganizer', 'EXT:rkw_events/Resources/Private/Language/locallang_csh_tx_rkwevents_domain_model_eventorganizer.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rkwevents_domain_model_eventorganizer');
$GLOBALS['TCA']['tx_rkwevents_domain_model_eventorganizer'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer',
		'label' => 'last_name',
		'label_alt' => 'first_name, company, email',
		'label_alt_force' => TRUE,
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'requestUpdate' => 'type',
		'default_sortby' => 'ORDER BY last_name, company, email',

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'searchFields' => 'salutation,first_name,last_name,company,address,zip,city,phone,fax,email,',
		'iconfile' => 'EXT:rkw_events/Resources/Public/Icons/tx_rkwevents_domain_model_eventorganizer.gif'
	),
	'interface' => array(
		// 'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, company, first_name, last_name, address, zip, city, phone, email',
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, company',

	),
	'types' => array(
		// '1' => array('showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden,--palette--;;1, company, first_name, last_name, phone, email'),
		'1' => array('showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden,--palette--;;1, company'),

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
				'foreign_table' => 'tx_rkwevents_domain_model_eventorganizer',
				'foreign_table_where' => 'AND tx_rkwevents_domain_model_eventorganizer.pid=###CURRENT_PID### AND tx_rkwevents_domain_model_eventorganizer.sys_language_uid IN (-1,0)',
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
		'salutation' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.salutation',
			'config' => array(
				'type' => 'select',
                'renderType' => 'selectSingle',
				'items' => array(
					array('LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.salutation.I.99', 99),
					array('LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.salutation.I.0', 0),
                    array('LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.salutation.I.1', 1)
				),
				'default' => 99,
				'size' => 1,
				'maxitems' => 1,
			),
		),
		'first_name' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.first_name',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trimd'
			),
		),
		'last_name' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.last_name',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'company' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.company',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim, required'
			),
		),
		'address' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.address',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'zip' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.zip',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'city' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.city',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'phone' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.phone',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'fax' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.fax',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'email' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventorganizer.email',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,email'
			),
		),
	),
);
