<?php
namespace RKW\RkwEvents\ViewHelpers;

use Madj2k\CoreExtended\Utility\GeneralUtility;
use Madj2k\CoreExtended\ViewHelpers\Format\AddSlashesViewHelper;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * Create property values pairs for JSON
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwEvents
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class JsonPropertyViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * The separator to define properties like title: my title
     *
     * @var string
     */
    protected static string $propertySeparator = ':';

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize
     *
     * @return void
     * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('property', 'string', 'The schema.org property name', true);
        $this->registerArgument('value', 'string', 'The related value to the property', true);
        $this->registerArgument('closingComma', 'bool', 'Set closing comma. Important for valid JSON. Do not use closing comma for last item of a set!', false, true);
        $this->registerArgument('printEmpty', 'bool', 'Force print even if value is empty');
    }


    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {

        $propertyName = $arguments['property'];
        $value = $arguments['value'];
        $closingComma = $arguments['closingComma'];
        $printEmpty = $arguments['printEmpty'];

        // no value => no print
        if (!$value && !$printEmpty) {
            return '';
        }

        // 1. Prepare value

        // remove tags (from RTE e.g.)
        $value = strip_tags($value);

        // remove linebreaks (from RTE e.g.)
        $value = str_replace(array("\r", "\n"), '', $value);

        // truncate all double quotes to avoid JSON-format issues
        $value = addcslashes($value, '\"');

        // 2. Creating return string with key:value pair
        $returnString = '"' . $propertyName . '"' . self::$propertySeparator . ' "' . $value . '"';

        if ($closingComma) {
            $returnString .= ',';
        }

        return $returnString;
    }


}
