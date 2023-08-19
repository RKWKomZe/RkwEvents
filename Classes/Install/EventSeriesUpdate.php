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
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Class SeoTitleUpdate
 */
class EventSeriesUpdate implements UpgradeWizardInterface
{
    public function getIdentifier(): string
    {
        return 'eventSeriesUpdate';
    }

    public function getTitle(): string
    {
        return 'RKW Events: Migrate Event to EventSeries';
    }

    public function getDescription(): string
    {
        return 'Migrate certain data from tx_rkwevents_domain_model_event to tx_rkwevents_domain_model_eventseries';
    }

    public function executeUpdate(): bool
    {
        $qbEventSeries = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_rkwevents_domain_model_eventseries');
        $qbEventSeries->getRestrictions()->removeAll();

        // First clear eventseries table!
        $qbEventSeries->delete('tx_rkwevents_domain_model_eventseries')
            ->where(
                $qbEventSeries->expr()->gt('uid', 0)
            )
            ->execute();



        $qbEvent = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_rkwevents_domain_model_event');
        $qbEvent->getRestrictions()->removeAll();

        $rows = $qbEvent->select('*')
            ->from('tx_rkwevents_domain_model_event')
            ->execute()
            ->fetchAll();

        foreach ($rows as $row) {

            // because the EventSeries will have the same uid as the original events, simply set the own UID as foreignId
            $qbEvent->update('tx_rkwevents_domain_model_event')
                ->set('series', $row['uid'])
                ->where(
                    $qbEvent->expr()->eq('uid', $qbEvent->createNamedParameter($row['uid'], Connection::PARAM_INT))
                )
                ->orderBy('uid')
                ->execute();


            // now create new EventSeries record
            $qbEvent->insert('tx_rkwevents_domain_model_eventseries')
                ->values([
                    // some system stuff
                    'sys_language_uid' => $row['sys_language_uid'],
                    'l10n_parent' => $row['l10n_parent'],
                    'l10n_diffsource' => $row['l10n_diffsource'],
                    'l10n_state' => $row['l10n_state'],
                    'tstamp' => $row['tstamp'],
                    'crdate' => $row['crdate'],
                    'cruser_id' => $row['cruser_id'],
                    'deleted' => $row['deleted'],
                    'hidden' => $row['hidden'],
                    // at the start we have always only 1:1 relation. Simply put the 1 into the field (for "has one record")
                    'event' => '1',
                    // custom extension fields
                    'uid' => $row['uid'],
                    'pid' => $row['pid'],
                    'record_type' => $row['record_type'],
                    'title' => $row['title'],
                    'subtitle' => $row['subtitle'],
                    'keywords' => $row['keywords'],
                    'description' => $row['description'],
                    'description2' => $row['description2'],
                    'target_learning' => $row['target_learning'],
                    'target_group' => $row['target_group'],
                    'schedule' => $row['schedule'],
                    'partner' => $row['partner'],
                    'add_info' => $row['add_info'],
                    'testimonials' => $row['testimonials'],
                    'reg_inhouse' => $row['reg_inhouse'],
                    'header_image' => $row['header_image'],
                    'additional_tile_flag' => $row['additional_tile_flag'],
                    'recommended_events' => $row['recommended_events'],
                    'recommended_links' => $row['recommended_links'],
                    'backend_user_exclusive' => $row['backend_user_exclusive'],
                    'document_type' => $row['document_type'],
                    'department' => $row['department'],
                    'categories_displayed' => $row['categories_displayed'],
                ])
                ->execute();

        }



        // update categories
        $qbSysCat = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category_record_mm');
        $qbSysCat->getRestrictions()->removeAll();
        $qbSysCat->update('sys_category_record_mm')
            ->set('tablenames', 'tx_rkwevents_domain_model_eventseries')
            ->where(
                $qbSysCat->expr()->eq('fieldname', $qbSysCat->createNamedParameter('categories')),
                $qbSysCat->expr()->eq('tablenames', $qbSysCat->createNamedParameter('tx_rkwevents_domain_model_event'))
            )
            ->execute();

        // update sysFileReferences
        $qbSysFileRef = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');
        $qbSysFileRef->getRestrictions()->removeAll();
        $qbSysFileRef->update('sys_file_reference')
            ->set('tablenames', 'tx_rkwevents_domain_model_eventseries')
            ->where(
                $qbSysFileRef->expr()->eq('fieldname', $qbSysFileRef->createNamedParameter('add_info', Connection::PARAM_STR)),
                $qbSysFileRef->expr()->eq('tablenames', $qbSysFileRef->createNamedParameter('tx_rkwevents_domain_model_event', Connection::PARAM_STR))
            )
            ->execute();


        return true;
    }

    public function updateNecessary(): bool
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $qb->getRestrictions()->removeAll();

        try {
            $qb->select('*')
                ->from('tx_rkwevents_domain_model_eventseries');
            // should work so far, the old eventseries has only a few records. The event tables over 1.000
            return $qb->execute()->rowCount() < 100;
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
