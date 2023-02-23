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

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class FileReferenceRepository
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class FileReferenceRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * Return all by record, fieldName and sysLanguageUid
     *
     * !! Could also be deprecated with TYPO3 v9 !!
     * !! Created for FalTranslationFixViewHelper.php for a FAL translation issue fix !!
     *
     * @param \TYPO3\CMS\Extbase\DomainObject\AbstractEntity $record
     * @param string $fieldName
     * @param int $sysLanguageUid
     * @return array
     */
    public function findAllByRecordFieldnameAndSysLangUid(
        AbstractEntity $record,
        string $fieldName,
        int $sysLanguageUid
    ): array {

        $className = get_class($record);
        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $dataMapper = $objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper::class);
        $tableName = $dataMapper->getDataMap($className)->getTableName();

        $itemList = [];

        foreach ($this->getReferenceRecordList($record->getUid(), $fieldName, $tableName) as $fileReference) {

            $itemUid = $fileReference['uid'];

            if (!$sysLanguageUid) {

                // a) if default language: Return standard object. Do nothing special

                // following does not work by any reason (does not contains "originalResource"!)
                // $this->findByUidForeign($record->getUid())

                $itemList[] = $this->findByUid($itemUid);

            } else {

                // b) foreign language
                $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('sys_file_reference');

                // First: Get the default language item
                /** @var \TYPO3\CMS\Core\Database\Query\QueryBuilder $queryBuilder */
                $queryBuilder = $connection->createQueryBuilder();
                $translatedEvent = $queryBuilder->select('uid')
                    ->from($tableName)
                    ->where(
                        $queryBuilder->expr()->eq(
                            'sys_language_uid',
                            $queryBuilder->createNamedParameter($sysLanguageUid, \PDO::PARAM_INT)
                        ),
                        $queryBuilder->expr()->eq(
                            'l10n_parent',
                            $queryBuilder->createNamedParameter($record->getUid(), \PDO::PARAM_STR)
                        )
                    )->execute()
                    ->fetchAll();


                if (empty($this->getReferenceRecordList($translatedEvent[0]['uid'], $fieldName, $tableName))) {

                    // FALLBACK if empty: Load default language file
                    $itemList[] = $this->findByUid($itemUid);

                } else {
                    // THE MAGIC (for what we are doing this!): Load item of translated record
                    foreach ($this->getReferenceRecordList($translatedEvent[0]['uid'], $fieldName, $tableName) as $translatedFileReference) {
                        $itemList[] = $this->findByUid($translatedFileReference['uid']);
                    }
                }
            }
        }

        return $itemList;
    }


    /**
     * getReferenceRecordList
     *
     * @param int $recordUid
     * @param string $fieldName
     * @param string $tableName
     * @return array
     */
    public function getReferenceRecordList (int $recordUid, string $fieldName, string $tableName): array
    {

        /** @var  \TYPO3\CMS\Core\Database\Connection $connectionPages */
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('sys_file_reference');

        // First: Get the default language item
        /** @var \TYPO3\CMS\Core\Database\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $connection->createQueryBuilder();
        $fileReferenceList = $queryBuilder->select('uid')
            ->from('sys_file_reference')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid_foreign',
                    $queryBuilder->createNamedParameter($recordUid, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'tablenames',
                    $queryBuilder->createNamedParameter($tableName, \PDO::PARAM_STR)
                ),
                $queryBuilder->expr()->eq(
                    'fieldname',
                    $queryBuilder->createNamedParameter($fieldName, \PDO::PARAM_STR)
                )
            )->execute()
            ->fetchAll();

        return $fileReferenceList;
    }
}

