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
 * Class BackendUser
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BackendUser extends \TYPO3\CMS\Extbase\Domain\Model\BackendUser
{
    /**
     * @var string
     */
    protected $lang = 'en';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\BackendUserGroup>
     */
    protected $backendUserGroups;

    /**
     * __construct
     */
    public function __construct()
    {
        // parent has no constructor
        //parent::__construct();

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

    protected function initStorageObjects(): void
    {
        $this->backendUserGroups = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Gets the lang of the user
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }


    /**
     * Sets the usergroups. Keep in mind that the property is called "usergroup"
     * although it can hold several usergroups.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $backendUserGroups
     * @return void
     * @api
     */
    public function setBackendUserGroups(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $backendUserGroups)
    {
        $this->backendUserGroups = $backendUserGroups;
    }


    /**
     * Adds a usergroup to the frontend user
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\BackendUserGroup $backendUserGroups
     * @return void
     * @api
     */
    public function addBackendUserGroups(\TYPO3\CMS\Extbase\Domain\Model\BackendUserGroup $backendUserGroups)
    {
        $this->backendUserGroups->attach($backendUserGroups);

    }

    /**
     * Removes a usergroup from the frontend user
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\BackendUserGroup $usergroup
     * @return void
     * @api
     */
    public function removeBackendUserGroups(\TYPO3\CMS\Extbase\Domain\Model\BackendUserGroup $backendUserGroups)
    {

        $this->backendUserGroups->detach($backendUserGroups);
    }


    /**
     * Returns the usergroups. Keep in mind that the property is called "usergroup"
     * although it can hold several usergroups.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage An object storage containing the usergroup
     * @api
     */
    public function getBackendUserGroups()
    {
        return $this->backendUserGroups;
    }


}
