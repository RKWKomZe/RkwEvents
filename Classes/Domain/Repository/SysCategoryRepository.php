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

/**
 * Class SysCategoryRepository
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class SysCategoryRepository extends \RKW\RkwBasics\Domain\Repository\CategoryRepository
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
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findChildrenByParent($category = 0, $excludeCategories = array())
    {
        $constraints = array();
        $query = $this->createQuery();

        $constraints[] = $query->equals('parent', $category);
        if (count($excludeCategories) > 0) {
            $constraints[] = $query->logicalNot($query->in('uid', $excludeCategories));
        }
        $query->matching($query->logicalAnd($constraints));

        return $query->execute();
        //===
    }
}
