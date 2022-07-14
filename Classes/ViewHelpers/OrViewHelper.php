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
     * Initialize
     *
     * @return void
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('or1', 'mixed', 'The first OR option', true);
        $this->registerArgument('or2', 'mixed', 'The second OR option', true);
        $this->registerArgument('firstIsSalutation', 'boolean', 'Special case handling if the first OR option is a saluation');
    }

    /**
     * simple or
     *
     * @return boolean
     */
    public function render()
    {
        $or1 = intval($this->arguments['or1']);
        $or2 = intval($this->arguments['or2']);
        $firstIsSalutation = intval($this->arguments['firstIsSalutation']);

        if ($firstIsSalutation) {

            if ($or1 != 99 || $or2) {
                return true;
            }

        } else {

            if ($or1 || $or2) {
                return true;
            }
        }

        return false;
    }
}