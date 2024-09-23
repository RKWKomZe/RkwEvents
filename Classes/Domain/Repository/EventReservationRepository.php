<?php

namespace RKW\RkwEvents\Domain\Repository;

use RKW\RkwEvents\Domain\Model\Event;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

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
 * Class EventReservationRepository
 * The repository for EventReservations
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventReservationRepository extends AbstractRepository
{

    /**
     * function findByEvent
     *
     * @param \RKW\RkwEvents\Domain\Model\Event|int $event
     * @param bool $respectStoragePid
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByEvent($event, bool $respectStoragePid = true): QueryResultInterface
    {
        $query = $this->createQuery();
        if (!$respectStoragePid) {
            $query->getQuerySettings()->setRespectStoragePage(false);
        }

        return $query->matching(
            $query->equals('event', $event)
        )->execute();
        //===
    }


    /**
     * function findByFeUser
     *
     * @param \Madj2k\FeRegister\Domain\Model\FrontendUser|\RKW\RkwEvents\Domain\Model\FrontendUser $feUser
     * @param bool $respectStoragePid
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByFeUser($feUser, bool $respectStoragePid = true): QueryResultInterface
    {
        $query = $this->createQuery();
        if (!$respectStoragePid) {
            $query->getQuerySettings()->setRespectStoragePage(false);
        }

        $query->setOrderings(
            [
                'crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
            ]
        );

        return $query->matching(
            $query->equals('feUser', $feUser)
        )->execute();
    }


    /**
     * function findByEventAndFeUser
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @param \Madj2k\FeRegister\Domain\Model\FrontendUser|\RKW\RkwEvents\Domain\Model\FrontendUser $feUser
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */

    public function findByEventAndFeUser(Event $event, $feUser): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        return $query->matching(
            $query->logicalAnd(
                $query->equals('feUser', $feUser),
                $query->equals('event', $event)
            )
        )->execute();
    }


    /**
     * Find all events that have been updated recently
     *
     * @api Used by SOAP-API
     * @param int $timestamp
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findByTimestamp(int $timestamp): QueryResultInterface
    {

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setIncludeDeleted(true);
        $query->getQuerySettings()->setIgnoreEnableFields(true);

        $query->matching(
            $query->greaterThanOrEqual('tstamp', $timestamp)
        );
        $query->setOrderings(['tstamp' => QueryInterface::ORDER_ASCENDING]);

        return $query->execute();
        //===
    }


}
