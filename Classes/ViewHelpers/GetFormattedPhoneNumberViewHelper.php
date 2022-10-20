<?php
namespace RKW\RkwEvents\ViewHelpers;

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

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Class GetFormattedPhoneNumberViewHelper
 *
 * Just a workaround for implicit using of the RkwAuthors ViewHelper
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class GetFormattedPhoneNumberViewHelper extends AbstractViewHelper
{
    /**
     * Build a full phone number
     *
     * @param \RKW\RkwAuthors\Domain\Model\Authors $author
     * @param integer $phoneExtensionLength
     * @return string
     */
    public function render(\RKW\RkwAuthors\Domain\Model\Authors $author, $phoneExtensionLength = 4)
    {

        return static::renderStatic(
            array(
                'author'               => $author,
                'phoneExtensionLength' => $phoneExtensionLength,
            ),
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }


    /**
     * Static rendering
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    static public function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {

        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('rkw_authors')) {

            $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

            /** @var \RKW\RkwAuthors\ViewHelpers\GetFormattedPhoneNumberViewHelper $getFormattedPhoneNumberViewHelper */
            $getFormattedPhoneNumberViewHelper = $objectManager->get('RKW\\RkwAuthors\\ViewHelpers\\GetFormattedPhoneNumberViewHelper');

            return $getFormattedPhoneNumberViewHelper::renderStatic($arguments, $renderChildrenClosure, $renderingContext);
        }

        return '';
    }
}

