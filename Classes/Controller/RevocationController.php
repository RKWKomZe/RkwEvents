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

use RKW\RkwEvents\Domain\Model\Revocation;
use RKW\RkwEvents\Service\RkwMailService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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
     * @var \RKW\RkwEvents\Service\RkwMailService
     */
    protected $rkwMailService;

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
     * @param \RKW\RkwEvents\Domain\Model\Revocation|null $newRevocation
     *
     * @return void
     */
    public function newAction(Revocation $newRevocation = null)
    {
        if (!$newRevocation) {
            $newRevocation = GeneralUtility::makeInstance('RKW\\RkwEvents\\Domain\\Model\\Revocation');
        }

        $this->view->assign('newRevocation', $newRevocation);

    }


    /**
     * action create
     *
     * @param \RKW\RkwEvents\Domain\Model\Revocation $newRevocation
 *
     * @TYPO3\CMS\Extbase\Annotation\Validate("RKW\RkwEvents\Validation\Validator\RevocationValidator", param="newRevocation")
     *
     * @return void
     */
    public function createAction(Revocation $newRevocation)
    {
        // Send Email
        $this->sendRevocationEmail($newRevocation);

        $this->addFlashMessage(
            LocalizationUtility::translate('tx_rkwevents_fluid.revocation.create.message', 'rkw_events'),
            '',
            \TYPO3\CMS\Core\Messaging\AbstractMessage::OK
        );
    }

    /**
     * @param \RKW\RkwEvents\Domain\Model\Revocation $revocation
     * @return void
     */
    protected function sendRevocationEmail(Revocation $revocation)
    {
        $this->rkwMailService->sendRevocationUserMail($revocation);
        $this->rkwMailService->sendRevocationAdminMail($revocation);
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
}
