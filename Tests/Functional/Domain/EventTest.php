<?php
namespace Madj2k\FeRegister\Tests\Functional\Domain;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use RKW\RkwEvents\Domain\Model\Event;
use RKW\RkwEvents\Domain\Repository\EventRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
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
 * EventTest
 *
 * @author Christian Dilger <c.dilger@addorange.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_FeRegister
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventTest extends FunctionalTestCase
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
     * @var \RKW\RkwEvents\Domain\Model\Event
     */
    private $event = null;

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

        $this->event = $this->objectManager->get(Event::class);

        //  set default values
        $this->event->setTestimonials("");
        $this->event->setDescription("");
        $this->event->setTargetGroup("");
        $this->event->setSchedule("");
        $this->event->setPartner("");

    }

    //=============================================

    /**
     * @test
     */
    public function eventPropertyRegSingleIsPersistedToTheDatabase()
    {
        $fixture['regSingle'] = true;

        $this->event->setTitle("My Event");
        $this->event->setRegSingle($fixture['regSingle']);

        $this->eventRepository->add($this->event);

        $this->persistenceManager->persistAll();

        $databaseResult = $this->getDatabaseConnection()->selectSingleRow('*', 'tx_rkwevents_domain_model_event','title = "My Event"');

        $this->assertFalse(empty($databaseResult));
        $this->assertNotNull($databaseResult['reg_single']);
        $this->assertTrue(isset($databaseResult['reg_single']));
        $this->assertEquals($fixture['regSingle'], (bool) $databaseResult['reg_single']);
    }

    /**
     * TearDown
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

}
