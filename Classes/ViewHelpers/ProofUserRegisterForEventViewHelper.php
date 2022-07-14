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
 * Class ProofUserRegisterForEvent
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ProofUserRegisterForEventViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
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
        $this->registerArgument('frontendUser', '\RKW\RkwEvents\Domain\Model\FrontendUser', 'The frontendUser');
    }

    /**
     * return TRUE, if given frontendUser are registered (for given event)
     *
     * @return bool
     */
    public function render()
    {
        $event = $this->arguments['event'];
        $frontendUser = $this->arguments['frontendUser'];

        /** @var \RKW\RkwEvents\Domain\Model\EventReservation $reservation */
        foreach ($event->getReservation() as $reservation) {

            if ($reservation->getFeUser()->getUid() == $frontendUser->getUid()) {
                return true;
            }
        }

        return false;
    }

}