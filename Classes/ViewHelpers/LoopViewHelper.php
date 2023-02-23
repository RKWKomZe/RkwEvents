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

/**
 * Class LoopViewHelper
 * Repeats rendering of childen $count times while updating $iteration
 *
 * @author Carlos Meyer <cm@davitec.de>
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class LoopViewHelper extends AbstractLoopViewHelper
{

    /**
     * Initialize
     *
     * @return void
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('count', 'integer', 'Number of times to render child content', true);
        $this->registerArgument('minimum', 'integer', 'Minimum number of loops before stopping', false, 0);
        $this->registerArgument('maximum', 'integer', 'Maxiumum number of loops before stopping', false, PHP_INT_MAX);
    }

    /**
     *
     * @return string
     * @throws \TYPO3Fluid\Fluid\Core\Exception
     */
    public function render()
    {

        $count = intval($this->arguments['count']);
        $minimum = intval($this->arguments['minimum']);
        $maximum = intval($this->arguments['maximum']);
        $iteration = $this->arguments['iteration'];
        $content = '';

        if ($count < $minimum) {
            $count = $minimum;
        } elseif ($count > $maximum) {
            $count = $maximum;
        }

        $backupVariable = null;
        if (true === $this->templateVariableContainer->exists($iteration)) {
            $backupVariable = $this->templateVariableContainer->get($iteration);
            $this->templateVariableContainer->remove($iteration);
        }

        for ($i = 0; $i < $count; $i++) {
            $content .= $this->renderIteration($i, 0, $count, 1, $iteration);
        }

        if (true === isset($backupVariable)) {
            $this->templateVariableContainer->add($iteration, $backupVariable);
        }

        return $content;
    }


    /**
     * @param int $i
     * @param int $from
     * @param int $to
     * @param int $step
     * @return boolean
     */
    protected function isLast($i, $from, $to, $step)
    {
        return ($i + $step >= $to);
    }
}
