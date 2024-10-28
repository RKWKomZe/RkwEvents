<?php

namespace RKW\RkwEvents\Utility;

use Madj2k\CoreExtended\Utility\GeneralUtility;
use RKW\RkwEvents\Domain\Model\Event;
use RKW\RkwEvents\Domain\Model\EventPlace;
use RKW\RkwEvents\Domain\Model\EventReservation;
use RKW\RkwEvents\Domain\Model\EventReservationAddPerson;
use RKW\RkwEvents\Domain\Model\EventWorkshop;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
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
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwFeecalculator
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CsvUtility
{
    /**
     * @var resource $csv
     */
    private static $csv;

    /**
     * createCsv - creates a CSV file with all reservation data of an event.
     * Includes additional "EventReservationAddPerson" (maximum: 3)
     *
     * @param Event  $event The event itself
     * @param string $separator The CSV file separator. Default is ";"
     * @param int    $maxAddPersons Number of additional persons are printed to the CSV file. Default is "3"
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public static function createCsv(Event $event, string $separator = ';', int $maxAddPersons = 3)
    {
        $attachmentName = date('Y-m-d', $event->getStart()) . '_' . GeneralUtility::slugify($event->getSeries()->getTitle()) . '.csv';

        self::$csv = fopen('php://output', 'w');

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$attachmentName");
        header("Pragma: no-cache");

        // add reservation data
        self::addReservationDataToCsv($event, $separator, $maxAddPersons);

        // add event data
        self::addEventDataToCsv($event, $separator);

        fclose(self::$csv);
    }



    /**
     * addReservationDataToCsv
     *
     * @param Event   $event
     * @param string  $separator
     * @param int $maxAddPersons
     * @return void
     */
    protected static function addReservationDataToCsv(Event $event, string $separator, int $maxAddPersons)
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
        // create CSV columns
        foreach ($event->getReservation() as $reservation) {
            foreach ((array)$reservation as $name => $property) {
                // remove starting sign "*"
                if (in_array(substr(trim($name), 2), $reservationAllowedColumns)) {
                    $headings[] = substr(trim($name), 2);
                }
            }
            break;
        }

        // extended headings for optional workshops
        /** @var EventWorkshop $workshop */
        foreach ($event->getWorkshop() as $workshop) {
            $headings[] = $workshop->getTitle();
        }

        // extended headings for up to 3 additional persons
        for ($i = 1; $i <= $maxAddPersons; $i++) {
            $headings[] = 'salutation_' . 'addPerson' . $i;
            $headings[] = 'firstName_' . 'addPerson' . $i;
            $headings[] = 'lastName_' . 'addPerson' . $i;
        }

        // set headings
        fputcsv(self::$csv, $headings, $separator);

        /** @var EventReservation $reservation */
        foreach ($event->getReservation() as $reservation) {

            $row = [];

            // add all reservation values
            foreach ($headings as $property) {
                $getter = 'get' . ucfirst($property);
                if (method_exists($reservation, $getter)) {
                    $row[] = self::propertyValueConverter($property, $reservation->$getter());
                }
            }


            // mark as participant (if he is one)
            /** @var EventWorkshop $workshop */
            foreach ($event->getWorkshop() as $workshop) {

                $isRegisteredForWorkshop = false;
                foreach ($reservation->getWorkshopRegister() as $registeredWorkshop) {

                    if ($registeredWorkshop->getUid() == $workshop->getUid()) {
                        // is a participant
                        $row[] = 1;
                        $isRegisteredForWorkshop = true;
                    }
                }

                // is not a participant
                if (!$isRegisteredForWorkshop) {
                    $row[] = '';
                }
            }


            // add additional persons reservation values
            /** @var EventReservationAddPerson $addPerson */
            $i = 0;
            foreach ($reservation->getAddPerson() as $addPerson) {
                $row[] = self::propertyValueConverter('salutation', $addPerson->getSalutation());
                $row[] = $addPerson->getFirstName();
                $row[] = $addPerson->getLastName();

                $i++;
                if ($i == $maxAddPersons) {
                    break;
                }
            }

            fputcsv(self::$csv, $row, $separator);
        }
    }



    /**
     * addEventDataToCsv
     *
     * @param Event  $event
     * @param string $separator
     * @return void
     */
    protected static function addEventDataToCsv(Event $event, string $separator)
    {
        // add empty rows
        fputcsv(self::$csv, [], $separator);
        fputcsv(self::$csv, [], $separator);

        $eventAllowedColumns = [
            'title',
            'start',
            'end',
            'place',
            'costs (reduced)',
            'trainer',
        ];

        // 1. event headings
        $headings = [];
        foreach ($eventAllowedColumns as $column) {
            $headings[] = $column;
        }
        fputcsv(self::$csv, $headings, $separator);

        // 2. event content
        $row = [];
        // add all reservation values
        $row[] = $event->getSeries()->getTitle();
        $row[] = date('d.m.Y', $event->getStart());
        $row[] = date('d.m.Y', $event->getEnd());
        if ($event->getPlace() instanceof EventPlace) {
            $row[] = $event->getPlace()->getName();
        } else {
            if ($event->getPlaceUnknown()) {
                $row[] = 'noch offen';
            } else {
                $row[] = 'online';
            }
        }
        if ($event->getCostsRed()) {
            $row[] = $event->getCostsReg() . '€ (' . $event->getCostsRed() . '€)';
        } else {
            $row[] = $event->getCostsReg() . '€';
        }
        $row[] = $event->getTrainer();

        fputcsv(self::$csv, $row, $separator);
    }



    /**
     * @param string $key
     * @param string $value
     * @return string
     */
    protected static function propertyValueConverter(string $key, string $value)
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

