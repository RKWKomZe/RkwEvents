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

use Madj2k\CoreExtended\Utility\GeneralUtility;
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
     * Returns the title prefixed by the start of the first contained event
     */
    public function eventSeriesTitle(&$parameters, $parentObject): void
    {
        $eventSeriesUid = $parameters['row']['uid'];
        $events = $this->getEvents($eventSeriesUid);

        if (!empty($events)) {
            $startDates = [];
            foreach ($events as $eventRecord) {
                if ($eventRecord['start'] > 0) {
                    $startDates[] = $eventRecord['start'];
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
     * Getting the events of the eventSeries record
     *
     * @param int $eventSeriesUid
     * @return array
     */
    protected function getEvents($eventSeriesUid): array
    {
        $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
            ->getQueryBuilderForTable('tx_rkwevents_domain_model_event');

        return $queryBuilder
            ->select('*')
            ->from('tx_rkwevents_domain_model_event')
            ->where(
                $queryBuilder->expr()->eq(
                    'series',
                    $queryBuilder->createNamedParameter($eventSeriesUid, \PDO::PARAM_INT)
                )
            )
            ->execute()
            ->fetchAll();
    }

}
