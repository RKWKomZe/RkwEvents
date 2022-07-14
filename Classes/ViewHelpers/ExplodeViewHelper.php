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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

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
class ExplodeViewHelper extends AbstractViewHelper
{

    /**
     * Initialize arguments.
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('delimiter', 'string', 'The delimiter');
        $this->registerArgument('string', 'string', 'String to explode');
        $this->registerArgument('optionAsValue', 'boolean', 'Optional: return values as indicated array', false, false);
    }

    /**
     * php explode
     *
     * @return array
     */
    public function render(): array
    {
        $delimiter = $this->arguments['delimiter'];

        $dividedOptions = GeneralUtility::trimExplode("$delimiter", $this->arguments['string']);

        // Either: return values as indicated array
        if (!$this->arguments['optionAsValue']) {
            return $dividedOptions;
        }

        // Or: return values as associative array
        $valueArray = array();
        foreach ($dividedOptions as $option) {
            $valueArray[strtolower($option)] = $option;
        }

        return $valueArray;
    }


}