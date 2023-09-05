<?php

namespace RKW\RkwEvents\Domain\Repository;
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
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class DepartmentRepository
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DepartmentRepository extends \RKW\RkwBasics\Domain\Repository\DepartmentRepository
{

    /**
     * findAll
     *
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAll()
    {

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        return $query->execute();
        //===
    }


    /**
     * findVisibleAndRestrictedByEvents
     *
     * @param int $storagePid the pid of the event storage
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findVisibleAndRestrictedByEvents($storagePid = 0)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $andWhere = '';
        if ($storagePid) {
            $andWhere = ' AND tx_rkwevents_domain_model_event.pid IN (' . $storagePid . ')';
        }

        $query->statement(
            'SELECT tx_rkwbasics_domain_model_department.*
            FROM tx_rkwbasics_domain_model_department
            LEFT JOIN tx_rkwevents_domain_model_eventseries ON tx_rkwbasics_domain_model_department.uid = tx_rkwevents_domain_model_eventseries.department
            LEFT JOIN tx_rkwevents_domain_model_event ON tx_rkwevents_domain_model_event.series = tx_rkwevents_domain_model_eventseries.uid
            WHERE tx_rkwbasics_domain_model_department.visibility = 1
            AND tx_rkwbasics_domain_model_department.uid IN (tx_rkwevents_domain_model_eventseries.department)
            AND tx_rkwevents_domain_model_eventseries.hidden = 0
            AND tx_rkwevents_domain_model_eventseries.deleted = 0
            AND tx_rkwevents_domain_model_event.hidden = 0
            AND tx_rkwevents_domain_model_event.deleted = 0
            AND (tx_rkwevents_domain_model_event.start = 0 OR tx_rkwevents_domain_model_event.end > unix_timestamp(now()))
            ' . $andWhere . '
            ' . \Madj2k\CoreExtended\Utility\QueryUtility::getWhereClauseEnabled('tx_rkwbasics_domain_model_department') . '
            GROUP BY tx_rkwbasics_domain_model_department.uid
            ORDER BY tx_rkwbasics_domain_model_department.name ASC
            '
        );

        return $query->execute();
        //===
    }

}
