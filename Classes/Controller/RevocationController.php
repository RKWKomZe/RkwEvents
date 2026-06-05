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
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
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
     * action create
     *
     * @param string $email
     * @param string $message
     *
     * @return void
     */
    public function createAction(string $email, string $message)
    {
        $email = trim($email);
        $message = trim($message);

        DebuggerUtility::var_dump($message);
        exit();


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
