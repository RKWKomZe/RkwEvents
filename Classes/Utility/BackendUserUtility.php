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

use RKW\RkwEvents\Domain\Model\BackendUser;
use RKW\RkwEvents\Domain\Model\BackendUserGroup;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\QueryGenerator;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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
     */
    public static function getMountpointsOfAllGroups($backendUser)
    {
        $allowedPidList = [];

        /** @var BackendUserGroup $backendUserGroup */
        foreach ($backendUser->getBackendUserGroups() as $backendUserGroup) {

            if ($backendUserGroup->getDatabaseMounts()) {
                foreach (GeneralUtility::trimExplode(',', $backendUserGroup->getDatabaseMounts(), true) as $initialRootPid) {

                    $queryGenerator = GeneralUtility::makeInstance(QueryGenerator::class);
                    $pidListWithChilds = $queryGenerator->getTreeList($initialRootPid, 999, 0, 1);

                    foreach (GeneralUtility::trimExplode(',', $pidListWithChilds, true) as $singlePid) {
                        $allowedPidList[] = (int)$singlePid;
                    }
                }
            }
        }

        return $allowedPidList;
    }

}
