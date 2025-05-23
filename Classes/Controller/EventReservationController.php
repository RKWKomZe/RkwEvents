<?php
namespace RKW\RkwEvents\Controller;


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


use Madj2k\CoreExtended\Utility\GeneralUtility as Common;
use Madj2k\FeRegister\Domain\Model\FrontendUser;
use Madj2k\FeRegister\Registration\FrontendUserRegistration;
use Madj2k\FeRegister\Utility\FrontendUserSessionUtility;
use Madj2k\FeRegister\Utility\FrontendUserUtility;
use RKW\RkwEvents\Domain\Model\Event;
use RKW\RkwEvents\Domain\Model\EventReservation;
use RKW\RkwEvents\Domain\Model\EventWorkshop;
use RKW\RkwEvents\Domain\Repository\BackendUserRepository;
use RKW\RkwEvents\Domain\Repository\CategoryRepository;
use RKW\RkwEvents\Domain\Repository\EventRepository;
use RKW\RkwEvents\Domain\Repository\EventReservationAddPersonRepository;
use RKW\RkwEvents\Domain\Repository\EventReservationRepository;
use RKW\RkwEvents\Domain\Repository\EventWorkshopRepository;
use RKW\RkwEvents\Domain\Repository\FrontendUserRepository;
use RKW\RkwEvents\Utility\DivUtility;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;


/**
 * EventReservationController
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @author Christian Dilger <c.dilger@addorange.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventReservationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_RESERVATION_CREATED_ADMIN = 'afterReservationCreatedAdmin';


    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_RESERVATION_CREATED_USER = 'afterReservationCreatedUser';


    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_RESERVATION_UPDATE_ADMIN = 'afterUpdateReservationAdmin';


    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_RESERVATION_UPDATE_USER = 'afterUpdateReservationUser';


    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_RESERVATION_DELETE_ADMIN = 'afterDeleteReservationAdmin';


    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_RESERVATION_DELETE_USER = 'afterDeleteReservationUser';


    /**
     * @var \RKW\RkwEvents\Domain\Repository\EventRepository
     */
    protected ?EventRepository $eventRepository;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\EventReservationRepository
     */
    protected ?EventReservationRepository $eventReservationRepository;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\EventReservationAddPersonRepository
     */
    protected ?EventReservationAddPersonRepository $eventReservationAddPersonRepository;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\EventWorkshopRepository
     */
    protected ?EventWorkshopRepository $eventWorkshopRepository;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\BackendUserRepository
     */
    protected ?BackendUserRepository $backendUserRepository;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\CategoryRepository
     */
    protected ?CategoryRepository $categoryRepository;


    /**
     * @var \RKW\RkwEvents\Domain\Repository\FrontendUserRepository
     */
    protected ?FrontendUserRepository $frontendUserRepository;


    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected ?PersistenceManager $persistenceManager;


    /**
     * @var \RKW\RkwEvents\Domain\Model\FrontendUser
     */
    protected $frontendUser = null;


    /**
     * @var \TYPO3\CMS\Core\Log\Logger
     */
    protected ?Logger $logger = null;


    /**
     * @param EventRepository $eventRepository,
     * @param EventReservationRepository $eventReservationRepository,
     * @param EventReservationAddPersonRepository $eventReservationAddPersonRepository,
     * @param EventWorkshopRepository $eventWorkshopRepository,
     * @param BackendUserRepository $backendUserRepository,
     * @param CategoryRepository $categoryRepository,
     * @param FrontendUserRepository $frontendUserRepository,
     * @param PersistenceManager $persistenceManager
    */
    public function __construct(
        EventRepository $eventRepository,
        EventReservationRepository $eventReservationRepository,
        EventReservationAddPersonRepository $eventReservationAddPersonRepository,
        EventWorkshopRepository $eventWorkshopRepository,
        BackendUserRepository $backendUserRepository,
        CategoryRepository $categoryRepository,
        FrontendUserRepository $frontendUserRepository,
        PersistenceManager $persistenceManager
    ) {
        $this->eventRepository = $eventRepository;
        $this->eventReservationRepository = $eventReservationRepository;
        $this->eventReservationAddPersonRepository = $eventReservationAddPersonRepository;
        $this->eventWorkshopRepository = $eventWorkshopRepository;
        $this->backendUserRepository = $backendUserRepository;
        $this->categoryRepository = $categoryRepository;
        $this->frontendUserRepository = $frontendUserRepository;
        $this->persistenceManager = $persistenceManager;
    }


    /**
     * initializeAction
     * !! The whole controller depends on the FeRegister Extension. Throw an error on dependency problems !!
     *
     * @return void
     */
    public function initializeAction(): void
    {
        if (!\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('fe_register')) {
            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::ERROR,
               'Error while initializing EventReservationController: Required extension FeRegister is not loaded!'
            );
            trigger_error(
                'Error while initializing EventReservationController: Required extension FeRegister is not loaded!',
                E_USER_ERROR
            );
            exit;
        }
    }


    /**
     * action new
     * Hint: Set default $event-Param to null for possibility to lead users,if event link is not longer available
     *
     * @param Event|null $event
     * @param EventReservation|null $newEventReservation
     * @param int $targetGroup
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("event")
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("newEventReservation")
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function newAction(Event $event = null, EventReservation $newEventReservation = null, int $targetGroup = 0): void
    {
        // catch all people who are trying to visit a dead reservation link
        if (!$event instanceof Event) {
            $this->redirect('show', 'Event', null, [], $this->settings['showPid']);
        }

        if ($this->getFrontendUser()) {

            $eventReservationResult = $this->eventReservationRepository->findByEventAndFeUser($event, $this->getFrontendUser());
            if (count($eventReservationResult)) {

                // already registered!
                $this->addFlashMessage(
                    LocalizationUtility::translate('eventReservationController.error.exists', 'rkw_events')
                );

                $uri = $this->uriBuilder->reset()
                    ->setTargetPageUid($this->settings['myEventsPid'])
                    ->uriFor('myEvents', null, 'Event', null, 'Pi1');

                $this->addFlashMessage(
                    LocalizationUtility::translate(
                        'eventReservationController.hint.reservations',
                        'rkw_events',
                        [
                            0 => "<a href='" . $uri . "'>",
                            1 => "</a>",
                        ]
                    )
                );

                // already registered
                $this->redirect('show', 'Event', null, ['event' => $event], (int)$this->settings['showPid']);
            }
        }

        if (!$newEventReservation) {
            $newEventReservation = GeneralUtility::makeInstance('RKW\\RkwEvents\\Domain\\Model\\EventReservation');
        }

        $newEventReservation->setEvent($event);

        $this->view->assign('event', $event);
        $this->view->assign('newEventReservation', $newEventReservation);
        $this->view->assign('frontendUser', $this->getFrontendUser());
        if ($this->getFrontendUser()) {
            $this->view->assign('validFrontendUserEmail', \Madj2k\FeRegister\Utility\FrontendUserUtility::isEmailValid($this->getFrontendUser()->getEmail()));
        }

        $this->view->assign('targetGroupList', $this->categoryRepository->findChildrenByParent((int)$this->settings['targetGroupsPid']));
        $this->view->assign('targetGroup', $targetGroup);
    }


    /**
     * action new
     *
     * @param Event|null            $event
     * @param EventReservation|null $newEventReservation
     * @param int                   $targetGroup
     * @return void
     * @throws AspectNotFoundException
     * @throws InvalidQueryException
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("event")
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("newEventReservation")
     */
    public function newStandaloneAction(
        Event $event = null,
        EventReservation $newEventReservation = null,
        int $targetGroup = 0
    ): void
    {

        if ((int)$this->settings['eventRegisterStandalone']) {

            // if an event is already given (and not read via flexform), we're coming from the "createAction"
            // hide form in fluid (for usability reasons)
            // Hint: Actually we can't work with an additional parameter, because we get an #1537633463 OutOfRangeException...
            // ... because the plugin name is longer than 30 signs: tx_rkwevents_standaloneregister
            // @toDo: Improve this solution through update to a shorter plugin name
            if ($event instanceof Event) {
                $this->view->assign('successfullyRegistered', true);
            }

            /** @var Event $event */
            $event = $this->eventRepository->findByUid((int)$this->settings['eventRegisterStandalone']);

            if (!$newEventReservation) {
                $newEventReservation = GeneralUtility::makeInstance(\RKW\RkwEvents\Domain\Model\EventReservation::class);
            }

            $newEventReservation->setEvent($event);

            $this->view->assign('event', $event);
            $this->view->assign('newEventReservation', $newEventReservation);
            $this->view->assign('frontendUser', $this->getFrontendUser());
            $this->view->assign('noBackButton', true);
            if ($this->getFrontendUser()) {
                $this->view->assign('validFrontendUserEmail', \Madj2k\FeRegister\Utility\FrontendUserUtility::isEmailValid($this->getFrontendUser()->getEmail()));
            }

            $this->view->assign('targetGroupList', $this->categoryRepository->findChildrenByParent((int)$this->settings['targetGroupsPid']));
            $this->view->assign('targetGroup', $targetGroup);

        }
    }


    /**
     * initializeCreateAction
     * If workshop is multiple choice, we have to handle not selected checkboxes (kill them)
     * -> Exception while property mapping at property path "workshopRegister": PHP Warning: spl_object_hash() expects parameter
     * 1 to be object, null given in /var/www/rkw-website-composer/web/typo3/sysext/extbase/Classes/Persistence/ObjectStorage.php
     * line 152
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentNameException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     */
    public function initializeCreateAction(): void
    {
        if ($this->request->hasArgument('newEventReservation')) {

            $newEventReservation = $this->request->getArgument('newEventReservation');
            if (array_key_exists('workshopRegister', $newEventReservation)) {
                // remove not chosen entries from workshopRegister-property
                $newEventReservation['workshopRegister'] = array_filter($newEventReservation['workshopRegister']);
                // re-set filtered element
                $this->request->setArgument('newEventReservation', $newEventReservation);
            }
        }
    }


    /**
     * action create
     *
     * @param EventReservation $newEventReservation
     * @param int $targetGroup
     * @return void
     * @throws \Madj2k\FeRegister\Exception
     * @throws \TYPO3\CMS\Core\Context\Exception\AspectNotFoundException
     * @throws \TYPO3\CMS\Core\Crypto\PasswordHashing\InvalidPasswordHashException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\NotImplementedException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @TYPO3\CMS\Extbase\Annotation\Validate("RKW\RkwEvents\Validation\Validator\EventReservationValidator", param="newEventReservation")
     * @TYPO3\CMS\Extbase\Annotation\Validate("Madj2k\FeRegister\Validation\Consent\TermsValidator", param="newEventReservation")
     * @TYPO3\CMS\Extbase\Annotation\Validate("Madj2k\FeRegister\Validation\Consent\PrivacyValidator", param="newEventReservation")
     * @TYPO3\CMS\Extbase\Annotation\Validate("Madj2k\FeRegister\Validation\Consent\MarketingValidator", param="newEventReservation")
     * @TYPO3\CMS\Extbase\Annotation\Validate("Madj2k\CoreExtended\Validation\CaptchaValidator", param="newEventReservation")
     */
    public function createAction(EventReservation $newEventReservation, int $targetGroup = 0): void
    {
        // standard behavior
        $showAction = 'show';
        $newAction = 'new';
        $controller = 'Event';

        // if we're in registerstandalone plugin, always use "newStandalone" as forward action
        if ($this->request->getPluginName() === 'Standalonereg') {
            // override showPid. Use current PID instead
           // $this->settings['showPid'] = intval($GLOBALS['TSFE']->id);
            // set different action and controller name
            $showAction = 'newStandalone';
            $newAction = 'newStandalone';
            $controller = 'EventReservation';
        }

        // 1. Check for existing reservations based on email.
        $frontendUser = $this->frontendUserRepository->findByUsername($newEventReservation->getEmail());
        if (count($frontendUser)) {

            $eventReservationResult = $this->eventReservationRepository->findByEventAndFeUser($newEventReservation->getEvent(), $frontendUser);
            if (count($eventReservationResult)) {

                // already registered!
                $this->addFlashMessage(
                    LocalizationUtility::translate(
                        'eventReservationController.error.exists', 'rkw_events'
                    ),
                    '',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                );

                if ($this->getFrontendUser()) {
                    // if user is logged in, set direct link to feusers event list
                    $uri = $this->uriBuilder->reset()
                        ->setTargetPageUid($this->settings['myEventsPid'])
                        ->uriFor('myEvents', null, 'Event', null, 'Pi1');
                } else {
                    // else just set a link to myrkw login
                    $uri = $this->uriBuilder->reset()
                        ->setTargetPageUid($this->settings['loginPid'])
                        ->build();
                }

                $this->addFlashMessage(
                    LocalizationUtility::translate(
                        'eventReservationController.hint.reservations',
                        'rkw_events',
                        [
                            0 => "<a href='" . $uri . "'>",
                            1 => "</a>",
                        ]
                    )
                );

                // already registered
                if ($this->request->getPluginName() === 'Standalonereg') {
                    $this->forward($showAction, $controller, null, ['newEventReservation' => $newEventReservation, 'event' => $newEventReservation->getEvent()], (int)$this->settings['showPid']);
                }
                $this->redirect($showAction, $controller, null, ['event' => $newEventReservation->getEvent()], (int)$this->settings['showPid']);
            }
        }

        // 2. Check available seats
        if (!DivUtility::hasFreeSeats($newEventReservation->getEvent(), $newEventReservation)) {

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'eventReservationController.error.seats', 'rkw_events'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );

            if ($this->request->getPluginName() === 'Standalonereg') {
                $this->forward($showAction, $controller, null, ['newEventReservation' => $newEventReservation, 'event' => $newEventReservation->getEvent()], (int)$this->settings['showPid']);
            }
            $this->redirect($showAction, $controller, null, ['event' => $newEventReservation->getEvent()], (int)$this->settings['showPid']);
        }

        // 3. Check if registration-time is over since the user may have been waiting too long
        if (DivUtility::hasRegTimeEnded($newEventReservation->getEvent())) {

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'eventReservationController.error.registration_time', 'rkw_events'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );

            if ($this->request->getPluginName() === 'Standalonereg') {
                $this->forward($showAction, $controller, null, ['newEventReservation' => $newEventReservation, 'event' => $newEventReservation->getEvent()], (int)$this->settings['showPid']);
            }
            $this->redirect($showAction, $controller, null, ['newEventReservation' => $newEventReservation, 'event' => $newEventReservation->getEvent()], (int)$this->settings['showPid']);
        }

        // 5. check if email is valid
        if (!\Madj2k\FeRegister\Utility\FrontendUserUtility::isEmailValid($newEventReservation->getEmail())) {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'eventReservationController.error.no_valid_email', 'rkw_events'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );

            $this->forward($newAction, null, null, ['newEventReservation' => $newEventReservation, 'event' => $newEventReservation->getEvent()]);
        }

        // check targetGroup
        if ($targetGroup) {
            $newEventReservation->addTargetGroup($this->categoryRepository->findByUid($targetGroup));
        }

        // if user is logged in and has a valid email, create the reservation now!
        if (
            ($this->getFrontendUser())
            && (\Madj2k\FeRegister\Utility\FrontendUserUtility::isEmailValid($this->getFrontendUser()->getEmail()))
        ) {
            // for standardization for reservation creation (also possible with optin)
            $this->finalSaveReservation($newEventReservation, $this->getFrontendUser());

            // add privacy info
            \Madj2k\FeRegister\DataProtection\ConsentHandler::add(
                $this->request,
                $this->getFrontendUser(),
                $newEventReservation,
                'new event reservation'
            );

            $this->addFlashMessage(
                LocalizationUtility::translate('eventReservationController.message.reservationCreated', 'rkw_events'),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::OK
            );

        } else {

            // check if email is not already used - relevant for logged in users with no email-address (e.g. via Facebook or Twitter)
            if (
                ($this->getFrontendUser())
                && (!\Madj2k\FeRegister\Utility\FrontendUserUtility::isUsernameUnique($newEventReservation->getEmail()))
            ) {

                $this->addFlashMessage(
                    LocalizationUtility::translate(
                        'eventReservationController.error.email_already_in_use', 'rkw_events'
                    ),
                    '',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                );

                $this->forward($newAction, null, null, ['newEventReservation' => $newEventReservation, 'event' => $newEventReservation->getEvent()]);
            }

            // register new user or simply send opt-in to existing user
            /** @var \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser */
            $frontendUser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(FrontendUser::class);
            $frontendUser->setTxFeregisterGender($newEventReservation->getSalutation());
            $frontendUser->setFirstName($newEventReservation->getFirstName());
            $frontendUser->setLastName($newEventReservation->getLastName());
            $frontendUser->setCompany($newEventReservation->getCompany());
            $frontendUser->setAddress($newEventReservation->getAddress());
            $frontendUser->setZip($newEventReservation->getZip());
            $frontendUser->setCity($newEventReservation->getCity());
            $frontendUser->setEmail($newEventReservation->getEmail());

            /** @var \Madj2k\FeRegister\Registration\FrontendUserRegistration $registration */
            $registration = $this->objectManager->get(FrontendUserRegistration::class);
            $registration->setFrontendUser($frontendUser)
                ->setData($newEventReservation)
                ->setDataParent($newEventReservation->getEvent())
                ->setCategory('rkwEvents')
                ->setRequest($this->request)
                ->startRegistration();

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'eventReservationController.message.reservationCreatedEmail', 'rkw_events',
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::OK
            );
        }

        if ($this->request->getPluginName() === 'Standalonereg') {
            $this->redirect($showAction, $controller, null, ['event' => $newEventReservation->getEvent()]);
        }

        $this->redirect($showAction, $controller, null, ['event' => $newEventReservation->getEvent()], (int)$this->settings['showPid']);
    }


    /**
     * action optIn
     * Comment by Maximilian Fäßler: We get tricky validation issues here (https://rkwticket.rkw.de/issues/2803)
     * -> So we ignore the validation itself and checking with internal alias "instanceof" for trustful data
     * -> Benefit: We can set more helpful error messages for frontend users
     *
     * @param Event $event
     * @param string $tokenUser
     * @param string $token
     * @return void
     * @throws \Madj2k\FeRegister\Exception
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\NotImplementedException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\TooDirtyException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("event")
     */
    public function optInAction(Event $event, string $tokenUser, string $token): void
    {
        // standard behavior
        $showAction = 'show';
        $controller = 'Event';

        // if we're in registerstandalone plugin, always use "newStandalone" as forward action
        if ($this->request->getPluginName() === 'Standalonereg') {
            $showAction = 'newStandalone';
            $controller = 'EventReservation';
        }

        // General error:
        if (
            !$event instanceof Event
            || $event->_isDirty()
        ) {
            $this->addFlashMessage(
                LocalizationUtility::translate('eventReservationController.error.somethingWentWrong', 'rkw_events'),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            if ($this->request->getPluginName() === 'Standalonereg') {
                $this->forward($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
            }
            $this->redirect($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
        }

        // Check if the event is still open for internal registration
        if (
            $event->getExtRegLink()
            || !$event->getRegRequired()
        ) {
            $this->addFlashMessage(
                LocalizationUtility::translate('eventReservationController.error.optInDisabled', 'rkw_events'),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            if ($this->request->getPluginName() === 'Standalonereg') {
                $this->forward($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
            }
            $this->redirect($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
        }

        /** @var \Madj2k\FeRegister\Registration\FrontendUserRegistration $registration */
        $registration = $this->objectManager->get(FrontendUserRegistration::class);
        $result = $registration->setFrontendUserToken($tokenUser)
            ->setCategory('rkwEvents')
            ->setRequest($this->request)
            ->validateOptIn($token);

        if (
            ($result >= 200 && $result < 300)
            && ($optIn = $registration->getOptInPersisted())
            && ($newEventReservation = $optIn->getData())
            && ($newEventReservation instanceof EventReservation)
            && ($feUser = $registration->getFrontendUserPersisted())
        ) {

            // 1. we need to re-fetch the event here, since the number of available seats or the dates may have been changed
            if ($result == 200) {

                /** @var Event $event */
                $event = $this->eventRepository->findByIdentifier($newEventReservation->getEvent()->getUid());
                $newEventReservation->setEvent($event);

                // 2. Check for existing reservations based on email.
                $eventReservationResult = $this->eventReservationRepository->findByEventAndFeUser($event, $feUser);
                if (count($eventReservationResult)) {

                    $this->addFlashMessage(
                        LocalizationUtility::translate('eventReservationController.error.exists', 'rkw_events'),
                        '',
                        \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                    );

                    $uri = $this->uriBuilder->reset()
                        ->setTargetPageUid($this->settings['loginPid'])
                        ->build();

                    $this->addFlashMessage(
                        LocalizationUtility::translate(
                            'eventReservationController.hint.reservations',
                            'rkw_events',
                            [
                                0 => "<a href='" . $uri . "'>",
                                1 => "</a>",
                            ]
                        )
                    );
                    if ($this->request->getPluginName() === 'Standalonereg') {
                        $this->forward($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
                    }
                    $this->redirect($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
                }

                // 3. Check available places
                if (!DivUtility::hasFreeSeats($event, $newEventReservation)) {

                    $this->addFlashMessage(
                        LocalizationUtility::translate('eventReservationController.error.seats', 'rkw_events'),
                        '',
                        \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                    );
                    if ($this->request->getPluginName() === 'Standalonereg') {
                        $this->forward($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
                    }
                    $this->redirect($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
                }

                // 4. Check if registration-time is over since the user may have been waiting too long
                if (DivUtility::hasRegTimeEnded($event)) {

                    $this->addFlashMessage(
                        LocalizationUtility::translate('eventReservationController.error.registration_time', 'rkw_events'),
                        '',
                        \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                    );
                    if ($this->request->getPluginName() === 'Standalonereg') {
                        $this->forward($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
                    }
                    $this->redirect($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
                }


                // 5. Create reservation!
                $this->finalSaveReservation($newEventReservation, $feUser, $optIn);

                $this->addFlashMessage(
                    LocalizationUtility::translate(
                        'eventReservationController.message.reservationCreated', 'rkw_events'
                    )
                );
                if ($this->request->getPluginName() === 'Standalonereg') {
                    $this->forward($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
                }
                $this->redirect($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
            }


        } elseif ($result >= 300 && $result < 400) {

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'eventReservationController.message.reservationCanceled', 'rkw_events'
                )
            );
            if ($this->request->getPluginName() === 'Standalonereg') {
                $this->forward($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
            }
            $this->redirect($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
        }


        $this->addFlashMessage(
            LocalizationUtility::translate(
                'eventReservationController.error.reservationError', 'rkw_events'
            ),
            '',
            \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
        );
        if ($this->request->getPluginName() === 'Standalonereg') {
            $this->forward($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
        }
        $this->redirect($showAction, $controller, null, ['event' => $event], (int)$this->settings['showPid']);
    }


    /**
     * action show
     *
     * @param EventReservation $eventReservation
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function showAction($eventReservation): void
    {
        if ($this->getFrontendUser()) {

            //security check
            if ($eventReservation->getFeUser() != $this->getFrontendUser()) {

                $this->addFlashMessage(
                    LocalizationUtility::translate(
                        'eventReservationController.message.error.notPermitted', 'rkw_events'
                    ),
                    '',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                );

                $this->redirect('list', 'Event', null, [], $this->settings['listPid']);
                //===
            }

            $event = $eventReservation->getEvent();
            $this->view->assign('eventReservation', $eventReservation);
            $this->view->assign('event', $event);

        } else {

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'eventReservationController.message.noUserLoggedIn', 'rkw_events'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );

            $this->redirect('list', 'Event', null, [], $this->settings['listPid']);
            //===
        }
    }


    /**
     * action edit
     *
     * @param EventReservation $eventReservation
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("eventReservation")
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function editAction(EventReservation $eventReservation): void
    {
        if ($this->getFrontendUser()) {

            //security check
            if ($eventReservation->getFeUser() != $this->getFrontendUser()) {

                $this->addFlashMessage(
                    LocalizationUtility::translate(
                        'eventReservationController.message.error.notPermitted', 'rkw_events'
                    ),
                    '',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                );

                $this->redirect('list', 'Event', null, [], $this->settings['listPid']);
                //===
            }

            // this "editMode" is a sign / marker and a simple way to use the FormFields-Partial in multiple ways
            $this->view->assign('editMode', 1);

            $this->view->assign('eventReservation', $eventReservation);
            $this->view->assign('event', $eventReservation->getEvent());

        } else {

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'eventReservationController.message.noUserLoggedIn', 'rkw_events'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );

            $this->redirect('list', 'Event', null, [], $this->settings['listPid']);
            //===
        }

    }


    /**
     * action update
     *
     * @param EventReservation $eventReservation
     * @return void
     * @TYPO3\CMS\Extbase\Annotation\Validate("RKW\RkwEvents\Validation\Validator\EventReservationValidator", param="eventReservation")
     * @TYPO3\CMS\Extbase\Annotation\Validate("Madj2k\CoreExtended\Validation\CaptchaValidator", param="eventReservation")
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function updateAction(EventReservation $eventReservation): void
    {
        if ($feUser = $this->getFrontendUser()) {

            // 1. security check
            if ($eventReservation->getFeUser() != $feUser) {
                $this->addFlashMessage(
                    LocalizationUtility::translate(
                        'eventReservationController.message.error.notPermitted', 'rkw_events'
                    ),
                    '',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                );

                $this->redirect('list', 'Event', null, [], $this->settings['listPid']);
                //===
            }


            // 2. only add additional persons that have at least one relevant field set
            /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $tempObjectStorage */
            $tempObjectStorage = $this->objectManager->get(ObjectStorage::class);

            // fetch all relevant objects in a temporary object storage
            foreach ($eventReservation->getAddPerson() as $addPerson) {
                if (
                    $addPerson->getFirstName()
                    || $addPerson->getLastName()
                ) {
                    $tempObjectStorage->attach($addPerson);
                }
            }

            // override existing object storage
            $eventReservation->setAddPerson($tempObjectStorage);

            // 2.2 Sub-operation: Register workshop reservations
            $workshopResult = DivUtility::workshopRegistration($eventReservation);
            // if there is no longer place in a workshop, set message. Don't break reservation! Just an info.
            if (!$workshopResult) {
                $this->addFlashMessage(
                    LocalizationUtility::translate(
                        'eventReservationController.error.workshop_full', 'rkw_events'
                    ),
                    '',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO
                );
            }

            // 3. some merging of FE-user
            if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('fe_register')) {
                DivUtility::mergeFeUsers($eventReservation, $feUser);

                /** @var \Madj2k\FeRegister\Domain\Repository\FrontendUserRepository $frontendUserRepository */
                $frontendUserRepository = $this->objectManager->get(\Madj2k\FeRegister\Domain\Repository\FrontendUserRepository::class);
                $frontendUserRepository->update($feUser);
            }

            // 4. finally save the whole stuff
            $this->eventReservationRepository->update($eventReservation);

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'eventReservationController.message.updateSuccess', 'rkw_events'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::OK
            );

            // 5. send final confirmation mail to user
            $this->signalSlotDispatcher->dispatch(__CLASS__, self::SIGNAL_AFTER_RESERVATION_UPDATE_USER, [$feUser, $eventReservation]);

            // 6. send information mail to be-users
            $adminMails = [];
            if ($beUsers = $eventReservation->getEvent()->getBeUser()) {
                /** @var \RKW\RkwEvents\Domain\Model\BackendUser $beUser */
                foreach ($beUsers as $beUser) {
                    if ($beUser->getEmail()) {
                        $adminMails[] = $beUser;
                    }
                }
            }

            // 7. send information mail to external users
            if ($externalContacts = $eventReservation->getEvent()->getExternalContact()) {
                /** @var  $externalContact \RKW\RkwEvents\Domain\Model\EventContact */
                foreach ($externalContacts as $externalContact) {
                    if ($externalContact->getEmail()) {
                        $adminMails[] = $externalContact;
                    }
                }
            }

            // 8. send information mail to admins from TypoScript
            $adminUidList = GeneralUtility::trimExplode(',', $this->settings['mail']['backendUser']);
            if ($adminUidList) {
                foreach ($adminUidList as $adminUid) {

                    /** @var \RKW\RkwEvents\Domain\Model\BackendUser $admin */
                    $admin = $this->backendUserRepository->findByUid($adminUid);
                    if (
                        ($admin)
                        && ($admin->getEmail())
                    ) {
                        $adminMails[] = $admin;
                    }
                }
            }
            $this->signalSlotDispatcher->dispatch(__CLASS__, self::SIGNAL_AFTER_RESERVATION_UPDATE_ADMIN, [$adminMails, $eventReservation]);

            $this->redirect('myEvents', 'Event', null, ['event' => $eventReservation->getEvent()]);
            //===

        } else {

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'eventReservationController.message.noUserLoggedIn', 'rkw_events'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );

            $this->redirect('list', 'Event', null, [], $this->settings['listPid']);
            //===
        }
    }


    /**
     * action delete
     *
     * @param EventReservation $eventReservation
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("eventReservation")
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function deleteAction(EventReservation $eventReservation): void
    {
        if ($this->getFrontendUser()) {

            //security check
            if ($eventReservation->getFeUser() != $this->getFrontendUser()) {

                $this->addFlashMessage(
                    LocalizationUtility::translate(
                        'eventReservationController.message.error.notPermitted', 'rkw_events'
                    ),
                    '',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                );

                $this->redirect('list', 'Event', null, [], $this->settings['listPid']);
                //===
            }

            $this->view->assign('eventReservation', $eventReservation);
            $this->view->assign('event', $eventReservation->getEvent());

        } else {

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'eventReservationController.message.noUserLoggedIn', 'rkw_events'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );

            $this->redirect('list', 'Event', null, [], $this->settings['listPid']);
            //===
        }

    }


    /**
     * Removes an reservation
     *
     * @param EventReservation $eventReservation
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("eventReservation")
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function removeAction(EventReservation $eventReservation): void
    {
        if ($this->getFrontendUser()) {

            // 1. security check
            if ($eventReservation->getFeUser() != $this->frontendUser) {
                $this->addFlashMessage(
                    LocalizationUtility::translate(
                        'eventReservationController.message.error.notPermitted', 'rkw_events'
                    ),
                    '',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                );

                $this->redirect('list', 'Event', null, [], $this->settings['listPid']);
            }

            $this->finalRemoveReservation($eventReservation);

            $this->redirect('myEvents', 'Event');

        } else {

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'eventReservationController.message.noUserLoggedIn', 'rkw_events'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );

            $this->redirect('list', 'Event', null, [], $this->settings['listPid']);
        }

    }


    /**
     * Removes a reservation (via direct link + hash)
     *
     * @param string $cancelRegHash
     * @param bool $confirmedByUser
     * @return void
     */
    public function removeByHashAction(string $cancelRegHash, bool $confirmedByUser = false): void
    {
        // @toDo: Check hash
        $eventReservation = $this->eventReservationRepository->findOneByCancelRegHash($cancelRegHash);

        if ($eventReservation instanceof EventReservation) {

            if ($confirmedByUser) {

                // if user has confirmed, remove it
                $this->finalRemoveReservation($eventReservation);

            } else {
                // really want to delete?
                $this->addFlashMessage(
                    LocalizationUtility::translate(
                        'eventReservationController.message.question', 'rkw_events'
                    ),
                    '',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
                );
                $this->view->assign('event', $eventReservation->getEvent());
                $this->view->assign('eventReservation', $eventReservation);
            }

        } else {
            // @toDo: Nichts gefunden, sind sie ggf schon abgemeldet?

            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'eventReservationController.message.nothingFoundByHash', 'rkw_events'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
        }


    }


    /**
     * Removes all reservations of a FE-User
     * Used by Signal-Slot
     *
     * @param \Madj2k\FeRegister\Domain\Model\FrontendUser $feUser
     * @return void
     */
    public function removeAllOfUserSignalSlot(\Madj2k\FeRegister\Domain\Model\FrontendUser $feUser): void
    {
        try {
            $eventReservations = $this->eventReservationRepository->findByFeUser($feUser, false);
            $settings = $this->getSettings();

            if ($eventReservations) {

                /** @var EventReservation $eventReservation */
                foreach ($eventReservations as $eventReservation) {

                    // 1. remove reservation and additional users
                    foreach ($eventReservation->getAddPerson() as $addPerson) {
                        $this->eventReservationAddPersonRepository->remove($addPerson);
                    }
                    // 2.1 remove workshops according to reservation
                    /** @var EventWorkshop $workshop */
                    foreach ($eventReservation->getWorkshopRegister() as $workshop) {
                        $workshop->removeRegisteredFrontendUsers($eventReservation->getFeUser());
                        $this->eventWorkshopRepository->update($workshop);
                    }
                    $this->eventReservationRepository->remove($eventReservation);
                    $this->persistenceManager->persistAll();

                    // 2.2 send final confirmation mail to user
                    $this->signalSlotDispatcher->dispatch(__CLASS__, self::SIGNAL_AFTER_RESERVATION_DELETE_USER, [$feUser, $eventReservation]);

                    // 3. send information mail to be-users
                    $adminMails = [];
                    if ($eventReservation->getEvent()) {
                        if ($beUsers = $eventReservation->getEvent()->getBeUser()) {
                            /** @var \RKW\RkwEvents\Domain\Model\BackendUser $beUser */
                            foreach ($beUsers as $beUser) {
                                if ($beUser->getEmail()) {
                                    $adminMails[] = $beUser;
                                }
                            }
                        }

                        // 4. send information mail to external users
                        if ($externalContacts = $eventReservation->getEvent()->getExternalContact()) {
                            /** @var  $externalContact \RKW\RkwEvents\Domain\Model\EventContact */
                            foreach ($externalContacts as $externalContact) {
                                if ($externalContact->getEmail()) {
                                    $adminMails[] = $externalContact;
                                }
                            }
                        }

                        // 5. send information mail to admins from TypoScript
                        $adminUidList = GeneralUtility::trimExplode(',', $settings['mail']['backendUser']);
                        if ($adminUidList) {
                            foreach ($adminUidList as $adminUid) {

                                /** @var \RKW\RkwEvents\Domain\Model\BackendUser $admin */
                                $admin = $this->backendUserRepository->findByUid($adminUid);
                                if (
                                    ($admin)
                                    && ($admin->getEmail())
                                ) {
                                    $adminMails[] = $admin;
                                }
                            }
                        }

                        $this->signalSlotDispatcher->dispatch(__CLASS__, self::SIGNAL_AFTER_RESERVATION_DELETE_ADMIN, [$adminMails, $feUser, $eventReservation]);
                    }

                    $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Deleted event reservation with uid %s of user with uid %s via signal-slot.', $eventReservation->getUid(), $feUser->getUid()));
                }
            }
        } catch (\Exception $e) {
            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::ERROR, sprintf('Error while deleting event reservation of user via signal-slot: %s', $e->getMessage()));
        }
    }


    /**
     * @param EventReservation $eventReservation
     * @return void
     * @throws AspectNotFoundException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    protected function finalRemoveReservation(EventReservation $eventReservation): void
    {

        $frontendUser = $this->getFrontendUser() ?: $eventReservation->getFeUser();

        // 2. remove reservation and additional users
        foreach ($eventReservation->getAddPerson() as $addPerson) {
            $this->eventReservationAddPersonRepository->remove($addPerson);
        }
        $this->eventReservationRepository->remove($eventReservation);

        $this->addFlashMessage(
            LocalizationUtility::translate(
                'eventReservationController.message.deleteSuccess', 'rkw_events'
            ),
            '',
            \TYPO3\CMS\Core\Messaging\AbstractMessage::OK
        );

        // 2.2 remove additional workshop reservations
        /** @var EventWorkshop $workshop */
        foreach ($eventReservation->getWorkshopRegister() as $workshop) {
            $workshop->removeRegisteredFrontendUsers($eventReservation->getFeUser());
            $this->eventWorkshopRepository->update($workshop);
        }

        // 3. send final confirmation mail to user
        $this->signalSlotDispatcher->dispatch(__CLASS__, self::SIGNAL_AFTER_RESERVATION_DELETE_USER, [$frontendUser, $eventReservation]);

        // 4. send information mail to be-users
        $adminMails = [];
        if ($beUsers = $eventReservation->getEvent()->getBeUser()) {
            /** @var \RKW\RkwEvents\Domain\Model\BackendUser $beUser */
            foreach ($beUsers as $beUser) {
                if ($beUser->getEmail()) {
                    $adminMails[] = $beUser;
                }
            }
        }

        // 5. send information mail to external users
        if ($externalContacts = $eventReservation->getEvent()->getExternalContact()) {
            /** @var  $externalContact \RKW\RkwEvents\Domain\Model\EventContact */
            foreach ($externalContacts as $externalContact) {
                if ($externalContact->getEmail()) {
                    $adminMails[] = $externalContact;
                }
            }
        }

        // 6. send information mail to admins from TypoScript
        $adminUidList = GeneralUtility::trimExplode(',', $this->settings['mail']['backendUser']);
        if ($adminUidList) {
            foreach ($adminUidList as $adminUid) {

                /** @var \RKW\RkwEvents\Domain\Model\BackendUser $admin */
                $admin = $this->backendUserRepository->findByUid($adminUid);
                if (
                    ($admin)
                    && ($admin->getEmail())
                ) {
                    $adminMails[] = $admin;
                }
            }
        }
        $this->signalSlotDispatcher->dispatch(__CLASS__, self::SIGNAL_AFTER_RESERVATION_DELETE_ADMIN, [$adminMails, $frontendUser, $eventReservation]);

    }


    /**
     * finalSaveOrder
     * Adds the order finally to database and sends information mails to user and admin
     * This function is used by "createOptInReservationAction" and "create"-function
     * Added by Maximilian Fäßler | FäßlerWeb
     *
     * @param EventReservation $newEventReservation
     * @param \Madj2k\FeRegister\Domain\Model\FrontendUser $feUser
     * @param \Madj2k\FeRegister\Domain\Model\OptIn $optIn
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    protected function finalSaveReservation(
        EventReservation $newEventReservation,
        \Madj2k\FeRegister\Domain\Model\FrontendUser $feUser,
        \Madj2k\FeRegister\Domain\Model\OptIn $optIn = null
    ): void
    {
        // optional service: Merge form data (eventReservation) in frontendUser, if some field is empty
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('fe_register')) {
            DivUtility::mergeFeUsers($newEventReservation, $feUser);

            /** @var \Madj2k\FeRegister\Domain\Repository\FrontendUserRepository $frontendUserRepository */
            $frontendUserRepository = $this->objectManager->get(\Madj2k\FeRegister\Domain\Repository\FrontendUserRepository::class);
            $frontendUserRepository->update($feUser);
        }

        // 1. set FeUser
        $newEventReservation->setFeUser($feUser);

        // 1.1 set hash for cancel-link
        $newEventReservation->setCancelRegHash(md5($newEventReservation->getCrdate() . $feUser->getEmail()));

        // 2. save reservation
        // only add additional persons that have at least one relevant field set
        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $tempObjectStorage */
        $tempObjectStorage = $this->objectManager->get(ObjectStorage::class);

        // fetch all relevant objects in a temporary object storage
        foreach ($newEventReservation->getAddPerson() as $addPerson) {
            if (
                $addPerson->getFirstName()
                || $addPerson->getLastName()
            ) {
                $tempObjectStorage->attach($addPerson);
            }
        }
        // override existing object storage
        $newEventReservation->setAddPerson($tempObjectStorage);

        // 2.2 Sub-operation: Register workshop reservations
        DivUtility::workshopRegistration($newEventReservation);
        /*
        // if there is no longer place in a workshop, set message. Don't break reservation! Just an info.
        if (!$workshopResult) {
            $this->addFlashMessage(
                LocalizationUtility::translate(
                    'eventReservationController.error.workshop_full', 'rkw_events'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO
            );
        }
        */

        $this->eventReservationRepository->add($newEventReservation);
        $this->persistenceManager->persistAll();

        // 2.3 add domain entry
        $newEventReservation->setServerHost(filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL));
        $newEventReservation->setShowPid((int)$this->settings['showPid']);

        // 3. add it to event
        $event = $newEventReservation->getEvent();
        $event->addReservation($newEventReservation);
        $this->eventRepository->update($event);
        $this->persistenceManager->persistAll();

        // 4. send final confirmation mail to user
        $this->signalSlotDispatcher->dispatch(__CLASS__, self::SIGNAL_AFTER_RESERVATION_CREATED_USER, [$feUser, $newEventReservation]);

        // 5. send information mail to be-users
        $adminMails = [];
        if ($beUsers = $newEventReservation->getEvent()->getBeUser()) {
            /** @var \RKW\RkwEvents\Domain\Model\BackendUser $beUser */
            foreach ($beUsers as $beUser) {
                if ($beUser->getEmail()) {
                    $adminMails[] = $beUser;
                }
            }
        }

        // 6. send information mail to external users
        if ($externalContacts = $newEventReservation->getEvent()->getExternalContact()) {
            /** @var  $externalContact \RKW\RkwEvents\Domain\Model\EventContact */
            foreach ($externalContacts as $externalContact) {
                if ($externalContact->getEmail()) {
                    $adminMails[] = $externalContact;
                }
            }
        }

        // 7. send information mail to admins from TypoScript
        $adminUidList = GeneralUtility::trimExplode(',', $this->settings['mail']['backendUser']);
        if ($adminUidList) {
            foreach ($adminUidList as $adminUid) {

                /** @var \RKW\RkwEvents\Domain\Model\BackendUser $admin */
                $admin = $this->backendUserRepository->findByUid($adminUid);
                if (
                    ($admin)
                    && ($admin->getEmail())
                ) {
                    $adminMails[] = $admin;
                }
            }
        }

        $this->signalSlotDispatcher->dispatch(__CLASS__, self::SIGNAL_AFTER_RESERVATION_CREATED_ADMIN, [$adminMails, $newEventReservation]);
    }


    /**
     * Id of logged User
     *
     * @return int
     * @throws \TYPO3\CMS\Core\Context\Exception\AspectNotFoundException
     */
    protected function getFrontendUserId(): int
    {
        if (
            ($frontendUser = FrontendUserSessionUtility::getLoggedInUser())
            && (! FrontendUserUtility::isGuestUser($frontendUser))
        ){
            return $frontendUser->getUid();
        }

        return 0;
    }


    /**
     * Returns current logged in user object
     *
     * @return \RKW\RkwEvents\Domain\Model\FrontendUser|NULL
     * @throws \TYPO3\CMS\Core\Context\Exception\AspectNotFoundException
     */
    protected function getFrontendUser()
    {
        $this->frontendUser = $this->frontendUserRepository->findByIdentifier($this->getFrontendUserId());

        if ($this->frontendUser instanceof \TYPO3\CMS\Extbase\Domain\Model\FrontendUser) {
            return $this->frontendUser;
        }

        return null;
    }


    /**
     * Returns SignalSlotDispatcher
     *
     * @return \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     */
    protected function getSignalSlotDispatcher(): \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
    {
        if (!$this->signalSlotDispatcher) {
            $this->signalSlotDispatcher = $this->objectManager->get(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
        }

        return $this->signalSlotDispatcher;
        //===
    }


    /**
     * Remove ErrorFlashMessage
     *
     * @see \TYPO3\CMS\Extbase\Mvc\Controller\ActionController::getErrorFlashMessage()
     */
    protected function getErrorFlashMessage(): bool
    {
        return false;
        //===
    }


    /**
     * Returns TYPO3 settings
     *
     * @param string $which Which type of settings will be loaded
     * @return array
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function getSettings($which = ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS): array
    {
        return Common::getTypoScriptConfiguration('Rkwevents', $which);
        //===
    }


    /**
     * Returns logger instance
     *
     * @return \TYPO3\CMS\Core\Log\Logger
     */
    protected function getLogger(): \TYPO3\CMS\Core\Log\Logger
    {
        if (!$this->logger instanceof \TYPO3\CMS\Core\Log\Logger) {
            $this->logger = GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
        }

        return $this->logger;
        //===
    }
}
