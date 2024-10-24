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
 * Class EventSeries
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventSeries extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     */
    protected $title;

    /**
     * urlOverride
     *
     * @var string
     */
    protected $urlOverride;

    /**
     * subtitle
     *
     * @var string
     */
    protected $subtitle;

    /**
     * keywords
     *
     * @var string
     */
    protected string $keywords = '';

    /**
     * description
     *
     * @var string
     */
    protected $description;

    /**
     * description2
     *
     * @var string
     */
    protected $description2;

    /**
     * targetLearning
     *
     * @var string
     */
    protected $targetLearning;

    /**
     * targetGroup
     *
     * @var string
     */
    protected $targetGroup;

    /**
     * regInhouse
     *
     * @var boolean
     */
    protected $regInhouse = false;

    /**
     *    schedule
     *
     * @var string
     */
    protected $schedule;

    /**
     * partner
     *
     * @var string
     */
    protected $partner;

    /**
     * testimonials
     *
     * @var string
     */
    protected $testimonials;

    /**
     * backendUserExclusive
     *
     * @var bool
     */
    protected $backendUserExclusive = false;

    /**
     * Holds recommendedLinks
     *
     * @var string
     */
    protected $recommendedLinks = '';

    /**
     * eventStartDate
     * holds the startDate of the most current event. Is always set via hook an saving an eventSeries record
     *
     * @var integer
     */
    protected $eventStartDate;

    /**
     * headerImage
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected $headerImage = null;

    /**
     * type
     *
     * @var \RKW\RkwBasics\Domain\Model\DocumentType
     */
    protected $documentType = null;

    /**
     * department
     *
     * @var \RKW\RkwBasics\Domain\Model\Department
     */
    protected $department = null;

    /**
     * Holds add info
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference>
     */
    protected $addInfo = null;

    /**
     * Holds recommendedEvents
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\Event>
     */
    protected $recommendedEvents = null;

    /**
     * Categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     */
    protected $categories;

    /**
     * CategoriesDisplayed
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     */
    protected $categoriesDisplayed;

    /**
     * department
     *
     * @var \RKW\RkwProjects\Domain\Model\Projects
     */
    protected $project = null;

    /**
     * Holds organizer
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventOrganizer>
     */
    protected $organizer = null;

    /**
     * Event
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\Event>
     */
    protected $event;

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
        $this->addInfo = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->recommendedEvents = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->categoriesDisplayed = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->organizer = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->event = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
     * @return string
     */
    public function getUrlOverride(): string
    {
        return $this->urlOverride;
    }

    /**
     * @param string $urlOverride
     * @return void
     */
    public function setUrlOverride(string $urlOverride): void
    {
        $this->urlOverride = $urlOverride;
    }

    /**
     * Returns the subtitle
     *
     * @return string $subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Sets the subtitle
     *
     * @param string $subtitle
     * @return void
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Returns the keywords
     *
     * @return string
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * Sets the keywords
     *
     * @param string $keywords
     * @return void
     */
    public function setKeywords(string $keywords): void
    {
        $this->keywords = $keywords;
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
     * Returns the description2
     *
     * @return string $description2
     */
    public function getDescription2()
    {
        return $this->description2;
    }

    /**
     * Sets the description2
     *
     * @param string $description2
     * @return void
     */
    public function setDescription2($description2)
    {
        $this->description2 = $description2;
    }

    /**
     * Returns the targetLearning
     *
     * @return string $targetLearning
     */
    public function getTargetLearning()
    {
        return $this->targetLearning;
    }

    /**
     * Sets the targetLearning
     *
     * @param string $targetLearning
     * @return void
     */
    public function setTargetLearning($targetLearning)
    {
        $this->targetLearning = $targetLearning;
    }

    /**
     * Returns the targetGroup
     *
     * @return string $targetGroup
     */
    public function getTargetGroup()
    {
        return $this->targetGroup;
    }

    /**
     * Sets the targetGroup
     *
     * @param string $targetGroup
     * @return void
     */
    public function setTargetGroup($targetGroup)
    {
        $this->targetGroup = $targetGroup;
    }

    /**
     * Returns the regInhouse
     *
     * @return boolean $regInhouse
     */
    public function getRegInhouse()
    {
        return $this->regInhouse;
    }

    /**
     * Sets the regInhouse
     *
     * @param boolean $regInhouse
     * @return void
     */
    public function setRegInhouse($regInhouse)
    {
        $this->regInhouse = $regInhouse;
    }

    /**
     * Returns the schedule
     *
     * @return string $schedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * Sets the schedule
     *
     * @param string $schedule
     * @return void
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Returns the partner
     *
     * @return string $partner
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * Sets the partner
     *
     * @param string $partner
     * @return void
     */
    public function setPartner($partner)
    {
        $this->partner = $partner;
    }

    /**
     * Returns the testimonials
     *
     * @return string $testimonials
     */
    public function getTestimonials()
    {
        return $this->testimonials;
    }

    /**
     * Sets the testimonials
     *
     * @param string $testimonials
     * @return void
     */
    public function setTestimonials($testimonials)
    {
        $this->testimonials = $testimonials;
    }

    /**
     * @return bool
     */
    public function isBackendUserExclusive(): bool
    {
        return $this->backendUserExclusive;
    }

    /**
     * @param bool $backendUserExclusive
     */
    public function setBackendUserExclusive(bool $backendUserExclusive): void
    {
        $this->backendUserExclusive = $backendUserExclusive;
    }

    /**
     * @return string
     */
    public function getRecommendedLinks(): string
    {
        return $this->recommendedLinks;
    }

    /**
     * @param string $recommendedLinks
     */
    public function setRecommendedLinks(string $recommendedLinks): void
    {
        $this->recommendedLinks = $recommendedLinks;
    }

    /**
     * Returns the eventStartDate
     *
     * @return int $eventStartDate
     */
    public function getEventStartDate(): int
    {
        return $this->eventStartDate;
    }

    /**
     * Sets the eventStartDate
     *
     * @param int $eventStartDate
     * @return void
     */
    public function setEventStartDate(int $eventStartDate)
    {
        $this->eventStartDate = $eventStartDate;
    }

    /**
     * Returns the headerImage
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $headerImage
     */
    public function getHeaderImage()
    {
        return $this->headerImage;
    }

    /**
     * Sets the headerImage
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $headerImage
     * @return void
     */
    public function setHeaderImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $headerImage)
    {
        $this->headerImage = $headerImage;
    }

    /**
     * Returns the document type
     *
     * @return \RKW\RkwBasics\Domain\Model\DocumentType $documentType
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * Sets the document type
     *
     * @param \RKW\RkwBasics\Domain\Model\DocumentType $documentType
     * @return void
     */
    public function setDocumentType(\RKW\RkwBasics\Domain\Model\DocumentType $documentType)
    {
        $this->documentType = $documentType;
    }

    /**
     * Returns the department
     *
     * @return \RKW\RkwBasics\Domain\Model\Department $department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Sets the department
     *
     * @param \RKW\RkwBasics\Domain\Model\Department $department
     * @return void
     */
    public function setDepartment(\RKW\RkwBasics\Domain\Model\Department $department)
    {
        $this->department = $department;
    }

    /**
     * Adds a addInfo
     *
     * @param \RKW\RkwEvents\Domain\Model\FileReference $addInfo
     * @return void
     */
    public function addAddInfo(\RKW\RkwEvents\Domain\Model\FileReference $addInfo)
    {
        $this->addInfo->attach($addInfo);
    }

    /**
     * Removes a addInfo
     *
     * @param \RKW\RkwEvents\Domain\Model\FileReference $addInfo
     * @return void
     */
    public function removeAddInfo(\RKW\RkwEvents\Domain\Model\FileReference $addInfo)
    {
        $this->addInfo->detach($addInfo);
    }

    /**
     * Returns the addInfo
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference> $addInfo
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets the addInfo
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\FileReference> $addInfo
     * @return void
     */
    public function setAddInfo(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $addInfo)
    {
        $this->addInfo = $addInfo;
    }

    /**
     * Adds a RecommendedEvents
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $recommendedEvents
     * @return void
     */
    public function addRecommendedEvents(\RKW\RkwEvents\Domain\Model\Event $recommendedEvents)
    {
        $this->recommendedEvents->attach($recommendedEvents);
    }

    /**
     * Removes a EventWorkshop
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $recommendedEvents
     * @return void
     */
    public function removeRecommendedEvents(\RKW\RkwEvents\Domain\Model\Event $recommendedEvents)
    {
        $this->recommendedEvents->detach($recommendedEvents);
    }

    /**
     * Returns the EventWorkshop
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\Event> $recommendedEvents
     */
    public function getRecommendedEvents()
    {
        return $this->recommendedEvents;
    }

    /**
     * Sets the EventWorkshop
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\Event> $recommendedEvents
     * @return void
     */
    public function setRecommendedEvents(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $recommendedEvents)
    {
        $this->recommendedEvents = $recommendedEvents;
    }

    /**
     * Adds a Category
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\Category $category
     * @return void
     */
    public function addCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $category)
    {
        $this->categories->attach($category);
    }

    /**
     * Removes a Category
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\Category $categoryToRemove The Category to be removed
     * @return void
     */
    public function removeCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $categoryToRemove)
    {
        $this->categories->detach($categoryToRemove);
    }

    /**
     * Returns the categories
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Sets the categories
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
     * @return void
     */
    public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Adds a CategoryDisplayed
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\Category $categoryDisplayed
     * @return void
     */
    public function addCategoryDisplayed(\TYPO3\CMS\Extbase\Domain\Model\Category $categoryDisplayed)
    {
        $this->categoriesDisplayed->attach($categoryDisplayed);
    }

    /**
     * Removes a CategoryDisplayed
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\Category $categoryDisplayedToRemove The CategoryDisplayed to be removed
     * @return void
     */
    public function removeCategoryDisplayed(\TYPO3\CMS\Extbase\Domain\Model\Category $categoryDisplayedToRemove)
    {
        $this->categoriesDisplayed->detach($categoryDisplayedToRemove);
    }

    /**
     * Returns the categoriesDisplayed
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categoriesDisplayed
     */
    public function getCategoriesDisplayed()
    {
        return $this->categoriesDisplayed;
    }

    /**
     * Sets the categoriesDisplayed
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categoriesDisplayed
     * @return void
     */
    public function setCategoriesDisplayed(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categoriesDisplayed)
    {
        $this->categoriesDisplayed = $categoriesDisplayed;
    }

    /**
     * Adds a Event
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @return void
     */
    public function addEvent(\RKW\RkwEvents\Domain\Model\Event $event)
    {
        $this->event->attach($event);
    }

    /**
     * Removes a Event
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @return void
     */
    public function removeEvent(\RKW\RkwEvents\Domain\Model\Event $event)
    {
        $this->event->detach($event);
    }

    /**
     * Returns the end
     *
     * @return \RKW\RkwProjects\Domain\Model\Projects
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Sets the end
     *
     * @param \RKW\RkwProjects\Domain\Model\Projects $end
     * @return void
     */
    public function setProject($end)
    {
        $this->project = $end;
    }

    /**
     * Adds a organizer
     *
     * @param \RKW\RkwEvents\Domain\Model\EventOrganizer $organizer
     * @return void
     */
    public function addOrganizer(\RKW\RkwEvents\Domain\Model\EventOrganizer $organizer)
    {
        $this->organizer->attach($organizer);
    }

    /**
     * Removes a organizer
     *
     * @param \RKW\RkwEvents\Domain\Model\EventOrganizer $backendUser
     * @return void
     */
    public function removeOrganizer(\RKW\RkwEvents\Domain\Model\EventOrganizer $organizer)
    {
        $this->organizer->detach($organizer);
    }

    /**
     * Returns the organizer
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventOrganizer> $organizer
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * Sets the organizer
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\EventOrganizer> $organizer
     * @return void
     */
    public function setOrganizer(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $organizer)
    {
        $this->organizer = $organizer;
    }


    /**
     * Returns the Event
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\Event> $event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Sets the Event
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwEvents\Domain\Model\Event> $event
     * @return void
     */
    public function setEvent(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $event)
    {
        $this->event = $event;
    }


}
