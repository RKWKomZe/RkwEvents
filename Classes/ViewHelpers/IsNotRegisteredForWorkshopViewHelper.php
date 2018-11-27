<?php

namespace RKW\RkwEvents\ViewHelpers;
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
 * Class IsNotRegisteredForWorkshopViewHelper
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class IsNotRegisteredForWorkshopViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * check if user is registered for workshop
     * Used for disable a checkbox (or disable not, if user is registered and may want to edit ;-))
     *
     * @param \RKW\RkwEvents\Domain\Model\EventWorkshop $eventWorkshop
     * @param \RKW\RkwEvents\Domain\Model\FrontendUser $frontendUser
     * @return boolean
     */
    public function render($eventWorkshop, $frontendUser)
    {

        if ($eventWorkshop->getRegisteredFrontendUsers()->offsetExists($frontendUser)) {
            return '';
            //===
        }

        return true;
        //===

    }
}