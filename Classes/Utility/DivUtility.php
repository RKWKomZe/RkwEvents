<?php

namespace RKW\RkwEvents\Utility;
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

use Madj2k\CoreExtended\Utility\GeneralUtility;
use RKW\RkwEvents\Domain\Model\EventWorkshop;
use RKW\RkwEvents\Domain\Model\FrontendUser;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * DivUtility
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DivUtility
{

    /**
     * Returns true if reg time for event has ended
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @return boolean
     */
    public static function hasRegTimeEnded(\RKW\RkwEvents\Domain\Model\Event $event)
    {
        // check if regEnd-time or start-time is in the past
        $date = $event->getRegEnd() ? $event->getRegEnd() : $event->getStart();
        if ($date < time()) {
            return true;
        }

        return false;
    }


    /**
     * check available seats
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $newEventReservation
     * @return boolean
     */
    public static function hasFreeSeats(\RKW\RkwEvents\Domain\Model\Event $event, \RKW\RkwEvents\Domain\Model\EventReservation $newEventReservation)
    {

        $countConfirmedReservations = self::countConfirmedReservations($event);
        $countEventReservationsWithAddPersons = self::countEventReservationsWithAddPersons($newEventReservation);

        $availableSeats = $event->getSeats() - $countConfirmedReservations;
        if ($availableSeats >= $countEventReservationsWithAddPersons) {
            return true;
            //===
        }

        return false;
        //===
    }


    /**
     * count confirmed reservations
     *
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @return int
     */
    public static function countConfirmedReservations($event)
    {

        $reservations = $event->getReservation();
        $confirmedReservations = 0;
        if (count($reservations) > 0) {
            foreach ($reservations as $reservation) {

                $confirmedReservations++;
                $addPerson = $reservation->getAddPerson();
                $confirmedReservations = $confirmedReservations + count($addPerson);

            }

            return $confirmedReservations;
            //===
        } else {
            return 0;
            //===
        }

    }


    /**
     * count reservations in eventReservation (included add Persons)
     *
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     * @return int
     */
    public static function countEventReservationsWithAddPersons($eventReservation)
    {
        $reservations = 1;
        $addPerson = $eventReservation->getAddPerson();

        foreach ($addPerson as $person) {
            if ($person->getFirstName() && $person->getLastName()) {
                $reservations++;
            }
        }

        return $reservations;
        //===
    }


    /**
     * merge eventReservation-data into frontendUser
     *
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     * @param \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser
     * @return void
     */
    public static function mergeFeUsers(\RKW\RkwEvents\Domain\Model\EventReservation $eventReservation, \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser)
    {

        // we just migrate the data to our object!
        if (
            ($frontendUser->getTxFeregisterGender() == 99)
            && (!$eventReservation->getSalutation() == 99)
        ) {
            $frontendUser->setTxFeregisterGender($eventReservation->getSalutation());
        }

        if (
            (!$frontendUser->getFirstName())
            && ($eventReservation->getFirstName())
        ) {
            $frontendUser->setFirstName($eventReservation->getFirstName());
        }

        if (
            (!$frontendUser->getLastName())
            && ($eventReservation->getLastName())
        ) {
            $frontendUser->setLastName($eventReservation->getLastName());
        }

        if (
            (!\Madj2k\FeRegister\Utility\FrontendUserUtility::isEmailValid($frontendUser->getEmail()))
            && (\Madj2k\FeRegister\Utility\FrontendUserUtility::isEmailValid($eventReservation->getEmail()))
        ) {
            $frontendUser->setEmail($eventReservation->getEmail());
        }

        if (
            (!$frontendUser->getCompany())
            && ($eventReservation->getCompany())
        ) {
            $frontendUser->setCompany($eventReservation->getCompany());
        }

        if (
            (!$frontendUser->getTelephone())
            && ($eventReservation->getPhone())
        ) {
            $frontendUser->setTelephone($eventReservation->getPhone());
        }

        // set only new address data together, or even not
        if (
            (!$frontendUser->getAddress() && !$frontendUser->getZip() && !$frontendUser->getCity())
            && ($eventReservation->getAddress() && $eventReservation->getZip() && $eventReservation->getCity())
        ) {
            $frontendUser->setAddress($eventReservation->getAddress());
            $frontendUser->setZip($eventReservation->getZip());
            $frontendUser->setCity($eventReservation->getCity());
        }
    }


    /**
     * furtherResultsAvailable
     * to manage lazy loading in fluid
     *
     * @author Maximilian Fäßler
     * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array $queryResult
     * @param int $limit
     * @param int $page
     * @param \RKW\RkwEvents\Domain\Model\Event &$firstItem
     * @return array
     */
    public static function prepareResultsList($queryResult, $limit, $page = 0, &$firstItem = null)
    {
        $eventList = $queryResult->toArray();

        // kill first item at first :)
        // we use this item only to be able to append events to existing groupings in frontend
        if (
            ($page > 0)
            && ($eventList[0])
            && ($eventList[0] instanceof \RKW\RkwEvents\Domain\Model\Event)
        ) {
            $firstItem = $eventList[0];
            array_shift($eventList);
        }


        // We always have our result set +1 optional result (to know if there would be more results)
        // If the count of the queryResult greater than the given limit, than there are more results
        // -> we remove the optional item in this case. Otherwise we do nothing special.
        if (count($eventList) > $limit) {
            array_pop($eventList);
        }

        return $eventList;
    }


    /**
     * workshopRegistration
     * Simply add registered FeUser to given EventWorkshop(s)
     *
     * @author Maximilian Fäßler
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     * @return void
     */
    public static function workshopRegistration(\RKW\RkwEvents\Domain\Model\EventReservation $eventReservation)
    {

        // check workshop reservation
        if (count($eventReservation->getWorkshopRegister())) {
            /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
            $objectManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
            /** @var \Rkw\RkwEvents\Domain\Repository\EventWorkshopRepository $eventWorkshopRepository */
            $eventWorkshopRepository = $objectManager->get(\Rkw\RkwEvents\Domain\Repository\EventWorkshopRepository::class);
            /** @var EventWorkshop $eventWorkshop */
            foreach ($eventReservation->getWorkshopRegister() as $eventWorkshop) {

                // if using OptIn: Avoid weird "The object of type "RKW\RkwEvents\Domain\Model\EventWorkshop" given to update must be persisted already, but is new."
                $eventWorkshopWorkaround = $eventWorkshopRepository->findByUid($eventWorkshop->getUid());

                // Danger: The FeUser-Entity can be also Madj2k or simply extbase (see EventModel)
                // -> Avoid errors: Just get the Event-FrontendUser
                /** @var \Rkw\RkwEvents\Domain\Repository\FrontendUserRepository $frontendUserRepository */
                $frontendUserRepository = $objectManager->get(\Rkw\RkwEvents\Domain\Repository\FrontendUserRepository::class);
                /** @var FrontendUser $eventUser */
                $eventUser = $frontendUserRepository->findByUid($eventReservation->getFeUser()->getUid());

                $eventWorkshopWorkaround->addRegisteredFrontendUsers($eventUser);

                $eventWorkshopRepository->update($eventWorkshopWorkaround);

            }
        }
    }



    /**
     * createMonthListArray
     * function which returns the current and upcoming months for filter sorting
     * returning timestamps (for january the function will return 01.01.; for february 01.02. etc... as timestamp)
     *
     * @param int $monthsToShow how many upcoming months include current
     * @return array
     */
    public static function createMonthListArray(int $monthsToShow = 6): array
    {
        $resultArray = [];

        for ($i = 0; $i <= $monthsToShow; $i++) {
            $month = strtotime(date('Y-m-01', mktime(0, 0, 0, date('m') + $i, 1, date('Y'))));
            setlocale(LC_TIME, "de_DE.UTF8");
            $resultArray[$month] = strftime('%B %Y', $month);
        }

        return $resultArray;
    }



    /**
     * createCategoryTree
     *
     * @param array $categoryList List of categories
     * @param int $parentCategoryForFilter the initial category
     * @return array
     * @throws \Exception
     */
    public static function createCategoryTree($categoryList, $parentCategoryForFilter)
    {
        $sortedCategoryList = [];

        // we need to know if there is an entry without parent
        $categoryUidList = [];
        foreach ($categoryList as $category) {
            $categoryUidList[] = $category->getUid();
        }

        /** @var \RKW\RkwEvents\Domain\Model\Category $category */
        foreach ($categoryList as $category) {

            // if there is a parent, add it as child content
            if (
                $category->getParent()
                && in_array($category->getParent()->getUid(), $categoryUidList)
            ) {
                $sortedCategoryList[$category->getParent()->getUid()][] = $category;
            } else {
                // if there is no parent entry, add it to "withoutParent" group
                $sortedCategoryList['withoutParent'][] = $category;
            }

        }

        return $sortedCategoryList;
    }

}
