<?php
namespace RKW\RkwEvents\UserFunctions;

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

/**
 * Class TcaProcFunc
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class TcaProcFunc
{

    /**
     * Returns selected categories
     *
     * @param array $params
     * @return void
     */
    public function getSelectedCategories(array $params): void
    {

        if (
            $params['table'] == 'tx_rkwevents_domain_model_event'
            && $params['row']['uid']
        ) {

            $categoryUidList = [];

            // FIRST: Get all sys_category entries

            $tableName = 'sys_category';
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableName);
            $result = $queryBuilder
                ->select('uid', 'title')
                ->from($tableName)
                ->join(
                    $tableName,
                    'sys_category_record_mm',
                    'sc_mm',
                    $queryBuilder->expr()->eq('sc_mm.uid_local', $queryBuilder->quoteIdentifier($tableName . '.uid'))
                )
                ->where(
                    $queryBuilder->expr()->eq('sc_mm.uid_foreign', $queryBuilder->createNamedParameter($params['row']['uid'], \PDO::PARAM_INT)),
                    $queryBuilder->expr()->eq('sc_mm.tablenames', $queryBuilder->createNamedParameter($params['table'], \PDO::PARAM_STR)),
                    $queryBuilder->expr()->eq('sc_mm.fieldname', $queryBuilder->createNamedParameter('categories', \PDO::PARAM_STR))
                )
                ->execute();

            while ($category = $result->fetch()) {

                if ($category) {
                    // put it into the result set
                    $params['items'][] = [$category['title'], $category['uid']];
                }
            }

        }

    }


    /**
     * Return a usable title
     *
     * @deprecated Function will be removed soon. After migrating "event" to "eventSeries" this function does no longer works
     * Solves by general TcaLabel->eventTitle solution
     *
     *
     * @param array $params
     * @return void
     */
    public function getEventTitle(array &$params): void
    {
        if (
            $params['table'] == 'tx_rkwevents_domain_model_event'
            && $params['row']['series']
        ) {

            $tableName = 'tx_rkwevents_domain_model_eventseries';
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableName);
            $result = $queryBuilder
                ->select('uid', 'title')
                ->from($tableName)
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($params['row']['series'], \PDO::PARAM_INT)),
                )
                ->execute();

            while ($eventSeries = $result->fetch()) {
                if ($eventSeries) {
                    // put it into the result set
                    $params['items'][] = [$eventSeries['title'], $row['uid']];
                }
            }

        }

    }

}
