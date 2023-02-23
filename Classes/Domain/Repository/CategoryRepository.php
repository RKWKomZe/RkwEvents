<?php

namespace RKW\RkwEvents\Domain\Repository;

use \RKW\RkwEvents\Domain\Model\Category;
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
 * Class CategoryRepository
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CategoryRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * initializeObject
     *
     * @return void
     */
    public function initializeObject()
    {

        /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');

        // don't add the pid constraint
        $querySettings->setRespectStoragePage(false);

        $this->setDefaultQuerySettings($querySettings);
    }


    /**
     * Returns all categories of a given parent category
     *
     * @param int $category
     * @param array $excludeCategories
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findChildrenByParent(int $category = 0, array $excludeCategories = []): QueryResultInterface
    {
        $constraints = array();
        $query = $this->createQuery();

        $constraints[] = $query->equals('parent', $category);
        if (count($excludeCategories) > 0) {
            $constraints[] = $query->logicalNot($query->in('uid', $excludeCategories));
        }
        $query->matching($query->logicalAnd($constraints));

        return $query->execute();
    }


    /**
     * findAllRestrictedByEvents
     *
     * @param int $storagePid the pid of the event storage
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllRestrictedByEvents(int $storagePid = 0): QueryResultInterface
    {

        // get all entries where MM is related to an event (of certain PID) which is currently running
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $andWhere = '';
        if ($storagePid) {
            $andWhere = ' AND tx_rkwevents_domain_model_event.pid IN (' . $storagePid . ')';
        }

        $query->statement(
            'SELECT sys_category.*
            FROM sys_category, tx_rkwevents_domain_model_event
            INNER JOIN sys_category_record_mm
            WHERE sys_category_record_mm.tablenames = "tx_rkwevents_domain_model_event"
            AND sys_category_record_mm.fieldname = "categories"
            AND sys_category.uid = sys_category_record_mm.uid_local
            AND tx_rkwevents_domain_model_event.uid = sys_category_record_mm.uid_foreign
            AND tx_rkwevents_domain_model_event.hidden = 0
            AND tx_rkwevents_domain_model_event.deleted = 0
            AND (tx_rkwevents_domain_model_event.start = 0 OR tx_rkwevents_domain_model_event.end > unix_timestamp(now()))
            ' . $andWhere . '
            GROUP BY sys_category.uid
            ORDER BY sys_category.title ASC
            '
        );

        return $query->execute();

    }
}
