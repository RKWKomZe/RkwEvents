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

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Exception\InvalidFieldNameException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Class EventStandaloneRegUpdate
 */
class EventStandaloneRegUpdate implements UpgradeWizardInterface
{
    public function getIdentifier(): string
    {
        return 'eventStandaloneRegUpdate';
    }

    public function getTitle(): string
    {
        return 'RKW Events: Shorter standalone register plugin name';
    }

    public function getDescription(): string
    {
        return 'Through TYPO3 changes over the years we have a max length for plugin names of 30 signs. A plugin name with 31 signs leads to issues: tx_rkwevents_standaloneregister on sending additional URL parameters.';
    }

    public function executeUpdate(): bool
    {
        $tableTtContent = 'tt_content';
        $qbTtContent = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(
            $tableTtContent
        );
        $qbTtContent->getRestrictions()->removeAll();

        $qbTtContent->update($tableTtContent)
            ->set('list_type', 'rkwevents_standalonereg')
            ->where(
                $qbTtContent->expr()->eq(
                    'list_type',
                    $qbTtContent->createNamedParameter('rkwevents_standaloneregister')
                )
            )
            ->orderBy('uid')
            ->execute();

        return true;
    }


    public function updateNecessary(): bool
    {
        $tableTtContent = 'tt_content';
        $qbTtContent = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableTtContent);
        $qbTtContent->getRestrictions()->removeAll();

        try {
            $qbTtContent->select('uid', 'list_type')
                ->from($tableTtContent)
                ->where(
                    $qbTtContent->expr()->eq(
                        'list_type',
                        $qbTtContent->createNamedParameter('rkwevents_standaloneregister')
                    )
                );

            // if there is no record with old entry, return false
            return $qbTtContent->execute()->rowCount() > 0;
        } catch (InvalidFieldNameException $e) {
            // Not needed to update when the old column doesn't exist
            return false;
        } catch (Exception $e) {
        }
    }

    public function getPrerequisites(): array
    {
        return [];
    }
}
