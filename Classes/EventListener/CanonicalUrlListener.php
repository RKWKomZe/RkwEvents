<?php
namespace RKW\RkwEvents\EventListener;

use Madj2k\CoreExtended\Utility\GeneralUtility as Common;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Seo\Event\ModifyUrlForCanonicalTagEvent;

class CanonicalUrlListener
{
    /**
     * @throws InvalidConfigurationTypeException
     */
    public function __invoke(ModifyUrlForCanonicalTagEvent $event): void
    {
        /** @var ServerRequestInterface|null $request */
        $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
        // check if it's an FE request
        if (!$request instanceof ServerRequestInterface) {
           // no FE? Go out!
            return;
        }

        // get extension settings
        $settings = $this->getSettings();

        /** @var PageArguments $pageArguments */
        $pageArguments = $request->getAttribute('routing');

        // compare if current pageUid == event show plugin page
        if ($pageArguments->getPageId() != $settings['showPid']) {
            // not the event show page? Do nothing
            return;
        }

        // check further plugin details before do anything
        $pluginKey = 'tx_rkwevents_pi1';
        $params = $request->getQueryParams();

        // First: Check for default params (raw / ugly origin url)
        $isDetail =
            (($params[$pluginKey]['controller'] ?? '') === 'Event') &&
            (($params[$pluginKey]['action'] ?? '') === 'show');

        // Second: Check for rewritten seo-friendly url
        $routing = $request->getAttribute('routing');
        if (
            !$isDetail
            && \is_object($routing)
            && \method_exists($routing, 'getRouteArguments')
            && \is_array($routing->getRouteArguments())
            && \array_key_exists($pluginKey . '__event', $routing->getRouteArguments())
        ) {
            $isDetail = true;
        }

        if (!$isDetail) {
            // not the show page? Go out!
            return;
        }

        // Use the current URL to build the canonical tag
        $uri = $request->getUri();
        $clean = $uri
            ->withQuery('')     // drop ?action=..., cHash, etc.
            ->withFragment(''); // drop #anchor

        // overrides the Core EXT:Seo canonical
        $event->setUrl((string)$clean);
    }


    /**
     * Returns TYPO3 settings
     *
     * @param string $which Which type of settings will be loaded
     * @return array
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    protected function getSettings(string $which = ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS): array
    {
        return Common::getTypoScriptConfiguration('Rkwevents', $which);
    }
}
