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
 * Class ExplodeViewHelper
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ExplodeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * php explode
     *
     * @param string $delimiter
     * @param string $string
     * @param boolean $optionAsValue
     * @return array
     */
    public function render($delimiter, $string, $optionAsValue = false)
    {

        $dividedOptions = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode("$delimiter", $string);

        // Either: return values as indicated array
        if (!$optionAsValue) {
            return $dividedOptions;
            //===
        }

        // Or: return values as associative array
        $valueArray = array();
        foreach ($dividedOptions as $option) {
            $valueArray[strtolower($option)] = $option;
        }

        return $valueArray;
        //===
    }


}