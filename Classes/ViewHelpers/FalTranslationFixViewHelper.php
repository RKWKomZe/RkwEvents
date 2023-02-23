<?php
namespace RKW\RkwEvents\ViewHelpers;

/*
 * This file is part of the FluidTYPO3/Vhs project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * To fix FAL issue for translated records
 *
 * !! SHOULD BE DEPRECATED WITH TYPO3 v9 !!
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @copyright RKW Kompetenzzentrum
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
     * @return array
     */
    public static function renderStatic(
        array $arguments, \
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): ?array
    {

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var \RKW\RkwEvents\Domain\Repository\FileReferenceRepository $fileReferenceRepository */
        $fileReferenceRepository = $objectManager->get(\RKW\RkwEvents\Domain\Repository\FileReferenceRepository::class);

        /** @var \TYPO3\CMS\Core\Context\LanguageAspect $languageAspect */
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $languageUid = $languageAspect->getId();

        return $fileReferenceRepository->findAllByRecordFieldnameAndSysLangUid($arguments['parentRecord'], $arguments['fieldName'], $languageUid);
    }


}
