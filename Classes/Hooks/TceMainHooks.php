<?php

namespace RKW\RkwEvents\Hooks;

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

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class TceMainHooks
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class TceMainHooks
{

    /**
     * Fetches GeoData from RkwGeodata
     *
     * @param $status
     * @param $table
     * @param $id
     * @param $fieldArray
     * @param $reference
     * @return void
     */
    function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$reference)
    {
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('rkw_geolocation')) {
            try {

                if ($table == 'tx_rkwevents_domain_model_eventplace') {

                    // set longitude and latitude into event location
                    $eventPlaceDb = array();
                    if ($status != 'new') {
                        $eventPlaceDb = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tx_rkwevents_domain_model_eventplace', intval($id));
                    }
                    $eventPlace = array_merge($eventPlaceDb, $fieldArray);

                    if (count($eventPlace)) {

                        /** @var \RKW\RkwGeolocation\Service\Geolocation $geoLocation */
                        $geoLocation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwGeolocation\\Service\\Geolocation');

                        // set country
                        $staticCountry = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('static_countries', $eventPlace['country']);
                        if ($staticCountry['cn_short_en']) {
                            $geoLocation->setCountry($staticCountry['cn_short_en']);
                        }

                        // set address
                        $geoLocation->setAddress($eventPlace['address'] . ', ' . $eventPlace['zip'] . ' ' . $eventPlace['city']);

                        /** @var \RKW\RkwGeolocation\Domain\Model\Geolocation $geoData */
                        $geoData = $geoLocation->fetchGeoData();

                        if ($geoData) {
                            $fieldArray['longitude'] = $geoData->getLongitude();
                            $fieldArray['latitude'] = $geoData->getLatitude();

                            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Successfully fetched geodata for location "%s".', $geoLocation->getAddress() . ',' . $geoLocation->getCountry()));
                        } else {
                            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::WARNING, sprintf('Could not fetch geodata for location "%s".', $geoLocation->getAddress() . ',' . $geoLocation->getCountry()));
                        }
                    }


                } elseif ($table == 'tx_rkwevents_domain_model_event') {

                    // inherit geo-data into event
                    if ($fieldArray['place']) {

                        $eventDb = array();
                        if ($status != 'new') {
                            $eventDb = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tx_rkwevents_domain_model_event', intval($id));
                        }
                        $event = array_merge($eventDb, $fieldArray);

                        $eventPlace = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tx_rkwevents_domain_model_eventplace', intval($event['place']));
                        if ($eventPlace) {

                            if (
                                ($eventPlace['longitude'])
                                && ($eventPlace['latitude'])
                            ) {
                                $fieldArray['longitude'] = $eventPlace['longitude'];
                                $fieldArray['latitude'] = $eventPlace['latitude'];

                                $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Successfully set geodata for event "%s" (id = %s).', $event['title'], intval($id)));

                            } else {
                                $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::WARNING, sprintf('Could not set geodata for event "%s" (id = %s).', $event['title'], intval($id)));
                            }

                        } else {
                            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::WARNING, sprintf('No location for event set "%s" (id = %s).', $event['title'], intval($id)));
                        }
                    }


                }
            } catch (\Exception $e) {
                $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::ERROR, sprintf('Could not set geodata for event. Reason: %s.', $e->getMessage()));
            }
        }





        try {
            if ($table == 'tx_rkwevents_domain_model_event') {

                $eventRaw = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tx_rkwevents_domain_model_event', intval($id));

                // $fieldArray: Current data before saving (not the complete dataset)
                // $eventRaw: The complete record from DB. This is only needed to correct already wrong saved events

                // REMOVE PLACE ON SAVING AN ONLINE EVENT
                if (
                    $fieldArray['online_event']
                    || $eventRaw['online_event']
                ) {
                    $fieldArray['place'] = '';
                }

                // REMOVE START & END IF TYPE IS "EventAnnouncement"
                if (
                    $fieldArray['record_type'] == '\RKW\RkwEvents\Domain\Model\EventAnnouncement'
                    || $eventRaw['record_type'] == '\RKW\RkwEvents\Domain\Model\EventAnnouncement'
                ) {
                    $fieldArray['start'] = 0;
                    $fieldArray['end'] = 0;
                }
            }
        } catch (\Exception $e) {
            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::ERROR, sprintf('Could not delete interfering data of an event record. Reason: %s.', $e->getMessage()));
        }

    }


    /**
     * Hook: processDatamap_afterFinish
     * This function deletes all reservations when coping an existing event
     *
     * @param  \TYPO3\CMS\Core\DataHandling\DataHandler $object
     * @return void
     * @see \TYPO3\CMS\Core\DataHandling\DataHandler::processRemapStack
     */
    function processCmdmap_afterFinish($object)
    {

        try {

            $idMapping = $object->substNEWwithIDs;
            $idTableMapping = $object->substNEWwithIDs_table;

            foreach ($idMapping as $key => $newId) {

                if (
                    ($table = $idTableMapping[$key])
                    && ($table == 'tx_rkwevents_domain_model_eventreservation')
                ) {

                    // delete corresponding reservations and set counter in event to zero
                    $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
                    /** @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager $persistenceManager */
                    $persistenceManager = $objectManager->get("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");

                    /** @var \RKW\RkwEvents\Domain\Repository\EventRepository $eventRepository */
                    $eventRepository = $objectManager->get('RKW\\RkwEvents\\Domain\\Repository\\EventRepository');
                    /** @var \RKW\RkwEvents\Domain\Repository\EventReservationRepository $reservationRepository */
                    $reservationRepository = $objectManager->get('RKW\\RkwEvents\\Domain\\Repository\\EventReservationRepository');
                    /** @var \RKW\RkwEvents\Domain\Repository\EventReservationAddPersonRepository $reservationAddPersonRepository */
                    $reservationAddPersonRepository = $objectManager->get('RKW\\RkwEvents\\Domain\\Repository\\EventReservationAddPersonRepository');

                    /** @var \RKW\RkwEvents\Domain\Model\Event $event */
                    /** @var \RKW\RkwEvents\Domain\Model\EventReservation $reservation */
                    // we need to fetch the raw data because the event-field is empty via extbase, since the event is hidden per default when copied!
                    if (
                        ($reservationRaw = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('tx_rkwevents_domain_model_eventreservation', intval($newId)))
                        && ($reservation = $reservationRepository->findByUid(intval($newId)))
                        && ($event = $eventRepository->findHiddenByUid(intval($reservationRaw['event']), false))
                    ) {

                        if ($addPersons = $reservationAddPersonRepository->findByEventReservation($reservation, false)) {

                            /** @var \RKW\RkwEvents\Domain\Model\EventReservationAddPerson $addPerson * */
                            foreach ($addPersons as $addPerson) {
                                $reservationAddPersonRepository->remove($addPerson);
                                $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Successfully deleted additional person with id "%s" for reservation with id "%s".', $addPerson->getUid(), $reservation->getUid()));
                            }
                        }

                        $event->removeReservation($reservation);
                        $eventRepository->update($event);
                        $reservationRepository->remove($reservation);

                        // persist all
                        $persistenceManager->persistAll();
                        $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Successfully deleted reservation with id "%s" for event with id "%s".', $reservation->getUid(), $event->getUid()));

                    } else {
                        $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::WARNING, sprintf('Could not fetch data of reservation or corresponding event (reservation-id: "%s").', intval($newId)));
                    }
                }
            }
        } catch (\Exception $e) {
            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::ERROR, sprintf('Could not delete reservations of copied event. Reason: %s.', $e->getMessage()));
        }

    }

    /**
     * Returns logger instance
     *
     * @return \TYPO3\CMS\Core\Log\Logger
     */
    protected function getLogger()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Log\\LogManager')->getLogger(__CLASS__);
        //===
    }
}

?>
