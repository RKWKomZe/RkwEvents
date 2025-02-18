<?php
namespace RKW\RkwEvents\Evaluation;


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
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;


/**
 * Class EventEndDate
 *
 * Class for field value validation/evaluation to be used in 'eval' of TCA
 * https://docs.typo3.org/m/typo3/reference-tca/8.7/en-us/ColumnsConfig/Type/Input.html
 *
 * https://stackoverflow.com/questions/42986309/typo3-tca-own-evaluation-validation-for-a-combination-of-three-fields
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventEndDate
{

    /**
     * JavaScript code for client side validation/evaluation
     *
     * @return string JavaScript code for client side validation/evaluation
     */
    public function returnFieldJS()
    {
        return 'return value;';
    }

    /**
     * Server-side validation/evaluation on saving the record
     *
     * @param string $value The field value to be evaluated
     * @param string $is_in The "is_in" value of the field configuration from TCA
     * @param bool $set Boolean defining if the value is written to the database or not.
     * @return string Evaluated field value
     */
    public function evaluateFieldValue($value, $is_in, &$set)
    {
        $formData = GeneralUtility::_GP('data');
        $eventId = key($formData['tx_rkwevents_domain_model_event']);
        $event = $formData['tx_rkwevents_domain_model_event'][$eventId];

        // do not proof announcements without any date
        if (str_ends_with($event['record_type'], 'EventScheduled')) {
            $courseStart = new \DateTime($event['start']);
            $courseEnd = new \DateTime($event['end']);

            if ($courseStart > $courseEnd) {
                $this->flashMessage(
                    LocalizationUtility::translate('evaluation.oops', 'rkw_events'),
                    LocalizationUtility::translate('evaluation.eventEndDate.error', 'rkw_events')
                );
                $set = false; //do not save value
            }
        }

        return $value;
    }

    /**
     * Server-side validation/evaluation on opening the record
     *
     * @param array $parameters Array with key 'value' containing the field value from the database
     * @return string Evaluated field value
     */
    public function deevaluateFieldValue(array $parameters)
    {
        return $parameters['value'];
    }

    /**
     * @param string $messageTitle
     * @param string $messageText
     * @param int $severity
     */
    protected function flashMessage($messageTitle, $messageText, $severity = FlashMessage::ERROR)
    {
        $message = GeneralUtility::makeInstance(
            FlashMessage::class,
            $messageText,
            $messageTitle,
            $severity,
            true
        );

        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $flashMessageService = $objectManager->get(FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();

        // @extensionScannerIgnoreLine
        $messageQueue->addMessage($message);
    }


}
