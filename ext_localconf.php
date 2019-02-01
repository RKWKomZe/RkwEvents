<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'RKW.' . $_EXTKEY,
	'Pi1',
	array(
		'Event' => 'myEvents, archive, list, show, showAddInfo, showSheet, showGalleryOne, showGalleryTwo',
        'EventReservation' => 'new, create, update, delete, remove, optIn, edit',
		'Ajax' => 'filter, more, moreArchive'
	),
	// non-cacheable actions
	array(
		'Event' => 'myEvents, show, list, showAddInfo, showSheet, showGalleryOne, showGalleryTwo',
        'EventReservation' => 'new, create, update, delete, remove, optIn, edit',
		'Ajax' => 'filter, more, moreArchive'
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'RKW.' . $_EXTKEY,
	'Eventmaps',
	array(
		'Event' => 'maps'
	),
	// non-cacheable actions
	array(
		'Event' => 'maps'
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'RKW.' . $_EXTKEY,
	'Eventinfo',
	array(
		'Event' => 'info'
	),
	// non-cacheable actions
	array(
		'Event' => 'info'
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'RKW.' . $_EXTKEY,
	'Eventtitle',
	array(
		'Event' => 'title'
	),
	// non-cacheable actions
	array(
		'Event' => 'title'
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'RKW.' . $_EXTKEY,
	'Galleryone',
	array(
		'Event' => 'showGalleryOne'
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'RKW.' . $_EXTKEY,
	'Gallerytwo',
	array(
		'Event' => 'showGalleryTwo'
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'RKW.' . $_EXTKEY,
	'Myeventreservation',
	array(
		'Event' => 'myEvents'
	),
	// non-cacheable actions
	array(
		'Event' => 'myEvents'
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'RKW.' . $_EXTKEY,
	'Seriesproposals',
	array(
		'Event' => 'seriesProposals'
	),
	// non-cacheable actions
	array(
		'Event' => 'seriesProposals'
	)
);



// Hook for Geodata and reservation cleanup on copy
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$_EXTKEY] = 'RKW\\RkwEvents\\Hooks\\TceMainHooks';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][$_EXTKEY] = 'RKW\\RkwEvents\\Hooks\\TceMainHooks';


// Register Command Controller
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'RKW\\RkwEvents\\Controller\\EventCommandController';

// Set logger
$GLOBALS['TYPO3_CONF_VARS']['LOG']['RKW']['RkwEvents']['writerConfiguration'] = array(

	// configuration for WARNING severity, including all
	// levels with higher severity (ERROR, CRITICAL, EMERGENCY)
	\TYPO3\CMS\Core\Log\LogLevel::WARNING => array(
		// add a FileWriter
		'TYPO3\\CMS\\Core\\Log\\Writer\\FileWriter' => array(
			// configuration for the writer
			'logFile' => 'typo3temp/logs/tx_rkwevents.log'
		)
	),
);


/**
 * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher
 */
$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('rkw_registration')) {
	$signalSlotDispatcher->connect(
		'RKW\\RkwRegistration\\Tools\\Registration',
		\RKW\RkwRegistration\Tools\Registration::SIGNAL_AFTER_CREATING_OPTIN_EXISTING_USER . 'RkwEvents',
		'RKW\\RkwEvents\\Service\\RkwMailService',
		'optInRequest'
	);

	$signalSlotDispatcher->connect(
		'RKW\\RkwRegistration\\Tools\\Registration',
		\RKW\RkwRegistration\Tools\Registration::SIGNAL_AFTER_CREATING_OPTIN_USER . 'RkwEvents',
		'RKW\\RkwEvents\\Service\\RkwMailService',
		'optInRequest'
	);

	$signalSlotDispatcher->connect(
		'RKW\\RkwRegistration\\Tools\\Registration',
		\RKW\RkwRegistration\Tools\Registration::SIGNAL_AFTER_DELETING_USER,
		'RKW\\RkwEvents\\Controller\\EventReservationController',
		'removeAllOfUserSignalSlot'
	);
}

$signalSlotDispatcher->connect(
	'RKW\\RkwEvents\\Controller\\EventReservationController',
	\RKW\RkwEvents\Controller\EventReservationController::SIGNAL_AFTER_RESERVATION_CREATED_USER,
	'RKW\\RkwEvents\\Service\\RkwMailService',
	'confirmReservationUser'
);

$signalSlotDispatcher->connect(
	'RKW\\RkwEvents\\Controller\\EventReservationController',
	\RKW\RkwEvents\Controller\EventReservationController::SIGNAL_AFTER_RESERVATION_CREATED_ADMIN,
	'RKW\\RkwEvents\\Service\\RkwMailService',
	'confirmReservationAdmin'
);

$signalSlotDispatcher->connect(
	'RKW\\RkwEvents\\Controller\\EventReservationController',
	\RKW\RkwEvents\Controller\EventReservationController::SIGNAL_AFTER_RESERVATION_UPDATE_USER,
	'RKW\\RkwEvents\\Service\\RkwMailService',
	'updateReservationUser'
);

$signalSlotDispatcher->connect(
	'RKW\\RkwEvents\\Controller\\EventReservationController',
	\RKW\RkwEvents\Controller\EventReservationController::SIGNAL_AFTER_RESERVATION_UPDATE_ADMIN,
	'RKW\\RkwEvents\\Service\\RkwMailService',
	'updateReservationAdmin'
);

$signalSlotDispatcher->connect(
	'RKW\\RkwEvents\\Controller\\EventReservationController',
	\RKW\RkwEvents\Controller\EventReservationController::SIGNAL_AFTER_RESERVATION_DELETE_USER,
	'RKW\\RkwEvents\\Service\\RkwMailService',
	'deleteReservationUser'
);

$signalSlotDispatcher->connect(
	'RKW\\RkwEvents\\Controller\\EventReservationController',
	\RKW\RkwEvents\Controller\EventReservationController::SIGNAL_AFTER_RESERVATION_DELETE_ADMIN,
	'RKW\\RkwEvents\\Service\\RkwMailService',
	'deleteReservationAdmin'
);


// additional RealUrl configuration
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {

    // Exclude tx_rkwevents_pi1[event] from cHash because TYPO3 does not do that
    if (strpos($GLOBALS['TYPO3_CONF_VARS']['FE']['cHashExcludedParameters'],'tx_rkwevents_pi1[event]') === false) {
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cHashExcludedParameters'] .= ', tx_rkwevents_pi1[event]';
    }

    if (! is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['postVarSets']['_DEFAULT'])) {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['postVarSets']['_DEFAULT'] = [];
    }

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['postVarSets']['_DEFAULT'] = array_merge (
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['postVarSets']['_DEFAULT'],
        [
            //===============================================
            // Events
            'tx-rkw-events' => [
                [
                    'GETvar'   => 'tx_rkwevents_pi1[controller]',
                    'valueMap' => [
                        'event'       => 'Event',
                        'reservation' => 'EventReservation',
                    ],
                ],
                [
                    'GETvar' => 'tx_rkwevents_pi1[action]',
                ],

                // look-up table - param has to be set in cHash-ignore in Install-Tool!
                [
                    'GETvar'      => 'tx_rkwevents_pi1[event]',
                    'lookUpTable' => [
                        'table'               => 'tx_rkwevents_domain_model_event',
                        'id_field'            => 'uid',
                        'alias_field'         => 'CONCAT(title, "-", uid)',
                        'addWhereClause'      => ' AND NOT deleted AND NOT hidden',
                        'useUniqueCache'      => 1,
                        'useUniqueCache_conf' => [
                            'strtolower'     => 1,
                            'spaceCharacter' => '-',
                        ],
                    ],
                ],
            ],
        ]
    );
}
