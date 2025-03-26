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

use Madj2k\CoreExtended\Utility\GeneralUtility;
use RKW\RkwEvents\Domain\Repository\EventRepository;
use RKW\RkwEvents\Domain\Repository\EventReservationAddPersonRepository;
use RKW\RkwEvents\Domain\Repository\EventReservationRepository;
use RKW\RkwEvents\Service\RkwMailService;
use RKW\RkwGeolocation\Service\Geolocation;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

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
     * @param $table
     * @param $id
     * @param array $recordToDelete
     * @param $recordWasDeleted
     * @param DataHandler $dataHandler
     * @return void
     */
    public function processCmdmap_deleteAction($table, $id, array $recordToDelete, &$recordWasDeleted, DataHandler $dataHandler)
    {

        // send cancellation mails on event delete
        // Important: Do only fetch events which are not started yet
        if (
            $table == 'tx_rkwevents_domain_model_event'
            && $recordToDelete['start'] > time()
        ) {

            $tableNameEventReservation = 'tx_rkwevents_domain_model_eventreservation';
            $queryBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableNameEventReservation);
            $result = $queryBuilder
                ->select('*')
                ->from($tableNameEventReservation)
                ->where(
                    $queryBuilder->expr()->eq(
                        'event',
                        $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT)
                    )
                )
                // should not necessary, just to be on the safe side
                ->andWhere(
                    // exclude hidden and deleted
                    $queryBuilder->expr()->eq(
                        'deleted',
                        $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                    )
                )
                ->execute();

            $mailService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(RkwMailService::class);
            $cancellationCounter = 0;
            while ($eventReservation = $result->fetchAssociative()) {

                $mailService->cancellationReservationUser($eventReservation);
                $cancellationCounter++;
            }

            // send eMail with count to BeUsers
            if ($cancellationCounter) {
                // send info mail to admins of the event
                $mailService->cancellationReservationAdmin($id, $cancellationCounter);
            }

        }
    }



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
    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$reference)
    {

        if (ExtensionManagementUtility::isLoaded('rkw_geolocation')) {
            try {

                if ($table == 'tx_rkwevents_domain_model_eventplace') {

                    // set longitude and latitude into event location
                    $eventPlaceDb = [];
                    if ($status != 'new') {
                        $eventPlaceDb = BackendUtility::getRecord('tx_rkwevents_domain_model_eventplace', intval($id));
                    }
                    $eventPlace = array_merge($eventPlaceDb, $fieldArray);

                    if (count($eventPlace)) {

                        /** @var Geolocation $geoLocation */
                        $geoLocation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Geolocation::class);

                        // set country
                        $staticCountry = BackendUtility::getRecord('static_countries', $eventPlace['country']);
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

                            $this->getLogger()->log(LogLevel::INFO, sprintf('Successfully fetched geodata for location "%s".', $geoLocation->getAddress() . ',' . $geoLocation->getCountry()));
                        } else {
                            $this->getLogger()->log(LogLevel::WARNING, sprintf('Could not fetch geodata for location "%s".', $geoLocation->getAddress() . ',' . $geoLocation->getCountry()));
                        }
                    }


                } elseif ($table == 'tx_rkwevents_domain_model_event') {

                    // inherit geo-data into event
                    if ($fieldArray['place']) {

                        $eventDb = [];
                        if ($status != 'new') {
                            $eventDb = BackendUtility::getRecord('tx_rkwevents_domain_model_event', intval($id));
                        }
                        $event = array_merge($eventDb, $fieldArray);

                        $eventPlace = BackendUtility::getRecord('tx_rkwevents_domain_model_eventplace', intval($event['place']));
                        if ($eventPlace) {

                            if (
                                ($eventPlace['longitude'])
                                && ($eventPlace['latitude'])
                            ) {
                                $fieldArray['longitude'] = $eventPlace['longitude'];
                                $fieldArray['latitude'] = $eventPlace['latitude'];

                                $this->getLogger()->log(LogLevel::INFO, sprintf('Successfully set geodata for event "%s" (id = %s).', $event['title'], intval($id)));

                            } else {
                                $this->getLogger()->log(LogLevel::WARNING, sprintf('Could not set geodata for event "%s" (id = %s).', $event['title'], intval($id)));
                            }

                        } else {
                            $this->getLogger()->log(LogLevel::WARNING, sprintf('No location for event set "%s" (id = %s).', $event['title'], intval($id)));
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->getLogger()->log(LogLevel::ERROR, sprintf('Could not set geodata for event. Reason: %s.', $e->getMessage()));
            }
        }



        try {
            if ($table == 'tx_rkwevents_domain_model_event') {

                $eventRaw = BackendUtility::getRecord('tx_rkwevents_domain_model_event', intval($id));

                // $fieldArray: Current data before saving (not the complete dataset)
                // $eventRaw: The complete record from DB. This is only needed to correct already wrong saved events

                // REMOVE PLACE ON SAVING AN ONLINE EVENT
                if (
                    $fieldArray['online_event']
                    || $eventRaw['online_event']
                ) {
                    $fieldArray['place'] = '';
                }

                if (
                    $fieldArray['place_unknown']
                    || $eventRaw['place_unknown']
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
            $this->getLogger()->log(LogLevel::ERROR, sprintf('Could not delete interfering data of an event record. Reason: %s.', $e->getMessage()));
        }


        /*
            if ($table == 'tx_rkwevents_domain_model_event') {

                $eventRaw = BackendUtility::getRecord('tx_rkwevents_domain_model_event', intval($id));
                $eventSeriesRaw = BackendUtility::getRecord('tx_rkwevents_domain_model_eventseries', intval($eventRaw['series']));

                $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_rkwevents_domain_model_event');
                $connection->update(
                    'tx_rkwevents_domain_model_event',
                    [
                        'title' => GeneralUtility::slugify($eventSeriesRaw['title'])
                    ],
                    ['series' => intval($eventRaw['series'])]
                );
            }
*/
    }


    /**
     * Hook: processDatamap_afterFinish
     * This function deletes all reservations when coping an existing event
     *
     * @param DataHandler $object
     * @return void
     * @see DataHandler::processRemapStack
     */
    public function processCmdmap_afterFinish($object)
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
                    $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ObjectManager::class);
                    /** @var PersistenceManager $persistenceManager */
                    $persistenceManager = $objectManager->get(PersistenceManager::class);

                    /** @var EventRepository $eventRepository */
                    $eventRepository = $objectManager->get(EventRepository::class);
                    /** @var EventReservationRepository $reservationRepository */
                    $reservationRepository = $objectManager->get(EventReservationRepository::class);
                    /** @var EventReservationAddPersonRepository $reservationAddPersonRepository */
                    $reservationAddPersonRepository = $objectManager->get(EventReservationAddPersonRepository::class);

                    /** @var \RKW\RkwEvents\Domain\Model\Event $event */
                    /** @var \RKW\RkwEvents\Domain\Model\EventReservation $reservation */
                    // we need to fetch the raw data because the event-field is empty via extbase, since the event is hidden per default when copied!
                    if (
                        ($reservationRaw = BackendUtility::getRecord('tx_rkwevents_domain_model_eventreservation', intval($newId)))
                        && ($reservation = $reservationRepository->findByUid(intval($newId)))
                        && ($event = $eventRepository->findHiddenByUid(intval($reservationRaw['event']), false))
                    ) {

                        if ($addPersons = $reservationAddPersonRepository->findByEventReservation($reservation, false)) {

                            /** @var \RKW\RkwEvents\Domain\Model\EventReservationAddPerson $addPerson * */
                            foreach ($addPersons as $addPerson) {
                                $reservationAddPersonRepository->remove($addPerson);
                                $this->getLogger()->log(LogLevel::INFO, sprintf('Successfully deleted additional person with id "%s" for reservation with id "%s".', $addPerson->getUid(), $reservation->getUid()));
                            }
                        }

                        $event->removeReservation($reservation);
                        $eventRepository->update($event);
                        $reservationRepository->remove($reservation);

                        // persist all
                        $persistenceManager->persistAll();
                        $this->getLogger()->log(LogLevel::INFO, sprintf('Successfully deleted reservation with id "%s" for event with id "%s".', $reservation->getUid(), $event->getUid()));

                    } else {
                        $this->getLogger()->log(LogLevel::WARNING, sprintf('Could not fetch data of reservation or corresponding event (reservation-id: "%s").', intval($newId)));
                    }
                }
            }
        } catch (\Exception $e) {
            $this->getLogger()->log(LogLevel::ERROR, sprintf('Could not delete reservations of copied event. Reason: %s.', $e->getMessage()));
        }
    }


     /**
     * Hooks into TCE Main and watches all record updates. If a change is
     * detected that would remove the record from the website, we try to find
     * related documents and remove them from the index.
     *
     * @param string $status Status of the current operation, 'new' or 'update'
     * @param string $table The table the record belongs to
     * @param mixed $uid The record's uid, [integer] or [string] (like 'NEW...')
     * @param array $fields The record's data, not used
     * @param DataHandler $tceMain TYPO3 Core Engine parent object, not used
     */
    public function processDatamap_afterDatabaseOperations($status, $table, $uid, array $fields, DataHandler $tceMain)
    {

        if ($table == 'tx_rkwevents_domain_model_event') {

            $eventRaw = BackendUtility::getRecord('tx_rkwevents_domain_model_event', intval($uid));
            $eventSeriesRaw = BackendUtility::getRecord('tx_rkwevents_domain_model_eventseries', intval($eventRaw['series']));

            if ($eventSeriesRaw['url_override']) {
                $titleSlug = GeneralUtility::slugify($eventSeriesRaw['title']);
                GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getConnectionForTable('tx_rkwevents_domain_model_event')
                    ->update(
                        'tx_rkwevents_domain_model_event',
                        ['title' => $titleSlug],
                        ['uid' => (int)$uid]
                    );
            }

        }

        if ($table == 'tx_rkwevents_domain_model_eventseries') {

            $eventSeriesRaw = BackendUtility::getRecord('tx_rkwevents_domain_model_eventseries', intval($uid));

            // check if eventSeries exists (important on BE-formfield-reload if the eventSeries is not persistent yet)
            if (is_array($eventSeriesRaw)) {

                $tableNameEvent = 'tx_rkwevents_domain_model_event';
                $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($tableNameEvent);
                $titleSlug = GeneralUtility::slugify($eventSeriesRaw['title']);

                // set (slugified) title from "eventSeries" to every NEW "event" (needed for event URL)
                if (key_exists($tableNameEvent, $tceMain->newRelatedIDs)) {

                    foreach ($tceMain->newRelatedIDs[$tableNameEvent] as $keyIndex => $newEventUid) {

                        $connection->update(
                            $tableNameEvent,
                            [
                                'title' => $titleSlug,
                            ],
                            ['uid' => (int) $newEventUid]
                        );
                    }
                }


                // set startDate of current or upcoming event (fallback: Last event)
                // @toDo: HINT: This code does NOT react to freshly added IRRE elements (hide; make visible etc need second save)
                // 1. Get event
                $queryBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableNameEvent);
                $result = $queryBuilder

                    ->select('uid', 'start')
                    ->from($tableNameEvent)
                    // get event with the highest date
                    ->where(
                        $queryBuilder->expr()->neq(
                            'start',
                            $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                        )
                    )
                    // or announcement (time value 0)
                    ->orWhere(
                        $queryBuilder->expr()->eq(
                            'start',
                            $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                        )
                    )
                    ->andWhere(
                        $queryBuilder->expr()->eq(
                            'series',
                            $queryBuilder->createNamedParameter($eventSeriesRaw['uid'], \PDO::PARAM_INT)
                        ),
                        // exclude hidden and deleted
                        $queryBuilder->expr()->eq(
                            'hidden',
                            $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                        ),
                        $queryBuilder->expr()->eq(
                            'deleted',
                            $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                        )
                    )
                    ->setMaxResults(1)
                    ->orderBy('start', 'DESC')
                    ->execute();

                $tableNameEventSeries = 'tx_rkwevents_domain_model_eventseries';

                while ($event = $result->fetchAssociative()) {

                    if (key_exists('start', $event)) {
                        $connection->update(
                            $tableNameEventSeries,
                            [
                                'event_start_date' => $event['start'],
                            ],
                            ['uid' => (int) $eventSeriesRaw['uid']]
                        );
                    }

                    // else: Do nothing

                }

            }
        }
    }



    /**
     * Returns logger instance
     *
     * @return \TYPO3\CMS\Core\Log\Logger
     */
    protected function getLogger()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);
    }
}


