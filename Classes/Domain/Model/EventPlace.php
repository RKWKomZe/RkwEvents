<?php

namespace RKW\RkwEvents\Domain\Model;
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
 * Class EventPlace
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventPlace extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * name
     *
     * @var string
     */
    protected $name;

    /**
     * short
     *
     * @var string
     */
    protected $short;

    /**
     * address
     *
     * @var string
     */
    protected $address;

    /**
     * zip
     *
     * @var string
     */
    protected $zip;

    /**
     * city
     *
     * @var string
     */
    protected $city;

    /**
     * country
     *
     * @var \SJBR\StaticInfoTables\Domain\Model\Country
     */
    protected $country;

    /**
     * longitude
     *
     * @var string
     */
    protected $longitude;

    /**
     * latitude
     *
     * @var string
     */
    protected $latitude;

    /**
     * @var integer
     */
    protected $crdate;


    /**
     * @var integer
     */
    protected $tstamp;


    /**
     * @var integer
     */
    protected $hidden;


    /**
     * @var integer
     */
    protected $deleted;

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the short
     *
     * @return string $short
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * Sets the short
     *
     * @param string $short
     * @return void
     */
    public function setShort($short)
    {
        $this->short = $short;
    }

    /**
     * Returns the address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }


    /**
     * Returns the zip
     *
     * @return string $zip
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Sets the zip
     *
     * @param string $zip
     * @return void
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * Returns the city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets the city
     *
     * @param string $city
     * @return void
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Returns the country
     *
     * @return \SJBR\StaticInfoTables\Domain\Model\Country $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the country
     *
     * @param \SJBR\StaticInfoTables\Domain\Model\Country $country
     * @return void
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Returns the longitude
     *
     * @return string $longitude
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Sets the longitude
     *
     * @param string $longitude
     * @return void
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Returns the latitude
     *
     * @return string $latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Sets the latitude
     *
     * @param string $latitude
     * @return void
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Returns the crdate value
     *
     * @return int
     * @api
     */
    public function getCrdate()
    {

        return $this->crdate;
    }


    /**
     * Returns the tstamp value
     *
     * @return int
     * @api
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Sets the hidden value
     *
     * @param int $hidden
     * @api
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }


    /**
     * Returns the hidden value
     *
     * @return int
     * @api
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Sets the deleted value
     *
     * @param int $deleted
     * @api
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }


    /**
     * Returns the deleted value
     *
     * @return int
     * @api
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

}
