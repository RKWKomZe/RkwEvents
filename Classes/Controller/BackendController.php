<?php

namespace RKW\RkwEvents\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Madj2k\CoreExtended\Transfer\CsvImporter;
use RKW\RkwEvents\Domain\Model\BackendUser;
use RKW\RkwEvents\Domain\Model\Event;
use RKW\RkwEvents\Domain\Repository\BackendUserRepository;
use RKW\RkwEvents\Domain\Repository\CategoryRepository;
use RKW\RkwEvents\Domain\Repository\DepartmentRepository;
use RKW\RkwEvents\Domain\Repository\DocumentTypeRepository;
use RKW\RkwEvents\Domain\Repository\EventContactRepository;
use RKW\RkwEvents\Domain\Repository\EventOrganizerRepository;
use RKW\RkwEvents\Domain\Repository\EventPlaceRepository;
use RKW\RkwEvents\Domain\Repository\EventRepository;
use RKW\RkwEvents\Utility\BackendUserUtility;
use RKW\RkwEvents\Utility\CsvUtility;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * BackendController
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BackendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * @var \RKW\RkwEvents\Domain\Repository\EventRepository
     */
    protected ?EventRepository $eventRepository = null;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\EventPlaceRepository
     */
    protected ?EventPlaceRepository $eventPlaceRepository = null;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\EventContactRepository
     */
    protected ?EventContactRepository $eventContactRepository = null;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\EventOrganizerRepository
     */
    protected ?EventOrganizerRepository $eventOrganizerRepository = null;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\DepartmentRepository
     */
    protected ?DepartmentRepository $departmentRepository = null;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\DocumentTypeRepository
     */
    protected ?DocumentTypeRepository $documentTypeRepository = null;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\BackendUserRepository
     */
    protected ?BackendUserRepository $backendUserRepository = null;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\CategoryRepository
     */
    protected ?CategoryRepository $categoryRepository = null;


    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected ?PersistenceManager $persistenceManager = null;


    /**
     * @param EventRepository $eventRepository
     * @param EventPlaceRepository $eventPlaceRepository
     * @param EventContactRepository $eventContactRepository
     * @param EventOrganizerRepository $eventOrganizerRepository
     * @param DepartmentRepository $departmentRepository
     * @param DocumentTypeRepository $documentTypeRepository
     * @param BackendUserRepository $backendUserRepository
     * @param CategoryRepository $categoryRepository
     * @param PersistenceManager $persistenceManager
     */
    public function __construct(
        EventRepository $eventRepository,
        EventPlaceRepository $eventPlaceRepository,
        EventContactRepository $eventContactRepository,
        EventOrganizerRepository $eventOrganizerRepository,
        DepartmentRepository $departmentRepository,
        DocumentTypeRepository $documentTypeRepository,
        BackendUserRepository $backendUserRepository,
        CategoryRepository $categoryRepository,
        PersistenceManager $persistenceManager
    ) {
        $this->eventRepository = $eventRepository;
        $this->eventPlaceRepository = $eventPlaceRepository;
        $this->eventContactRepository = $eventContactRepository;
        $this->eventOrganizerRepository = $eventOrganizerRepository;
        $this->departmentRepository = $departmentRepository;
        $this->documentTypeRepository = $documentTypeRepository;
        $this->backendUserRepository = $backendUserRepository;
        $this->categoryRepository = $categoryRepository;
        $this->persistenceManager = $persistenceManager;
    }


    /**
     * csvExport
     *
     * @return void
     */
    public function csvExportAction(): void
    {
        /** @var BackendUser $currentBackendUser */
        $currentBackendUser = $this->backendUserRepository->findByUid(intval($GLOBALS['BE_USER']->user['uid']));

        $this->view->assign('currentBackendUser', $currentBackendUser);

        // if user is not an admin, set specific page uids which are related to the users groups
        $querySettings = $this->eventRepository->createQuery()->getQuerySettings();
        if ($currentBackendUser->getIsAdministrator()) {
            // let see admin everything
            $querySettings->setRespectStoragePage(false);
        } else {
            // restrict other user to it's allowed content
            $allowedPidsOfCurrentBackendUser = BackendUserUtility::getMountpointsOfAllGroups($currentBackendUser);
            $querySettings->setStoragePageIds($allowedPidsOfCurrentBackendUser);
        }
        $this->eventRepository->setDefaultQuerySettings($querySettings);

        $this->view->assign('eventListNotFinished', $this->eventRepository->findNotFinishedOrderAsc(100));

        $this->view->assign('eventListFinished', $this->eventRepository->findFinishedOrderAsc(100));
    }


    /**
     * createCsv
     *
     * @param Event $event
     * @return void
     */
    public function createCsvAction(Event $event): void
    {
        CsvUtility::createCsv($event);
        exit;
    }


    /**
     * Shows input
     *
     * @return void
     */
    public function showAction(): void
    {
        $this->view->assignMultiple(
            [
                'content'       => '',
                'documentTypes' => $this->documentTypeRepository->findByType('events'),
                'departments'   => $this->departmentRepository->findAll(),
                'organizers'    => $this->eventOrganizerRepository->findAll(),
            ]
        );

    }


    /**
     * Makes import
     *
     * @param array $data
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function createAction(array $data): void
    {
        $fileType = $_FILES['tx_rkwevents_tools_rkweventseventsimport']['type']['data']['file'];
        $filePath = $_FILES['tx_rkwevents_tools_rkweventseventsimport']['tmp_name']['data']['file'];

        // check file type
        if (strtolower($fileType) !== 'text/csv') {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'backendController.error.wrongFileType',
                    'rkw_events'
                ),
                '',
                AbstractMessage::ERROR
            );

            $this->forward('show');
        }

        try {
            /** @var \Madj2k\CoreExtended\Transfer\CsvImporter $csvImporter */
            $csvImporter = $this->objectManager->get(CsvImporter::class);
            $csvImporter->setTableName('tx_rkwevents_domain_model_event');

            // init importer and do some basic setup
            $csvImporter->readCsv($filePath);

            // "setAllowedTables" is used for main and all sub-relational-tables like "event.place"
            $csvImporter->setAllowedTables(
                [
                    'tx_rkwevents_domain_model_eventseries',
                    'tx_rkwevents_domain_model_event',
                    'tx_rkwevents_domain_model_eventplace',
                    'tx_rkwevents_domain_model_eventcontact',
                    'tx_rkwauthors_domain_model_authors',
                ]
            );
            $csvImporter->setAllowedRelationTables(
                [
                    'tx_rkwevents_domain_model_eventseries' => [
                        'tx_rkwevents_domain_model_event',
                        'sys_category',
                        'tx_rkwbasics_domain_model_documenttype',
                        'tx_rkwbasics_domain_model_department',
                        'be_users'                                  // field "backend_user_exclusive"
                    ],
                    'tx_rkwevents_domain_model_event' => [
                        'be_users',                                 // field "be_user" (e-mail admin)
                        'tx_rkwevents_domain_model_eventplace',
                        'tx_rkwevents_domain_model_eventcontact',
                        'tx_rkwauthors_domain_model_authors',
                        'tx_rkwevents_domain_model_eventorganizer',
                    ]
                ]
            );
            $csvImporter->setExcludeColumns(
                [
                    'tx_rkwevents_domain_model_eventseries' => [
                        'tstamp', 'crdate', 'cruser_id', 'deleted', 'starttime', 'endtime',
                        'sorting', 'sys_language_uid', 'l10n_parent', 'l10n_diffsource'
                    ],
                    'tx_rkwevents_domain_model_event' => [
                        'testimonials', 'logos', 'add_info', 'presentations', 'sheet',
                        'gallery1', 'gallery2', 'reservation', 'workshop1', 'workshop2', 'workshop3', 'reminder_mail_tstamp',
                        'survey_before', 'survey_after', 'survey_after_mail_tstamp', 'longitude', 'latitude', 'recommended_events',
                        'recommended_links', 'header_image', 'tstamp', 'crdate', 'cruser_id', 'deleted', 'starttime', 'endtime',
                        'sorting', 'sys_language_uid', 'l10n_parent', 'l10n_diffsource'
                    ]
                ]
            );
            $csvImporter->setIncludeColumns(
                [
                    'tx_rkwevents_domain_model_eventseries' => [
                        'pid'
                    ],
                    'tx_rkwevents_domain_model_event' => [
                        'pid'
                    ],
                    'tx_rkwevents_domain_model_eventplace' => [
                        'pid'
                    ],
                    'tx_rkwevents_domain_model_eventcontact' => [
                        'pid'
                    ],
                    'tx_rkwauthors_domain_model_authors' => [
                        'pid'
                    ]
                ]
            );
            $csvImporter->setUniqueSelectColumns(
                [
                    'tx_rkwauthors_domain_model_eventseries' => ['pid', 'title'],
                    'tx_rkwevents_domain_model_event' => ['pid', 'start', 'series'],
                    'tx_rkwevents_domain_model_eventplace' => ['pid', 'address', 'zip', 'city'],
                    'tx_rkwevents_domain_model_eventcontact' => ['pid', 'email'],
                    'tx_rkwauthors_domain_model_authors' => ['pid', 'email'],
                ]
            );

            // default: always set new events hidden
            $additionalData['event.hidden'] = 1;

            // user input data from import form
            if ($data['targetPid']) {
                $additionalData = [
                    'pid' => intval($data['targetPid']),
                    'event.pid' => intval($data['targetPid']),
                    'event.place.pid' => intval($data['targetPid']),
                    'event.external_contact.pid' => intval($data['targetPid']),
                ];
            }

            if ($data['targetPidAuthors']) {
                $additionalData['event.internal_contact.pid'] = intval($data['targetPidAuthors']);
            }

            if ($data['document_type']) {
                $additionalData['document_type'] = intval($data['document_type']);
            }

            if ($data['department']) {
                $additionalData['department.uid'] = intval($data['department']);
            }

            if ($data['organizer']) {
                $additionalData['organizer.uid'] = intval($data['organizer']);
            }

            if ($data['activate']) {
                $additionalData['event.hidden'] = 0;
            }

            $csvImporter->setAdditionalData($additionalData);
            $csvImporter->applyAdditionalData();

            $defaultValues = [
                'event.seats' => 100000,
                'event.record_type' => '\RKW\RkwEvents\Domain\Model\EventScheduled'
            ];

            $csvImporter->setDefaultValues($defaultValues);
            $csvImporter->applyDefaultValues();

            if ($result = $csvImporter->import()) {

                $this->addFlashMessage(
                    LocalizationUtility::translate(
                        'backendController.message.importSuccessful',
                        'rkw_events',
                        [count($result)]
                    ),
                    '',
                    AbstractMessage::OK
                );
            } else {

                $this->addFlashMessage(
                    LocalizationUtility::translate(
                        'backendController.warning.noRecordsImported',
                        'rkw_events',
                    ),
                    '',
                    AbstractMessage::ERROR
                );
            }



        } catch (\Exception $e) {

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'backendController.error.importFailed',
                    'rkw_events',
                    $e->getMessage(),
                ),
                '',
                AbstractMessage::ERROR
            );
        }

        $this->redirect('show');

    }

}
