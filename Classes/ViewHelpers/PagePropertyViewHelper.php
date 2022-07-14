<?php
namespace RKW\RkwEvents\ViewHelpers;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * To get a page property of a given page UID
 *
 * Inspired by https://github.com/FluidTYPO3/vhs/blob/development/Classes/ViewHelpers/Page/InfoViewHelper.php
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class PagePropertyViewHelper extends AbstractViewHelper
{

    use CompileWithRenderStatic;

    /**
     * Initialize
     *
     * @return void
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument(
            'pageUid',
            'integer',
            'If specified, this UID will be used to fetch page data instead of using the current page.',
            false,
            0
        );
        $this->registerArgument(
            'field',
            'string',
            'If specified, only this field will be returned/assigned instead of the complete page record.'
        );
    }


    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $returnSpecificField = $arguments['field'];
        $pageUid = (integer) $arguments['pageUid'];
        if (0 === $pageUid) {
            $pageUid = $GLOBALS['TSFE']->id;
        }

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var \RKW\RkwEvents\Domain\Repository\FileReferenceRepository $fileReferenceRepository */
        $rkwBasicsPagesRepository = $objectManager->get(\RKW\RkwBasics\Domain\Repository\PagesRepository::class);

        $pages = $rkwBasicsPagesRepository->findByUid($pageUid);

        if ($returnSpecificField) {
            $getter = 'get' . ucfirst($returnSpecificField);
            return $pages->$getter();
        } else {
            return $pages;
        }

    }

}

