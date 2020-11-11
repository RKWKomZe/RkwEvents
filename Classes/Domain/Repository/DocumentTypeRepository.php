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
 * Class DocumentTypeRepository
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DocumentTypeRepository extends \RKW\RkwBasics\Domain\Repository\DocumentTypeRepository
{

    /**
     * findOneByIdAndType
     *
     * @param integer $id
     * @param string $type
     * @return \RKW\RkwEvents\Domain\Model\Authors
     */
    public function findOneByIdAndType($id, $type)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching(
            $query->logicalAnd(
                $query->equals('type', $type),
                $query->equals('uid', intval($id))
            )
        );

        return $query->execute()->getFirst();
        //===
    }


    /**
     * findOneByIdAndType
     *
     * @param string $type
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByType($type)
    {

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching(
            $query->equals('type', $type)

        );

        return $query->execute();
        //===
    }


    /**
     * findAllByTypeAndVisibilityAndRestrictedByEvents
     *
     * @param string $type
     * @param boolean $includeDefault
     * @param integer $storagePid the pid of the event storage
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array
     * @A
     */
    public function findAllByTypeAndVisibilityAndRestrictedByEvents($type = null, $includeDefault = true, $storagePid = 0)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $andWhere = '';
        if ($storagePid) {
            $andWhere = ' AND tx_rkwevents_domain_model_event.pid = ' . intval($storagePid) . '';
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
            LEFT JOIN tx_rkwevents_domain_model_event
            ON tx_rkwbasics_domain_model_documenttype.uid = tx_rkwevents_domain_model_event.document_type 
            WHERE tx_rkwbasics_domain_model_documenttype.uid IN (tx_rkwevents_domain_model_event.document_type)
            AND tx_rkwevents_domain_model_event.hidden = 0
            AND tx_rkwevents_domain_model_event.deleted = 0
            AND (tx_rkwevents_domain_model_event.start = 0 OR tx_rkwevents_domain_model_event.end > unix_timestamp(now()))
            ' . $andWhere . '
            ' . \RKW\RkwBasics\Helper\QueryTypo3::getWhereClauseForEnableFields('tx_rkwbasics_domain_model_documenttype') . '
            GROUP BY tx_rkwbasics_domain_model_documenttype.uid
            ORDER BY tx_rkwbasics_domain_model_documenttype.name ASC
            '
        );

        return $query->execute();
        //===
    }


}