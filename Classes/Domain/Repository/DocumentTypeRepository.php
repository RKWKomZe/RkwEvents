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

use RKW\RkwEvents\Domain\Model\Authors;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Class DocumentTypeRepository
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DocumentTypeRepository extends \RKW\RkwBasics\Domain\Repository\DocumentTypeRepository
{

    /**
     * findOneByIdAndType
     *
     * @param int $id
     * @param string $type
     * @return \RKW\RkwEvents\Domain\Model\Authors
     */
    public function findOneByIdAndType(int $id, string $type):? Authors
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching(
            $query->logicalAnd(
                $query->equals('type', $type),
                $query->equals('uid', $id)
            )
        );

        return $query->execute()->getFirst();
    }


    /**
     * findOneByIdAndType
     *
     * @param string $type
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByType(string $type): QueryResultInterface
    {

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching(
            $query->equals('type', $type)

        );

        return $query->execute();
    }


    /**
     * findAllByTypeAndVisibilityAndRestrictedByEvents
     *
     * @param string $type
     * @param boolean $includeDefault
     * @param int $storagePid the pid of the event storage
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllByTypeAndVisibilityAndRestrictedByEvents(
        string $type = '',
        bool $includeDefault = true,
        int $storagePid = 0
    ): QueryResultInterface {

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $andWhere = '';
        if ($storagePid) {
            $andWhere = ' AND tx_rkwevents_domain_model_event.pid IN (' . $storagePid . ')';
        }

        if (!$type) {
            $type = 'default';
        }

        if ($includeDefault) {
            $andWhere .= '
            AND
            (tx_rkwbasics_domain_model_documenttype.visibility = 1
                AND
                (tx_rkwbasics_domain_model_documenttype.type = "' . $type . '"
                OR tx_rkwbasics_domain_model_documenttype.type = "default"
                )
            )';
        } else {
            $andWhere .= '
            AND
            (tx_rkwbasics_domain_model_documenttype.visibility = 1
            AND tx_rkwbasics_domain_model_documenttype.type = "' . $type . '"
            )';

        }

        $query->statement(
            'SELECT tx_rkwbasics_domain_model_documenttype.*
            FROM tx_rkwbasics_domain_model_documenttype
            LEFT JOIN tx_rkwevents_domain_model_eventseries ON tx_rkwbasics_domain_model_documenttype.uid = tx_rkwevents_domain_model_eventseries.document_type
            LEFT JOIN tx_rkwevents_domain_model_event ON tx_rkwevents_domain_model_event.series = tx_rkwevents_domain_model_eventseries.uid
            WHERE tx_rkwbasics_domain_model_documenttype.uid IN (tx_rkwevents_domain_model_eventseries.document_type)
            AND tx_rkwevents_domain_model_eventseries.hidden = 0
            AND tx_rkwevents_domain_model_eventseries.deleted = 0
            AND tx_rkwevents_domain_model_event.hidden = 0
            AND tx_rkwevents_domain_model_event.deleted = 0
            AND (tx_rkwevents_domain_model_event.start = 0 OR tx_rkwevents_domain_model_event.end > unix_timestamp(now()))
            ' . $andWhere . '
            ' . \Madj2k\CoreExtended\Utility\QueryUtility::getWhereClauseEnabled('tx_rkwbasics_domain_model_documenttype') . '
            GROUP BY tx_rkwbasics_domain_model_documenttype.uid
            ORDER BY tx_rkwbasics_domain_model_documenttype.name ASC
            '
        );

        return $query->execute();
        //===
    }


}
