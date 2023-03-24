<?php

namespace RKW\RkwEvents\Domain\Repository;

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
 * Class EventReservationAddPersonRepository
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventReservationAddPersonRepository extends AbstractRepository
{


    /**
     * function findByEvent
     *
     * @param \RKW\RkwEvents\Domain\Model\EventReservation|int $eventReservation
     * @param bool $respectStoragePid
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByEventReservation($eventReservation, bool $respectStoragePid = true): QueryResultInterface
    {
        $query = $this->createQuery();
        if (!$respectStoragePid) {
            $query->getQuerySettings()->setRespectStoragePage(false);
        }

        return $query->matching(
            $query->equals('eventReservation', $eventReservation)
        )->execute();
        //===
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
            $query->greaterThanOrEqual('tstamp', intval($timestamp))
        );
        $query->setOrderings(array('tstamp' => QueryInterface::ORDER_ASCENDING));

        return $query->execute();
        //===
    }
}
