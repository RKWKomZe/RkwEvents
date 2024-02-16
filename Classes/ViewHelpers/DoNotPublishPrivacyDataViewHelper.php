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

use RKW\RkwEvents\Domain\Model\EventContact;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class DoNotPublishPrivacyDataViewHelper
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DoNotPublishPrivacyDataViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments.
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('contact', 'mixed', 'The contact');
    }

    /**
     * return true, if the given contact is NOT eligible to receive external data (BE AWARE OF PRIVACY WHILE USING THIS!)
     *
     * @return bool
     */
    public function render()
    {
        /** @var EventContact $eventContact */
        $eventContact = $this->arguments['contact'];
        if (
            $eventContact instanceof EventContact
            && !$eventContact->getRecipientOfPersonalData()
        ) {
            return true;
        }

        return false;
    }


}
