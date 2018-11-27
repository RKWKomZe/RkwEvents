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
 * Class DocumentTypeRepository
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DocumentTypeRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * findOneByIdAndType
     *
     * @param integer $id
     * @param string $type
     * @return \RKW\RkwAuthors\Domain\Model\Authors
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


}