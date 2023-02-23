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
 * Class FileReference
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class FileReference extends \TYPO3\CMS\Extbase\Domain\Model\FileReference
{

    /**
     * @var string
     */
    protected $fieldname = '';

    /**
     * @var integer
     */
    protected $uidLocal = 0;

    /**
     * @var \RKW\RkwEvents\Domain\Model\File
     */
    protected $file;

    /**
     * Returns the fieldname
     *
     * @return int $fieldname
     */
    public function getFieldname()
    {
        return $this->fieldname;
    }

    /**
     * Sets the fieldname
     *
     * @param int $fieldname
     * @return void
     */
    public function setFieldname($fieldname)
    {
        $this->fieldname = $fieldname;
    }

    /**
     * Returns the uidLocal
     *
     * @return int $uidLocal
     */
    public function getUidLocal()
    {
        return $this->uidLocal;
    }

    /**
     * Sets the uidLocal
     *
     * @param int $uidLocal
     * @return void
     */
    public function setUidLocal($uidLocal)
    {
        $this->uidLocal = $uidLocal;
    }

    /**
     * Set file
     *
     * @param \RKW\RkwEvents\Domain\Model\File $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return \RKW\RkwEvents\Domain\Model\File
     */
    public function getFile()
    {
        return $this->file;
    }
}
