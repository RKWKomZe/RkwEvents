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
 * Class IsMandatoryFieldViewHelper
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class IsMandatoryFieldViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Initialize arguments.
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('fieldName', 'string', 'The field name');
        $this->registerArgument('mandatoryFields', 'string', 'List of mandatory fields from TypoScript');
    }

    /**
     * return TRUE, if the given fieldName is NOT in given mandatoryFields (string-list from TypoScript)
     * TRUE if optional
     *
     * @return bool
     */
    public function render()
    {
        $fieldName = $this->arguments['fieldName'];
        $mandatoryFields = $this->arguments['mandatoryFields'];

        $mandatoryFieldsArray = array_map('trim', explode(',', $mandatoryFields));

        if (!in_array($fieldName, $mandatoryFieldsArray)) {
            return true;
        }

        return false;
    }


}