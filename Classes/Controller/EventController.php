<?php

namespace RKW\RkwEvents\Controller;

use RKW\RkwEvents\Utility\DivUtility;
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
class EventController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
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
     * @var \RKW\RkwBasics\Domain\Repository\DepartmentRepository
     * @inject
     */
    protected $departmentRepository = null;

    /**
     * documentTypeRepository
     *
     * @var \RKW\RkwBasics\Domain\Repository\DocumentTypeRepository
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
     * @return void
     */
    public function listAction()
    {
        // 1. get event list
        $listItemsPerView = intval($this->settings['itemsPerPage']) ? intval($this->settings['itemsPerPage']) : 10;
        $queryResult = $this->eventRepository->findNotFinishedOrderAsc($listItemsPerView + 1, $this->settings);

        // 2. proof if we have further results (query with listItemsPerQuery + 1)
        $eventList = DivUtility::prepareResultsList($queryResult, $listItemsPerView);
        $showMoreLink = count($eventList) < count($queryResult) ? true : false;

        // 3. get department and document list (for filter)
        $departmentList = $this->departmentRepository->findAllByVisibility();
        $documentTypeList = $this->documentTypeRepository->findAllByTypeAndVisibility('events', false);
        $categoryList = $this->categoryRepository->findChildrenByParent(intval($this->settings['parentCategoryForFilter']));

        // 4. sort event list (group by month)
        $sortedEventList = DivUtility::groupEventsByMonth($eventList);

        $this->view->assignMultiple(
            array(
                'filter'           => array('project' => $this->settings['projectUids']),
                'sortedEventList'  => $sortedEventList,
                'departmentList'   => $departmentList,
                'documentTypeList' => $documentTypeList,
                'categoryList'     => $categoryList,
                'page',
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

    /**
     * action listSimple
     *
     * @return void
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
        $sortedEventList = DivUtility::groupEventsByMonth($eventList);
        $noGrouping = true;
        $this->view->assignMultiple(
            [
                'filter'           => [
                    'project' => $this->settings['projectUids'],
                    'noGrouping' => $noGrouping,
                ],
                'sortedEventList'  => $sortedEventList,
                'noGrouping'       => $noGrouping,
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
                'noGrouping'       => true,
            ]
        );
    }

    /**
     * action archive
     *
     * @return void
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

        // 4. sort event list (group by month)
        $sortedEventList = DivUtility::groupEventsByMonth($eventList);

        // archive parameter
        $this->view->assign('archive', 1);

        $this->view->assign('sortedEventList', $sortedEventList);
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
        $getParams = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_rkwevents_pi1');
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
        $getParams = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_rkwevents_pi1');
        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        $event = $this->eventRepository->findByUid($eventUid);

        $this->view->assign('listPid', $this->settings['listPid']);
        $this->view->assign('event', $event);
        $this->view->assign('frontendUser', $this->getFrontendUser());
    }


    /**
     * action maps
     * Added by Maximilian Fäßler | FäßlerWeb
     *
     * @return void
     */
    public function mapsAction()
    {
        $getParams = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_rkwevents_pi1');
        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        $event = $this->eventRepository->findByUid($eventUid);

        $this->view->assign('event', $event);
    }


    /**
     * action info
     * Added by Maximilian Fäßler | FäßlerWeb
     *
     * @return void
     */
    public function infoAction()
    {
        $getParams = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_rkwevents_pi1');
        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        $event = $this->eventRepository->findByUid($eventUid);

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
        $getParams = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_rkwevents_pi1');

        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        $event = $this->eventRepository->findByIdentifier(filter_var($eventUid, FILTER_SANITIZE_NUMBER_INT));

        $this->view->assignMultiple(array(
            'event' => $event,
        ));

    }


    /**
     * action seriesProposals
     * returns running events of a certain series
     *
     * @return void
     */
    public function seriesProposalsAction()
    {
        $getParams = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_rkwevents_pi1');

        $eventUid = preg_replace('/[^0-9]/', '', $getParams['event']);
        /** @var \RKW\RkwEvents\Domain\Model\Event $event */
        $event = $this->eventRepository->findByIdentifier(filter_var($eventUid, FILTER_SANITIZE_NUMBER_INT));

        $eventList = $this->eventRepository->findRunningBySeries($event);

        // !! Die Variable $event darf nicht als "event" übergeben werden, sonst haben wir duplizierte Paramter !!
        $this->view->assignMultiple(array(
            'givenEvent' => $event,
            'eventList'  => $eventList,
        ));

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