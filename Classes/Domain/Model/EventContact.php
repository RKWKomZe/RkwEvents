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
 * Class EventContact
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventContact extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
{

    /**
     * salutation
     *
     * @var integer
     */
    protected $salutation = 99;


    /**
     * country
     *
     * @var \SJBR\StaticInfoTables\Domain\Model\Language
     */
    protected $lang;


    /**
     * Returns the salutation
     *
     * @return integer $salutation
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * Sets the salutation
     *
     * @param integer $salutation
     * @return void
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
    }

    /**
     * Returns the language
     *
     * @return \SJBR\StaticInfoTables\Domain\Model\Language $language
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Sets the language
     *
     * @param \SJBR\StaticInfoTables\Domain\Model\Language $language
     * @return void
     */
    public function setLang($language)
    {
        $this->lang = $language;
    }

}
