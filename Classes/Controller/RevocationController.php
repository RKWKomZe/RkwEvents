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

use RKW\RkwEvents\Domain\Model\EventReservation;
use RKW\RkwEvents\Domain\Repository\EventReservationRepository;
use RKW\RkwEvents\Service\RkwMailService;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * RevocationController
 *
 * @author Christian Dilger <c.dilger@addorange.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RevocationController extends ActionController
{

    /**
     * @var \RKW\RkwEvents\Domain\Repository\EventReservationRepository
     */
    protected $eventReservationRepository;

    /**
     * @var \RKW\RkwEvents\Service\RkwMailService
     */
    protected $rkwMailService;

    /**
     * @param \RKW\RkwEvents\Domain\Repository\EventReservationRepository $eventReservationRepository
     */
    public function injectEventReservationRepository(EventReservationRepository $eventReservationRepository)
    {
        $this->eventReservationRepository = $eventReservationRepository;
    }

    /**
     * @param \RKW\RkwEvents\Service\RkwMailService $rkwMailService
     */
    public function injectRkwMailService(RkwMailService $rkwMailService)
    {
        $this->rkwMailService = $rkwMailService;
    }

    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {
    }

    /**
     * action show
     *
     * @param string $email
     * @param string $bookingReference
     * @return void
     */
    public function showAction(string $email, string $bookingReference)
    {
        $email = trim($email);
        $bookingReference = trim($bookingReference);

        $reservations = $this->eventReservationRepository->findByBookingReferenceAndEmail($bookingReference, $email);
        /** @var \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation */
        $eventReservation = $reservations->getFirst();

        if (!$eventReservation) {
            $this->addFlashMessage(
                LocalizationUtility::translate('revocation.error.notFound', 'rkw_events'),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            $this->redirect('new');
        }

        if ($eventReservation->getRevokedAt()) {
            $this->addFlashMessage(
                LocalizationUtility::translate('revocation.error.alreadyRevoked', 'rkw_events'),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING
            );
            $this->redirect('new');
        }

        // Check if event already took place
        if ($eventReservation->getEvent()->getStart() < new \DateTime()) {
            $this->addFlashMessage(
                LocalizationUtility::translate('revocation.error.tooLate', 'rkw_events'),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            $this->redirect('new');
        }

        // Check 14 days period
        $crdate = $eventReservation->getCrdate();
        $now = new \DateTime();
        $diff = $now->diff($crdate);
        if ($diff->invert === 0 && $diff->days > 14) {
             $this->addFlashMessage(
                LocalizationUtility::translate('revocation.error.tooLate', 'rkw_events'),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            $this->redirect('new');
        }

        $this->view->assign('eventReservation', $eventReservation);
    }

    /**
     * action create
     *
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     * @return void
     */
    public function createAction(EventReservation $eventReservation)
    {
        if ($eventReservation->getRevokedAt()) {
             $this->redirect('new');
        }

        $eventReservation->setRevokedAt(time());
        $this->eventReservationRepository->update($eventReservation);

        // Send Email
        $this->sendRevocationEmail($eventReservation);

        $this->addFlashMessage(
            LocalizationUtility::translate('revocation.create.message', 'rkw_events'),
            '',
            \TYPO3\CMS\Core\Messaging\AbstractMessage::OK
        );
    }

    /**
     * @param \RKW\RkwEvents\Domain\Model\EventReservation $eventReservation
     * @return void
     */
    protected function sendRevocationEmail(EventReservation $eventReservation)
    {
        $this->rkwMailService->revocationReservationUser($eventReservation);
        $this->rkwMailService->revocationReservationAdmin($eventReservation);
    }
}
