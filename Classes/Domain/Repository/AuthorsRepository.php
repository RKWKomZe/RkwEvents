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
 * Class AuthorsRepository
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class AuthorsRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{


    /**
     * findOneInternalByEmailForImport
     *
     * @param int $pid
     * @param string $email
     * @return \RKW\RkwEvents\Domain\Model\Authors
     * @api
     */
    public function findOneInternalByEmailForImport($pid, $email)
    {

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching(
            $query->logicalAnd(
                $query->equals('pid', intval($pid)),
                $query->equals('internal', 1),
                $query->equals('email', $email)
            )
        );

        return $query->execute()->getFirst();
        //===
    }

}
