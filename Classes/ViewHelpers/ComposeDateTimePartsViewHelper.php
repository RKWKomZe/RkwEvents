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

use RKW\RkwBasics\Utility\FrontendLocalizationUtility;
use RKW\RkwEvents\Domain\Model\Event;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class ComposeDateTimePartsViewHelper
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ComposeDateTimePartsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
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
     * @param \RKW\RkwEvents\Domain\Model\Event $event
     * @param string $languageKey
     * @param bool $onlyDate
     * @param bool $onlyTime
     *
     * @return boolean
     */
    public function render($event, $languageKey = 'default', $onlyDate = false, $onlyTime = false)
    {
        // for secure: If an event is hidden or deleted, the following VH content is still callable (and throws errors)
        // (Uncaught TYPO3 Exception: Call to a member function getStart() on null | Error thrown in file /var/www/rkw-kompetenzzentrum.de/surf/releases/20201006153122/web/typo3conf/ext/rkw_events/Classes/ViewHelpers/ComposeDateTimePartsViewHelper.php in line xyz.)
        if (!$event instanceof Event) {
            return false;
        }

        // 1. start date & time
        // set always the starting date
        if (!$onlyTime) {
            $output = date("d.m.Y", $event->getStart());
        }

        if (
            !$onlyDate
            && date("Hi", $event->getStart())
        ) {
            if (!$onlyTime) {
                $output .= ', ';
            }
            $output .= date("H:i", $event->getStart());
            // if startDate != endDate OR no endDate is given, so close here with time_after-string
            if (
                date("d.m.Y", $event->getStart()) != date("d.m.Y", $event->getEnd())
                || !date("Hi", $event->getEnd())
            ) {
                if (TYPO3_MODE == 'BE') {
                    // for emails send via cron e.g.
                    $output .= ' ' . FrontendLocalizationUtility::translate('tx_rkwevents_fluid.partials_event_info_time.time_after', 'rkw_events', null, $languageKey);
                } else {
                    if (
                        !$onlyTime
                        && date("d.m.Y", $event->getStart()) != date("d.m.Y", $event->getEnd())
                    ) {
                        $output .= ' ' . LocalizationUtility::translate('tx_rkwevents_fluid.partials_event_info_time.time_after', 'rkw_events', null);
                    }
                }

            }
        }

        // 2. end date & time
        if ($event->getEnd()) {
            if (
                !$onlyTime
                && date("d.m.Y", $event->getStart()) != date("d.m.Y", $event->getEnd())
            ) {
                $output .= ' - ';
                $output .= date("d.m.Y", $event->getEnd());
                //$output .= ', ';
            }

            if (
                !$onlyDate
                && date("Hi", $event->getEnd())
            ) {
                $output .= ' - ';
                $output .= date("H:i", $event->getEnd());
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