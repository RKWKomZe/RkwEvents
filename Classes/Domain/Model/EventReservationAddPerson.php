<?php

namespace RKW\RkwEvents\Domain\Model;
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
 * Class EventReservationAddPerson
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventReservationAddPerson extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * event
     *
     * @var \RKW\RkwEvents\Domain\Model\EventReservation
     */
    protected $eventReservation = null;

    /**
     * salutation
     *
     * @var integer
     */
    protected $salutation = 99;

    /**
     * firstName
     *
     * @var string
     */
    protected $firstName;

    /**
     * lastName
     *
     * @var string
     */
    protected $lastName;

    /**
     * @var integer
     */
    protected $crdate;


    /**
     * @var integer
     */
    protected $tstamp;

    /**
     * @var integer
     */
    protected $deleted;

    /**
     * @var integer
     */
    protected $hidden;


    /**
     * Returns the eventReservation
     *
     * @return \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     */
    public function getEventReservation()
    {
        return $this->eventReservation;
    }

    /**
     * Sets the eventReservation
     *
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     * @return void
     */
    public function setEventReservation(\RKW\RkwEvents\Domain\Model\EventReservation $eventReservation)
    {
        $this->eventReservation = $eventReservation;
    }

    /**
     * Returns the salutation
     *
     * @return int $salutation
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * Sets the salutation
     *
     * @param int $salutation
     * @return void
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
    }

    /**
     * Returns the firstName
     *
     * @return string $firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Sets the firstName
     *
     * @param string $firstName
     * @return void
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Returns the lastName
     *
     * @return string $lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Sets the lastName
     *
     * @param string $lastName
     * @return void
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }


    /**
     * Returns the crdate value
     *
     * @return int
     * @api
     */
    public function getCrdate()
    {

        return $this->crdate;
    }


    /**
     * Returns the tstamp value
     *
     * @return int
     * @api
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Sets the hidden value
     *
     * @param int $hidden
     * @api
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }


    /**
     * Returns the hidden value
     *
     * @return int
     * @api
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Sets the deleted value
     *
     * @param int $deleted
     * @api
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }


    /**
     * Returns the deleted value
     *
     * @return int
     * @api
     */
    public function getDeleted()
    {
        return $this->deleted;
    }


}
