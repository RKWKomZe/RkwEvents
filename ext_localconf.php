<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        //=================================================================
        // Configure Plugins
        //=================================================================
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
            'Pi1',
            array(
                'Event' => 'myEvents, archive, list, listSimple, show, showAddInfo, showSheet, showGalleryOne, showGalleryTwo',
                'EventReservation' => 'new, create, update, delete, remove, optIn, edit',
            ),
            // non-cacheable actions
            array(
                'Event' => 'myEvents, show, list, listSimple, showAddInfo, showSheet, showGalleryOne, showGalleryTwo',
                'EventReservation' => 'new, create, update, delete, remove, optIn, edit',
            )
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
            'Recommendation',
            array(
                'Event' => 'listSimple',
            ),
            // non-cacheable actions
            array(
                'Event' => 'listSimple',
            )
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
            'Similar',
            array(
                'Event' => 'listSimilar'
            ),
            // non-cacheable actions
            array(
                'Event' => 'listSimilar'
            )
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
            'Prefiltered',
            array(
                'Event' => 'listPrefiltered'
            ),
            // non-cacheable actions
            array(
                'Event' => 'listPrefiltered'
            )
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
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
            'RKW.' . $extKey,
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
            'RKW.' . $extKey,
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
            'RKW.' . $extKey,
            'Galleryone',
            array(
                'Event' => 'showGalleryOne'
            )
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
            'Gallerytwo',
            array(
                'Event' => 'showGalleryTwo'
            )
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
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
            'RKW.' . $extKey,
            'Seriesproposals',
            array(
                'Event' => 'seriesProposals'
            ),
            // non-cacheable actions
            array(
                'Event' => 'seriesProposals'
            )
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'RKW.' . $extKey,
            'Standaloneregister',
            array(
                'EventReservation' => 'newStandalone, create, optIn',
            ),
            // non-cacheable actions
            array(
                'EventReservation' => 'newStandalone, create, optIn',
            )
        );

        //=================================================================
        // Register TCA evaluation to be available in 'eval' of TCA
        //=================================================================
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals']['RKW\\RkwEvents\\Evaluation\\EventEndDate'] = '';

        //=================================================================
        // Register Hook for Geodata and reservation cleanup on copy
        //=================================================================
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$extKey] = 'RKW\\RkwEvents\\Hooks\\TceMainHooks';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][$extKey] = 'RKW\\RkwEvents\\Hooks\\TceMainHooks';

        //=================================================================
        //  Register Command Controller
        //=================================================================
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'RKW\\RkwEvents\\Controller\\EventCommandController';

        //=================================================================
        //  Override TagGenerator of ext:core_extended
        //=================================================================
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['TYPO3\CMS\Frontend\Page\PageGenerator']['generateMetaTags']['metatag'] =
            \RKW\RkwEvents\MetaTag\MetaTagGenerator::class . '->generate';

        //=================================================================
        //  Override Routing
        //=================================================================
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['aspects']['EventPersistedSlugifiedPatternMapper'] = \RKW\RkwEvents\Routing\EventPersistedSlugifiedPatternMapper::class;

        //=================================================================
        // Register Signal-Slots
        //=================================================================
        /**
         * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher
         */
        $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);

        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('fe_register')) {
            $signalSlotDispatcher->connect(
                Madj2k\FeRegister\Registration\AbstractRegistration::class,
                \Madj2k\FeRegister\Registration\AbstractRegistration::SIGNAL_AFTER_CREATING_OPTIN . 'RkwEvents',
                RKW\RkwEvents\Service\RkwMailService::class,
                'optInRequest'
            );

            $signalSlotDispatcher->connect(
                Madj2k\FeRegister\Registration\AbstractRegistration::class,
                \Madj2k\FeRegister\Registration\AbstractRegistration::SIGNAL_AFTER_REGISTRATION_ENDED,
                RKW\RkwEvents\Controller\EventReservationController::class,
                'removeAllOfUserSignalSlot'
            );
        }

        $signalSlotDispatcher->connect(
            RKW\RkwEvents\Controller\EventReservationController::class,
            \RKW\RkwEvents\Controller\EventReservationController::SIGNAL_AFTER_RESERVATION_CREATED_USER,
            RKW\RkwEvents\Service\RkwMailService::class,
            'confirmReservationUser'
        );

        $signalSlotDispatcher->connect(
            RKW\RkwEvents\Controller\EventReservationController::class,
            \RKW\RkwEvents\Controller\EventReservationController::SIGNAL_AFTER_RESERVATION_CREATED_ADMIN,
            RKW\RkwEvents\Service\RkwMailService::class,
            'confirmReservationAdmin'
        );

        $signalSlotDispatcher->connect(
            RKW\RkwEvents\Controller\EventReservationController::class,
            \RKW\RkwEvents\Controller\EventReservationController::SIGNAL_AFTER_RESERVATION_UPDATE_USER,
            RKW\RkwEvents\Service\RkwMailService::class,
            'updateReservationUser'
        );

        $signalSlotDispatcher->connect(
            RKW\RkwEvents\Controller\EventReservationController::class,
            \RKW\RkwEvents\Controller\EventReservationController::SIGNAL_AFTER_RESERVATION_UPDATE_ADMIN,
            RKW\RkwEvents\Service\RkwMailService::class,
            'updateReservationAdmin'
        );

        $signalSlotDispatcher->connect(
            RKW\RkwEvents\Controller\EventReservationController::class,
            \RKW\RkwEvents\Controller\EventReservationController::SIGNAL_AFTER_RESERVATION_DELETE_USER,
            RKW\RkwEvents\Service\RkwMailService::class,
            'deleteReservationUser'
        );

        $signalSlotDispatcher->connect(
            RKW\RkwEvents\Controller\EventReservationController::class,
            \RKW\RkwEvents\Controller\EventReservationController::SIGNAL_AFTER_RESERVATION_DELETE_ADMIN,
            RKW\RkwEvents\Service\RkwMailService::class,
            'deleteReservationAdmin'
        );

        //=================================================================
        // ATTENTION: deactivated due to faulty mapping in TYPO3 9.5
        // Add XClasses for extending existing classes
        //=================================================================
//        if (class_exists(\RKW\RkwAuthors\Domain\Model\Authors::class)) {
//            // for TYPO3 12+
//            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\RKW\RkwAuthors\Domain\Model\Authors::class] = [
//                'className' => \RKW\RkwEvents\Domain\Model\Authors::class
//            ];
//
//            // for TYPO3 9.5 - 11.5 only, not required for TYPO3 12
//            \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
//                ->registerImplementation(
//                    \RKW\RkwAuthors\Domain\Model\Authors::class,
//                    \RKW\RkwEvents\Domain\Model\Authors::class
//                );
//        }
//
//        if (class_exists(\RKW\RkwSurvey\Domain\Model\Survey::class)) {
//            // for TYPO3 12+
//            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\RKW\RkwSurvey\Domain\Model\Survey::class] = [
//                'className' => \RKW\RkwEvents\Domain\Model\Survey::class
//            ];
//
//            // for TYPO3 9.5 - 11.5 only, not required for TYPO3 12
//            \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
//                ->registerImplementation(
//                    \RKW\RkwSurvey\Domain\Model\Survey::class,
//                    \RKW\RkwEvents\Domain\Model\Survey::class
//                );
//        }
//
//
//        // for TYPO3 12+
//        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\RKW\RkwBasics\Domain\Model\Department::class] = [
//            'className' => \RKW\RkwEvents\Domain\Model\Department::class
//        ];
//
//        // for TYPO3 9.5 - 11.5 only, not required for TYPO3 12
//        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
//            ->registerImplementation(
//                \RKW\RkwBasics\Domain\Model\Department::class,
//                \RKW\RkwEvents\Domain\Model\Department::class
//            );
//
//
//        // for TYPO3 12+
//        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\RKW\RkwBasics\Domain\Model\DocumentType::class] = [
//            'className' => \RKW\RkwEvents\Domain\Model\DocumentType::class
//        ];
//
//        // for TYPO3 9.5 - 11.5 only, not required for TYPO3 12
//        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
//            ->registerImplementation(
//                \RKW\RkwBasics\Domain\Model\DocumentType::class,
//                \RKW\RkwEvents\Domain\Model\DocumentType::class
//            );
//
//
//        // for TYPO3 12+
//        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Extbase\Domain\Model\BackendUser::class] = [
//            'className' => \TYPO3\CMS\Extbase\Domain\Model\BackendUser::class
//        ];
//
//        // for TYPO3 9.5 - 11.5 only, not required for TYPO3 12
//        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
//            ->registerImplementation(
//                \TYPO3\CMS\Extbase\Domain\Model\BackendUser::class,
//                \RKW\RkwEvents\Domain\Model\BackendUser::class
//            );
//
//
//        // for TYPO3 12+
//        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Extbase\Domain\Model\BackendUserGroup::class] = [
//            'className' => \RKW\RkwEvents\Domain\Model\BackendUserGroup::class
//        ];
//
//        // for TYPO3 9.5 - 11.5 only, not required for TYPO3 12
//        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
//            ->registerImplementation(
//                \TYPO3\CMS\Extbase\Domain\Model\BackendUserGroup::class,
//                \RKW\RkwEvents\Domain\Model\BackendUserGroup::class
//            );
//
//
//        // for TYPO3 12+
//        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Extbase\Domain\Model\Category::class] = [
//            'className' => \RKW\RkwEvents\Domain\Model\Category::class
//        ];
//
//        // for TYPO3 9.5 - 11.5 only, not required for TYPO3 12
//        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
//            ->registerImplementation(
//                \TYPO3\CMS\Extbase\Domain\Model\Category::class,
//                \RKW\RkwEvents\Domain\Model\Category::class
//            );

        //=================================================================
        // Register Logger
        //=================================================================
        $GLOBALS['TYPO3_CONF_VARS']['LOG']['RKW']['RkwEvents']['writerConfiguration'] = array(

            // configuration for WARNING severity, including all
            // levels with higher severity (ERROR, CRITICAL, EMERGENCY)
            \TYPO3\CMS\Core\Log\LogLevel::WARNING => array(

                // add a FileWriter
                'TYPO3\\CMS\\Core\\Log\\Writer\\FileWriter' => array(
                    // configuration for the writer
                    'logFile' => \TYPO3\CMS\Core\Core\Environment::getVarPath()  . '/log/tx_rkwevents.log'
                )
            ),
        );

    },
    'rkw_events'
);

