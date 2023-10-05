<?php
namespace RKW\RkwEvents\MetaTag;

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

use Madj2k\CoreExtended\Utility\GeneralUtility;
use RKW\RkwEvents\Domain\Repository\EventRepository;
use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use TYPO3\CMS\Core\Utility\RootlineUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class MetaTagGenerator
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Steffen Kroggel
 * @package Madj2k_CoreExtended
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class MetaTagGenerator extends \Madj2k\CoreExtended\MetaTag\MetaTagGenerator
{
    /**
     * Generate the meta tags that can be set in backend and add them to frontend by using the MetaTag API
     *
     * @param array $params
     * @return void
     * @todo write tests
     */
    public function generate(array $params): void
    {
        parent::generate($params);
        $metaTagManagerRegistry = GeneralUtility::makeInstance(MetaTagManagerRegistry::class);

        if (
            ($eventParams = GeneralUtility::_GP('tx_rkwevents_pi1'))
            && ($eventId = $eventParams['event'])
        ){

            /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
            $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

            /** @var \RKW\RkwEvents\Domain\Repository\EventRepository $eventRepository */
            $eventRepository = $objectManager->get(EventRepository::class);

            /** @var \RKW\RkwEvents\Domain\Model\Event $event */
            if ($event = $eventRepository->findByIdentifier($eventId)) {

                if ($keywords = $event->getKeywords()) {
                    $manager = $metaTagManagerRegistry->getManagerForProperty('keywords');
                    $manager->removeProperty('keywords');

                    // @extensionScannerIgnoreLine
                    $manager->addProperty('keywords', $keywords);
                }

                $settingsFramework = GeneralUtility::getTypoScriptConfiguration(
                    'RkwEvents',
                    ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
                );

                if ($settingsFramework) {

                    /** @var \TYPO3\CMS\Fluid\View\StandaloneView $view */
                    $standaloneView = $objectManager->get(StandaloneView::class);

                    $standaloneView->setLayoutRootPaths($settingsFramework['view']['layoutRootPaths']);
                    $standaloneView->setPartialRootPaths($settingsFramework['view']['partialRootPaths']);
                    $standaloneView->setTemplateRootPaths($settingsFramework['view']['templateRootPaths']);
                    $standaloneView->setTemplate('Standalone/Description');

                    $standaloneView->assign('event', $event);
                    $description = trim(strip_tags($standaloneView->render()));

                    $manager = $metaTagManagerRegistry->getManagerForProperty('description');
                    $manager->removeProperty('description');

                    // @extensionScannerIgnoreLine
                    $manager->addProperty('description', $description);
                }
            }
        }


        return;
        $description = '';
        $keywords = '';
        if (!empty($params['page']['description'])) {
          $description = $params['page']['description'];
        }

        if (!empty($params['page']['keywords'])) {
            $keywords = $params['page']['keywords'];
        }

        if (
            (
               (empty($description))
               || (empty($keywords))
            )
            && ($pageId = $params['page']['uid'])
        ){

            // get rootLine based on given id
            /** @var \TYPO3\CMS\Core\Utility\RootlineUtility $rootLineUtility */
            $rootLineUtility = new RootlineUtility($pageId);
            $pages = $rootLineUtility->get();

            foreach ($pages as $page) {
                if (
                    empty($description)
                    && isset($page['description'])
                    && ($page['description'])
                ){
                    $description = $page['description'];
                }

                if (
                    (empty($keywords))
                    && isset($page['keywords'])
                    && ($page['keywords'])
                ){
                    $keywords = $page['keywords'];
                }

                if (!empty($description) && !empty($keywords)) {
                    break;
                }
            }
        }

        if (!empty($description)) {
            $manager = $metaTagManagerRegistry->getManagerForProperty('description');

            // @extensionScannerIgnoreLine
            $manager->addProperty('description', $description);
        }

        if (!empty($keywords)) {
            $manager = $metaTagManagerRegistry->getManagerForProperty('keywords');

            // @extensionScannerIgnoreLine
            $manager->addProperty('keywords', $keywords);
        }
    }
}
