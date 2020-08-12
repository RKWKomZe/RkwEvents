<?php

namespace RKW\RkwEvents\ViewHelpers;

use RKW\RkwEvents\Utility\DivUtility;

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
 * Class RegTimeEndEventsViewHelper
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RegTimeEndEventsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Returns true if reg time for event is still running
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @return bool
     * @author Carlos Meyer <cm@davitec.de>
     */
    public function render(\RKW\RkwEvents\Domain\Model\Event $event)
    {

        return !DivUtility::hasRegTimeEnded($event);
        //===
    }
}
