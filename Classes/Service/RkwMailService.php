<?php

namespace RKW\RkwEvents\Service;

use Madj2k\CoreExtended\Utility\GeneralUtility as Common;
use Madj2k\Postmaster\Mail\MailMessage;
use RKW\RkwEvents\Domain\Model\BackendUser;
use RKW\RkwEvents\Domain\Model\Event;
use RKW\RkwEvents\Domain\Model\EventContact;
use RKW\RkwEvents\Domain\Model\EventReservation;
use RKW\RkwEvents\Domain\Repository\EventRepository;
use RKW\RkwEvents\Domain\Repository\EventReservationRepository;
use SJBR\StaticInfoTables\Domain\Model\Language;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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
 * RkwMailService
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RkwMailService implements \TYPO3\CMS\Core\SingletonInterface
{

    /**
     * Constants for google link
     *
     * @const integer
     */
    const GOOGLE_LINK = 'http://maps.google.com/maps/dir/';


    /**
     * Handles opt-in event
     *
     * @param \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser
     * @param \Madj2k\FeRegister\Domain\Model\OptIn $optIn
     * @param mixed $signalInformation
     * @return void
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function optInRequest(
        \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser,
        \Madj2k\FeRegister\Domain\Model\OptIn $optIn,
        $signalInformation
    )
    {
        // get settings
        $settings = $this->getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $settingsDefault = $this->getSettings();
        $showPid = intval($settingsDefault['showPid']);

        // if plugin is "standalonereg" (and not "pi1") set flag to true. Needed in mail template
        $request = GeneralUtility::_GP('tx_rkwevents_standalonereg');
        $isStandaloneRegisterPlugin = (bool)$request;
        if ($isStandaloneRegisterPlugin) {
            // if standalone reservation form plugin: Override showPid!
            $showPid = intval($GLOBALS['TSFE']->id);
        }

        if ($settings['view']['templateRootPaths']) {

            /** @var \Madj2k\Postmaster\Mail\MailMessage $mailService */
            $mailService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(MailMessage::class);

            // send new user an email with token
            $mailService->setTo($frontendUser, [
                'marker' => [
                    'frontendUser' => $frontendUser,
                    'optIn'        => $optIn,
                    'pageUid'      => intval($GLOBALS['TSFE']->id),
                    'loginPid'     => intval($settingsDefault['loginPid']),
                    'showPid'      => $showPid,
                    'showNewsletterCheckbox'        => intval($settingsDefault['showNewsletterCheckbox']),
                    'isStandaloneRegisterPlugin'    => $isStandaloneRegisterPlugin
                ],
            ]);

            // set reply address
            if (
                (ExtensionManagementUtility::isLoaded('rkw_authors'))
                && ($optIn->getData()->getEvent())
            ){
                if (count($optIn->getData()->getEvent()->getInternalContact()) > 0) {

                    /** @var \RKW\RkwEvents\Domain\Model\Authors $contact */
                    foreach ($optIn->getData()->getEvent()->getInternalContact() as $contact) {

                        if ($contact->getEmail()) {
                            $mailService->getQueueMail()->setReplyToAddress($contact->getEmail());
                            break;
                        }
                    }
                }
            }

            $mailService->getQueueMail()->setSubject(
                LocalizationUtility::translate(
                    'rkwMailService.optInReservationUser.subject',
                    'rkw_events',
                    null,
                    $frontendUser->getTxFeregisterLanguageKey()
                )
            );

            $mailService->getQueueMail()->addTemplatePaths($settings['view']['templateRootPaths']);
            $mailService->getQueueMail()->addPartialPaths($settings['view']['partialRootPaths']);

            $mailService->getQueueMail()->setPlaintextTemplate('Email/OptInReservationUser');
            $mailService->getQueueMail()->setHtmlTemplate('Email/OptInReservationUser');

            $mailService->send();
        }
    }


    /**
     * Handles confirm mail for user
     * Works with FeRegister-FrontendUser -> this is correct! (data comes from TxFeRegister)
     *
     * @param \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     * @return void
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function confirmReservationUser(
        \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser,
        \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
    )
    {
        // send confirmation
        $this->userMail($frontendUser, $eventReservation, 'confirmation', true);

        if (ExtensionManagementUtility::isLoaded('rkw_survey')) {
            // send additional mail for survey (is some "surveyBefore" ist set in event)
            if ($eventReservation->getEvent()->getSurveyBefore()) {
                $this->userMail($frontendUser, $eventReservation, 'survey');
            }
        }
    }


    /**
     * Handles confirm mail for admin
     *
     * @param BackendUser|array $backendUser
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     * @return void
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function confirmReservationAdmin(
        $backendUser,
        \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
    )
    {
        $this->adminMail($backendUser, $eventReservation, 'confirmation');
    }

    /**
     * Handles update mail for user
     *
     * @param \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     * @return void
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function updateReservationUser(
        \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser,
        \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
    )
    {
        $this->userMail($frontendUser, $eventReservation, 'update');
    }


    /**
     * Handles update mail for admin
     *
     * @param BackendUser|array $backendUser
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     * @return void
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function updateReservationAdmin(
        $backendUser,
        \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
    )
    {
        $this->adminMail($backendUser, $eventReservation, 'update');
    }


    /**
     * Handles delete mail for user
     *
     * @param \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     * @return void
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function deleteReservationUser(
        \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser,
        \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
    )
    {
        $this->userMail($frontendUser, $eventReservation, 'delete');
    }


    /**
     * Handles delete mail for admin
     *
     * @param BackendUser|array $backendUser
     * @param \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     * @return void
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function deleteReservationAdmin(
        $backendUser,
        \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser,
        \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
    )
    {
        $this->adminMail($backendUser, $eventReservation, 'delete', $frontendUser);
    }


    /**
     * Handles cancellation mail for user (if event is deleted via backend)
     * Called via hook
     *
     * @param array $eventReservation
     * @return void
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function cancellationReservationUser(array $eventReservation)
    {
        $action = "cancellation";

        // fetch reservation object
        $eventReservationRepository = GeneralUtility::makeInstance(EventReservationRepository::class);
        /** @var EventReservation $eventReservation */
        $eventReservation = $eventReservationRepository->findByUid($eventReservation['uid']);


        // get settings
        $settings = $this->getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $settingsDefault = $this->getSettings();
        $showPid = intval($settingsDefault['showPid']);

        if ($settings['view']['templateRootPaths']) {

            /** @var \Madj2k\Postmaster\Mail\MailMessage $mailService */
            $mailService = GeneralUtility::makeInstance(MailMessage::class);

            $eventTitle = $eventReservation->getEvent()->getSeries()->getTitle();
            if ($eventReservation->getEvent()->getSeries()->getSubtitle()) {
                $eventTitle .= ' - ' . $eventReservation->getEvent()->getSeries()->getSubtitle();
            }

            // send new user an email with token
            $mailService->setTo($eventReservation->getFeUser(), [
                'marker' => [
                    'event'        => $eventReservation->getEvent(),
                    'frontendUser' => $eventReservation->getFeUser(),
                    'pageUid'      => intval($GLOBALS['TSFE']->id),
                    'loginPid'     => intval($settingsDefault['loginPid']),
                    'showPid'      => $showPid,
                    'uniqueKey'    => uniqid(),
                    'currentTime'  => time(),
                    'surveyPid'    => intval($settingsDefault['surveyPid']),
                    'eventTitle'    => $eventTitle,
                ],
            ]);

            // set reply address
            if (
                (ExtensionManagementUtility::isLoaded('rkw_authors'))
                && ($eventReservation->getEvent())
            ){
                if (count($eventReservation->getEvent()->getInternalContact()) > 0) {

                    /** @var \RKW\RkwEvents\Domain\Model\Authors $contact */
                    foreach ($eventReservation->getEvent()->getInternalContact() as $contact) {

                        if ($contact->getEmail()) {
                            $mailService->getQueueMail()->setReplyToAddress($contact->getEmail());
                            break;
                        }
                    }
                }
            }

            $mailService->getQueueMail()->setSubject(
                LocalizationUtility::translate(
                    'rkwMailService.' . strtolower($action) . 'ReservationUser.subject',
                    'rkw_events',
                    null,
                    $eventReservation->getFeUser()->getTxFeregisterLanguageKey()
                )
            );

            $mailService->getQueueMail()->addTemplatePaths($settings['view']['templateRootPaths']);
            $mailService->getQueueMail()->addPartialPaths($settings['view']['partialRootPaths']);

            $mailService->getQueueMail()->setPlaintextTemplate('Email/' . ucfirst(strtolower($action)) . 'ReservationUser');
            $mailService->getQueueMail()->setHtmlTemplate('Email/' . ucfirst(strtolower($action)) . 'ReservationUser');

            $mailService->send();
        }
    }


    /**
     * Handles cancellation mail for admin (if event is deleted via backend)
     * Called via hook
     *
     * @param int $eventUid
     * @param int $cancellationMailsCount
     * @return void
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function cancellationReservationAdmin(int $eventUid, int $cancellationMailsCount)
    {
        $action = "cancellation";

        $eventRepository = GeneralUtility::makeInstance(EventRepository::class);
        /** @var Event $event */
        $event = $eventRepository->findByUid($eventUid);

        // get settings
        $settings = $this->getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $settingsDefault = $this->getSettings();
        $showPid = intval($settingsDefault['showPid']);

        $recipients = array_merge($event->getBeUser()->toArray(), $event->getExternalContact()->toArray());

        if ($settings['view']['templateRootPaths']) {

            /** @var \Madj2k\Postmaster\Mail\MailMessage $mailService */
            $mailService = GeneralUtility::makeInstance(MailMessage::class);

            foreach ($recipients as $recipient) {

                if (
                    (
                        ($recipient instanceof BackendUser)
                        || ($recipient instanceof EventContact)
                    )
                    && ($recipient->getEmail())
                ) {

                    $language = $recipient->getLang();
                    if ($language instanceof Language) {
                        $language = $language->getTypo3Code();
                    }

                    $name = '';
                    if ($recipient instanceof BackendUser) {
                        $name = $recipient->getRealName();
                    }
                    if ($recipient instanceof EventContact) {
                        $name = $recipient->getFirstName() . ' ' . $recipient->getLastName();
                    }

                    $eventTitle = $event->getSeries()->getTitle();
                    if ($event->getSeries()->getSubtitle()) {
                        $eventTitle .= ' - ' . $event->getSeries()->getSubtitle();
                    }

                    // send new user an email with token
                    $mailService->setTo($recipient, [
                        'marker'  => [
                            'event'         => $event,
                            'admin'         => $recipient,
                            'pageUid'       => intval($GLOBALS['TSFE']->id),
                            'loginPid'      => intval($settingsDefault['loginPid']),
                            'showPid'       => $showPid,
                            'fullName'      => $name,
                            'language'      => $language,
                            'reservationCancellationCount' => $cancellationMailsCount,
                            'eventTitle'    => $eventTitle,
                        ],
                        'subject' => LocalizationUtility::translate(
                            'rkwMailService.' . strtolower($action) . 'ReservationAdmin.subject',
                            'rkw_events',
                            null,
                            $language
                        ),
                    ]);
                }
            }

            $mailService->getQueueMail()->setSubject(
                LocalizationUtility::translate(
                    'rkwMailService.' . strtolower($action) . 'ReservationAdmin.subject',
                    'rkw_events',
                    null,
                    'de'
                )
            );

            $mailService->getQueueMail()->addTemplatePaths($settings['view']['templateRootPaths']);
            $mailService->getQueueMail()->addPartialPaths($settings['view']['partialRootPaths']);

            $mailService->getQueueMail()->setPlaintextTemplate('Email/' . ucfirst(strtolower($action)) . 'ReservationAdmin');
            $mailService->getQueueMail()->setHtmlTemplate('Email/' . ucfirst(strtolower($action)) . 'ReservationAdmin');

            if (count($mailService->getTo())) {
                $mailService->send();
            }
        }
    }


    /**
     * inform user about upcoming events
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $eventReservationList
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @return void
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function informUpcomingEventUser(
        \TYPO3\CMS\Extbase\Persistence\ObjectStorage $eventReservationList,
        \RKW\RkwEvents\Domain\Model\Event $event
    ){

        // get settings
        $settings = $this->getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $settingsDefault = $this->getSettings();

        if ($settings['view']['templateRootPaths']) {

            /** @var \Madj2k\Postmaster\Mail\MailMessage $mailService */
            $mailService = GeneralUtility::makeInstance(MailMessage::class);

            if (count($eventReservationList)) {
                /** @var \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation */
                foreach ($eventReservationList as $eventReservation) {
                    if ($eventReservation->getFeUser()) {

                        $googleMapsLink = '';
                        if ($eventReservation->getEvent()->getPlace() instanceof \RKW\RkwEvents\Domain\Model\EventPlace) {

                            // create google link for location
                            // (1) Google basic link, (2) user address, (3) separator for route from->to, (4) event address
                            $googleMapsLink = self::GOOGLE_LINK;
                            $googleMapsLink .= urlencode($eventReservation->getAddress() . ', ' . $eventReservation->getZip() . ' ' . $eventReservation->getCity());
                            $googleMapsLink .= '/';
                            $googleMapsLink .= urlencode($eventReservation->getEvent()->getPlace()->getAddress() . ', ' . $eventReservation->getEvent()->getPlace()->getZip() . ' ' . $eventReservation->getEvent()->getPlace()->getCity());
                        }

                        // send mail to main participant
                        $mailService->setTo($eventReservation->getFeUser(), [
                            'marker'  => [
                                'reservation'    => $eventReservation,
                                'event'          => $eventReservation->getEvent(),
                                'frontendUser'   => $eventReservation->getFeUser(),
                                'googleMapsLink' => $googleMapsLink,
                                'pageUid'        => intval($GLOBALS['TSFE']->id),
                                'loginPid'       => intval($settingsDefault['loginPid']),
                                // a) Problem: We're out of RootPid context here! (function is called via cronjob)
                                //'showPid'        => intval($settingsDefault['showPid']),
                                // b) Use manually set showPid of EventReservation instead!
                                'showPid'        => intval($eventReservation->getShowPid()),
                                'extRelPath'     => PathUtility::stripPathSitePrefix(ExtensionManagementUtility::extPath('rkw_events')),
                            ],
                            'subject' => LocalizationUtility::translate(
                                'rkwMailService.informUpcomingEventUser.subject',
                                'rkw_events',
                                [0 => $eventReservation->getEvent()->getSeries()->getTitle()],
                                $eventReservation->getFeUser()->getTxFeregisterLanguageKey()
                            ),
                        ]);
                    }
                }

                // set reply address
                if (ExtensionManagementUtility::isLoaded('rkw_authors')) {
                    if (count($eventReservation->getEvent()->getInternalContact()) > 0) {

                        /** @var \RKW\RkwEvents\Domain\Model\Authors $contact */
                        foreach ($eventReservation->getEvent()->getInternalContact() as $contact) {

                            if ($contact->getEmail()) {
                                $mailService->getQueueMail()->setReplyToAddress($contact->getEmail());
                                break;
                            }
                        }
                    }
                }

                $mailService->getQueueMail()->setSubject(
                    LocalizationUtility::translate(
                        'rkwMailService.informUpcomingEventUser.subject',
                        'rkw_events',
                        [0 => $event->getSeries()->getTitle()],
                        $settings['settings']['defaultLanguageKey'] ? $settings['settings']['defaultLanguageKey'] : 'default'
                    )
                );

                $mailService->getQueueMail()->addTemplatePaths($settings['view']['templateRootPaths']);
                $mailService->getQueueMail()->addPartialPaths($settings['view']['partialRootPaths']);

                $mailService->getQueueMail()->setPlaintextTemplate('Email/InformUpcomingEventUser');
                $mailService->getQueueMail()->setHtmlTemplate('Email/InformUpcomingEventUser');

                if ($mailService->getTo() > 0) {
                    $mailService->send();
                }
            }
        }
    }


    /**
     * send survey to user for past events
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $eventReservationList
     * @return void
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function sendSurveyForPastEvent(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $eventReservationList)
    {

        if (ExtensionManagementUtility::isLoaded('rkw_survey')) {
            // get settings
            $settings = $this->getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
            $settingsDefault = $this->getSettings();

            if ($settings['view']['templateRootPaths']) {

                /** @var \Madj2k\Postmaster\Mail\MailMessage $mailService */
                $mailService = GeneralUtility::makeInstance(MailMessage::class);

                if (count($eventReservationList)) {
                    foreach ($eventReservationList as $eventReservation) {
                        if ($eventReservation->getFeUser()) {

                            // send new user an email with token
                            $mailService->setTo($eventReservation->getFeUser(), [
                                'marker'  => [
                                    'reservation'  => $eventReservation,
                                    'event'        => $eventReservation->getEvent(),
                                    'frontendUser' => $eventReservation->getFeUser(),
                                    'sha1Token'    => sha1($eventReservation->getFeUser()->getEmail() . $eventReservation->getFeUser()->getUid() . $eventReservation->getEvent()->getUid()),
                                    'surveyPid'    => intval($settingsDefault['surveyPid']),
                                    'loginPid'     => intval($settingsDefault['loginPid']),
                                    'showPid'      => intval($settingsDefault['showPid']),
                                ],
                                'subject' => LocalizationUtility::translate(
                                    'rkwMailService.sendSurveyForPastEvent.subject',
                                    'rkw_events',
                                    [0 => $eventReservation->getEvent()->getTitle()],
                                    $eventReservation->getFeUser()->getTxFeregisterLanguageKey()
                                ),
                            ]);
                        }
                    }

                    $mailService->getQueueMail()->setSubject(
                        LocalizationUtility::translate(
                            'rkwMailService.sendSurveyForPastEvent.subject',
                            'rkw_events',
                            null,
                            'de'
                        )
                    );

                    $mailService->getQueueMail()->addTemplatePaths($settings['view']['templateRootPaths']);
                    $mailService->getQueueMail()->addPartialPaths($settings['view']['partialRootPaths']);

                    $mailService->getQueueMail()->setPlaintextTemplate('Email/SendSurveyForPastEvent');
                    $mailService->getQueueMail()->setHtmlTemplate('Email/SendSurveyForPastEvent');

                    if ($mailService->getTo() > 0) {
                        $mailService->send();
                    }
                }
            }
        }
    }


    /**
     * Sends an E-Mail to a Frontend-User
     *
     * @param \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     * @param boolean $sendCalendarMeeting
     * @param string $action
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function userMail(
        \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser,
        \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation,
        $action = 'confirmation',
        $sendCalendarMeeting = false
    )
    {
        // get settings
        $settings = $this->getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $settingsDefault = $this->getSettings();
        $showPid = intval($settingsDefault['showPid']);

        // if plugin is "standalonereg" (and not "pi1") set flag to true. Needed in mail template
        $request = GeneralUtility::_GP('tx_rkwevents_standalonereg');
        $isStandaloneRegisterPlugin = (bool)$request;
        if ($isStandaloneRegisterPlugin) {
            // if standalone reservation form plugin: Override showPid!
            $showPid = intval($GLOBALS['TSFE']->id);
        }

        if ($settings['view']['templateRootPaths']) {

            /** @var \Madj2k\Postmaster\Mail\MailMessage $mailService */
            $mailService = GeneralUtility::makeInstance(MailMessage::class);

            // send new user an email with token
            $mailService->setTo($frontendUser, [
                'marker' => [
                    'reservation'  => $eventReservation,
                    'frontendUser' => $frontendUser,
                    'pageUid'      => intval($GLOBALS['TSFE']->id),
                    'loginPid'     => intval($settingsDefault['loginPid']),
                    'showPid'      => $showPid,
                    'cancelRegPid' => intval($settingsDefault['cancelRegPid']),
                    'uniqueKey'    => uniqid(),
                    'currentTime'  => time(),
                    'surveyPid'    => intval($settingsDefault['surveyPid']),
                    'showNewsletterCheckbox'        => intval($settingsDefault['showNewsletterCheckbox']),
                    'isStandaloneRegisterPlugin'    => $isStandaloneRegisterPlugin
                ],
            ]);

            // set reply address
            if (
                (ExtensionManagementUtility::isLoaded('rkw_authors'))
                && ($eventReservation->getEvent())
            ){
                if (count($eventReservation->getEvent()->getInternalContact()) > 0) {

                    /** @var \RKW\RkwEvents\Domain\Model\Authors $contact */
                    foreach ($eventReservation->getEvent()->getInternalContact() as $contact) {

                        if ($contact->getEmail()) {
                            $mailService->getQueueMail()->setReplyToAddress($contact->getEmail());
                            break;
                        }
                    }
                }
            }

            $mailService->getQueueMail()->setSubject(
                LocalizationUtility::translate(
                    'rkwMailService.' . strtolower($action) . 'ReservationUser.subject',
                    'rkw_events',
                    null,
                    $frontendUser->getTxFeregisterLanguageKey()
                )
            );

            $mailService->getQueueMail()->addTemplatePaths($settings['view']['templateRootPaths']);
            $mailService->getQueueMail()->addPartialPaths($settings['view']['partialRootPaths']);

            $mailService->getQueueMail()->setPlaintextTemplate('Email/' . ucfirst(strtolower($action)) . 'ReservationUser');
            $mailService->getQueueMail()->setHtmlTemplate('Email/' . ucfirst(strtolower($action)) . 'ReservationUser');

            // Attach calendar event if set
            if (
                ($sendCalendarMeeting)
                && ($settings['settings']['attachCalendarEvent'])
            ) {
                //$mailService->getQueueMail()->setCalendarTemplate($settings['view']['templateRootPaths'] . 'Email/' . ucfirst(strtolower($action)) . 'ReservationUser');
                $mailService->getQueueMail()->setCalendarTemplate('Email/' . ucfirst(strtolower($action)) . 'ReservationUser');
            }

            $mailService->send();
        }
    }


    /**
     * Sends an E-Mail to an Admin
     *
     * @param BackendUser|array $backendUser
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     * @param string $action
     * @param \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser
     * @throws \Madj2k\Postmaster\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function adminMail(
        $backendUser,
        \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation,
        $action = 'confirmation',
        \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser = null
    )
    {
        // get settings
        $settings = $this->getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $settingsDefault = $this->getSettings();
        $showPid = intval($settingsDefault['showPid']);

        // if plugin is "standalonereg" (and not "pi1") set flag to true. Needed in mail template
        $request = GeneralUtility::_GP('tx_rkwevents_standalonereg');
        $isStandaloneRegisterPlugin = (bool)$request;
        if ($isStandaloneRegisterPlugin) {
            // if standalone reservation form plugin: Override showPid!
            $showPid = intval($GLOBALS['TSFE']->id);
        }

        $recipients = [];
        if (is_array($backendUser)) {
            $recipients = $backendUser;
        } else {
            $recipients[] = $backendUser;
        }

        if ($settings['view']['templateRootPaths']) {

            /** @var \Madj2k\Postmaster\Mail\MailMessage $mailService */
            $mailService = GeneralUtility::makeInstance(MailMessage::class);

            foreach ($recipients as $recipient) {

                if (
                    (
                        ($recipient instanceof BackendUser)
                        || ($recipient instanceof EventContact)
                    )
                    && ($recipient->getEmail())
                ) {

                    $language = $recipient->getLang();
                    if ($language instanceof Language) {
                        $language = $language->getTypo3Code();
                    }

                    $name = '';
                    if ($recipient instanceof BackendUser) {
                        $name = $recipient->getRealName();
                    }
                    if ($recipient instanceof EventContact) {
                        $name = $recipient->getFirstName() . ' ' . $recipient->getLastName();
                    }

                    // send new user an email with token
                    $mailService->setTo($recipient, [
                        'marker'  => [
                            'reservation'  => $eventReservation,
                            'admin'        => $recipient,
                            'frontendUser' => $frontendUser,
                            'pageUid'      => intval($GLOBALS['TSFE']->id),
                            'loginPid'     => intval($settingsDefault['loginPid']),
                            'showPid'      => $showPid,
                            'fullName'     => $name,
                            'language'     => $language,
                            'showNewsletterCheckbox'        => intval($settingsDefault['showNewsletterCheckbox']),
                            'isStandaloneRegisterPlugin'    => $isStandaloneRegisterPlugin
                        ],
                        'subject' => LocalizationUtility::translate(
                            'rkwMailService.' . strtolower($action) . 'ReservationAdmin.subject',
                            'rkw_events',
                            null,
                            $recipient->getLang()
                        ),
                    ]);
                }
            }

            if (
                ($eventReservation->getFeUser())
                && ($eventReservation->getFeUser()->getEmail())
            ) {
                $mailService->getQueueMail()->setReplyAddress($eventReservation->getFeUser()->getEmail());
            }

            $mailService->getQueueMail()->setSubject(
                LocalizationUtility::translate(
                    'rkwMailService.' . strtolower($action) . 'ReservationAdmin.subject',
                    'rkw_events',
                    null,
                    'de'
                )
            );

            $mailService->getQueueMail()->addTemplatePaths($settings['view']['templateRootPaths']);
            $mailService->getQueueMail()->addPartialPaths($settings['view']['partialRootPaths']);

            $mailService->getQueueMail()->setPlaintextTemplate('Email/' . ucfirst(strtolower($action)) . 'ReservationAdmin');
            $mailService->getQueueMail()->setHtmlTemplate('Email/' . ucfirst(strtolower($action)) . 'ReservationAdmin');

            if (count($mailService->getTo())) {
                $mailService->send();
            }
        }
    }


    /**
     * Returns TYPO3 settings
     *
     * @param string $which Which type of settings will be loaded
     * @return array
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function getSettings(string $which = ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS): array
    {
        return Common::getTypoScriptConfiguration('Rkwevents', $which);
    }
}
