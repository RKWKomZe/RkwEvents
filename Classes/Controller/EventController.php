<?php

namespace RKW\RkwEvents\Controller;

use RKW\RkwEvents\Domain\Model\Event;
use RKW\RkwEvents\Utility\DivUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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

/**
 * Class EventController
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventController extends \RKW\RkwAjax\Controller\AjaxAbstractController
{
    /**
     * eventRepository
     *
     * @var \RKW\RkwEvents\Domain\Repository\EventRepository
     * @inject
     */
    protected $eventRepository = null;

    /**
     * eventReservationRepository
     *
     * @var \RKW\RkwEvents\Domain\Repository\EventReservationRepository
     * @inject
     */
    protected $eventReservationRepository = null;

    /**
     * categoryRepository
     *
     * @var \RKW\RkwEvents\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository = null;

    /**
     * departmentRepository
     *
     * @var \RKW\RkwEvents\Domain\Repository\DepartmentRepository
     * @inject
     */
    protected $departmentRepository = null;

    /**
     * documentTypeRepository
     *
     * @var \RKW\RkwEvents\Domain\Repository\DocumentTypeRepository
     * @inject
     */
    protected $documentTypeRepository = null;

    /**
     * frontendUserRepository
     *
     * @var \RKW\RkwEvents\Domain\Repository\FrontendUserRepository
     * @inject
     */
    protected $frontendUserRepository = null;

    /**
     * logged FrontendUser
     *
     * @var \RKW\RkwEvents\Domain\Model\FrontendUser
     */
    protected $frontendUser = null;


    /**
     * action myEvents
     * "landing page" for user to inform about own event reservations
     *
     * @return void
     */
    public function myEventsAction()
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

            $myReservations = $this->eventReservationRepository->findByFeUser($this->getFrontendUser());
            $this->view->assign('myReservations', $myReservations);
        }
    }


    /**
     * action list
     *
     * Hint: The given params ($filter, $page, $archive) are only needed for AJAX purpose
     *
     * @param array $filter
     * @param integer $page
     * @param bool $archive
     * @param bool $noEventFound
     * @return void
     * @throws \Exception
     */
    public function listAction($filter = array(), $page = 0, $archive = false, $noEventFound = false)
    {

        // get department and document list (for filter)
        $globalEventSettings = \RKW\RkwBasics\Utility\GeneralUtility::getTyposcriptConfiguration('rkwEvents', \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $departmentList = $this->departmentRepository->findVisibleAndRestrictedByEvents(strip_tags($globalEventSettings['persistence']['storagePid']));
        $documentTypeList = $this->documentTypeRepository->findAllByTypeAndVisibilityAndRestrictedByEvents('events', false, strip_tags($globalEventSettings['persistence']['storagePid']));
        $categoryListRaw = $this->categoryRepository->findAllRestrictedByEvents(strip_tags($globalEventSettings['persistence']['storagePid']))->toArray();
        $categoryList = DivUtility::createCategoryTree($categoryListRaw, $this->settings['parentCategoryForFilter']);

        if ($filter || $page || $archive) {

            // NEW INTEGRATED AJAX PART (formerly AjaxController->filterAction)
            // HINT: Following lines are NOT supporting the old AjaxApi. Use old AjaxController for using the old AjaxApi

            // 1. filter the filterArray ;-)
            foreach ($filter as $key => $value) {
                $filter[$key] = filter_var($value, FILTER_SANITIZE_STRING);
            }

            // 2. get event list
            $listItemsPerView = intval($this->settings['itemsPerPage']) ? intval($this->settings['itemsPerPage']) : 10;
            $queryResult = $this->eventRepository->findByFilterOptions($filter, $listItemsPerView, intval($page), boolval($archive));

            // 3. proof if we have further results (query with listItemsPerQuery + 1)
            $lastItem = null;
            $eventList = DivUtility::prepareResultsList($queryResult, $listItemsPerView, intval($page), $lastItem);

            // 4. Check if we need to display a more-link
            $showMoreLink = count($eventList) < count($queryResult) ? true : false;

            if ($page > 0) {
                $showMoreLink = count($eventList) < (count($queryResult) - 1) ? true : false;
            }

            // get new list
            $replacements = array(
                'sortedEventList'  => $eventList,
                'departmentList'   => $departmentList,
                'documentTypeList' => $documentTypeList,
                'categoryList'     => $categoryList,
                'ajaxTypeNum'  => intval($this->settings['ajaxTypeNum']), //@deprecated
                'showPid'      => intval($this->settings['showPid']),
                'pageMore'     => $page + 1,
                'showMoreLink' => $showMoreLink,
                'filter'       => $filter,
            );

            $this->view->assignMultiple($replacements);

        } else {

            // STANDARD LIST VIEW PART

            // if: use multiple view (three list types)
            if ($this->settings['list']['multipartView']['enabled']) {
                $limitStarted = (int) $this->settings['list']['multipartView']['limitStarted'];
                $limitUpcoming = (int) $this->settings['list']['multipartView']['limitUpcoming'];

                $this->view->assignMultiple(
                    array(
                        'showDividedList' => true,
                        'startedEventList' => $this->eventRepository->findNotFinishedOrderAsc($limitStarted, $this->settings, '\RKW\RkwEvents\Domain\Model\EventScheduled', true),
                        'filterStartedEventList' => array(
                            'project' => $this->settings['projectUids'],
                            'recordType' => 'EventScheduled',
                            'onlyStarted' => true
                        ),
                        // Change: Announcements are now also part of "upcoming"
                        'upcomingEventList' => $this->eventRepository->findNotFinishedOrderAsc($limitUpcoming, $this->settings, '', false, true),
                        'filterUpcomingEventList' => array(
                            'project' => $this->settings['projectUids'],
                            //'recordType' => 'EventScheduled',
                            'onlyUpcoming' => true
                        ),
                        /*
                        'announcementEventList' => $this->eventRepository->findNotFinishedOrderAsc($limit, $this->settings, '\RKW\RkwEvents\Domain\Model\EventAnnouncement'),
                        'filterAnnouncementEventList' => array(
                            'project' => $this->settings['projectUids'],
                            'recordType' => 'EventAnnouncement'
                        )
                        */
                    )
                );
            } else {
                // else: return one list

                // 1. get event list
                $listItemsPerView = intval($this->settings['itemsPerPage']) ? intval($this->settings['itemsPerPage']) : 10;
                $queryResult = $this->eventRepository->findNotFinishedOrderAsc($listItemsPerView + 1, $this->settings);
                
                // 2. proof if we have further results (query with listItemsPerQuery + 1)
                $eventList = DivUtility::prepareResultsList($queryResult, $listItemsPerView);
                $showMoreLink = count($eventList) < count($queryResult) ? true : false;

                $this->view->assign('sortedEventList', $eventList);
            }


            $this->view->assignMultiple(
                array(
                    'filter'           => array('project' => $this->settings['projectUids']),
                    'departmentList'   => $departmentList,
                    'documentTypeList' => $documentTypeList,
                    'categoryList'     => $categoryList,
                    'page',
                    'timeArrayList' => DivUtility::createMonthListArray($this->settings['list']['filter']['showTimeNumberOfMonths']),
                    'noEventFound' => $noEventFound
                )
            );

            // target template is also used by ajax - so we have to set typoscript settings this way
            $this->view->assignMultiple(
                array(
                    'ajaxTypeNum'  => intval($this->settings['ajaxTypeNum']),
                    'showPid'      => intval($this->settings['showPid']),
                    'pageMore'     => 1,
                    'showMoreLink' => $showMoreLink,
                )
            );
        }

    }

    /**
     * action listSimple
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function listSimpleAction()
    {
        // 1. get event list
        $listItemsPerView = (int)$this->settings['itemsPerPage'] ? (int)$this->settings['itemsPerPage'] : 10;
        $queryResult = $this->eventRepository->findNotFinishedOrderAsc($listItemsPerView + 1, $this->settings);

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
     * @param \RKW\RkwEvents\Domain\Model\Event $event Needed for ajax request e.g.
     * @param integer                           $page
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function listSimilarAction($event = null, $page = 0)
    {
        if (!$event instanceof Event) {
            $getParams = GeneralUtility::_GP('tx_rkwevents_pi1');
            $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
            /** @var Event $event */
            $event = $this->eventRepository->findByIdentifier(filter_var($eventUid, FILTER_SANITIZE_NUMBER_INT));
        }

        if ($event instanceof \RKW\RkwEvents\Domain\Model\Event) {
            $listItemsPerView = intval($this->settings['listSimilar']['itemsPerPage']) ? intval($this->settings['listSimilar']['itemsPerPage']) : 6;
            $queryResult = $this->eventRepository->findSimilar($event, $listItemsPerView, intval($page), $this->settings);
            $eventList = DivUtility::prepareResultsList($queryResult, $listItemsPerView);
            if ($this->settings['listSimilar']['showMoreLink']) {
                $showMoreLink = count($eventList) < count($queryResult) ? true : false;
            } else {
                $showMoreLink = false;
            }

            // target template is also used by ajax - so we have to set typoscript settings this way
            $this->view->assignMultiple(
                array(
                    'sortedEventList' => $eventList,
                    'ajaxTypeNum'     => intval($this->settings['ajaxTypeNum']),
                    'showPid'         => intval($this->settings['showPid']),
                    'pageMore'        => $page + 1,
                    'showMoreLink'    => $showMoreLink,
                    'currentEvent'    => $event
                )
            );
        }
    }


    /**
     * action archive
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function archiveAction()
    {
        // 1. get event list
        $listItemsPerView = intval($this->settings['itemsPerPage']) ? intval($this->settings['itemsPerPage']) : 10;
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
        $this->view->assign('ajaxTypeNum', intval($this->settings['ajaxTypeNum']));
        $this->view->assign('showPid', intval($this->settings['showPid']));
        $this->view->assign('pageMore', 1);
        $this->view->assign('showMoreLink', $showMoreLink);

    }


    /**
     * action show
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @return void
     * @ignorevalidation $event
     */
    public function showAction(\RKW\RkwEvents\Domain\Model\Event $event = null)
    {
        $this->handleContentNotFound($event);

        /*
        // Fallback: Using old "notAvailable" message INSIDE show action
        if (!$event instanceof \RKW\RkwEvents\Domain\Model\Event) {

            $uri = $this->uriBuilder->reset()
                ->setTargetPageUid($this->settings['listPid'])
                ->setCreateAbsoluteUri(true)
                ->build();

            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'eventController.message.error.notAvailable',
                    'rkw_events',
                    array(
                        0 => $uri
                    )
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
        }
        */

        $this->view->assign('event', $event);
    }


    /**
     * action showAddInfo
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @return void
     * @ignorevalidation $event
     */
    public function showAddInfoAction(\RKW\RkwEvents\Domain\Model\Event $event)
    {
        $this->view->assign('event', $event);
    }


    /**
     * action showSheet
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @return void
     * @ignorevalidation $event
     */
    public function showSheetAction(\RKW\RkwEvents\Domain\Model\Event $event)
    {
        $this->view->assign('event', $event);
    }


    /**
     * action showGalleryOne
     *
     * @return void
     */
    public function showGalleryOneAction()
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
    public function showGalleryTwoAction()
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
    public function mapsAction()
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
    public function infoAction()
    {
        $getParams = GeneralUtility::_GP('tx_rkwevents_pi1');
        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        $event = $this->eventRepository->findByUid($eventUid);

        //$this->handleContentNotFound($event);

        $this->view->assign('isReservationPage', 0);
        if (intval($GLOBALS['TSFE']->id) == intval($this->settings['reservationPid'])) {
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
    public function titleAction()
    {
        $getParams = GeneralUtility::_GP('tx_rkwevents_pi1');

        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        $event = $this->eventRepository->findByIdentifier(filter_var($eventUid, FILTER_SANITIZE_NUMBER_INT));

        //$this->handleContentNotFound($event);

        $this->view->assignMultiple(array(
            'event' => $event,
        ));

    }

    /**
     * action description
     * returns structured meta description in view
     *
     * @return string
     */
    public function descriptionAction()
    {
        $getParams = GeneralUtility::_GP('tx_rkwevents_pi1');

        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        $event = $this->eventRepository->findByIdentifier(filter_var($eventUid, FILTER_SANITIZE_NUMBER_INT));

        return trim(
            $this->view->assignMultiple([
                'event' => $event,
            ])->render()
        );

    }

    /**
     * action seriesProposals
     * returns manually selected events ("RecommendedEvents") or as fallback running events of a certain series
     *
     * Hint: Used as sidebar plugin since nov 2021
     *
     * @return void|string
     */
    public function seriesProposalsAction()
    {
        $getParams = GeneralUtility::_GP('tx_rkwevents_pi1');

        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        /** @var \RKW\RkwEvents\Domain\Model\Event $event */
        $event = $this->eventRepository->findByIdentifier(filter_var($eventUid, FILTER_SANITIZE_NUMBER_INT));

        if ($event instanceof \RKW\RkwEvents\Domain\Model\Event) {
            if ($event->getRecommendedEvents()->count()) {
                $eventList = $event->getRecommendedEvents();
            } else {
                // fallback
                if ($event->getSeries()->count()) {
                    $eventList = $this->eventRepository->findRunningBySeries($event);
                }
            }

            $this->view->assignMultiple(array(
                'sortedEventList'  => $eventList,
                'showPid' => intval($this->settings['showPid'])
            ));
        }

    }





    /**
     * handleContentNotFound
     * if event is not given (hidden or deleted) make 404 redirect
     * Hint: 404 seems not to working yet. Firefox and Chrome are repeating a 302
     *
     * @param mixed $event
     * @return void
     */
    protected function handleContentNotFound($event)
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
     * @return integer
     */
    protected function getFrontendUserId()
    {
        // is $GLOBALS set?
        if (
            ($GLOBALS['TSFE'])
            && ($GLOBALS['TSFE']->loginUser)
            && ($GLOBALS['TSFE']->fe_user->user['uid'])
        ) {
            return intval($GLOBALS['TSFE']->fe_user->user['uid']);
            //===
        }

        return false;
        //===
    }


    /**
     * Returns current logged in user object
     *
     * @return \RKW\RkwEvents\Domain\Model\FrontendUser|NULL
     */
    protected function getFrontendUser()
    {
        /** @var \RKW\RkwEvents\Domain\Repository\FrontendUserRepository $frontendUserRepository */
        //$frontendUserRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwEvents\\Domain\\Repository\\FrontendUserRepository');
        $this->frontendUser = $this->frontendUserRepository->findByIdentifier($this->getFrontendUserId());

        if ($this->frontendUser instanceof \TYPO3\CMS\Extbase\Domain\Model\FrontendUser) {
            return $this->frontendUser;
            //===
        }

        return null;
        //===
    }
}