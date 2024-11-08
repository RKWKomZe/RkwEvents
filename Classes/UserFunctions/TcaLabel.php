<?php

namespace RKW\RkwEvents\UserFunctions;

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

use Doctrine\DBAL\Driver\Exception;
use RKW\RkwEvents\Domain\Model\Event;
use RKW\RkwEvents\Domain\Model\EventPlace;
use RKW\RkwEvents\Domain\Model\EventSeries;
use RKW\RkwEvents\Domain\Repository\EventRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * TCA
 *
 * @author Christian Dilger <c.dilger@addorange.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class TcaLabel
{

    /**
     *
     * @deprecated We can't use that function, neither the old nor the new way
     * -> because we can't set the "series"-property inside the "events"-TCA to "passthrough", we got a massive ....
     * -> ... overload on loading an "EventSeries"-Record via TCA (Backend) because of bidirectional relation
     * -> Because of the bidirectional relation this function will call multiple times on loading an EvenSeries-Record
     *
     *  (new)Returns the title with the start of the most current event
     *  (old) Returns the title with the start of the first contained event
     *
     */
    public function eventSeriesTitle(&$parameters, $parentObject): void
    {
        $eventSeriesUid = $parameters['row']['uid'];
        $eventSeriesTitle = $parameters['row']['title'];


        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $eventRepository = $objectManager->get(EventRepository::class);

        $events = $eventRepository->findAllBySeries($eventSeriesUid);

        if (count($events)) {
            $startDates = [];
            foreach ($events as $event) {
                if ($event->getStart() > 0) {
                    $startDates[] = $event->getStart();
                }
            }

            sort($startDates);

            $startDate = date('d.m.Y', (int)$startDates[0]);

            $suffix = (count($startDates) > 1)
                ? LocalizationUtility::translate(
                    'LLL:EXT:rkw_events/Resources/Private/Language/locallang_db.xlf:tx_rkwevents_domain_model_eventseries.from',
                    'rkw_events',
                    [$startDate])
                : $startDate;

            $parameters['title'] = $parameters['row']['title'] . ' (' . $suffix . ')';
        }

/*
        $tableName = 'tx_rkwevents_domain_model_event';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableName);
        $result = $queryBuilder
            ->select('uid', 'start')
            ->from($tableName)
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($eventSeriesUid, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->neq(
                    'start',
                    $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                ),
            )
            ->orWhere(
                $queryBuilder->expr()->gt(
                    'end',
                    $queryBuilder->createNamedParameter(time(), \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'start',
                    $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                ),
            )
            ->orderBy('start', 'DESC')
            ->setMaxResults(1)
            ->execute();

        while ($event = $result->fetchAssociative()) {
            if (
                is_array($event)
                && key_exists('start', $event)
            ) {
                // put it into the result set
                $parameters['title'] = $eventSeriesTitle . ' (' . date('d.m.Y', (int)$event['start']) . ')';
            }
        }

        // Fallback
        if (empty($parameters['title'])) {
            $parameters['title'] = $eventSeriesTitle;
        }
        */

    }


    /**
     * eventTitle: ##startDate##title##place
     *
     * Returns the title with the start of the first contained event
     * @throws Exception
     */
    public function eventTitle(&$parameters, $parentObject): void
    {
        $eventUid = $parameters['row']['uid'];

        /*
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $eventRepository = $objectManager->get(EventRepository::class);

        $event = $eventRepository->findByIdentifier($eventUid);

        if ($event instanceof Event) {

            $eventTitle = [];

            // if startDate is set? (be aware of announcements)
            if ($event->getStart() > 0) {
                $eventTitle[] = date("Y-m-d H:i", $event->getStart());
            }

            // is place already set, unknown, or an online event?
            if (
                $event->getPlace() instanceof EventPlace
                && $event->getPlace()->getName()
                && $event->getPlace()->getCity()
            ) {
                //$eventTitle[] = $event->getPlace()->getName() . ', ' . $event->getPlace()->getCity();
                $eventTitle[] = $event->getPlace()->getCity();
            } elseif ($event->getOnlineEvent()) {
                // @toDo: Does we need this?
                //$eventTitle[] = 'Online Event';
            }

            // Set title
            // prevent issues for new records (which are not persistent and having no EventSeries relation yet)
            if ($event->getSeries() instanceof EventSeries) {
                $eventTitle[] = $event->getSeries()->getTitle();
            }



            // assemble title only if there is something inside the array. Otherwise, do nothing.
            if (count($eventTitle)) {
                $parameters['title'] = implode('; ', $eventTitle);
            }
        }
        */


        // Alternative solution: Three DB-Queries with minimalistic output
        // (seems to be faster)
        $eventRecord = [];
        $placeRecord = [];
        $seriesRecord = [];

        // 1. Get event
        $tableName = 'tx_rkwevents_domain_model_event';
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableName);
        $result = $queryBuilder
            ->select('uid', 'place', 'series', 'start')
            ->from($tableName)
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($eventUid, \PDO::PARAM_INT)
                ),
            )
            ->execute();

        while ($event = $result->fetchAssociative()) {
            $eventRecord = $event;
        }

        // 2. Get place
        if (key_exists('place', $eventRecord)) {

            $tableName = 'tx_rkwevents_domain_model_eventplace';
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableName);
            $result = $queryBuilder
                ->select('uid', 'name', 'city')
                ->from($tableName)
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($eventRecord['place'], \PDO::PARAM_INT)
                    ),
                )
                ->execute();

            while ($eventPlace = $result->fetchAssociative()) {
                $placeRecord = $eventPlace;
            }
        }

        // 3. Get series (for title)
        if (key_exists('series', $eventRecord)) {

            $tableName = 'tx_rkwevents_domain_model_eventseries';
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableName);
            $result = $queryBuilder
                ->select('uid', 'title')
                ->from($tableName)
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($eventRecord['series'], \PDO::PARAM_INT)
                    ),
                )
                ->execute();

            while ($eventSeries = $result->fetchAssociative()) {
                $seriesRecord = $eventSeries;
            }
        }


        $eventTitle = [];

        // if startDate is set? (be aware of announcements)
        if ($eventRecord['start'] > 0) {
            $eventTitle[] = date("Y-m-d H:i", $eventRecord['start']);
        }

        // is place already set, unknown, or an online event?
        if (!empty($placeRecord['city'])) {
            $eventTitle[] = $placeRecord['city'];
        }

        // Set title
        // prevent issues for new records (which are not persistent and having no EventSeries relation yet)
        if (!empty($seriesRecord['title'])) {
            $eventTitle[] = $seriesRecord['title'];
        }

        // assemble title only if there is something inside the array. Otherwise, do nothing.
        if (count($eventTitle)) {
            $parameters['title'] = implode('; ', $eventTitle);
        }

    }

}
