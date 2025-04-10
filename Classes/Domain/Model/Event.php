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
 * Class Event
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Event extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $recordType;

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
     * showTime
     *
     * @var boolean
     */
    protected $showTime = true;

    /**
     * longitude
     *
     * @var string
     */
    protected $longitude;

    /**
     * latitude
     *
     * @var string
     */
    protected $latitude;

    /**
     * seats
     *
     * @var integer
     */
    protected $seats;

    /**
     * costsUnknown
     *
     * @var boolean
     */
    protected $costsUnknown = true;

    /**
     * costsReg
     *
     * @var float
     */
    protected $costsReg;

    /**
     * costsRed
     *
     * @var float
     */
    protected $costsRed;

    /**
     * costsRedCondition
     *
     * @var string
     */
    protected $costsRedCondition;

    /**
     * costsRedLink
     *
     * @var string
     */
    protected $costsRedLink;

    /**
     * costsTax
     *
     * @var integer
     */
    protected $costsTax;

    /**
     * regRequired
     *
     * @var boolean
     */
    protected $regRequired = true;

    /**
     * regSingle
     *
     * @var boolean
     */
    protected $regSingle = false;

    /**
     * regEnd
     *
     * @var integer
     */
    protected $regEnd;

    /**
     * cancelEnd
     *
     * @var integer
     */
    protected $cancelEnd;

    /**
     * extRegLink
     *
     * @var string
     */
    protected $extRegLink;

    /**
     * extCancelInfo
     *
     * @var string
     */
    protected $extCancelInfo;

    /**
     * extCancelLink
     *
     * @var string
     */
    protected $extCancelLink;

    /**
     * onlineEvent
     *
     * @var bool
     */
    protected $onlineEvent = false;

    /**
     * placeUnknown
     *
     * @var bool
     */
    protected $placeUnknown = false;

    /**
     * code
     *
     * @var string
     */
    protected $code;

    /**
     * trainer
     *
     * @var string
     */
    protected $trainer;

    /**
     * eligibility
     *
     * @var bool
     */
    protected $eligibility = false;

    /**
     * eligibilityLink
     *
     * @var string
     */
    protected $eligibilityLink = '';

    /**
     * extShowLink
     *
     * @var string
     */
    protected $extShowLink;

    /**
     * additionalTileFlag
     *
     * @var string
     */
    protected $additionalTileFlag = '';

    /**
     * extendedNetwork
     *
     * @var bool
     */
    protected $extendedNetwork = false;

    /**
     * customPrivacyConsentShow
     *
     * @var bool
     */
    protected $customPrivacyConsentShow;

    /**
     * customPrivacyConsent
     *
     * @var string
     */
    protected $customPrivacyConsent;

    /**
     * onlineEventAccessLink
     *
     * @var string
     */
    protected $onlineEventAccessLink;

    /**
     * registerAddInformation
     *
     * @var string
     */
    protected $registerAddInformation;

    /**
     * workshopSelectType
     *
     * @var int
     */
    protected $workshopSelectType = 0;

    /**
     * workshopSelectReq
     *
     * @var boolean
     */
    protected $workshopSelectReq = false;

    /**
     * customFieldShow
     *
     * @var bool
     */
    protected $customFieldShow;

    /**
     * customFieldMandatory
     *
     * @var bool
     */
    protected $customFieldMandatory;

    /**
     * customFieldLabel
     *
     * @var string
     */
    protected $customFieldLabel;

    /**
     * customFieldPlaceholder
     *
     * @var string
     */
    protected $customFieldPlaceholder;

    /**
     * customFieldType
     *
     * @var bool
     */
    protected $customFieldType;

    /**
     * customFieldFullWidth
     *
     * @var bool
     */
    protected $customFieldFullWidth;

    /**
     * type
     *
     * @var \RKW\RkwBasics\Domain\Model\DocumentType
     */
    protected $documentType = null;

    /**
     * Holds place
     *
     * @var \RKW\RkwEvents\Domain\Model\EventPlace
     */
    protected $place = null;

    /**
     * Holds surveyBefore
     *
     * @var \RKW\RkwEvents\Domain\Model\Survey
     */
    protected $surveyBefore = null;

    /**
     * Holds surveyAfter
     *
     * @var \RKW\RkwEvents\Domain\Model\Survey
     */
    protected $surveyAfter = null;

    /**
     * A series of events
     *
     * @var \RKW\RkwEvents\Domain\Model\EventSeries
     */
    protected $series = null;

    /**
     * A couple of logos
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference>
     */
    protected $logos = null;

    /**
     * currency
     *
     * @var \SJBR\StaticInfoTables\Domain\Model\Currency
     */
    protected $currency = null;

    /**
     * Holds external contact
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventContact>
     */
    protected $externalContact = null;

    /**
     * Holds internal contacts
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\Authors>
     */
    protected $internalContact = null;

    /**
     * Holds internal contacts
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\BackendUser>
     */
    protected $beUser = null;


    /**
     * Holds the presentations
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference>
     */
    protected $presentations = null;


    /**
     * Holds sheets
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventSheet>
     */
    protected $sheet = null;


    /**
     * Holds gallery1
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference>
     */
    protected $gallery1 = null;

    /**
     * Holds gallery2
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference>
     */
    protected $gallery2 = null;


    /**
     * Holds reservations
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventReservation>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $reservation = null;

    /**
     * Holds workshops
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventWorkshop>
     */
    protected $workshop = null;

    /**
     * Holds workshop1
     *
     * @deprecated
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventWorkshop>
     */
    protected $workshop1 = null;

    /**
     * Holds workshop2
     *
     * @deprecated
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventWorkshop>
     */
    protected $workshop2 = null;

    /**
     * Holds workshop3
     *
     * @deprecated
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventWorkshop>
     */
    protected $workshop3 = null;

    /**
     * reminderMailTstamp
     *
     * @var integer
     */
    protected $reminderMailTstamp = 0;

    /**
     * surveyAfterMailTstamp
     *
     * @var integer
     */
    protected $surveyAfterMailTstamp = 0;


    /**
     * distance
     *
     * @var string
     */
    protected $distance;

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
    protected $hidden;


    /**
     * @var integer
     */
    protected $deleted;



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
        $this->logos = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->reservation = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->sheet = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->externalContact = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->internalContact = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->beUser = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->presentations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->gallery1 = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->gallery2 = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->workshop = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->workshop1 = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->workshop2 = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->workshop3 = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
     * Returns the recordType value
     *
     * @return string
     * @api
     */
    public function getRecordType()
    {
        return $this->recordType;
    }

    /**
     * Sets the recordType value
     *
     * @param $recordType
     * @return string
     * @api
     */
    public function setRecordType($recordType)
    {
        return $this->recordType = $recordType;
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
     * @return boolean
     */
    public function isShowTime()
    {
        return $this->showTime;
    }

    /**
     * @return boolean
     */
    public function getShowTime()
    {
        return $this->showTime;
    }

    /**
     * @param boolean $showTime
     */
    public function setShowTime($showTime)
    {
        $this->showTime = $showTime;
    }

    /**
     * Returns the longitude
     *
     * @return string $longitude
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Sets the longitude
     *
     * @param string $longitude
     * @return void
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Returns the latitude
     *
     * @return string $latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Sets the latitude
     *
     * @param string $latitude
     * @return void
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Returns the seats
     *
     * @return int $seats
     */
    public function getSeats()
    {
        return $this->seats;
    }

    /**
     * Sets the seats
     *
     * @param int $seats
     * @return void
     */
    public function setSeats($seats)
    {
        $this->seats = $seats;
    }

    /**
     * @return boolean
     */
    public function isCostsUnknown()
    {
        return $this->costsUnknown;
    }

    /**
     * @return boolean
     */
    public function getCostsUnknown()
    {
        return $this->costsUnknown;
    }

    /**
     * @param boolean $costsUnknown
     */
    public function setCostsUnknown($costsUnknown)
    {
        $this->costsUnknown = $costsUnknown;
    }

    /**
     * Returns the costsReg
     *
     * @return float $costsReg
     */
    public function getCostsReg()
    {
        return $this->costsReg;
    }

    /**
     * Sets the costsReg
     *
     * @param float $costsReg
     * @return void
     */
    public function setCostsReg($costsReg)
    {
        $this->costsReg = $costsReg;
    }

    /**
     * Returns the costsRed
     *
     * @return float $costsRed
     */
    public function getCostsRed()
    {
        return $this->costsRed;
    }

    /**
     * Sets the costsRed
     *
     * @param float $costsRed
     * @return void
     */
    public function setCostsRed($costsRed)
    {
        $this->costsRed = $costsRed;
    }

    /**
     * Returns the costsRedCondition
     *
     * @return string $costsRedCondition
     */
    public function getCostsRedCondition()
    {
        return $this->costsRedCondition;
    }

    /**
     * Sets the costsRedCondition
     *
     * @param string $costsRedCondition
     * @return void
     */
    public function setCostsRedCondition($costsRedCondition)
    {
        $this->costsRedCondition = $costsRedCondition;
    }

    /**
     * Returns the costsRedLink
     *
     * @return string $costsRedLink
     */
    public function getCostsRedLink()
    {
        return $this->costsRedLink;
    }

    /**
     * Sets the costsRedLink
     *
     * @param string $costsRedLink
     * @return void
     */
    public function setCostsRedLink($costsRedLink)
    {
        $this->costsRedLink = $costsRedLink;
    }

    /**
     * Returns the costsTax
     *
     * @return int $costsTax
     */
    public function getCostsTax()
    {
        return $this->costsTax;
    }

    /**
     * Sets the costsTax
     *
     * @param int $costsTax
     * @return void
     */
    public function setCostsTax($costsTax)
    {
        $this->costsTax = $costsTax;
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
     * Returns the regSingle
     *
     * @return boolean $regSingle
     */
    public function getRegSingle()
    {
        return $this->regSingle;
    }

    /**
     * Sets the regSingle
     *
     * @param boolean $regSingle
     * @return void
     */
    public function setRegSingle($regSingle)
    {
        $this->regSingle = $regSingle;
    }

    /**
     * Returns the regEnd
     *
     * @return int $regEnd
     */
    public function getRegEnd()
    {

        return $this->regEnd;
    }

    /**
     * Sets the regEnd
     *
     * @param int $regEnd
     * @return void
     */
    public function setRegEnd($regEnd)
    {
        $this->regEnd = $regEnd;
    }

    /**
     * Returns the cancelEnd
     *
     * @return int $cancelEnd
     */
    public function getCancelEnd()
    {

        return $this->cancelEnd;
    }

    /**
     * Sets the cancelEnd
     *
     * @param int $cancelEnd
     * @return void
     */
    public function setCancelEnd($cancelEnd)
    {
        $this->cancelEnd = $cancelEnd;
    }

    /**
     * Returns the extRegLink
     *
     * @return string $extRegLink
     */
    public function getExtRegLink()
    {
        return $this->extRegLink;
    }

    /**
     * Sets the extRegLink
     *
     * @param string $extRegLink
     * @return void
     */
    public function setExtRegLink($extRegLink)
    {
        $this->extRegLink = $extRegLink;
    }

    /**
     * Returns the extCancelInfo
     *
     * @return string $extCancelInfo
     */
    public function getExtCancelInfo()
    {
        return $this->extCancelInfo;
    }

    /**
     * Sets the extCancelInfo
     *
     * @param string $extCancelInfo
     * @return void
     */
    public function setExtCancelInfo($extCancelInfo)
    {
        $this->extCancelInfo = $extCancelInfo;
    }

    /**
     * Returns the extCancelLink
     *
     * @return string $extCancelLink
     */
    public function getExtCancelLink()
    {
        return $this->extCancelLink;
    }

    /**
     * Sets the extCancelLink
     *
     * @param string $extCancelLink
     * @return void
     */
    public function setExtCancelLink($extCancelLink)
    {
        $this->extCancelLink = $extCancelLink;
    }

    /**
     * Returns the onlineEvent
     *
     * @return bool $onlineEvent
     */
    public function getOnlineEvent()
    {
        return $this->onlineEvent;
    }

    /**
     * Sets the onlineEvent
     *
     * @param bool $onlineEvent
     * @return void
     */
    public function setOnlineEvent($onlineEvent)
    {
        $this->onlineEvent = $onlineEvent;
    }


    /**
     * Returns the placeUnknown
     *
     * @return bool $placeUnknown
     */
    public function getPlaceUnknown()
    {
        return $this->placeUnknown;
    }

    /**
     * Sets the placeUnknown
     *
     * @param bool $placeUnknown
     * @return void
     */
    public function setPlaceUnknown($placeUnknown)
    {
        $this->placeUnknown = $placeUnknown;
    }


    /**
     * Returns the onlineEventAccessLink
     *
     * @return string $onlineEventAccessLink
     */
    public function getOnlineEventAccessLink()
    {
        return $this->onlineEventAccessLink;
    }

    /**
     * Sets the onlineEventAccessLink
     *
     * @param string $onlineEventAccessLink
     * @return void
     */
    public function setOnlineEventAccessLink($onlineEventAccessLink)
    {
        $this->onlineEventAccessLink = $onlineEventAccessLink;
    }

    /**
     * @return string
     */
    public function getRegisterAddInformation(): string
    {
        return $this->registerAddInformation;
    }

    /**
     * @param string $registerAddInformation
     */
    public function setRegisterAddInformation(string $registerAddInformation): void
    {
        $this->registerAddInformation = $registerAddInformation;
    }

    /**
     * Returns the extShowLink
     *
     * @return string $extShowLink
     */
    public function getExtShowLink()
    {
        return $this->extShowLink;
    }

    /**
     * Sets the extShowLink
     *
     * @param string $extShowLink
     * @return void
     */
    public function setExtShowLink($extShowLink)
    {
        $this->extShowLink = $extShowLink;
    }

    /**
     * @return string
     */
    public function getAdditionalTileFlag(): string
    {
        return $this->additionalTileFlag;
    }

    /**
     * @param string $additionalTileFlag
     */
    public function setAdditionalTileFlag(string $additionalTileFlag): void
    {
        $this->additionalTileFlag = $additionalTileFlag;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getTrainer()
    {
        return $this->trainer;
    }

    /**
     * @param string $trainer
     */
    public function setTrainer($trainer)
    {
        $this->trainer = $trainer;
    }

    /**
     * @return boolean
     */
    public function isEligibility()
    {
        return $this->eligibility;
    }

    /**
     * @param boolean $eligibility
     */
    public function setEligibility($eligibility)
    {
        $this->eligibility = $eligibility;
    }

    /**
     * Returns the eligibilityLink
     *
     * @return string $eligibilityLink
     */
    public function getEligibilityLink(): string
    {
        return $this->eligibilityLink;
    }

    /**
     * Sets the eligibilityLink
     *
     * @param string $eligibilityLink
     * @return void
     */
    public function setEligibilityLink(string $eligibilityLink): void
    {
        $this->eligibilityLink = $eligibilityLink;
    }

    /**
     * @return bool
     */
    public function isExtendedNetwork(): bool
    {
        return $this->extendedNetwork;
    }

    /**
     * @param bool $extendedNetwork
     */
    public function setExtendedNetwork(bool $extendedNetwork): void
    {
        $this->extendedNetwork = $extendedNetwork;
    }

    /**
     * @return bool
     */
    public function getCustomPrivacyConsentShow(): bool
    {
        return $this->customPrivacyConsentShow;
    }

    /**
     * @param bool $customPrivacyConsentShow
     * @return void
     */
    public function setCustomPrivacyConsentShow(bool $customPrivacyConsentShow): void
    {
        $this->customPrivacyConsentShow = $customPrivacyConsentShow;
    }

    /**
     * @return string
     */
    public function getCustomPrivacyConsent(): string
    {
        return $this->customPrivacyConsent;
    }

    /**
     * @param string $customPrivacyConsent
     */
    public function setCustomPrivacyConsent(string $customPrivacyConsent): void
    {
        $this->customPrivacyConsent = $customPrivacyConsent;
    }

    /**
     * @return int
     */
    public function getWorkshopSelectType(): int
    {
        return $this->workshopSelectType;
    }

    /**
     * @param int $workshopSelectType
     * @return void
     */
    public function setWorkshopSelectType(int $workshopSelectType): void
    {
        $this->workshopSelectType = $workshopSelectType;
    }

    /**
     * @return bool
     */
    public function isWorkshopSelectReq(): bool
    {
        return $this->workshopSelectReq;
    }

    /**
     * @return bool
     */
    public function getWorkshopSelectReq(): bool
    {
        return $this->workshopSelectReq;
    }

    /**
     * @param bool $workshopSelectReq
     * @return void
     */
    public function setWorkshopSelectReq(bool $workshopSelectReq): void
    {
        $this->workshopSelectReq = $workshopSelectReq;
    }

    /**
     * @return bool
     */
    public function getCustomFieldShow(): bool
    {
        return $this->customFieldShow;
    }

    /**
     * @param bool $customFieldShow
     * @return void
     */
    public function setCustomFieldShow(bool $customFieldShow): void
    {
        $this->customFieldShow = $customFieldShow;
    }

    /**
     * @return bool
     */
    public function getCustomFieldMandatory(): bool
    {
        return $this->customFieldMandatory;
    }

    /**
     * @param bool $customFieldMandatory
     * @return void
     */
    public function setCustomFieldMandatory(bool $customFieldMandatory): void
    {
        $this->customFieldMandatory = $customFieldMandatory;
    }

    /**
     * @return string
     */
    public function getCustomFieldLabel(): string
    {
        return $this->customFieldLabel;
    }

    /**
     * @param string $customFieldLabel
     * @return void
     */
    public function setCustomFieldLabel(string $customFieldLabel): void
    {
        $this->customFieldLabel = $customFieldLabel;
    }

    /**
     * @return string
     */
    public function getCustomFieldPlaceholder(): string
    {
        return $this->customFieldPlaceholder;
    }

    /**
     * @param string $customFieldPlaceholder
     * @return void
     */
    public function setCustomFieldPlaceholder(string $customFieldPlaceholder): void
    {
        $this->customFieldPlaceholder = $customFieldPlaceholder;
    }

    /**
     * @return bool
     */
    public function isCustomFieldType(): bool
    {
        return $this->customFieldType;
    }

    /**
     * @param bool $customFieldType
     * @return void
     */
    public function setCustomFieldType(bool $customFieldType): void
    {
        $this->customFieldType = $customFieldType;
    }

    /**
     * @return bool
     */
    public function isCustomFieldFullWidth(): bool
    {
        return $this->customFieldFullWidth;
    }

    /**
     * @param bool $customFieldFullWidth
     * @return void
     */
    public function setCustomFieldFullWidth(bool $customFieldFullWidth): void
    {
        $this->customFieldFullWidth = $customFieldFullWidth;
    }

    /**
     * Returns the place
     *
     * @return \RKW\RkwEvents\Domain\Model\EventPlace $place
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Sets the place
     *
     * @param \RKW\RkwEvents\Domain\Model\EventPlace $place
     * @return void
     */
    public function setPlace(\RKW\RkwEvents\Domain\Model\EventPlace $place)
    {
        $this->place = $place;
    }

    /**
     * Returns the surveyBefore
     *
     * @return \RKW\RkwEvents\Domain\Model\Survey $surveyBefore
     */
    public function getSurveyBefore()
    {
        return $this->surveyBefore;
    }

    /**
     * Sets the surveyBefore
     *
     * @param \RKW\RkwEvents\Domain\Model\Survey $surveyBefore
     * @return void
     */
    public function setSurveyBefore(\RKW\RkwEvents\Domain\Model\Survey $surveyBefore)
    {
        $this->surveyBefore = $surveyBefore;
    }

    /**
     * Returns the surveyAfter
     *
     * @return \RKW\RkwEvents\Domain\Model\Survey $surveyAfter
     */
    public function getSurveyAfter()
    {
        return $this->surveyAfter;
    }

    /**
     * Sets the surveyAfter
     *
     * @param \RKW\RkwEvents\Domain\Model\Survey $surveyAfter
     * @return void
     */
    public function setSurveyAfter(\RKW\RkwEvents\Domain\Model\Survey $surveyAfter)
    {
        $this->surveyAfter = $surveyAfter;
    }

    /**
     * Returns the series
     *
     * @return \RKW\RkwEvents\Domain\Model\EventSeries $series
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Sets the series
     *
     * @param \RKW\RkwEvents\Domain\Model\EventSeries $series
     * @return void
     */
    public function setSeries(\RKW\RkwEvents\Domain\Model\EventSeries $series)
    {
        $this->series = $series;
    }

    /**
     * Adds a EventPartnerLogo
     *
     * @param \RKW\RkwEvents\Domain\Model\FileReference $logo
     * @return void
     */
    public function addLogo(\RKW\RkwEvents\Domain\Model\FileReference $logo)
    {
        $this->logos->attach($logo);
    }

    /**
     * Removes a EventPartnerLogo
     *
     * @param \RKW\RkwEvents\Domain\Model\FileReference $logoToRemove The EventPartnerLogo to be removed
     * @return void
     */
    public function removeLogo(\RKW\RkwEvents\Domain\Model\FileReference $logoToRemove)
    {
        $this->logos->detach($logoToRemove);
    }

    /**
     * Returns the logos
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference> $logos
     */
    public function getLogos()
    {
        return $this->logos;
    }

    /**
     * Sets the logos
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference> $logos
     * @return void
     */
    public function setLogos(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $logos)
    {
        $this->logos = $logos;
    }

    /**
     * Returns the currency
     *
     * @return \SJBR\StaticInfoTables\Domain\Model\Currency  $currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Sets the currency
     *
     * @param \SJBR\StaticInfoTables\Domain\Model\Currency $currency
     * @return void
     */
    public function setCurrency(\SJBR\StaticInfoTables\Domain\Model\Currency $currency)
    {
        $this->currency = $currency;
    }

    /**
     * Adds a externalContact
     *
     * @param \RKW\RkwEvents\Domain\Model\EventContact $externalContact
     * @return void
     */
    public function addExternalContact(\RKW\RkwEvents\Domain\Model\EventContact $externalContact)
    {
        $this->externalContact->attach($externalContact);
    }

    /**
     * Removes a externalContact
     *
     * @param \RKW\RkwEvents\Domain\Model\EventContact $externalContact
     * @return void
     */
    public function removeExternalContact(\RKW\RkwEvents\Domain\Model\EventContact $externalContact)
    {
        $this->externalContact->detach($externalContact);
    }

    /**
     * Returns the externalContact
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventContact> $externalContact
     */
    public function getExternalContact()
    {
        return $this->externalContact;
    }

    /**
     * Sets the externalContact
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventContact> $externalContact
     * @return void
     */
    public function setExternalContact(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $externalContact)
    {
        $this->externalContact = $externalContact;
    }


    /**
     * Adds a internalContact
     *
     * @param \RKW\RkwEvents\Domain\Model\Authors $internalContact
     * @return void
     */
    public function addInternalContact(\RKW\RkwEvents\Domain\Model\Authors $internalContact)
    {
        $this->internalContact->attach($internalContact);
    }

    /**
     * Removes a internalContact
     *
     * @param \RKW\RkwEvents\Domain\Model\Authors $internalContact
     * @return void
     */
    public function removeInternalContact(\RKW\RkwEvents\Domain\Model\Authors $internalContact)
    {
        $this->internalContact->detach($internalContact);
    }

    /**
     * Returns the internalContact
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\Authors> $internalContact
     */
    public function getInternalContact()
    {
        return $this->internalContact;
    }

    /**
     * Sets the internalContact
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\Authors> $internalContact
     * @return void
     */
    public function setInternalContact(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $internalContact)
    {
        $this->internalContact = $internalContact;
    }


    /**
     * Adds a beUser
     *
     * @param \RKW\RkwEvents\Domain\Model\BackendUser $beUser
     * @return void
     */
    public function addBeUser(\RKW\RkwEvents\Domain\Model\BackendUser $beUser)
    {
        $this->beUser->attach($beUser);
    }

    /**
     * Removes a beUser
     *
     * @param \RKW\RkwEvents\Domain\Model\BackendUser $backendUser
     * @return void
     */
    public function removeBeUser(\RKW\RkwEvents\Domain\Model\BackendUser $beUser)
    {
        $this->beUser->detach($beUser);
    }

    /**
     * Returns the beUser
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\BackendUser> $beUser
     */
    public function getBeUser()
    {
        return $this->beUser;
    }

    /**
     * Sets the beUser
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\BackendUser> $beUser
     * @return void
     */
    public function setBeUser(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $beUser)
    {
        $this->beUser = $beUser;
    }


    /**
     * Adds a presentation
     *
     * @param \RKW\RkwEvents\Domain\Model\FileReference $presentation
     * @return void
     */
    public function addPresentations(\RKW\RkwEvents\Domain\Model\FileReference $presentation)
    {
        $this->presentations->attach($presentation);
    }

    /**
     * Removes a presentation
     *
     * @param \RKW\RkwEvents\Domain\Model\FileReference $presentation The file to be removed
     * @return void
     */
    public function removePresentations(\RKW\RkwEvents\Domain\Model\FileReference $presentation)
    {
        $this->presentations->detach($presentation);
    }

    /**
     * Returns the presentations
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference> $presentations
     */
    public function getPresentations()
    {
        return $this->presentations;
    }

    /**
     * Sets the presentations
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference> $presentations
     * @return void
     */
    public function setPresentations(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $presentations)
    {
        $this->presentations = $presentations;
    }


    /**
     * Adds a Sheet
     *
     * @param \RKW\RkwEvents\Domain\Model\EventSheet $sheet
     * @return void
     */
    public function addSheet(\RKW\RkwEvents\Domain\Model\EventSheet $sheet)
    {
        $this->sheet->attach($sheet);
    }

    /**
     * Removes a Sheet
     *
     * @param \RKW\RkwEvents\Domain\Model\EventSheet $sheet
     * @return void
     */
    public function removeSheet(\RKW\RkwEvents\Domain\Model\EventSheet $sheet)
    {
        $this->sheet->detach($sheet);
    }

    /**
     * Returns the Sheets
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventSheet> $sheets
     */
    public function getSheet()
    {
        return $this->sheet;
    }

    /**
     * Sets the Sheet
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventSheet> $sheet
     * @return void
     */
    public function setSheet(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $sheet)
    {
        $this->sheet = $sheet;
    }

    /**
     * Adds a FileReference
     *
     * @param \RKW\RkwEvents\Domain\Model\FileReference $image
     * @return void
     */
    public function addGallery1(\RKW\RkwEvents\Domain\Model\FileReference $image)
    {
        $this->gallery1->attach($image);
    }

    /**
     * Removes a FileReference
     *
     * @param \RKW\RkwEvents\Domain\Model\FileReference $imageToRemove The EventGalleryImage to be removed
     * @return void
     */
    public function removeGallery1(\RKW\RkwEvents\Domain\Model\FileReference $imageToRemove)
    {
        $this->gallery1->detach($imageToRemove);
    }

    /**
     * Returns the reference
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference> $images
     */
    public function getGallery1()
    {
        return $this->gallery1;
    }

    /**
     * Sets the gallery1
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference> $images
     * @return void
     */
    public function setGallery1(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $images)
    {
        $this->gallery1 = $images;
    }

    /**
     * Adds a FileReference
     *
     * @param \RKW\RkwEvents\Domain\Model\FileReference $image
     * @return void
     */
    public function addGallery2(\RKW\RkwEvents\Domain\Model\FileReference $image)
    {
        $this->gallery2->attach($image);
    }

    /**
     * Removes a FileReference
     *
     * @param \RKW\RkwEvents\Domain\Model\FileReference $imageToRemove The EventGalleryImage to be removed
     * @return void
     */
    public function removeGallery2(\RKW\RkwEvents\Domain\Model\FileReference $imageToRemove)
    {
        $this->gallery2->detach($imageToRemove);
    }

    /**
     * Returns the reference
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference> $images
     */
    public function getGallery2()
    {
        return $this->gallery2;
    }

    /**
     * Sets the gallery1
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference> $images
     * @return void
     */
    public function setGallery2(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $images)
    {
        $this->gallery2 = $images;
    }

    /**
     * Adds a EventReservation
     *
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $reservation
     * @return void
     */
    public function addReservation(\RKW\RkwEvents\Domain\Model\EventReservation $reservation)
    {
        $this->reservation->attach($reservation);
    }

    /**
     * Removes a EventReservation
     *
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $reservationToRemove The EventReservation to be removed
     * @return void
     */
    public function removeReservation(\RKW\RkwEvents\Domain\Model\EventReservation $reservationToRemove)
    {
        $this->reservation->detach($reservationToRemove);
    }

    /**
     * Returns the reservations
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventReservation> $reservation
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * Sets the reservation
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventReservation> $reservation
     * @return void
     */
    public function setReservation(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Adds a EventWorkshop
     *
     * @param \RKW\RkwEvents\Domain\Model\EventWorkshop $workshop
     * @return void
     */
    public function addWorkshop(\RKW\RkwEvents\Domain\Model\EventWorkshop $workshop)
    {
        $this->workshop->attach($workshop);
    }

    /**
     * Removes a EventWorkshop
     *
     * @param \RKW\RkwEvents\Domain\Model\EventWorkshop $workshop
     * @return void
     */
    public function removeWorkshop(\RKW\RkwEvents\Domain\Model\EventWorkshop $workshop)
    {
        $this->workshop->detach($workshop);
    }

    /**
     * Returns the EventWorkshop
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventWorkshop> $workshop
     */
    public function getWorkshop()
    {
        return $this->workshop;
    }

    /**
     * Sets the EventWorkshop
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventWorkshop> $workshop
     * @return void
     */
    public function setWorkshop(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $workshop)
    {
        $this->workshop = $workshop;
    }


    /**
     * Adds a EventWorkshop
     *
     * @deprecated
     *
     * @param \RKW\RkwEvents\Domain\Model\EventWorkshop $workshop
     * @return void
     */
    public function addWorkshop1(\RKW\RkwEvents\Domain\Model\EventWorkshop $workshop)
    {
        $this->workshop1->attach($workshop);
    }

    /**
     * Removes a EventWorkshop
     *
     * @deprecated
     *
     * @param \RKW\RkwEvents\Domain\Model\EventWorkshop $workshop
     * @return void
     */
    public function removeWorkshop1(\RKW\RkwEvents\Domain\Model\EventWorkshop $workshop)
    {
        $this->workshop1->detach($workshop);
    }

    /**
     * Returns the EventWorkshop
     *
     * @deprecated
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventWorkshop> $workshop
     */
    public function getWorkshop1()
    {
        return $this->workshop1;
    }

    /**
     * Sets the EventWorkshop
     *
     * @deprecated
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventWorkshop> $workshop
     * @return void
     */
    public function setWorkshop1(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $workshop)
    {
        $this->workshop1 = $workshop;
    }

    /**
     * Adds a EventWorkshop
     *
     * @deprecated
     *
     * @param \RKW\RkwEvents\Domain\Model\EventWorkshop $workshop
     * @return void
     */
    public function addWorkshop2(\RKW\RkwEvents\Domain\Model\EventWorkshop $workshop)
    {
        $this->workshop2->attach($workshop);
    }

    /**
     * Removes a EventWorkshop
     *
     * @deprecated
     *
     * @param \RKW\RkwEvents\Domain\Model\EventWorkshop $workshop
     * @return void
     */
    public function removeWorkshop2(\RKW\RkwEvents\Domain\Model\EventWorkshop $workshop)
    {
        $this->workshop2->detach($workshop);
    }

    /**
     * Returns the EventWorkshop
     *
     * @deprecated
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventWorkshop> $workshop
     */
    public function getWorkshop2()
    {
        return $this->workshop2;
    }

    /**
     * Sets the EventWorkshop
     *
     * @deprecated
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventWorkshop> $workshop
     * @return void
     */
    public function setWorkshop2(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $workshop)
    {
        $this->workshop2 = $workshop;
    }

    /**
     * Adds a EventWorkshop
     *
     * @deprecated
     *
     * @param \RKW\RkwEvents\Domain\Model\EventWorkshop $workshop
     * @return void
     */
    public function addWorkshop3(\RKW\RkwEvents\Domain\Model\EventWorkshop $workshop)
    {
        $this->workshop3->attach($workshop);
    }

    /**
     * Removes a EventWorkshop
     *
     * @deprecated
     *
     * @param \RKW\RkwEvents\Domain\Model\EventWorkshop $workshop
     * @return void
     */
    public function removeWorkshop3(\RKW\RkwEvents\Domain\Model\EventWorkshop $workshop)
    {
        $this->workshop3->detach($workshop);
    }

    /**
     * Returns the EventWorkshop
     *
     * @deprecated
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventWorkshop> $workshop
     */
    public function getWorkshop3()
    {
        return $this->workshop3;
    }

    /**
     * Sets the EventWorkshop
     *
     * @deprecated
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventWorkshop> $workshop
     * @return void
     */
    public function setWorkshop3(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $workshop)
    {
        $this->workshop3 = $workshop;
    }

    /**
     * Returns the reminderMailTstamp
     *
     * @return int $reminderMailTstamp
     */
    public function getReminderMailTstamp()
    {
        return $this->reminderMailTstamp;
    }

    /**
     * Sets the reminderMailTstamp
     *
     * @param int $reminderMailTstamp
     * @return void
     */
    public function setReminderMailTstamp($reminderMailTstamp)
    {
        $this->reminderMailTstamp = $reminderMailTstamp;
    }

    /**
     * Returns the surveyAfterMailTstamp
     *
     * @return int $surveyAfterMailTstamp
     */
    public function getSurveyAfterMailTstamp()
    {
        return $this->surveyAfterMailTstamp;
    }

    /**
     * Sets the surveyAfterMailTstamp
     *
     * @param int $surveyAfterMailTstamp
     * @return void
     */
    public function setSurveyAfterMailTstamp($surveyAfterMailTstamp)
    {
        $this->surveyAfterMailTstamp = $surveyAfterMailTstamp;
    }

    /**
     * Returns the distance
     *
     * @return int $distance
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Sets the distance
     *
     * @param int $distance
     * @return void
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
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
