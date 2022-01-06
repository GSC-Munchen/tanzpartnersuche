<?php
defined('TYPO3_MODE') || die();

call_user_func(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Tanzpartnersuche',
        'Tanzpartnersuche',
        [
            \GSC\Tanzpartnersuche\Controller\MainController::class => 'index, show, help, search, detail',
            \GSC\Tanzpartnersuche\Controller\UserController::class => 'index, list, show, new, create, edit, update, delete, login',
            \GSC\Tanzpartnersuche\Controller\OfferController::class => 'index, list, show, new, create, edit, update, delete'
        ],
        // non-cacheable actions
        [
            \GSC\Tanzpartnersuche\Controller\MainController::class => 'search, detail',
            \GSC\Tanzpartnersuche\Controller\UserController::class => 'create, update, delete, ',
            \GSC\Tanzpartnersuche\Controller\OfferController::class => 'create, update, delete'
        ]
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    tanzpartnersuche {
                        iconIdentifier = tanzpartnersuche-plugin-tanzpartnersuche
                        title = LLL:EXT:tanzpartnersuche/Resources/Private/Language/locallang_db.xlf:tx_tanzpartnersuche_tanzpartnersuche.name
                        description = LLL:EXT:tanzpartnersuche/Resources/Private/Language/locallang_db.xlf:tx_tanzpartnersuche_tanzpartnersuche.description
                        tt_content_defValues {
                            CType = list
                            list_type = tanzpartnersuche_tanzpartnersuche
                        }
                    }
                }
                show = *
            }
       }'
    );

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'tanzpartnersuche-plugin-tanzpartnersuche',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:tanzpartnersuche/Resources/Public/Icons/user_plugin_tanzpartnersuche.svg']
    );
});
