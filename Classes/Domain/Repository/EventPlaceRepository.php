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
 * Class EventPlaceRepository
 * The repository for event places
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventPlaceRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{


    /**
     * Find places by address, zip and city
     *
     * @param int $pid
     * @param string $address
     * @param string $zip
     * @param string $city
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @api
     */
    public function findByAddressZipCityForImport(
        int $pid,
        string $address,
        string $zip,
        string $city
    ): QueryResultInterface {


        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching(
            $query->logicalOr(
                $query->logicalAnd(
                    $query->equals('pid', intval($pid)),
                    $query->equals('address', trim($address)),
                    $query->equals('zip', trim($zip)),
                    $query->equals('city', trim($city))
                )
            )
        );

        return $query->execute();
    }


    /**
     * Find all events that have been updated recently
     *
     * @param int $timestamp
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     * @api Used by SOAP-API
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
    }

}
