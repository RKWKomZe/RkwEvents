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
 * Class EventWorkshop
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventWorkshop extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     */
    protected $title;

    /**
     * description
     *
     * @var string
     */
    protected $description;

    /**
     * type
     *
     * @var string
     */
    protected $type;

    /**
     * previousExperience
     *
     * @var string
     */
    protected $previousExperience;

    /**
     * objective
     *
     * @var string
     */
    protected $objective;

    /**
     * speaker
     *
     * @var string
     */
    protected $speaker;

    /**
     * start
     *
     * @var integer
     */
    protected $start;

    /**
     * end
     *
     * @var integer
     */
    protected $end;

    /**
     * availableSeats
     *
     * @var integer
     */
    protected $availableSeats;

    /**
     * regRequired
     *
     * @var boolean
     */
    protected $regRequired = true;

    /**
     * costs
     *
     * @var float
     */
    protected $costs;

    /**
     * Holds registeredFrontendUsers
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FrontendUser>
     */
    protected $registeredFrontendUsers = null;

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->registeredFrontendUsers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns the type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the previousExperience
     *
     * @param string $previousExperience
     * @return void
     */
    public function setPreviousExperience($previousExperience)
    {
        $this->previousExperience = $previousExperience;
    }

    /**
     * Returns the previousExperience
     *
     * @return string $previousExperience
     */
    public function getPreviousExperience()
    {
        return $this->previousExperience;
    }

    /**
     * Sets the type
     *
     * @param string $type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns the objective
     *
     * @return string $objective
     */
    public function getObjective()
    {
        return $this->objective;
    }

    /**
     * Sets the objective
     *
     * @param string $objective
     * @return void
     */
    public function setObjective($objective)
    {
        $this->objective = $objective;
    }

    /**
     * Returns the speaker
     *
     * @return string $speaker
     */
    public function getSpeaker()
    {
        return $this->speaker;
    }

    /**
     * Sets the speaker
     *
     * @param string $speaker
     * @return void
     */
    public function setSpeaker($speaker)
    {
        $this->speaker = $speaker;
    }

    /**
     * Returns the start
     *
     * @return int $start
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Sets the start
     *
     * @param int $start
     * @return void
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * Returns the end
     *
     * @return int $end
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Sets the end
     *
     * @param int $end
     * @return void
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * Returns the availableSeats
     *
     * @return int $availableSeats
     */
    public function getAvailableSeats()
    {
        return $this->availableSeats;
    }

    /**
     * Sets the availableSeats
     *
     * @param int $availableSeats
     * @return void
     */
    public function setAvailableSeats($availableSeats)
    {
        $this->availableSeats = $availableSeats;
    }

    /**
     * Returns the regRequired
     *
     * @return boolean $regRequired
     */
    public function getRegRequired()
    {
        return $this->regRequired;
    }

    /**
     * Sets the regRequired
     *
     * @param boolean $regRequired
     * @return void
     */
    public function setRegRequired($regRequired)
    {
        $this->regRequired = $regRequired;
    }

    /**
     * Returns the boolean state of regRequired
     *
     * @return boolean
     */
    public function isRegRequired()
    {
        return $this->regRequired;
    }

    /**
     * Returns the costs
     *
     * @return float $costs
     */
    public function getCosts()
    {
        return $this->costs;
    }

    /**
     * Sets the costs
     *
     * @param float $costs
     * @return void
     */
    public function setCosts($costs)
    {
        $this->costs = $costs;
    }

    /**
     * Adds a frontendUser
     *
     * @param \RKW\RkwEvents\Domain\Model\FrontendUser $registeredFrontendUsers
     * @return void
     */
    public function addRegisteredFrontendUsers(\RKW\RkwEvents\Domain\Model\FrontendUser $registeredFrontendUsers)
    {
        $this->registeredFrontendUsers->attach($registeredFrontendUsers);
    }

    /**
     * Removes a frontendUser
     *
     * @param \RKW\RkwEvents\Domain\Model\FrontendUser $registeredFrontendUsers
     * @return void
     */
    public function removeRegisteredFrontendUsers(\RKW\RkwEvents\Domain\Model\FrontendUser $registeredFrontendUsers)
    {
        $this->registeredFrontendUsers->detach($registeredFrontendUsers);
    }

    /**
     * Returns the frontendUser
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FrontendUser> $registeredFrontendUsers
     */
    public function getRegisteredFrontendUsers()
    {
        return $this->registeredFrontendUsers;
    }

    /**
     * Sets the frontendUser
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FrontendUser> $registeredFrontendUsers
     * @return void
     */
    public function setRegisteredFrontendUsers(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $registeredFrontendUsers)
    {
        $this->registeredFrontendUsers = $registeredFrontendUsers;
    }

}
