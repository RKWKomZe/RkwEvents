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
 * Class IfAddPersonExistsViewHelper
 *
 * Problem fluid: We can't check <f:if condition="<f:count>{reservation.addPerson}</f:count>" because the objects are existing
 * even they're empty. This ViewHelper helps to detect, if at least one additional person exists
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class IfAddPersonExistsViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Initialize arguments.
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('eventReservation', '\RKW\RkwEvents\Domain\Model\EventReservation', 'The eventReservation');
    }

    /**
     * returns true, if minimum one additional person is set
     *
     * @return boolean
     */
    public function render()
    {
        /** @var \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation */
        $eventReservation = $this->arguments['eventReservation'];

        /** @var \RKW\RkwEvents\Domain\Model\EventReservationAddPerson $addPerson */
        foreach ($eventReservation->getAddPerson() as $addPerson) {
            if ($addPerson->getFirstName() || $addPerson->getLastName()) {
                return true;
            }
        }

        return false;
    }
}
