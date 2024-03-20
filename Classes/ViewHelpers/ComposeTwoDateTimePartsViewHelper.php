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

use TYPO3\CMS\Extbase\Utility\LocalizationUtility as FrontendLocalizationUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;
use RKW\RkwEvents\Domain\Model\Event;

/**
 * Class ComposeTwoDateTimePartsViewHelper
 *
 * Origin class "ComposeDateTimePartsViewHelper" only usable with events
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ComposeTwoDateTimePartsViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * Initialize arguments.
     *
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('startDate', 'string', 'The first date', true);
        $this->registerArgument('endDate', 'string', 'The second date');
        $this->registerArgument('languageKey', 'string', 'Optional language key', false, 'default');
        $this->registerArgument('onlyTime', 'bool', 'Show only time');
        $this->registerArgument('onlyDate', 'bool', 'Show only date');
    }

    /**
     * Managed datetime output for detail view and email templates
     * -----------------------------------
     * -> Old template code which we've replaced with this ViewHelper:
     * {event.start -> f:format.date(format:"d.m.Y")}
     * <f:if condition="{event.start -> f:format.date(format:'Hi')}">,
     *        {event.start -> f:format.date(format:"H:i")}
     *        <f:if condition="{event.start -> f:format.date(format:'d.m.Y')} <> {event.end -> f:format.date(format:'d.m.Y')}">
     *            <f:else>
     *                <f:translate key="tx_rkwevents_fluid.partials_event_info_time.time_after" />
     *            </f:else>
     *        </f:if>
     *        <f:if condition="{event.end -> f:format.date(format:'Hi')}">
     *            <f:else>
     *                <f:translate key="tx_rkwevents_fluid.partials_event_info_time.time_after" />
     *            </f:else>
     *        </f:if>
     * </f:if>
     * <f:if condition="{event.end}">
     *        - <f:if condition="{event.start -> f:format.date(format:'d.m.Y')} == {event.end -> f:format.date(format:'d.m.Y')}">
     *            <f:else>
     *                {event.end -> f:format.date(format:"d.m.Y")},
     *            </f:else>
     *        </f:if>
     *        <f:if condition="{event.end -> f:format.date(format:'Hi')}">
     *            {event.end -> f:format.date(format:"H:i")}
     *            <f:translate key="tx_rkwevents_fluid.partials_event_info_time.time_after" />
     *        </f:if>
     * </f:if>
     * -----------------------------------
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $startDate = $arguments['startDate'];
        $endDate = $arguments['endDate'];
        $languageKey = $arguments['languageKey'];
        $onlyTime = $arguments['onlyTime'];
        $onlyDate = $arguments['onlyDate'];


        // 1. start date & time
        // set always the starting date
        if (!$onlyTime) {
            $output = date("d.m.Y", $startDate);
        }

        if (
            !$onlyDate
            && date("Hi", $startDate)
        ) {
            if (!$onlyTime) {
                $output .= ', ';
            }
            $output .= date("H:i", $startDate);
            // if startDate != endDate OR no endDate is given, so close here with time_after-string
            if (
                date("d.m.Y", $startDate) != date("d.m.Y", $endDate)
                || !date("Hi", $endDate)
            ) {
                if (TYPO3_MODE == 'BE') {
                    // for emails send via cron e.g.
                    $output .= ' ' . FrontendLocalizationUtility::translate('tx_rkwevents_fluid.partials_event_info_time.time_after', 'rkw_events', null, $languageKey);
                } else {
                    if (
                        !$onlyTime
                        && date("d.m.Y", $startDate) != date("d.m.Y", $endDate)
                    ) {
                        $output .= ' ' . LocalizationUtility::translate('tx_rkwevents_fluid.partials_event_info_time.time_after', 'rkw_events', null);
                    }
                }

            }
        }

        // 2. end date & time
        if ($endDate) {
            if (
                !$onlyTime
                && date("d.m.Y", $startDate) != date("d.m.Y", $endDate)
            ) {
                $output .= ' - ';
                $output .= date("d.m.Y", $endDate);
                //$output .= ', ';
            }

            if (
                !$onlyDate
                && date("Hi", $endDate)
            ) {
                $output .= ' - ';
                $output .= date("H:i", $endDate);
                if (TYPO3_MODE == 'BE') {
                    // for emails send via cron e.g.
                    $output .= ' ' . FrontendLocalizationUtility::translate('tx_rkwevents_fluid.partials_event_info_time.time_after', 'rkw_events', null, $languageKey);
                } else {
                    $output .= ' ' . LocalizationUtility::translate('tx_rkwevents_fluid.partials_event_info_time.time_after', 'rkw_events', null);
                }
            }
        }

        return $output;
    }
}
