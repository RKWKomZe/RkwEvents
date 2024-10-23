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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
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
     * Returns the title with the start of the first contained event
     */
    public function eventSeriesTitle(&$parameters, $parentObject): void
    {
        $eventSeriesUid = $parameters['row']['uid'];

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var EventRepository $eventRepository */
        $eventRepository = $objectManager->get(EventRepository::class);

        $events = $eventRepository->findAllBySeries($eventSeriesUid);

        if (count($events)) {
            $startDates = [];
            /** @var \RKW\RkwEvents\Domain\Model\Event $event */
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

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var EventRepository $eventRepository */
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
    }

}
