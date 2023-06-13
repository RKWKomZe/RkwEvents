<?php

namespace RKW\RkwEvents\Utility;
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

use Madj2k\CoreExtended\Utility\GeneralUtility;
use Madj2k\CoreExtended\Utility\QueryUtility;
use RKW\RkwEvents\Domain\Model\BackendUser;
use RKW\RkwEvents\Domain\Model\BackendUserGroup;

/**
 * BackendUserUtility
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BackendUserUtility
{

    /**
     * getMountpointsOfAllGroups
     *
     * @param BackendUser $backendUser
     * @return array Returns mountpoints (PID) which are allowed to this user (WITH sub-PIDs!)
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     */
    public static function getMountpointsOfAllGroups($backendUser)
    {
        $allowedPidList = [];

        /** @var BackendUserGroup $backendUserGroup */
        foreach ($backendUser->getBackendUserGroups() as $backendUserGroup) {

            if ($backendUserGroup->getDatabaseMounts()) {
                foreach (GeneralUtility::trimExplode(',', $backendUserGroup->getDatabaseMounts(), true) as $initialRootPid) {

                    $pidListWithChilds = QueryUtility::getTreeList($initialRootPid);
                    foreach (GeneralUtility::trimExplode(',', $pidListWithChilds, true) as $singlePid) {
                        $allowedPidList[] = (int)$singlePid;
                    }
                }
            }
        }

        return $allowedPidList;
    }

}
