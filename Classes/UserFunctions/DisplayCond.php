<?php
namespace RKW\RkwEvents\UserFunctions;


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

use RKW\RkwBasics\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class TypoScriptSettings
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DisplayCond
{
    /**
     * configurationManager
     *
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     * @inject
     */
    protected $configurationManager;

    /**
     * Returns a setting
     * Used for flexform displaycond
     *
     * Hint: Works only for certain fields and field namings. NOT usable for other use cases!
     *
     * @param array $flexForm
     * @return bool
     */
    public function resolveEventFilterCondition($flexForm)
    {
        // get field name and cut the word "Info" or "Link"
        $currentField = GeneralUtility::trimExplode('.', substr($flexForm['flexContext']['currentFieldName'], 0, -4));
        $settings = $this->getSettings();

        // shorthand array_key_exists "hack" for multidimensional array
        if (strpos(json_encode($settings), $settings[$currentField[1]][$currentField[2]][$currentField[3]]) > 0) {
            return (bool) $settings[$currentField[1]][$currentField[2]][$currentField[3]];
        }

        return false;
    }


    /**
     * showCostsRedLink
     * just returns if function is activated via TS
     *
     * @param array $flexForm
     * @return bool
     */
    public function showCostsRedLink($params)
    {
        $settings = $this->getSettings();
        return $settings['costs']['red']['hint']['enabled'];
    }


    /**
     * Returns TYPO3 settings
     *
     * @param string $which Which type of settings will be loaded
     * @return array
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function getSettings($which = ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS)
    {
        return GeneralUtility::getTyposcriptConfiguration('Rkwevents', $which);
    }


}