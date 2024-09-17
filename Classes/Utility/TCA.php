<?php

namespace RKW\RkwEvents\Utility;

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

use RKW\RkwEvents\Domain\Repository\EventRepository;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * TCA utility
 *
 * @author Christian Dilger <c.dilger@addorange.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class TCA
{

    /**
     * Returns the title with the start of the first contained event
     */
    public function eventSeriesTitle(&$parameters, $parentObject): void
    {
        $eventSeriesUid = $parameters['row']['uid'];

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ObjectManager::class);
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

}
