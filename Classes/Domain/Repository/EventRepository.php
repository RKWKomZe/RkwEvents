<?php

namespace RKW\RkwEvents\Domain\Repository;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
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
 * Class EventRepository
 * The repository for events
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * Logger
     *
     * @var \TYPO3\CMS\Core\Log\Logger
     */
    protected $logger;

    // Order by start date
    protected $defaultOrderings = array(
        'start' => QueryInterface::ORDER_ASCENDING,
    );


    /**
     * constraintBackendUserExclusive
     *
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\NotInterface
     */
    protected function constraintBackendUserExclusive()
    {
        $query = $this->createQuery();
        // if no backend user is logged in, exclude events with property backendUserExclusive = 1
        if (
            !$GLOBALS['BE_USER'] instanceof \TYPO3\CMS\Backend\FrontendBackendUserAuthentication
            && !$GLOBALS['BE_USER'] instanceof \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
        ) {
            return $query->logicalNot($query->equals('backendUserExclusive', 1));
        }
    }


    /**
     * Return not finished events
     *
     * @deprecated Seems not to be used at the moment
     *
     * @return mixed
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findNotFinished()
    {
        $query = $this->createQuery();

        return $query->matching(
            $query->greaterThanOrEqual('end', time()))
            ->execute();

    }


    /**
     * Return reservations of upcoming event
     *
     * @param integer $timeFrame
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findUpcomingEventsForReminder($timeFrame = 86400)
    {

        $query = $this->createQuery();

        $query->getQuerySettings()->setRespectStoragePage(false);

        return $query->matching(
            $query->logicalAnd(
                $query->logicalAnd(
                    $query->logicalAnd(
                        $query->logicalAnd(
                            $query->lessThanOrEqual('start', time() + $timeFrame),
                            $query->greaterThanOrEqual('start', time())
                        ),
                        $query->greaterThan('end', time())
                    )
                ),
                $query->equals('reminderMailTstamp', 0)
            )

        )->execute();
    }


    /**
     * Return reservations of past event
     *
     * @param integer $timeFrame
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findAllByPastEvents($timeFrame = 86400)
    {
        $query = $this->createQuery();

        // use end date, if end date is set. Else use start date
        return $query->matching(
            $query->logicalOr(
                $query->logicalAnd(
                    $query->lessThanOrEqual('end', time() - $timeFrame),
                    $query->greaterThan('end', 0)
                ),
                $query->logicalAnd(
                    $query->lessThanOrEqual('start', time() - $timeFrame),
                    $query->equals('end', 0)
                )
            )
        )->execute();
    }


    /**
     * Return reservations of past event, which have to sent their survey after
     *
     * @param integer $timeFrame
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findAllByPastEventsWithoutSentSurveyAfter($timeFrame = 86400)
    {
        $query = $this->createQuery();

        // use end date, if end date is set. Else use start date
        $query->matching(
            $query->logicalAnd(
                $query->logicalAnd(
                    $query->equals('surveyAfterMailTstamp', 0),
                    $query->greaterThan('surveyAfter', 0)
                ),
                $query->logicalOr(
                    $query->logicalAnd(
                        $query->lessThanOrEqual('end', time() - $timeFrame),
                        $query->greaterThan('end', 0)
                    ),
                    $query->logicalAnd(
                        $query->lessThanOrEqual('start', time() - $timeFrame),
                        $query->equals('end', 0)
                    )
                )
            )

        );

        return $query->execute();
    }


    /**
     * function findUpcomingByFeUser
     *
     * @deprecated
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser|\RKW\RkwEvents\Domain\Model\FrontendUser $feUser
     * @param bool $respectStoragePid
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findUpcomingByFeUser($feUser, $respectStoragePid = true)
    {
        $query = $this->createQuery();
        if (!$respectStoragePid) {
            $query->getQuerySettings()->setRespectStoragePage(false);
        }

        return $query->matching(
            $query->logicalAnd(
                $query->greaterThanOrEqual('start', time()),
                $query->equals('reservation.feUser', $feUser)
            )
        )->execute();
    }


    /**
     * Return started and former events by User
     *
     * @deprecated
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser|\RKW\RkwEvents\Domain\Model\FrontendUser $feUser
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findStartedAndFinishedByUser($feUser)
    {
        $query = $this->createQuery();

        return $query->matching(
            $query->logicalAnd(
                $query->logicalOr(
                    $query->logicalAnd(
                        $query->lessThan('end', time()),
                        $query->greaterThan('end', 0)
                    ),
                    $query->lessThanOrEqual('start', time())
                ),
                $query->equals('reservation.feUser', $feUser)
            )
        )->execute();
    }


    /**
     * findByFilterOptions
     *
     * @param array $filter
     * @param int $limit
     * @param int $page
     * @param boolean $archive
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findByFilterOptions($filter, $limit, $page, $archive = false)
    {

        // results between 'from' and 'till' (with additional proof item to check, if there are more results -> +1)
        $offset = $page * $limit;
        $limit = $limit + 1;

        // if we are on a page > 1, we also fetch none item twice
        // we need this to figure out which date was the last for grouping!
        if ($page > 0) {
            $offset--;
            $limit++;
        }

        $query = $this->createQuery();

        //always
        $constraints[] =
            $query->logicalNot(
                $query->equals('title', '')
            );

        $constraints[] = $this->constraintBackendUserExclusive();

        $geoData = null;

        $categoryList = [];
        if ($filter['category']) {
            // get category first level childs
            /** @var CategoryRepository $categoryRepository */
            $categoryRepository = GeneralUtility::makeInstance(CategoryRepository::class);
            $categoryList = $categoryRepository->findChildrenByParent(intval($filter['category']))->toArray();
            // add parent itself as object
            $categoryList[] = $categoryRepository->findByUid(intval($filter['category']));
        }

        // a) Either: SQL statement
        // if: filter option "address" is filled (for proximity search)
        if ($filter['address']) {

            /** @var \RKW\RkwGeolocation\Service\Geolocation $geoLocation */
            $geoLocation = GeneralUtility::makeInstance('RKW\\RkwGeolocation\\Service\\Geolocation');
            $geoLocation->setAddress(filter_var($filter['address'], FILTER_SANITIZE_STRING));

            try {

                $departmentFilter = '';
                if ($filter['department']) {
                    $departmentFilter = ' AND department = ' . intval($filter['department']);
                }

                $documentTypeFilter = '';
                if ($filter['documentType']) {
                    $documentTypeFilter = ' AND document_type = ' . intval($filter['documentType']);
                }

                $categoryFilter = '';
                if ($filter['category']) {
                    //$categoryFilter = ' AND uid IN (SELECT uid_foreign FROM sys_category_record_mm WHERE tablenames = "tx_rkwevents_domain_model_event" and fieldname="categories" AND uid_local=' . intval($filter['category']) . ')';
                    $categoryUidList = [];
                    foreach ($categoryList as $category) {
                        $categoryUidList[] = $category->getUid();
                    }

                    $categoryFilter = ' AND uid IN (SELECT uid_foreign FROM sys_category_record_mm WHERE tablenames = "tx_rkwevents_domain_model_event" and fieldname="categories" AND uid_local IN ' . implode(',', $categoryUidList) . ')';
                }

                $projectFilter = '';
                if (
                    (ExtensionManagementUtility::isLoaded('rkw_projects'))
                    && ($filter['project'])
                    && ($projectUids = GeneralUtility::trimExplode(',', $filter['project'], true))
                ) {
                    $projectFilter = ' AND project IN (' . implode(', ', $projectUids) . ')';
                }

                // filter by start/end time (OR -> includes announcements)
                $timeFilter = ' AND (end >= ' . time() . ' OR start = 0)';
                if ($archive) {
                    $timeFilter = ' AND (end < ' . time() . ')';
                }

                $onlyOnlineEvents = '';
                if ($filter['onlyOnlineEvents']) {
                    $onlyOnlineEvents = ' AND online_event = 1 AND place = ""';
                }

                $freeOfChargeFilter = '';
                if ($filter['freeOfCharge']) {
                    $freeOfChargeFilter = ' AND costs_reg = 0.00';
                }

                $eligibilityFilter = '';
                if ($filter['eligible']) {
                    $eligibilityFilter = ' AND eligibility = 1';
                }

                $andWhere = $departmentFilter . $documentTypeFilter . $categoryFilter . $projectFilter . $timeFilter . $onlyOnlineEvents . $freeOfChargeFilter . $eligibilityFilter . ' AND pid IN (' . implode(', ', $this->getStoragePid()) . ')';

                $geoLocation->getQueryStatementDistanceSearch($query, 'tx_rkwevents_domain_model_event', $limit, $offset, $andWhere, 'distance ASC, start ASC');

            } catch (\Exception $e) {
                // api request failed
                $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::ERROR, sprintf('RkwGeolocation call failed in EventRepository: %s.', $e->getMessage()));
            }


            // b) OR: query constraints (THIS PART IS IN PRINCIPLE DEPRECATED WITH THE STATEMENT ABOVE.
            // BUT: WE NOT ALWAYS NEED A RADIUS SEARCH! (performance))
        } else {

            if ($archive) {

                // 1. Sort by end-date
                $query->setOrderings(
                    array(
                        'start' => QueryInterface::ORDER_DESCENDING,
                    )
                );

                // 2. filter by time
                $constraints[] = $query->lessThan('end', time());

            } else {

                // 1. Sort by end-date
                $query->setOrderings(
                    array(
                        'record_type' => QueryInterface::ORDER_DESCENDING,
                        'start' => QueryInterface::ORDER_ASCENDING,
                    )
                );

                // 2. filter by time
                $constraints[] =
                    $query->logicalOr(
                        $query->greaterThanOrEqual('end', time()),
                        // include announcements (without start date)
                        $query->equals('start', 0)
                    );

            }

            // 3. additional filter options
            if ($filter['department']) {
                $constraints[] = $query->equals('department', intval($filter['department']));
            }
            if ($filter['documentType']) {
                $constraints[] = $query->equals('documentType', intval($filter['documentType']));
            }
            if ($filter['category']) {
                $categoryQueries = [];
                foreach ($categoryList as $category) {
                    $categoryQueries[] = $query->contains('categories', $category);
                }
                $constraints[] = $query->logicalOr($categoryQueries);
            }
            // additional filter options
            if ($filter['time']) {
                $month = date("M", intval($filter['time']));
                $year = date("Y", intval($filter['time']));
                $lastDayOfMonthTimestamp = strtotime('last day of ' . $year . '-' . $month);

                $constraints[] = $query->logicalAnd(
                    $query->greaterThanOrEqual('end', intval($filter['time'])),
                    $query->lessThanOrEqual('end', $lastDayOfMonthTimestamp)
                );

            }
            if ($filter['recordType']) {
                $constraints[] = $query->equals('recordType', '\RKW\RkwEvents\Domain\Model\\' . $filter['recordType']);
            }

            if ($filter['onlyStarted']) {
                $constraints[] = $query->lessThanOrEqual('start', time());
            }

            if ($filter['onlyUpcoming']) {
                $constraints[] = $query->logicalOr(
                    $query->greaterThanOrEqual('start', time()),
                    // include announcements (without start date)
                    $query->equals('start', 0)
                );
            }
            if (
                (ExtensionManagementUtility::isLoaded('rkw_projects'))
                && ($filter['project'])
                && ($projectUids = GeneralUtility::trimExplode(',', $filter['project'], true))
            ) {
                $constraints[] = $query->in('project', $projectUids);
            }
            if ($filter['onlyOnlineEvents']) {
                $constraints[] = $query->equals('online_event', 1);
                // for secure, if an event has both information. Then place has priority (Event with place is no online event)
                $constraints[] = $query->equals('place', '');
            }
            if ($filter['freeOfCharge']) {
                $constraints[] = $query->equals('costs_reg', '0.00');
            }
            if ($filter['eligible']) {
                $constraints[] = $query->equals('eligibility', 1);
            }

            // NOW: construct final query!
            $query->matching($query->logicalAnd(array_filter($constraints)));
            $query->setOffset($offset);
            $query->setLimit($limit);
        }

        // Hint: if no query is added, this dataset is equal to findAll() with sort & date restriction
        $result = $query->execute();

        return $result;
    }


    /**
     * Return not finished events
     * For the FIRST result page (just with simple limit)
     *
     * @param int $limit
     * @param array $settings
     * @param string $recordType only announcements or scheduled
     * @param bool $onlyStarted if true only started events are shown
     * @param bool $onlyUpcoming if true only upcoming (not started) events are shown
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findNotFinishedOrderAsc($limit, $settings = array(), $recordType = '', $onlyStarted = false, $onlyUpcoming = false)
    {
        $query = $this->createQuery();

        $constraints[] =
            $query->logicalOr(
                $query->greaterThanOrEqual('end', time()),
                // include announcements (without start date)
                $query->equals('start', 0)
            );
        $constraints[] =
            $query->logicalNot(
                $query->equals('title', '')
            );

        // backendUser special case
        $constraints[] = $this->constraintBackendUserExclusive();

        if (
            ($settings['eventUids'])
            && ($eventUids = GeneralUtility::trimExplode(',', $settings['eventUids'], true))
        ) {
            $constraints[] = $query->in('uid', $eventUids);
        }

        if ((ExtensionManagementUtility::isLoaded('rkw_projects'))) {
            if (
                ($settings['projectUids'])
                && ($projectUids = GeneralUtility::trimExplode(',', $settings['projectUids'], true))
            ) {
                $constraints[] = $query->in('project', $projectUids);
            }
        }

        // disable storagePid for some cases
        if (
            ($settings['eventUids'])
            || ($settings['projectUids'])
        ) {
            $query->getQuerySettings()->setRespectStoragePage(false);
        }

        if ($recordType) {
            $constraints[] = $query->equals('recordType', $recordType);
        }

        if ($onlyStarted) {
            $constraints[] = $query->lessThanOrEqual('start', time());
        }

        if ($onlyUpcoming) {
            $constraints[] =  $query->logicalOr(
                $query->greaterThanOrEqual('start', time()),
                // include announcements (without start date)
                $query->equals('start', 0)
            );
        }

        return $query->matching(
            $query->logicalAnd(array_filter($constraints))
        )
            ->setOrderings(
                array(
                    'record_type' => QueryInterface::ORDER_DESCENDING,
                    'start' => QueryInterface::ORDER_ASCENDING,
                    // this is a "fix" for unequal list behavior between the multipart view and the standard list view
                    'tstamp' => QueryInterface::ORDER_ASCENDING,
                )
            )
            ->setLimit($limit)
            ->execute();
    }


    /**
     * Return finished events
     * For the FIRST result page (just with simple limit)
     *
     * @param int $limit
     * @param array $settings
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findFinishedOrderAsc($limit, $settings = array())
    {

        $query = $this->createQuery();
        $constraints[] = $query->lessThan('end', time());
        $constraints[] = $query->logicalNot($query->equals('title', ''));

        $constraints[] = $this->constraintBackendUserExclusive();

        if (
            (ExtensionManagementUtility::isLoaded('rkw_projects'))
            && ($settings['projectUids'])
            && ($projectUids = GeneralUtility::trimExplode(',', $settings['projectUids'], true))
        ) {
            $constraints[] = $query->in('project', $projectUids);
        }

        return $query->matching(
            $query->logicalAnd(array_filter($constraints))
        )
            ->setOrderings(
                array(
                    'start' => QueryInterface::ORDER_DESCENDING,
                )
            )
            ->setLimit($limit)
            ->execute();
    }


    /**
     * Find event by title and start
     * Used by CSV-importer
     *
     * @param string $title
     * @param integer $start
     * @return \RKW\RkwEvents\Domain\Model\Event
     */
    public function findOneByTitleAndStart($title, $start)
    {
        $query = $this->createQuery();

        return $query->matching(
            $query->logicalAnd(
                $query->equals('title', trim($title)),
                $query->equals('start', intval($start))
            )
        )->execute()->getFirst();
    }


    /**
     * findRunningBySeries
     * Find running events by series (without given event!)
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @return mixed
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findRunningBySeries($event)
    {
        $query = $this->createQuery();

        // @toDo: If the given $event has no series, all events without series are retrieved. Is this okay as a kind of fallback?

        $constraints[] =
            $query->logicalAnd(
                $query->equals('series', $event->getSeries()),
                $query->logicalOr(
                    $query->greaterThanOrEqual('end', time()),
                    // include announcements (without start date)
                    $query->equals('start', 0)
                ),
                $query->logicalNot(
                    $query->equals('uid', $event)
                )
            );

        $constraints[] = $this->constraintBackendUserExclusive();

        return $query->matching($query->logicalAnd(array_filter($constraints)))->execute();
    }

    /**
     * function findHiddenByUid
     *
     * @param \RKW\RkwEvents\Domain\Model\Event|int $event
     * @param bool $respectStoragePid
     * @return \RKW\RkwEvents\Domain\Model\Event
     */
    public function findHiddenByUid($event, $respectStoragePid = true)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);

        if (!$respectStoragePid) {
            $query->getQuerySettings()->setRespectStoragePage(false);
        }

        return $query->matching(
            $query->equals('uid', $event)
        )->execute()->getFirst();
    }


    /**
     * Find all events that have been updated recently
     *
     * @api Used by SOAP-API
     * @param integer $timestamp
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findByTimestamp($timestamp)
    {

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setIncludeDeleted(true);
        $query->getQuerySettings()->setIgnoreEnableFields(true);

        $query->matching(
            $query->greaterThanOrEqual('tstamp', intval($timestamp))
        );
        $query->setOrderings(array('tstamp' => QueryInterface::ORDER_ASCENDING));

        return $query->execute();
    }

    /**
     * findByFilterOptions
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @param int $limit
     * @param int $page
     * @param array $settings
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findSimilar($event, $limit, $page, $settings)
    {
        // results between 'from' and 'till' (with additional proof item to check, if there are more results -> +1)
        $offset = $page * $limit;
        $limit = $limit + 1;

        // if we are on a page > 1, we also fetch none item twice
        // we need this to figure out which date was the last for grouping!
        if ($page > 0) {
            $offset--;
            $limit++;
        }

        $query = $this->createQuery();

        // query basics as "AND" query
        // !! do NOT show already running events !!
        $constraints[] =
            $query->logicalOr(
                $query->greaterThanOrEqual('start', time()),
                // include announcements (without start date)
                $query->equals('start', 0)
            );
        $constraints[] =
            $query->logicalNot(
                $query->equals('title', '')
            );

        $constraints[] = $this->constraintBackendUserExclusive();

        $query->setOrderings(
            array(
                'record_type' => QueryInterface::ORDER_DESCENDING,
                'start' => QueryInterface::ORDER_ASCENDING,
            )
        );

        // exclude given event
        $constraints[] = $query->logicalNot(
            $query->equals('uid', $event)
        );

        // exclude given event series
        if ($event->getSeries()->count()) {
            $constraints[] = $query->logicalNot(
                $query->equals('series', $event->getSeries())
            );
        }

        // exclude manually set recommendations (returned through other plugin "seriesProposals")
        if ($event->getRecommendedEvents()->count()) {
            $constraints[] = $query->logicalNot(
                $query->in('uid', $event->getRecommendedEvents())
            );
        }

        // for all options which could be part of the "similar query"
        // START: SubQuery
        $constraintsSubQueryOr = array();

        if (
            $settings['listSimilar']['searchQuery']['byDepartment']
            && $event->getDepartment()
        ) {
            $constraintsSubQueryOr[] = $query->equals('department', $event->getDepartment());
        }
         if (
             $settings['listSimilar']['searchQuery']['byDocumentType']
             && $event->getDocumentType())
         {
             $constraintsSubQueryOr[] = $query->equals('documentType', $event->getDocumentType());
         }
         if (
             $settings['listSimilar']['searchQuery']['byCategories']
             && $event->getCategories()->count())
         {
             $categoryQueries[] = $query->in('categories', $event->getCategories());
             $constraintsSubQueryOr[] = $query->logicalOr($categoryQueries);
         }
         if (
             ExtensionManagementUtility::isLoaded('rkw_projects')
             && $settings['listSimilar']['searchQuery']['byProject']
             && $event->getProject()
         ) {
             $constraintsSubQueryOr[] = $query->equals('project', $event->getProject());
         }

         // fallback, if nothing is set
         // avoid error: "There must be at least one constraint or a non-empty array of constraints given. "
         if (!$constraintsSubQueryOr) {
             // give something in
             $constraintsSubQueryOr[] = $query->equals('uid', 0);
         }
        // END: SubQuery

        // add OR elements as subQuery
        $constraints[] = $query->logicalOr($constraintsSubQueryOr);

        // NOW: construct final query!
        $query->matching($query->logicalAnd(array_filter($constraints)));
        $query->setOffset($offset);
        $query->setLimit($limit);

        return $query->execute();
    }

    /**
     * Returns logger instance
     *
     * @return \TYPO3\CMS\Core\Log\Logger
     */
    protected function getLogger()
    {

        if (!$this->logger instanceof \TYPO3\CMS\Core\Log\Logger) {
            $this->logger = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Log\\LogManager')->getLogger(__CLASS__);
        }

        return $this->logger;
    }


    /**
     * calculateDistance between two geo data
     *
     * @param float|int $lat1 Lat for point 1
     * @param float|int $lng1 Lng for point 1
     * @param float|int $lat2 Lat for point 2
     * @param float|int $lng1 Lng for point 2
     * @return float|int
     * @deprecated since 23-02-2017
     */
    protected function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {

        $earthRadius = 6378.16;

        $dlng = self::radians($lng2 - $lng1);
        $dlat = self::radians($lat2 - $lat1);
        $a = pow(sin($dlat / 2), 2) + cos(self::radians($lat1)) * cos(self::radians($lat2)) * pow(sin($dlng / 2), 2);
        $angle = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $result = $angle * $earthRadius;

        return $result < 10 ? round($result, 1) : round($result);
    }


    /**
     * radians
     *
     * @param $x
     * @return float|int
     * @deprecated since 23-02-2017
     */
    protected function radians($x)
    {
        return $x * pi() / 180;
    }


    /**
     * Returns storage pid of repository
     *
     * @return array
     * @throws \Exception
     * @throws \RuntimeException
     */
    protected function getStoragePid()
    {
        $settings = $this->getTsForPage(intval($GLOBALS['TSFE']->id));
        $storagePidString = $settings['persistence.']['storagePid'];
        // $settings['persistence.']['storagePid'] can be empty and simply defined as "Record Storage Page" in the plugins content element!
        if (!$storagePidString) {
            // find by PID and list_type rkwevents_pi1
            $mres = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'pages',
                'tt_content',
                'pid=' . intval($GLOBALS['TSFE']->id) . ' AND list_type="rkwevents_pi1" AND deleted = 0 AND hidden = 0'
            );
            while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($mres)) {
                $storagePidString = $row['pages'];
            }
        }

        // @toDo: Set fallback PID 1, to avoid any error? (could be confusing on problems while development)
        return GeneralUtility::trimExplode(',', $storagePidString);
    }


    /**
     * Return TS-Settings for given pid
     *
     * @param $pageId
     * @return array
     * @throws \Exception
     * @throws \RuntimeException
     */
    private function getTsForPage($pageId)
    {
        /** @var \TYPO3\CMS\Core\TypoScript\TemplateService $template */
        $template = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TypoScript\\TemplateService');
        $template->tt_track = 0;
        $template->init();

        /** @var \TYPO3\CMS\Frontend\Page\PageRepository $sysPage */
        $sysPage = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');
        $rootLine = $sysPage->getRootLine(intval($pageId));
        $template->runThroughTemplates($rootLine, 0);
        $template->generateConfig();

        return $template->setup['plugin.']['tx_rkwevents.'];
    }
}