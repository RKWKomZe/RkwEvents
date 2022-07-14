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
 * Class HideAddPersonViewHelper
 *
 * @deprecated By MF July 2022: Seems no longer used
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class HideAddPersonViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Initialize arguments.
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('addPerson', '\TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'The event');
        $this->registerArgument('cssClassName', 'string', 'The event');
        $this->registerArgument('index', 'integer', 'The event');
    }

    /**
     * Returns class or nothing
     *
     * @return string series names comma separated
     */
    public function render($addPerson, $cssClassName, $index)
    {
        $addPerson = $this->arguments['addPerson'];
        $cssClassName = $this->arguments['cssClassName'];
        $index = $this->arguments['index'];

        if ($addPerson->toArray()[$index]) {
            return '';
        }
        else {
            return $cssClassName;
        }
    }
}

?>