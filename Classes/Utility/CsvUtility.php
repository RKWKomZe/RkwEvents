<?php

namespace RKW\RkwEvents\Utility;

use RKW\RkwEvents\Domain\Model\Event;
use RKW\RkwEvents\Domain\Model\EventReservation;
use RKW\RkwEvents\Domain\Model\EventReservationAddPerson;
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
 * CsvUtility
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwFeecalculator
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CsvUtility
{

    /**
     * action csv
     * because extbase makes some trouble if some survey has a starttime in future, ist disabled or something, just give the uid
     *
     * @param Event $event
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public static function createCsv(Event $event, $separator = ';')
    {
        $reservationAllowedColumns = [
            'salutation',
            'firstName',
            'lastName',
            'company',
            'address',
            'zip',
            'city',
            'phone',
            'mobile',
            'fax',
            'email',
            'remark',
        ];

        $headings = [];
        foreach ($event->getReservation() as $reservation) {
            foreach ((array)$reservation as $name => $property) {
                // remove starting sign "*"
                if (in_array(substr(trim($name), 2), $reservationAllowedColumns)) {
                    $headings[] = substr(trim($name), 2);
                }
            }
        }

        // extended headings for up to 3 additional persons
        for ($i = 1; $i <= 3; $i++) {
            $headings[] = 'salutation_' . 'addPerson' . $i;
            $headings[] = 'firstName_' . 'addPerson' . $i;
            $headings[] = 'lastName_' . 'addPerson' . $i;
        }

        $attachmentName = date('Y-m-d', $event->getStart()) . '_' . str_replace(' ', '_', strtolower($event->getTitle())) . '.csv';

        $csv = fopen('php://output', 'w');

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$attachmentName");
        header("Pragma: no-cache");

        // set headings
        fputcsv($csv, $headings, $separator);

        /** @var EventReservation $reservation */
        foreach ($event->getReservation() as $reservation) {

            $row = [];
            foreach ($headings as $property) {

                $getter = 'get' . ucfirst($property);

                if (method_exists($reservation, $getter)) {
                    $row[] = self::propertyValueConverter($property, $reservation->$getter());
                }
            }

            /** @var EventReservationAddPerson $addPerson */
            foreach ($reservation->getAddPerson() as $addPerson) {
                $row[] = self::propertyValueConverter('salutation', $addPerson->getSalutation());
                $row[] = $addPerson->getFirstName();
                $row[] = $addPerson->getLastName();
            }

            fputcsv($csv, $row, $separator);
        }

        fclose($csv);
    }


    /**
     * @param string $key
     * @param string $value
     * @return string
     */
    protected static function propertyValueConverter($key, $value)
    {

        if ($key === "salutation") {
            // override
            $value = LocalizationUtility::translate(
                'tx_rkwevents_domain_model_eventreservation.salutation.I.' . $value,
                'RkwEvents'
            );
        }

        return $value;
    }

}
