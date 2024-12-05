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
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Class SeoTitleUpdate
 */
class EventSeriesHeaderImagesUpdate implements UpgradeWizardInterface
{
    public function getIdentifier(): string
    {
        return 'eventSeriesHeaderImagesUpdate';
    }

    public function getTitle(): string
    {
        return 'RKW Events: Migrate header images from Event to EventSeries';
    }

    public function getDescription(): string
    {
        return 'Migrate missing header images from tx_rkwevents_domain_model_event to tx_rkwevents_domain_model_eventseries';
    }

    public function executeUpdate(): bool
    {
        $qbSysFileRefHeaderImages = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');
        $qbSysFileRefHeaderImages->getRestrictions()->removeAll();
        $qbSysFileRefHeaderImages->update('sys_file_reference')
            ->set('tablenames', 'tx_rkwevents_domain_model_eventseries')
            ->where(
                $qbSysFileRefHeaderImages->expr()->eq('fieldname', $qbSysFileRefHeaderImages->createNamedParameter('image', Connection::PARAM_STR)),
                $qbSysFileRefHeaderImages->expr()->eq('tablenames', $qbSysFileRefHeaderImages->createNamedParameter('tx_rkwevents_domain_model_event', Connection::PARAM_STR))
            )
            ->execute();


        return true;
    }

    public function updateNecessary(): bool
    {

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');
        $qb->getRestrictions()->removeAll();

        try {
            $qb->select('*')
                ->from('sys_file_reference')
                ->where('tablenames = :tablenames')
                ->andWhere('fieldname = :fieldname')
                ->setParameter('tablenames', 'tx_rkwevents_domain_model_event')
                ->setParameter('fieldname', 'image')
            ;
            return $qb->execute()->rowCount() > 0;
        } catch (InvalidFieldNameException $e) {
            return false;
        }
    }

    public function getPrerequisites(): array
    {
        return [];
    }
}
