<?php
declare(strict_types=1);
namespace RKW\RkwEvents\Install;

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

use Doctrine\DBAL\Exception\InvalidFieldNameException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Class EventSeriesTitleUpdate
 */
class EventSeriesTitleUpdate implements UpgradeWizardInterface
{
    public function getIdentifier(): string
    {
        return 'eventSeriesTitleUpdate';
    }

    public function getTitle(): string
    {
        return 'RKW Events: EventSeries Title Update';
    }

    public function getDescription(): string
    {
        return 'Adds newest event title to new eventSeries field';
    }

    public function executeUpdate(): bool
    {

        $tableNameEvent = 'tx_rkwevents_domain_model_event';
        $connectionEventTable = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($tableNameEvent);

        // get all events, group by series: Get only the newest event of every series (ignores announcements)
        $eventList = $connectionEventTable
            ->select('uid', 'start', 'series')
            ->from($tableNameEvent)
            // get event with the highest date
            ->where(
                $connectionEventTable->expr()->neq(
                    'start',
                    $connectionEventTable->createNamedParameter(0, \PDO::PARAM_INT)
                ),
                $connectionEventTable->expr()->neq(
                    'series',
                    $connectionEventTable->createNamedParameter(0, \PDO::PARAM_INT)
                )
            )
            ->orderBy('start', 'DESC')
            ->groupBy('series')
            ->execute();

        $tableNameEventSeries = 'tx_rkwevents_domain_model_eventseries';
        $connectionEventSeriesTable = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($tableNameEventSeries);

        // now set the date of every event to its series
        while ($event = $eventList->fetchAssociative()) {

            if (key_exists('start', $event)) {
                $connectionEventSeriesTable->update(
                    $tableNameEventSeries,
                    [
                        'event_start_date' => $event['start'],
                    ],
                    ['uid' => (int) $event['series']]
                );
            }

        }

        return true;
    }


    /**
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function updateNecessary(): bool
    {

        // @toDo: Check if new eventSeries.event_start_date is filled

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $qb->getRestrictions()->removeAll();

        try {
            $qb->select('uid', 'event_start_date')
                ->from('tx_rkwevents_domain_model_eventseries')
                ->where(
                    $qb->expr()->neq(
                        'event_start_date',
                        $qb->createNamedParameter(0, \PDO::PARAM_INT)
                    ),
                )
            ;

            // return true, if there are rows without event_start_date
            return $qb->execute()->rowCount() > 0;
        } catch (InvalidFieldNameException $e) {
            // Not needed to update when the old column doesn't exist
            return false;
        }
    }

    public function getPrerequisites(): array
    {
        return [];
    }
}
