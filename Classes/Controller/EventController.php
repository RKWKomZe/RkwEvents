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

use Brotkrueml\Schema\Type\TypeFactory;
use Madj2k\FeRegister\Utility\FrontendUserSessionUtility;
use Madj2k\FeRegister\Utility\FrontendUserUtility;
use RKW\RkwEvents\Domain\Model\Event;
use RKW\RkwEvents\Domain\Model\EventSeries;
use RKW\RkwEvents\Domain\Model\FrontendUser;
use RKW\RkwEvents\Domain\Repository\CategoryRepository;
use RKW\RkwEvents\Domain\Repository\DepartmentRepository;
use RKW\RkwEvents\Domain\Repository\DocumentTypeRepository;
use RKW\RkwEvents\Domain\Repository\EventRepository;
use RKW\RkwEvents\Domain\Repository\EventReservationBookedRepository;
use RKW\RkwEvents\Domain\Repository\EventReservationRepository;
use RKW\RkwEvents\Domain\Repository\EventReservationWaitlistRepository;
use RKW\RkwEvents\Domain\Repository\FrontendUserRepository;
use RKW\RkwEvents\Utility\DivUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * EventController
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @author Christian Dilger <c.dilger@addorange.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventController extends \Madj2k\AjaxApi\Controller\AjaxAbstractController
{

    /**
     * @var \RKW\RkwEvents\Domain\Repository\EventRepository
     */
    protected ?EventRepository $eventRepository = null;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\EventReservationRepository
     */
    protected ?EventReservationRepository $eventReservationRepository = null;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\EventReservationBookedRepository
     */
    protected ?EventReservationBookedRepository $eventReservationBookedRepository = null;

    /**
     * @var \RKW\RkwEvents\Domain\Repository\EventReservationWaitlistRepository
     */
    protected ?EventReservationWaitlistRepository $eventReservationWaitlistRepository = null;

    /**
     * @var \RKW\RkwEvents\Domain\Repository\CategoryRepository
     */
    protected ?CategoryRepository $categoryRepository = null;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\DepartmentRepository
     */
    protected ?DepartmentRepository $departmentRepository = null;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\DocumentTypeRepository
     */
    protected ?DocumentTypeRepository $documentTypeRepository = null;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\FrontendUserRepository
     */
    protected ?FrontendUserRepository $frontendUserRepository = null;


    /**
     * @var \RKW\RkwEvents\Domain\Model\FrontendUser
     */
    protected ?FrontendUser $frontendUser = null;



    /**
     * @param EventRepository $eventRepository
     * @param EventReservationRepository $eventReservationRepository
     * @param EventReservationBookedRepository $eventReservationBookedRepository
     * @param EventReservationWaitlistRepository $eventReservationWaitlistRepository
     * @param CategoryRepository $categoryRepository
     * @param DepartmentRepository $departmentRepository
     * @param DocumentTypeRepository $documentTypeRepository
     * @param FrontendUserRepository $frontendUserRepository
     */
    public function __construct(
        EventRepository $eventRepository,
        EventReservationRepository $eventReservationRepository,
        EventReservationBookedRepository $eventReservationBookedRepository,
        EventReservationWaitlistRepository $eventReservationWaitlistRepository,
        CategoryRepository $categoryRepository,
        DepartmentRepository $departmentRepository,
        DocumentTypeRepository $documentTypeRepository,
        FrontendUserRepository $frontendUserRepository
    ) {
        $this->eventRepository = $eventRepository;
        $this->eventReservationRepository = $eventReservationRepository;
        $this->eventReservationBookedRepository = $eventReservationBookedRepository;
        $this->eventReservationWaitlistRepository = $eventReservationWaitlistRepository;
        $this->categoryRepository = $categoryRepository;
        $this->departmentRepository = $departmentRepository;
        $this->documentTypeRepository = $documentTypeRepository;
        $this->frontendUserRepository = $frontendUserRepository;
    }


    /**
     * action myEvents
     * "landing page" for user to inform about own event reservations
     *
     * @return void
     */
    public function myEventsAction(): void
    {
        if (!$this->getFrontendUser()) {

            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'eventController.message.error.notPermitted', 'rkw_events'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
        } else {

            $myReservations = $this->eventReservationBookedRepository->findByFeUser($this->getFrontendUser());
            $myWaitlist = $this->eventReservationWaitlistRepository->findByFeUser($this->getFrontendUser());

            $this->view->assign('myReservations', $myReservations);
            $this->view->assign('myWaitlist', $myWaitlist);
        }
    }


    /**
     * action list
     *
     * Hint: The given params ($filter, $page, $archive) are only needed for AJAX purpose
     *
     * ! Has $noEventFound any function? The related function handleContentNotFound is without use currently
     *
     * @param array $filter
     * @param int $page
     * @param bool $archive
     * @param bool $noEventFound
     * @return void
     * @throws \Exception
     */
    public function listAction(
        array $filter = [],
        int $page = 0,
        bool $archive = false,
        bool $noEventFound = false
    ): void
    {

        // get department and document list (for filter)
        $globalEventSettings = \Madj2k\CoreExtended\Utility\GeneralUtility::getTypoScriptConfiguration('rkwEvents', \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $departmentList = $this->departmentRepository->findVisibleAndRestrictedByEvents(strip_tags($globalEventSettings['persistence']['storagePid']));
        $documentTypeList = $this->documentTypeRepository->findAllByTypeAndVisibilityAndRestrictedByEvents('events', false, strip_tags($globalEventSettings['persistence']['storagePid']));
        $categoryListRaw = $this->categoryRepository->findAllRestrictedByEvents(strip_tags($globalEventSettings['persistence']['storagePid']))->toArray();
        $categoryList = DivUtility::createCategoryTree($categoryListRaw, $this->settings['parentCategoryForFilter']);

        // if no specific listPid is set, simply use the current one
        $regInhouseListPid = $this->settings['list']['regInHouseTile']['listPid'] ?: (int)$GLOBALS['TSFE']->id;

        if ($filter || $page || $archive) {

            // NEW INTEGRATED AJAX PART (formerly AjaxController->filterAction)
            // HINT: Following lines are NOT supporting the old AjaxApi. Use old AjaxController for using the old AjaxApi

            // 1. filter the filterArray ;-)
            foreach ($filter as $key => $value) {
                $filter[$key] = filter_var($value, FILTER_SANITIZE_STRING);
            }

            // 2. get event list
            $listItemsPerView = (int)$this->settings['itemsPerPage'] ?: 10;

            // if reg_inhouse item is enabled via TS substract one item (on the first page)
            // (except regInhouse is part of the filter options (the tile is not shown then))
            if (
                $this->settings['list']['regInHouseTile']['show']
                && !array_key_exists('regInhouse', $filter)
            ) {
                --$listItemsPerView;
            }

            $queryResult = $this->eventRepository->findByFilterOptions($filter, $listItemsPerView, $page, $archive);

            // 3. proof if we have further results (query with listItemsPerQuery + 1)
            $lastItem = null;
            $eventList = DivUtility::prepareResultsList($queryResult, $listItemsPerView, $page, $lastItem);

            // 4. Check if we need to display a more-link
            $showMoreLink = count($eventList) < count($queryResult);

            if ($page > 0) {
                $showMoreLink = count($eventList) < (count($queryResult) - 1) ? true : false;
            }

            // get new list
            $replacements = [
                'sortedEventList'  => $eventList,
                'departmentList'   => $departmentList,
                'documentTypeList' => $documentTypeList,
                'categoryList'     => $categoryList,
                'ajaxTypeNum'  => (int)$this->settings['ajaxTypeNum'], //@deprecated
                'showPid'      => (int)$this->settings['showPid'],
                'pageMore'     => $page + 1,
                'showMoreLink' => $showMoreLink,
                'filter'       => $filter,
                'timeArrayList' => DivUtility::createMonthListArray($this->settings['list']['filter']['showTimeNumberOfMonths']),
                'regInhouseListPid' => $regInhouseListPid
            ];

            $this->view->assignMultiple($replacements);

        } else {

            // STANDARD LIST VIEW PART

            // if: use multiple view (three list types)
            if ($this->settings['list']['multipartView']['enabled']) {
                $limitStarted = (int) $this->settings['list']['multipartView']['limitStarted'];
                $limitUpcoming = (int) $this->settings['list']['multipartView']['limitUpcoming'];

                $this->view->assignMultiple(
                    [
                        'showDividedList' => true,
                        'startedEventList' => $this->eventRepository->findNotFinishedOrderAsc($limitStarted, $this->settings, '\RKW\RkwEvents\Domain\Model\EventScheduled', true),
                        'filterStartedEventList' => [
                            'project' => $this->settings['projectUids'],
                            'recordType' => 'EventScheduled',
                            'onlyStarted' => true
                        ],
                        // Change: Announcements are now also part of "upcoming" (and since 20.03.25 also the running events...)
                        // -> This is not a special list anymore. It's now a kind of classic view with all events
                        'upcomingEventList' => $this->eventRepository->findNotFinishedOrderAsc($limitUpcoming, $this->settings),
                        'filterUpcomingEventList' => [
                            'project' => $this->settings['projectUids'],
                            //'recordType' => 'EventScheduled',
                            //'onlyUpcoming' => true
                        ],
                        /*
                        'announcementEventList' => $this->eventRepository->findNotFinishedOrderAsc($limit, $this->settings, '\RKW\RkwEvents\Domain\Model\EventAnnouncement'),
                        'filterAnnouncementEventList' => [
                            'project' => $this->settings['projectUids'],
                            'recordType' => 'EventAnnouncement'
                        ]
                        */
                    ]
                );
            } else {
                // else: return one list

                // 1. get event list
                $listItemsPerView = (int)$this->settings['itemsPerPage'] ?: 10;

                // if reg_inhouse item is enabled via TS substract one item (on the first page)
                if ($this->settings['list']['regInHouseTile']['show']) {
                    --$listItemsPerView;
                }

                $queryResult = $this->eventRepository->findNotFinishedOrderAsc($listItemsPerView + 1, $this->settings);

                // 2. proof if we have further results (query with listItemsPerQuery + 1)
                $eventList = DivUtility::prepareResultsList($queryResult, $listItemsPerView);
                $showMoreLink = count($eventList) < count($queryResult) ? true : false;

                $this->view->assign('sortedEventList', $eventList);
            }


            $this->view->assignMultiple(
                [
                    'filter'           => ['project' => $this->settings['projectUids']],
                    'departmentList'   => $departmentList,
                    'documentTypeList' => $documentTypeList,
                    'categoryList'     => $categoryList,
                    'page',
                    'timeArrayList' => DivUtility::createMonthListArray($this->settings['list']['filter']['showTimeNumberOfMonths']),
                    'noEventFound' => $noEventFound
                ]
            );

            // target template is also used by ajax - so we have to set typoscript settings this way
            $this->view->assignMultiple(
                [
                    'ajaxTypeNum'  => (int)$this->settings['ajaxTypeNum'],
                    'showPid'      => (int)$this->settings['showPid'],
                    'pageMore'     => 1,
                    'showMoreLink' => $showMoreLink,
                    'regInhouseListPid' => $regInhouseListPid
                ]
            );
        }

    }


    /**
     * action list
     *
     * Hint: The given params ($filter, $page, $archive) are only needed for AJAX purpose
     *
     *
     * @param array $filter
     * @param int   $page
     * @param bool  $archive
     * @param bool  $noEventFound
     * @return void
     * @throws \Exception
     */
    public function listRegInhouseAction(
        array $filter = [],
        int   $page = 0,
        bool  $archive = false,
        bool $noEventFound = false
    ): void
    {

        // @toDo: Dieses Plugin einstampfen und einfach im Link diese Filter-Var mitgeben? Würde in Sachen Flexform etc einiges erleichtern

        // @toDo: Und falls Reginhouse-PID gesetzt, dann nutze sie. Ansonsten einfach die aktuelle nehmen!

        // @toDo: Aber wie kenntlich machen, dass man eine reine RegInhouse-Liste sieht?

        $filter['regInhouse'] = 1;

        $this->listAction($filter, $page);
    }


    /**
     * action listSimple
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function listSimpleAction(): void
    {
        // 1. get event list
        $listItemsPerView = (int)$this->settings['itemsPerPage'] ? (int)$this->settings['itemsPerPage'] : 10;
        $queryResult = $this->eventRepository->findNotFinishedOrderAsc($listItemsPerView + 1, $this->settings, '', false, false, true);

        // 2. proof if we have further results (query with listItemsPerQuery + 1)
        $eventList = DivUtility::prepareResultsList($queryResult, $listItemsPerView);
        $showMoreLink = (count($eventList) < count($queryResult) ? true : false);

        // 3. get department and document list (for filter)
        $departmentList = $this->departmentRepository->findAllByVisibility();
        $documentTypeList = $this->documentTypeRepository->findAllByTypeAndVisibility('events', false);
        $categoryList = $this->categoryRepository->findChildrenByParent((int)$this->settings['parentCategoryForFilter']);

        $this->view->assignMultiple(
            [
                'filter'           => [
                    'project' => $this->settings['projectUids'],
                ],
                'sortedEventList'  => $eventList,
                'departmentList'   => $departmentList,
                'documentTypeList' => $documentTypeList,
                'categoryList'     => $categoryList,
                'page'
            ]
        );
        // target template is also used by ajax - so we have to set typoscript settings this way
        $this->view->assignMultiple(
            [
                'ajaxTypeNum'  => (int)$this->settings['ajaxTypeNum'],
                'showPid'      => (int)$this->settings['showPid'],
                'pageMore'     => 1,
                'showMoreLink' => $showMoreLink,
            ]
        );
    }


    /**
     * action listSimilar
     * returns similar events for a detail view page
     *
     * @param \RKW\RkwEvents\Domain\Model\Event|null $event Needed for ajax request e.g.
     * @param int                                    $page
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function listSimilarAction(Event $event = null, int $page = 0): void
    {
        if (!$event instanceof Event) {
            $getParams = GeneralUtility::_GP('tx_rkwevents_pi1');
            $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
            /** @var Event $event */
            $event = $this->eventRepository->findByIdentifier(filter_var($eventUid, FILTER_SANITIZE_NUMBER_INT));
        }

        if (
            $event instanceof \RKW\RkwEvents\Domain\Model\Event
            && $event->getSeries() instanceof EventSeries
        ) {
            $listItemsPerView = (int)$this->settings['listSimilar']['itemsPerPage'] ? (int)$this->settings['listSimilar']['itemsPerPage'] : 6;
            $queryResult = $this->eventRepository->findSimilar($event, $listItemsPerView, $page, $this->settings);
            $eventList = DivUtility::prepareResultsList($queryResult, $listItemsPerView);
            if ($this->settings['listSimilar']['showMoreLink']) {
                $showMoreLink = count($eventList) < count($queryResult) ? true : false;
            } else {
                $showMoreLink = false;
            }

            // target template is also used by ajax - so we have to set typoscript settings this way
            $this->view->assignMultiple(
                [
                    'sortedEventList' => $eventList,
                    'ajaxTypeNum'     => (int)$this->settings['ajaxTypeNum'],
                    'showPid'         => (int)$this->settings['showPid'],
                    'pageMore'        => $page + 1,
                    'showMoreLink'    => $showMoreLink,
                    'currentEvent'    => $event
                ]
            );
        }
    }


    /**
     * action listPrefiltered
     * returns a list which is prefiltered by flexform
     *
     * @todo This action contains workarounds due to a strange MoreButton-URL override with tx_solr prefix (only on LIVE)
     *
     * @param int $page
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function listPrefilteredAction(int $page = 0): void
    {
        $filter = [];
        // grab given categories from Flexform
        $filter['category'] = $this->settings['categoriesList'];

        //$listItemsPerView = (int)$this->settings['listPrefiltered']['itemsPerPage'] ?: 6;
        // workaround (@toDo)
        $listItemsPerView = 999;

        $queryResult = $this->eventRepository->findByFilterOptions($filter, $listItemsPerView, $page);
        $eventList = DivUtility::prepareResultsList($queryResult, $listItemsPerView);

        // Check if we need to display a more-link
        //$showMoreLink = count($eventList) < count($queryResult);
        // workaround (@toDo)
        $showMoreLink = false;


        if ($page > 0) {
            $showMoreLink = count($eventList) < (count($queryResult) - 1);
        }

        // target template is also used by ajax - so we have to set typoscript settings this way
        $this->view->assignMultiple(
            [
                'sortedEventList' => $eventList,
                'ajaxTypeNum'     => (int)$this->settings['ajaxTypeNum'],
                'showPid'         => (int)$this->settings['showPid'],
                'pageMore'        => $page + 1,
                'showMoreLink'    => $showMoreLink,
            ]
        );
    }


    /**
     * action archive
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function archiveAction(): void
    {
        // 1. get event list
        $listItemsPerView = (int)$this->settings['itemsPerPage'] ? (int)$this->settings['itemsPerPage'] : 10;
        $queryResult = $this->eventRepository->findFinishedOrderAsc($listItemsPerView + 1, $this->settings);

        // 2. proof if we have further results (query with listItemsPerQuery + 1)
        $eventList = DivUtility::prepareResultsList($queryResult, $listItemsPerView);
        $showMoreLink = count($eventList) < count($queryResult) ? true : false;

        // 3. get department list (for filter)
        $departmentList = $this->departmentRepository->findAllByVisibility();

        // archive parameter
        $this->view->assign('archive', 1);

        $this->view->assign('sortedEventList', $eventList);
        $this->view->assign('departmentList', $departmentList);
        $this->view->assign('page', 0);

        // target template is also used by ajax - so we have to set typoscript settings this way
        $this->view->assign('ajaxTypeNum', (int)$this->settings['ajaxTypeNum']);
        $this->view->assign('showPid', (int)$this->settings['showPid']);
        $this->view->assign('pageMore', 1);
        $this->view->assign('showMoreLink', $showMoreLink);

    }


    /**
     * action show
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @return void
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("event")
     */
    public function showAction(\RKW\RkwEvents\Domain\Model\Event $event = null): void
    {
    //     $this->handleContentNotFound($event);

        // If no event is given (hidden; deleted) Extbase does not provide the event object
        // Or if container (the "EventSeries") is hidden or deleted
        if (
            !$event instanceof \RKW\RkwEvents\Domain\Model\Event
            || !$event->getSeries() instanceof EventSeries
        ) {

            $arguments = [
                'noEventFound' => true,
            ];

            $uri = $this->uriBuilder->reset()
                ->setTargetPageUid($this->settings['listPid'])
                ->setCreateAbsoluteUri(true)
                ->setArguments($arguments)
                ->build();

            $this->redirectToUri($uri, 0, 404);
        }

        $this->view->assign('event', $event);

        /*
        $event = \Brotkrueml\Schema\Type\TypeFactory::createType('Event')
            ->setProperty('name', 'Fancy Event 789abc')
            ->setProperty('image', 'https:/example.org/event.png')
            ->setProperty('url', 'https://example.org/')
            ->setProperty('isAccessibleForFree', true)
            ->setProperty('sameAs', 'https://twitter.com/fancy-event')
            ->addProperty('sameAs', 'https://facebook.com/fancy-event')
        ;

        $schemaManager = GeneralUtility::makeInstance(
            \Brotkrueml\Schema\Manager\SchemaManager::class
        );
        $schemaManager->addType($event);
        */

    }


    /**
     * action showAddInfo
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @return void
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("event")
     */
    public function showAddInfoAction(\RKW\RkwEvents\Domain\Model\Event $event): void
    {
        $this->view->assign('event', $event);
    }


    /**
     * action showSheet
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @return void
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("event")
     */
    public function showSheetAction(\RKW\RkwEvents\Domain\Model\Event $event): void
    {
        $this->view->assign('event', $event);
    }


    /**
     * action showGalleryOne
     *
     * @return void
     */
    public function showGalleryOneAction(): void
    {
        $getParams = GeneralUtility::_GP('tx_rkwevents_pi1');
        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        $event = $this->eventRepository->findByUid($eventUid);

        $this->view->assign('listPid', $this->settings['listPid']);
        $this->view->assign('event', $event);
    }


    /**
     * action showGalleryTwo
     *
     * @return void
     */
    public function showGalleryTwoAction(): void
    {
        $getParams = GeneralUtility::_GP('tx_rkwevents_pi1');
        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        $event = $this->eventRepository->findByUid($eventUid);

        $this->view->assign('listPid', $this->settings['listPid']);
        $this->view->assign('event', $event);
        $this->view->assign('frontendUser', $this->getFrontendUser());
    }


    /**
     * action maps
     * Added by Maximilian Fäßler
     *
     * @return void
     */
    public function mapsAction(): void
    {
        $getParams = GeneralUtility::_GP('tx_rkwevents_pi1');
        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        $event = $this->eventRepository->findByUid($eventUid);

        //$this->handleContentNotFound($event);

        $this->view->assign('event', $event);
    }


    /**
     * action info
     * Added by Maximilian Fäßler
     *
     * @return void
     */
    public function infoAction(): void
    {
        $getParams = GeneralUtility::_GP('tx_rkwevents_pi1');
        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        $event = $this->eventRepository->findByUid($eventUid);

        //$this->handleContentNotFound($event);

        $this->view->assign('isReservationPage', 0);
        if ((int)$GLOBALS['TSFE']->id == (int)$this->settings['reservationPid']) {
            $this->view->assign('isReservationPage', 1);
        }

        $this->view->assign('event', $event);
    }


    /**
     * action title
     * returns title name in view
     *
     * @return void
     */
    public function titleAction(): void
    {
        $getParams = GeneralUtility::_GP('tx_rkwevents_pi1');

        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        $event = $this->eventRepository->findByIdentifier(filter_var($eventUid, FILTER_SANITIZE_NUMBER_INT));

        //$this->handleContentNotFound($event);

        $this->view->assignMultiple([
            'event' => $event,
        ]);

    }


    /**
     * action seriesProposals
     * returns manually selected events ("RecommendedEvents") or as fallback running events of a certain series
     *
     * Hint: Used as sidebar plugin since nov 2021
     *
     * @return void
     */
    public function seriesProposalsAction(): void
    {
        $getParams = GeneralUtility::_GP('tx_rkwevents_pi1');

        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        /** @var \RKW\RkwEvents\Domain\Model\Event $event */
        $event = $this->eventRepository->findByIdentifier(filter_var($eventUid, FILTER_SANITIZE_NUMBER_INT));

        if (
            $event instanceof \RKW\RkwEvents\Domain\Model\Event
            && $event->getSeries() instanceof EventSeries
        ) {
            if ($event->getSeries()->getRecommendedEvents()->count()) {
                $eventList = $event->getSeries()->getRecommendedEvents();
            } else {
                // fallback
                $eventList = $this->eventRepository->findRunningByCategories($event);
            }

            $this->view->assignMultiple([
                'eventMain' => $event,
                'sortedEventList'  => $eventList,
                'showPid' => (int)$this->settings['showPid']
            ]);
        }

    }


    /**
     * handleContentNotFound
     * if event is not given (hidden or deleted) make 404 redirect
     * Hint: 404 seems not to working yet. Firefox and Chrome are repeating a 302
     *
     * @deprecated
     *
     * @param mixed $event
     * @return void
     */
    protected function handleContentNotFound($event): void
    {
        if (!$event instanceof \RKW\RkwEvents\Domain\Model\Event) {

            // Sideeffect: Adds an parameter to the url which always show the "event not available" message
            $arguments = [
                'noEventFound' => true,
            ];

            $uri = $this->uriBuilder->reset()
                ->setTargetPageUid($this->settings['listPid'])
                ->setCreateAbsoluteUri(true)
                ->setArguments($arguments)
                ->build();


            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'eventController.message.error.notAvailable2',
                    'rkw_events'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );

            $this->redirectToUri($uri, 0, 404);

        }
    }


    /**
     * Id of logged User
     *
     * @return int
     * @throws \TYPO3\CMS\Core\Context\Exception\AspectNotFoundException
     */
    protected function getFrontendUserId(): int
    {
        if (
            ($frontendUser = FrontendUserSessionUtility::getLoggedInUser())
            && (! FrontendUserUtility::isGuestUser($frontendUser))
        ){
            return $frontendUser->getUid();
        }

        return 0;
    }


    /**
     * Returns current logged in user object
     *
     * @return \RKW\RkwEvents\Domain\Model\FrontendUser|null
     * @throws \TYPO3\CMS\Core\Context\Exception\AspectNotFoundException
     */
    protected function getFrontendUser():? FrontendUser
    {
        /** @var \RKW\RkwEvents\Domain\Repository\FrontendUserRepository $frontendUserRepository */
        $this->frontendUser = $this->frontendUserRepository->findByIdentifier($this->getFrontendUserId());

        if ($this->frontendUser instanceof \TYPO3\CMS\Extbase\Domain\Model\FrontendUser) {
            return $this->frontendUser;
        }

        return null;
    }

}
