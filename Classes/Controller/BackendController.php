<?php

namespace RKW\RkwEvents\Controller;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * BackendController
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class BackendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * eventRepository
     *
     * @var \RKW\RkwEvents\Domain\Repository\EventRepository
     * @inject
     */
    protected $eventRepository = null;

    /**
     * eventPlaceRepository
     *
     * @var \RKW\RkwEvents\Domain\Repository\EventPlaceRepository
     * @inject
     */
    protected $eventPlaceRepository = null;

    /**
     * eventContactRepository
     *
     * @var \RKW\RkwEvents\Domain\Repository\EventContactRepository
     * @inject
     */
    protected $eventContactRepository = null;

    /**
     * eventOrganizerRepository
     *
     * @var \RKW\RkwEvents\Domain\Repository\EventOrganizerRepository
     * @inject
     */
    protected $eventOrganizerRepository = null;

    /**
     * departmentRepository
     *
     * @var \RKW\RkwEvents\Domain\Repository\DepartmentRepository
     * @inject
     */
    protected $departmentRepository = null;

    /**
     * documentTypeRepository
     *
     * @var \RKW\RkwEvents\Domain\Repository\DocumentTypeRepository
     * @inject
     */
    protected $documentTypeRepository = null;

    /**
     * backendUserRepository
     *
     * @var \RKW\RkwEvents\Domain\Repository\BackendUserRepository
     * @inject
     */
    protected $backendUserRepository = null;

    /**
     * categoryRepository
     *
     * @var \RKW\RkwEvents\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository = null;


    /**
     * Persistence Manager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * objectManager
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;


    /**
     * Shows input
     *
     * @return void
     */
    public function showAction()
    {
        $this->view->assignMultiple(
            array(
                'content'       => '',
                'documentTypes' => $this->documentTypeRepository->findByType('events'),
                'departments'   => $this->departmentRepository->findAll(),
                'organizers'    => $this->eventOrganizerRepository->findAll(),
            )
        );

    }


    /**
     * Makes import
     *
     * @param array $data
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function createAction($data)
    {

        $doImport = false;
        if ($data['doImport']) {
            $doImport = true;
        }

        $timeZone = 'GMT+1';
        if ($data['timeZone']) {
            $timeZone = trim($data['timeZone']);
        }

        $pid = intval($data['targetPid']);
        $pidAuthors = intval($data['targetPidAuthors']);

        $typeId = 0;
        if ($data['typeId']) {
            $typeId = intval($data['typeId']);
        }

        $topicId = 0;
        if ($data['topicId']) {
            $topicId = intval($data['topicId']);
        }

        $organizerId = 0;
        if ($data['organizerId']) {
            $organizerId = intval($data['organizerId']);
        }

        $hidden = 1;
        if ($data['activate']) {
            $hidden = 0;
        }

        $countryCode = 'DE';
        $currencyCode = 'EUR';


        $allowedRows = array(
            'pid',
            'pidAuthors',
            'typeId',
            'topicId',
            'organizerId',
            'title',
            'subtitle',
            'description',
            'description2',
            'partner',
            'start',
            'end',
            'time',
            'regEnd',
            'regRequired',
            'extRegLink',
            'currency',
            'seats',
            'costsReg',
            'costsRed',
            'costsRedCondition',
            'costsTaxIncluded',
            'placeName',
            'address',
            'zip',
            'city',
            //'country',
            'targetGroup',
            'targetLearning',
            'schedule',
            'code',
            'trainer',
            'eligibility',
            'onlineEvent',
            'categories',
            'isAnnouncement'
        );

        foreach (range(1, 10) as $contactNumber) {

            foreach (
                array(
                    'name',
                    'firstName',
                    'lastName',
                    'company',
                    'address',
                    'zip',
                    'city',
                    'phone',
                    'email',
                ) as $key) {

                $allowedRows[] = 'contact' . $contactNumber . ucfirst($key);
            }
        }

        $importCounter = 0;
        $importPlaceCounter = 0;
        $importExternalContactCounter = 0;
        if ($data['csv']) {

            // get lines
            $lines = explode("\r\n", $data['csv']);

            if (count($lines) > 1) {

                // now get first line - this defines the rows
                $header = explode("\t", $lines[0]);

                // now check the header for allowed rows - other rows will be ignored
                $importRows = array();
                foreach ($header as $row) {

                    if (in_array(trim($row), $allowedRows)) {
                        $importRows[] = trim($row);
                    } else {
                        $importRows[] = false;

                        if (trim($row)) {
                            $this->addFlashMessage(
                                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                    'backendController.error.ignoredRow',
                                    'rkw_events',
                                    array(trim($row))
                                ),
                                '',
                                \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
                            );
                        }
                    }

                }

                // now go through each line and import was is to import
                foreach ($lines as $lineNumber => $line) {

                    if ($lineNumber == 0) {
                        continue;
                    }
                    //===

                    // put all data from col into an associative array
                    $rows = explode("\t", $line);
                    $tempData = array();
                    foreach ($rows as $rowNumber => $value) {

                        if ($key = $importRows[$rowNumber]) {
                            $tempData[$key] = $this->stringCleanUp($value);
                        }

                    }

                    if (
                        $tempData['title']
                        // an announcement does not need a start date
                        && ($tempData['start'] || $tempData['isAnnouncement'])
                    ) {

                        if ($tempData['pid']) {
                            $pid = intval($tempData['pid']);
                        }

                        if ($tempData['pidAuthors']) {
                            $pidAuthors = intval($tempData['pidAuthors']);
                        }

                        if ($tempData['typeId']) {
                            $typeId = intval($tempData['typeId']);
                        }

                        if ($tempData['topicId']) {
                            $topicId = intval($tempData['topicId']);
                        }

                        if ($tempData['organizerId']) {
                            $organizerId = intval($tempData['organizerId']);
                        }


                        //======================================================================
                        // 1. Create Event and set data
                        /** @var \RKW\RkwEvents\Domain\Model\Event $event */
                        $event = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance("RKW\\RkwEvents\\Domain\\Model\\Event");

                        // set hidden state
                        $event->setHidden($hidden);

                        // set PID
                        $event->setPid($pid);

                        // set data
                        if ($tempData['isAnnouncement']) {
                            $event->setRecordType('\RKW\RkwEvents\Domain\Model\EventAnnouncement');
                        }
                        if ($tempData['title']) {
                            $event->setTitle($tempData['title']);
                        }
                        if ($tempData['subtitle']) {
                            $event->setSubtitle($tempData['subtitle']);
                        }
                        if ($tempData['description']) {
                            $event->setDescription($this->parseHtml($tempData['description']));
                        }
                        if ($tempData['description2']) {
                            $event->setDescription2($this->parseHtml($tempData['description2']));
                        }
                        if ($tempData['partner']) {
                            $event->setPartner($this->parseHtml($tempData['partner']));
                        }
                        if ($tempData['targetLearning']) {
                            $event->setTargetLearning($this->parseHtml($tempData['targetLearning']));
                        }
                        if ($tempData['targetGroup']) {
                            $event->setTargetGroup($tempData['targetGroup']);
                        }
                        if ($tempData['schedule']) {
                            $event->setSchedule($this->parseHtml($tempData['schedule']));
                        }
                        if ($tempData['regRequired']) {
                            $event->setRegRequired(true);
                        }
                        if ($tempData['seats']) {
                            $event->setSeats(intval($tempData['seats']));
                        }
                        if ($tempData['extRegLink']) {
                            if (
                                strpos($tempData['extRegLink'], 'http://') === false
                                && strpos($tempData['extRegLink'], 'https://') === false
                            ) {
                                $tempData['extRegLink'] = 'http://' . $tempData['extRegLink'];
                            }
                            $event->setExtRegLink($tempData['extRegLink']);
                        } elseif ($event->getSeats() < 1) {
                            $event->setSeats(100000);
                        }
                        if ($tempData['eligibility']) {
                            $event->setEligibility(true);
                        }
                        if ($tempData['onlineEvent']) {
                            $event->setOnlineEvent(true);
                        }
                        if ($tempData['code']) {
                            $event->setCode($tempData['code']);
                        }
                        if ($tempData['trainer']) {
                            $event->setTrainer($tempData['trainer']);
                        }
                        if ($tempData['categories']) {

                            // workaround with objectStorage: Using $event->addCategory leads to "Call to a member function attach() on null"
                            // even creating event via ObjectManager does not helps here (instead of "makeInstance")

                            $categoryUidList = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $tempData['categories']);

                            $objectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
                            foreach ($categoryUidList as $categoryUid) {
                                /** @var \TYPO3\CMS\Extbase\Domain\Model\Category $category */
                                $category = $this->categoryRepository->findByUid($categoryUid);
                                if ($category instanceof \TYPO3\CMS\Extbase\Domain\Model\Category) {
                                    $objectStorage->attach($category);
                                }
                            }

                            $event->setCategories($objectStorage);
                        }

                        //======================================================================
                        // 2. Special case for time
                        $hours = array(
                            'start'  => '0',
                            'end'    => '0',
                            'regEnd' => '0',
                        );

                        if ($tempData['time']) {

                            if (preg_match('/^[^0-9]*([0-9:]{1,4}:[0-9]{2}|[0-9]{1,2})([^0-9]+([0-9:]{1,4}:[0-9]{2}|[0-9]{1,2}))?/', $tempData['time'], $hoursMatch)) {

                                if ($hoursMatch[1]) {
                                    $hours['start'] = strtotime($hoursMatch[1] . ' GMT', 0);
                                }

                                if ($hoursMatch[3]) {
                                    $hours['end'] = strtotime($hoursMatch[3] . ' GMT', 0);
                                }

                            }
                        }

                        //======================================================================
                        // 3. Set date and time
                        foreach (array('start', 'end', 'regEnd') as $timeLabel) {
                            $time = 0;

                            // fix for missing end-time
                            if (
                                ($timeLabel == 'end')
                                && (
                                    (!$tempData[$timeLabel])
                                    || (strpos($tempData[$timeLabel], '0000-00-00') === 0)
                                )
                            ) {
                                $tempData[$timeLabel] = $tempData['start'];
                            }

                            // process time
                            if (is_integer($tempData[$timeLabel])) {
                                $time = intval($tempData[$timeLabel]);

                            } elseif (preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})( ([0-9]{2}):([0-9]{2}):([0-9]{2}))?/', $tempData[$timeLabel], $timeMatch)) {

                                if (
                                    (intval($timeMatch[1]))
                                    && (intval($timeMatch[2]))
                                    && (intval($timeMatch[3]))
                                ) {
                                    $time = strtotime($tempData[$timeLabel] . ' ' . $timeZone, 0) + $hours[$timeLabel];
                                }

                            }

                            // check for daylight saving time and correct it!
                            $dateStart = new \DateTime(strftime("%Y-%m-%d %H:%M:%S", $time));
                            if ((bool)$dateStart->format('I')) {
                                $time -= 60 * 60;
                            }

                            $setter = 'set' . ucfirst($timeLabel);
                            if ($time) {
                                /** @var \RKW\RkwEvents\Domain\Model\Event $event */
                                $event->$setter(intval($time));
                            }

                        }

                        //======================================================================
                        // check for existing events
                        if ($this->eventRepository->findOneByTitleAndStart($event->getTitle(), $event->getStart())) {
                            $this->addFlashMessage(
                                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                    'backendController.error.eventAlreadyExists',
                                    'rkw_events',
                                    array($event->getTitle(), strftime("%Y-%m-%d %H:%M:%S", $event->getStart()), $lineNumber + 1)
                                ),
                                '',
                                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                            );
                            continue;
                            //===
                        }

                        //======================================================================
                        // 4. Handling for prices
                        $event->setCostsReg(0);
                        if ($tempData['costsReg']) {
                            $event->setCostsReg(floatval(str_replace(',', '.', str_replace('.', '', $tempData['costsReg']))));
                        }
                        if ($tempData['costsRed']) {
                            $event->setCostsRed(floatval(str_replace(',', '.', str_replace('.', '', $tempData['costsRed']))));

                            if ($tempData['costsRedCondition']) {
                                $event->setCostsRedCondition($tempData['costsRedCondition']);
                            } else {
                                if (preg_match('/^[0-9,\.]+[ ]?(Euro|€)(.+)$/', $tempData['costsRed'], $tempMatch)) {
                                    $event->setCostsRedCondition(trim($tempMatch[2]));
                                }
                            }
                        }
                        if ($tempData['costsTaxIncluded']) {
                            $event->setCostsTaxIncluded(true);
                        }

                        /** @var \SJBR\StaticInfoTables\Domain\Repository\CountryRepository $countryRepository */
                        $currencyRepository = $this->objectManager->get('SJBR\\StaticInfoTables\\Domain\\Repository\\CurrencyRepository');
                        if ($tempData['currency']) {
                            $currencyCode = $tempData['currency'];
                        }

                        /** @var \SJBR\StaticInfoTables\Domain\Model\Currency $currency */
                        $currency = $currencyRepository->findOneByIsoCodeA3($currencyCode);
                        if ($currency) {
                            $event->setCurrency($currency);
                        } else {
                            $this->addFlashMessage(
                                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                    'backendController.error.invalidCurrencyCode',
                                    'rkw_events',
                                    array($currencyCode, $lineNumber + 1)
                                ),
                                '',
                                \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
                            );
                        }

                        //======================================================================
                        // 5. Check for place
                        if (
                            $tempData['address']
                            && $tempData['zip']
                            && $tempData['city']
                        ) {

                            // fix zip
                            $zip = $tempData['zip'];
                            if ($countryCode == 'DE') {
                                $zip = (str_pad($tempData['zip'], 5, 0, STR_PAD_LEFT));
                            }

                            /** @var \RKW\RkwGeolocation\Service\Geolocation $geoLocation */
                            $geoLocation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwGeolocation\\Service\\Geolocation');

                            // 5.1 check for existing one
                            $eventPlaces = $this->eventPlaceRepository->findByAddressZipCityForImport($pid, $tempData['address'], $zip, $tempData['city']);
                            if (count($eventPlaces) > 0) {

                                if (count($eventPlaces) == 1) {

                                    /** @var \RKW\RkwEvents\Domain\Model\EventPlace $eventPlace */
                                    $eventPlace = $eventPlaces->getFirst();
                                    $event->setPlace($eventPlace);
                                    $event->setLongitude($eventPlace->getLongitude());
                                    $event->setLatitude($eventPlace->getLatitude());

                                } else {

                                    $this->addFlashMessage(
                                        \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                            'backendController.error.moreThanOnePlace',
                                            'rkw_events',
                                            array($lineNumber + 1)
                                        ),
                                        '',
                                        \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
                                    );
                                }

                                // 5.2 create new one
                            } else {

                                /** @var \RKW\RkwEvents\Domain\Model\EventPlace $eventPlace */
                                $eventPlace = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance("RKW\\RkwEvents\\Domain\\Model\\EventPlace");

                                /** @var \SJBR\StaticInfoTables\Domain\Repository\CountryRepository $countryRepository */
                                $countryRepository = $this->objectManager->get('SJBR\\StaticInfoTables\\Domain\\Repository\\CountryRepository');

                                // set PID
                                $eventPlace->setPid($pid);

                                $eventPlace->setName($tempData['placeName']);
                                $eventPlace->setAddress($tempData['address']);
                                $eventPlace->setZip($zip);
                                $eventPlace->setCity($tempData['city']);
                                $geoLocation->setAddress($eventPlace->getAddress() . ',' . $eventPlace->getZip() . ' ' . $eventPlace->getCity());

                                if ($tempData['country']) {
                                    $countryCode = $tempData['country'];
                                }

                                /** @var \SJBR\StaticInfoTables\Domain\Model\Country $country */
                                $country = $countryRepository->findOneByIsoCodeA2($countryCode);
                                if ($country) {
                                    $eventPlace->setCountry($country);
                                    $geoLocation->setCountry($country->getShortNameEn());
                                } else {
                                    $this->addFlashMessage(
                                        \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                            'backendController.error.invalidCountryCode',
                                            'rkw_events',
                                            array($countryCode, $lineNumber + 1)
                                        ),
                                        '',
                                        \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
                                    );
                                }

                                // in every case we need the geoData
                                try {

                                    /** @var \RKW\RkwGeolocation\Domain\Model\Geolocation $geoData */
                                    $geoData = $geoLocation->fetchGeoData();
                                    if ($geoData) {
                                        $eventPlace->setLongitude($geoData->getLongitude());
                                        $eventPlace->setLatitude($geoData->getLatitude());

                                    } else {
                                        throw new \Exception ('No geoData available.');
                                        //===
                                    }

                                } catch (\Exception $e) {
                                    $this->addFlashMessage(
                                        \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                            'backendController.error.geoDataApiOffline',
                                            'rkw_events',
                                            array($e->getMessage(), $lineNumber + 1)
                                        ),
                                        '',
                                        \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
                                    );
                                }

                                $importPlaceCounter++;
                                if ($doImport) {
                                    $this->eventPlaceRepository->add($eventPlace);
                                    $this->persistenceManager->persistAll();
                                    $event->setPlace($eventPlace);
                                    $event->setLongitude($eventPlace->getLongitude());
                                    $event->setLatitude($eventPlace->getLatitude());
                                }
                            }


                        } else {

                            // throw only an error, if it's NOT an announcement (an announcement does not need a place)
                            if (!$tempData['isAnnouncement']) {
                                $this->addFlashMessage(
                                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                        'backendController.error.noEventLocationGiven',
                                        'rkw_events',
                                        array($lineNumber + 1)
                                    ),
                                    '',
                                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                                );
                                continue;
                                //===
                            }


                        }

                        //======================================================================
                        // 6. Check for contact
                        foreach (range(1, 10) as $contactNumber) {

                            if ($tempData['contact' . $contactNumber . 'Email']) {



                                // 6.1 check for be-users (only if there is no external registration link)
                                /** @var \RKW\RkwEvents\Domain\Model\BackendUser $beUser */
                                $beUser = $this->backendUserRepository->findOneByEmail($tempData['contact' . $contactNumber . 'Email']);
                                if (
                                    $beUser
                                    && !$tempData['extRegLink']
                                ) {
                                    $event->addBeUser($beUser);

                                // 6.2 check for internal contact
                                } else if (
                                    (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('rkw_authors'))

                                    /** @var \RKW\RkwEvents\Domain\Repository\AuthorsRepository $authorsRepository */
                                    && ($authorsRepository = $this->objectManager->get('RKW\\RkwEvents\\Domain\\Repository\\AuthorsRepository'))

                                    /** @var \RKW\RkwEvents\Domain\Model\Authors $internalContact */
                                    && ($internalContact = $authorsRepository->findOneInternalByEmailForImport($pidAuthors, $tempData['contact' . $contactNumber . 'Email']))
                                ){
                                    $event->addInternalContact($internalContact);


                                // 6.3 check for external contact
                                /** @var  \RKW\RkwEvents\Domain\Model\EventContact */
                                } elseif ($externalContact = $this->eventContactRepository->findOneByEmailForImport($pid, $tempData['contact' . $contactNumber . 'Email'])) {
                                    $event->addExternalContact($externalContact);

                                // 6.4 create new one
                                } else {

                                    /** @var \RKW\RkwEvents\Domain\Model\EventContact $eventContact */
                                    $eventContact = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance("RKW\\RkwEvents\\Domain\\Model\\EventContact");

                                    // set PID
                                    $eventContact->setPid($pid);

                                    if (
                                        ($tempData['contact' . $contactNumber . 'FirstName'])
                                        && ($tempData['contact' . $contactNumber . 'LastName'])
                                    ) {
                                        $eventContact->setFirstName($tempData['contact' . $contactNumber . 'FirstName']);
                                        $eventContact->setLastName($tempData['contact' . $contactNumber . 'LastName']);

                                    } elseif ($tempData['contact' . $contactNumber . 'Name']) {

                                        // Delete "Dr." and split
                                        $nameBase = trim(str_replace('Dr.', '', $tempData['contact' . $contactNumber . 'Name']));
                                        $nameSplit = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(' ', $nameBase);
                                        if (count($nameSplit) == 2) {
                                            $eventContact->setFirstName($nameSplit[0]);
                                            $eventContact->setLastName($nameSplit[1]);
                                        }
                                    }

                                    if ($tempData['contact' . $contactNumber . 'Company']) {
                                        $eventContact->setCompany($tempData['contact' . $contactNumber . 'Company']);
                                    }
                                    if ($tempData['contact' . $contactNumber . 'Address']) {
                                        $eventContact->setAddress($tempData['contact' . $contactNumber . 'Address']);
                                    }
                                    if ($tempData['contact' . $contactNumber . 'City']) {
                                        $eventContact->setCity($tempData['contactCity']);
                                    }
                                    if ($tempData['contact' . $contactNumber . 'Zip']) {
                                        $eventContact->setZip($tempData['contact' . $contactNumber . 'Zip']);
                                        if ($countryCode == 'DE') {
                                            $eventContact->setZip(str_pad($tempData['contact' . $contactNumber . 'Zip'], 5, 0, STR_PAD_LEFT));
                                        }
                                    }
                                    if ($tempData['contact' . $contactNumber . 'Phone']) {
                                        $eventContact->setTelephone($tempData['contactPhone']);
                                    }

                                    $eventContact->setEmail($tempData['contact' . $contactNumber . 'Email']);

                                    $importExternalContactCounter++;
                                    if ($doImport) {
                                        $this->eventContactRepository->add($eventContact);
                                        $this->persistenceManager->persistAll();
                                        $event->addExternalContact($eventContact);
                                    }

                                }
                            }
                        }

                        // check if events with internal contacts and no external ones have an BE-User set!
                        if (
                            ($event->getRegRequired())
                            && ($event->getInternalContact() > 0)
                            && ($event->getExternalContact() == 0)
                            && ($event->getBeUser() == 0)
                        ) {

                            $this->addFlashMessage(
                                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                    'backendController.error.noBackendUserFound',
                                    'rkw_events',
                                    array($lineNumber + 1)
                                ),
                                '',
                                \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
                            );
                        }

                        //======================================================================
                        // 7. Check for department
                        if ($topicId) {

                            /** @var \RKW\RkwBasics\Domain\Model\Department $department */
                            if ($department = $this->departmentRepository->findByIdentifier(intval($topicId))) {
                                $event->setDepartment($department);

                            } else {
                                $this->addFlashMessage(
                                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                        'backendController.error.departmentNotFound',
                                        'rkw_events',
                                        array(intval($topicId), $lineNumber + 1)
                                    ),
                                    '',
                                    \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
                                );
                            }
                        }

                        //======================================================================
                        // 8. Check for type
                        if ($typeId) {

                            /** @var \RKW\RkwBasics\Domain\Model\DocumentType $documentType */
                            if ($documentType = $this->documentTypeRepository->findOneByIdAndType(intval($typeId), 'events')) {
                                $event->setDocumentType($documentType);

                            } else {
                                $this->addFlashMessage(
                                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                        'backendController.error.documentTypeNotFound',
                                        'rkw_events',
                                        array(intval($tempData['typeId']), $lineNumber + 1)
                                    ),
                                    '',
                                    \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
                                );
                            }
                        }


                        //======================================================================
                        // 8. Check for organizer
                        if ($organizerId) {

                            /** @var \RKW\RkwEvents\Domain\Model\EventOrganizer $organizer */
                            if ($organizer = $this->eventOrganizerRepository->findByIdentifier(intval($organizerId))) {
                                $event->addOrganizer($organizer);

                            } else {
                                $this->addFlashMessage(
                                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                        'backendController.error.organizerNotFound',
                                        'rkw_events',
                                        array(intval($tempData['organizerId']), $lineNumber + 1)
                                    ),
                                    '',
                                    \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
                                );
                            }
                        }

                        $importCounter++;
                        if ($doImport) {
                            $this->eventRepository->add($event);
                            $this->persistenceManager->persistAll();
                        }

                    } elseif (count($tempData)) {

                        $this->addFlashMessage(
                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                'backendController.error.noBasicDataGiven',
                                'rkw_events',
                                array($lineNumber + 1)
                            ),
                            '',
                            \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                        );
                        continue;
                        //===
                    }

                }
            }
        }

        if ($importCounter < 1) {

            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'backendController.error.nothingImported',
                    'rkw_events'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
            );

        } else {

            if ($doImport) {
                $this->addFlashMessage(
                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        'backendController.error.importSuccessfull',
                        'rkw_events',
                        array($importCounter, $importPlaceCounter, $importExternalContactCounter)
                    ),
                    '',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::OK
                );
            } else {
                $this->addFlashMessage(
                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        'backendController.error.importCheckSuccessfull',
                        'rkw_events',
                        array($importCounter, $importPlaceCounter, $importExternalContactCounter)
                    ),
                    '',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::OK
                );
            }
        }

        $this->redirect('show');

    }


    /**
     * Cleans given string for import
     *
     * @param string $string
     * @return string
     */
    protected function parseHtml($string)
    {
        // add p-tag-wrap
        if (strip_tags($string) == $string) {
            $string = '<p>' . nl2br($string) . '</p>';
        }

        // get replacement for UL-lists
        // $string = preg_replace('/[\\]{1}[n]{1}\* (.*?)/', '<ul><li>$1</li></ul>', $string);
        // $string = preg_replace('/<\/ul><ul>/', '', $string);

        return trim($string);
        //===
    }


    /**
     * Cleans given string for import
     *
     * @param string $string
     * @return string
     */
    protected function stringCleanUp($string)
    {
        // configuration for HTML-Cleanup
        $tagCfg = array(
            'br' => array(),
            'a'  => array(),
            'p'  => array(),
            'ul' => array(
                'nesting' => 1,
            ),
            'ol' => array(
                'nesting' => 1,
            ),
            'li' => array(
                'nesting' => 1,
            ),

        );
        $additionalConfig = array(
            'stripEmptyTags' => 1,
        );

        /** @var \TYPO3\CMS\Core\Html\HtmlParser $parseObj */
        $parseObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Html\\HtmlParser');
        $string = $parseObj->HTMLcleaner(trim($string), $tagCfg, 0, 0, $additionalConfig);
        $string = str_replace('\n', "", trim($string));
        $string = str_replace('<p><p>', '<p>', trim($string));
        $string = str_replace('</p></p>', '</p>', trim($string));
        $string = str_replace('&shy;', '', trim($string));

        return trim($string);
        //===
    }
}