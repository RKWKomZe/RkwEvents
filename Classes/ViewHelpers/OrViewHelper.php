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
 * Class OrViewHelper
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * simple or
     *
     * @param mixed $or1
     * @param mixed $or2
     * @param boolean $firstIsSalutation
     * @return boolean
     */
    public function render($or1, $or2, $firstIsSalutation = false)
    {

        if ($firstIsSalutation) {

            if ($or1 != 99 || $or2) {
                return true;
                //===
            }

        } else {

            if ($or1 || $or2) {
                return true;
                //===
            }
        }

        return false;
        //===
    }
}