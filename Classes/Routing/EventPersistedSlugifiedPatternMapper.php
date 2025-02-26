<?php
namespace RKW\RkwEvents\Routing;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendGroupRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class EventPersistedSlugifiedPatternMapper
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @copyright Maximilian Fäßler
 * @package Rkw_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class EventPersistedSlugifiedPatternMapper extends \Madj2k\DrSerp\Routing\Aspect\PersistedSlugifiedPatternMapper
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
        // Idea: Work with context aspect
        //$aspectTest = GeneralUtility::makeInstance(VisibilityAspect::class, true, true, true);
        //$this->context->setAspect('visibility', $aspectTest);
        //$this->context->setAspect('includeDeletedRecords', true);


        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($this->tableName)
            ->from($this->tableName);
        $queryBuilder->setRestrictions(
            GeneralUtility::makeInstance(FrontendRestrictionContainer::class, $this->context)
        );
        // Frontend Groups are not available at this time (initialized via TSFE->determineId)
        // So this must be excluded to allow access restricted records
        $queryBuilder->getRestrictions()->removeByType(FrontendGroupRestriction::class);


        // ####### EDIT START #######
        $queryBuilder->getRestrictions()->removeByType(DeletedRestriction::class);
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);
        // ####### EDIT END #######


        return $queryBuilder;
    }
}
