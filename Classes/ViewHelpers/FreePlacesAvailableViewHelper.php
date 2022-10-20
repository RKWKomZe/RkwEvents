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
 * Class FreePlacesAvailableViewHelper
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class FreePlacesAvailableViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Initialize arguments.
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('event', '\RKW\RkwEvents\Domain\Model\Event', 'The event');
    }

    /**
     * Returns true if free places are available
     *
     * @return boolean
     * @author Carlos Meyer <cm@davitec.de>
     */
    public function render()
    {
        /** @var \RKW\RkwEvents\Domain\Model\Event $event */
        $event = $this->arguments['event'];

        $reservations = $event->getReservation();
        $confirmedReservations = 0;

        if (count($reservations) > 0) {

            /** @var \RKW\RkwEvents\Domain\Model\EventReservation $reservation */
            foreach ($reservations as $reservation) {
                $confirmedReservations++;
                $addPerson = $reservation->getAddPerson();
                $confirmedReservations = $confirmedReservations + count($addPerson);
            }

            if ($confirmedReservations < $event->getSeats()) {
                return true;
            } else {
                return false;
            }

        } else {
            if ($event->getSeats() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }
}

