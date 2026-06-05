<?php
namespace RKW\RkwEvents\Validation\Validator;

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
use RKW\RkwEvents\Domain\Model\Revocation;

/**
 * Class RevocationValidator
 *
 * @author Christian Dilger <c.dilger@addorange.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RevocationValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{

    /**
     * TypoScript Settings
     *
     * @var array
     */
    protected $settings = null;

    /**
     * validation
     *
     * @var \RKW\RkwEvents\Domain\Model\Revocation $newRevocation
     * @return bool
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function isValid($newRevocation): bool
    {

        // initialize typoscript settings
        $this->getSettings();

        // get mandatory fields
        $mandatoryFields = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(",", $this->settings['mandatoryFields']['revocation']);

        $isValid = true;

        // 1. Check mandatory fields main person
        if ($mandatoryFields) {

            foreach ($mandatoryFields as $field) {

                $getter = 'get' . ucfirst($field);
                if (method_exists($newRevocation, $getter)) {

                    if (!trim($newRevocation->$getter())) {

                        $propertyName = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                            'tx_rkwevents_validator.revocation.' . lcfirst($field),
                            'rkw_events'
                        );

                        $this->result->forProperty(lcfirst($field))->addError(
                            new \TYPO3\CMS\Extbase\Error\Error(
                                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                                    'tx_rkwevents_validator.not_filled',
                                    'rkw_events',
                                    [$propertyName]
                                ), 1449314603
                            )
                        );
                        $isValid = false;
                    }
                }
            }
        }

        return $isValid;

    }


    /**
     * Returns TYPO3 settings
     *
     * @return array
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function getSettings(): array
    {

        if (!$this->settings) {
            $this->settings = Common::getTypoScriptConfiguration('Rkwevents');
        }

        if (!$this->settings) {
            return [];
        }
        //===

        return $this->settings;
        //===
    }

}

