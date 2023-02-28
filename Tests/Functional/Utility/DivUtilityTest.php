<?php
namespace Madj2k\FeRegister\Tests\Functional\Utility;

use RKW\RkwEvents\Utility\DivUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use RKW\RkwEvents\Domain\Model\EventReservation;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use RKW\RkwEvents\Domain\Model\Event;
use RKW\RkwEvents\Domain\Repository\EventRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use RKW\RkwEvents\Domain\Model\EventReservationAddPerson;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

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
 * DivUtilityTest
 *
 * @author Christian Dilger <c.dilger@addorange.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_FeRegister
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DivUtilityTest extends FunctionalTestCase
{

    /**
     * @var string[]
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/static_info_tables',
        'typo3conf/ext/rkw_basics',
        'typo3conf/ext/rkw_events',
    ];

    /**
     * @var string[]
     */
    protected $coreExtensionsToLoad = [];

    /**
     * @var \RKW\RkwEvents\Domain\Repository\EventRepository
     */
    private $eventRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    private $persistenceManager = null;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    private $objectManager = null;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->importDataSet(__DIR__ . '/../Fixtures/Database/Pages.xml');

        $this->setUpFrontendRootPage(
            1,
            [
                'EXT:fe_register/Configuration/TypoScript/setup.txt',
            ]
        );

        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->eventRepository = $this->objectManager->get(EventRepository::class);

    }

    //=============================================

    /**
     * @test
     */
    public function countConfirmedReservationsDoesNotIncludeDeletedReservations()
    {
        /**
         * Scenario:
         *
         * Given an event exists
         * Given there is a reservation
         * Given there is a second reservation
         * Given the second reservation is deleted
         * When I count confirmed reservations
         * Then the result is 1
         */

        $this->importDataSet(__DIR__ . '/DivUtilityTest/Fixtures/Database/Check10.xml');

        /** @var Event $event */
        $event = $this->eventRepository->findByUid(1);

        self::assertCount(1, $event->getReservation());
        self::assertEquals(1, DivUtility::countConfirmedReservations($event));

    }

    /**
     * @test
     */
    public function hasFreeSeatsReturnsTrueIfConfirmedReservationsDoNotExceedNumberOfFreeSeats()
    {
        /**
         * Scenario:
         *
         * Given an event exists
         * Given the event has 3 seats
         * Given there are 2 confirmed reservations for 1 seat each
         * Given I place 1 new reservation for 1 seat
         * When I check if there are free seats available
         * Then the result is true.
         */

        $this->importDataSet(__DIR__ . '/DivUtilityTest/Fixtures/Database/Check20.xml');

        /** @var Event $event */
        $event = $this->eventRepository->findByUid(1);

        /** @var EventReservation $newEventReservation */
        $newEventReservation = $this->objectManager->get(EventReservation::class);
        $newEventReservation->setEvent($event);
        //  set addperson as 4 items are populated from controller
        for ($i = 0; $i < 4; $i++) {
            /** @var EventReservationAddPerson $person */
            $person = $this->objectManager->get(EventReservationAddPerson::class);
            $newEventReservation->addAddPerson($person);
        }

        self::assertCount(2, $event->getReservation());
        self::assertTrue(DivUtility::hasFreeSeats($event, $newEventReservation));

    }

    /**
     * @test
     */
    public function hasFreeSeatsReturnsTrueIfConfirmedAndNotDeletedReservationsAndNewReservationIncludingAddedPersonsDoNotExceedNumberOfFreeSeats()
    {
        /**
         * Scenario:
         *
         * Given an event exists
         * Given the event has 3 seats
         * Given there are 3 confirmed reservations for 1 seat each
         * Given the last 1 confirmed reservation has been deleted
         * Given I place 1 new reservation for 1 seat
         * When I check if there are free seats available
         * Then the result is true.
         */

        $this->importDataSet(__DIR__ . '/DivUtilityTest/Fixtures/Database/Check30.xml');

        /** @var Event $event */
        $event = $this->eventRepository->findByUid(1);

        /** @var EventReservation $newEventReservation */
        $newEventReservation = $this->objectManager->get(EventReservation::class);
        $newEventReservation->setEvent($event);
        //  set addperson as 4 items are populated from controller
        for ($i = 0; $i < 4; $i++) {
            /** @var EventReservationAddPerson $person */
            $person = $this->objectManager->get(EventReservationAddPerson::class);
            $newEventReservation->addAddPerson($person);
        }

        self::assertEquals(1, DivUtility::countEventReservationsWithAddPersons($newEventReservation));

        self::assertCount(2, $event->getReservation());
        self::assertTrue(DivUtility::hasFreeSeats($newEventReservation->getEvent(), $newEventReservation));

    }

    /**
     * @test
     */
    public function hasFreeSeatsReturnsFalseIfConfirmedReservationsAndNewReservationIncludingAddedPersonsDoNotExceedNumberOfFreeSeats()
    {
        /**
         * Scenario:
         *
         * Given an event exists
         * Given the event has 3 seats
         * Given there are 2 confirmed reservations for 1 seat each
         * Given I place 1 new reservation
         * Given the new reservation does include 1 more person
         * When I check if there are free seats available
         * Then the result is false.
         */

        $this->importDataSet(__DIR__ . '/DivUtilityTest/Fixtures/Database/Check40.xml');

        /** @var Event $event */
        $event = $this->eventRepository->findByUid(1);

        /** @var EventReservation $newEventReservation */
        $newEventReservation = $this->objectManager->get(EventReservation::class);
        $newEventReservation->setEvent($event);
        //  set addperson as 4 items are populated from controller
        for ($i = 0; $i < 4; $i++) {
            /** @var EventReservationAddPerson $person */
            $person = $this->objectManager->get(EventReservationAddPerson::class);

            if ($i === 0) {
                $person->setFirstName('Karl');
                $person->setLastName('Lauterbach');
            }

            $newEventReservation->addAddPerson($person);
        }

        self::assertEquals(2, DivUtility::countEventReservationsWithAddPersons($newEventReservation));

        self::assertCount(2, $event->getReservation());
        self::assertFalse(DivUtility::hasFreeSeats($newEventReservation->getEvent(), $newEventReservation));

    }

    /**
     * TearDown
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

}
