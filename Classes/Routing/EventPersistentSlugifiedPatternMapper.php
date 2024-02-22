<?php
namespace RKW\RkwEvents\Routing;

use InvalidArgumentException;
use TYPO3\CMS\Core\Context\VisibilityAspect;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendGroupRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Routing\Aspect\PersistedAliasMapper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class EventSlugifiedPatternMapper
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @copyright Maximilian Fäßler
 * @package Rkw_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventPersistentSlugifiedPatternMapper extends \Madj2k\CoreExtended\Routing\Aspect\PersistedSlugifiedPatternMapper
{

    /**
     * @param array $settings
     * @throws \InvalidArgumentException
     */
    public function __construct(array $settings)
    {
        parent::__construct($settings);


    }


    protected function createQueryBuilder(): QueryBuilder
    {
        $aspectTest = GeneralUtility::makeInstance(VisibilityAspect::class, true, true, true);
        $this->context->setAspect('visibility', $aspectTest);

        //$this->context->setAspect('includeDeletedRecords', true);

    //    $aspectTest = GeneralUtility::makeInstance(VisibilityAspect::class, false, false, true);
    //    $this->context->setAspect('visibility', $aspectTest);

        // $this->context->getPropertyFromAspect('visibility', 'includeDeletedRecords')
        /** @var VisibilityAspect $aspect */
        //$aspect = $this->context->getAspect('visibility');
        //$bool = $aspect->includeDeletedRecords();
    //    DebuggerUtility::var_dump($this->context->getAspect('visibility')); exit;

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($this->tableName)
            ->from($this->tableName);
        $queryBuilder->setRestrictions(
            GeneralUtility::makeInstance(FrontendRestrictionContainer::class, $this->context)
        );
        // Frontend Groups are not available at this time (initialized via TSFE->determineId)
        // So this must be excluded to allow access restricted records
        $queryBuilder->getRestrictions()->removeByType(FrontendGroupRestriction::class);


        // EDIT START
        // @toDo: Instead of do this override we maybe simply can use FrontendRestrictionContainer?
    //    $queryBuilder->getRestrictions()->removeByType(DeletedRestriction::class);
    //    $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);
        // EDIT END


        return $queryBuilder;
    }
}