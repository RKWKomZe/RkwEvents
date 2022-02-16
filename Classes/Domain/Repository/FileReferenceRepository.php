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
 * Class FileReferenceRepository
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class FileReferenceRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * Return all by record, fieldname and sysLanguageUid
     *
     * @return mixed
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException

    public function findNotFinished()
    {
        $query = $this->createQuery();

        $dataMapper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper::class);
        $tableName = $dataMapper->getDataMap($className)->getTableName();

        return $query->matching(
            $query->logicalAnd(
                $query->equals('sysLanguageUid', $sysLanguageUid),
                $query->equals('uidForeign', $record->getUid()),
                $query->equals('tablenames', $getTableOfRecordFunction),
                $query->equals('fieldName', $fieldName)

            )
            ->execute();
    }*/
}
