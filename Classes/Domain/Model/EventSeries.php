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
 * Class EventSeries
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventSeries extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * name
     *
     * @var string
     */
    protected $name = '';

    /**
     * short
     *
     * @var string
     */
    protected $short = '';

    /**
     * rota
     *
     * @var string
     */
    protected $rota = '';

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
     * Returns the rota
     *
     * @return string $rota
     */
    public function getRota()
    {
        return $this->rota;
    }

    /**
     * Sets the rota
     *
     * @param string $rota
     * @return void
     */
    public function setRota($rota)
    {
        $this->rota = $rota;
    }

}