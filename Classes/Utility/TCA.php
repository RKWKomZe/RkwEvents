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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * TCA utility
 *
 * @author Christian Dilger <c.dilger@addorange.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class TCA
{

    /**
     * Returns true if reg time for event has ended
     */
    public function eventTitle(&$parameters, $parentObject): void
    {
        $record = BackendUtility::getRecord($parameters['table'], $parameters['row']['uid']);
        $start = BackendUtility::datetime((int)$record['start']);
        $parameters['title'] = $start . ' - ' . $record['title'];
    }

}
