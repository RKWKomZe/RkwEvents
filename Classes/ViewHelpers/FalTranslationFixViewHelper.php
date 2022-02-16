<?php
namespace RKW\RkwEvents\ViewHelpers;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Abstract class with basic functionality for loop view helpers.
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class FalTranslationFixViewHelper extends AbstractViewHelper
{

    use CompileWithRenderStatic;

    /**
     * Initialize
     *
     * <rkw:falTranslationFix parentRecord='{event}' fieldName='addInfo' />
     *
     * @return void
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('parentRecord', 'mixed', 'The parent record of the FAL item(s)');
        $this->registerArgument('fieldName', 'mixed', 'The property field name of the wanted FAL items');
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $GLOBALS['TSFE']->sys_language_uid;


        $falItem = $arguments['falItem'];
        var_dump($falItem); exit;
    }


}
