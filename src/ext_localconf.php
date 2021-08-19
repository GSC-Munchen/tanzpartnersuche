<?php
defined('TYPO3_MODE') || die();

call_user_func(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Tanzpartnersuche',
        'Main',
        [
            \GSC\Tanzpartnersuche\Controller\MainController::class => 'index, show, docs, edit, new',
            \GSC\Tanzpartnersuche\Controller\UserController::class => 'index, list, show, new, create, edit, update, delete'
        ],
        // non-cacheable actions
        [
            \GSC\Tanzpartnersuche\Controller\MainController::class => 'show',
            \GSC\Tanzpartnersuche\Controller\UserController::class => 'create, update, delete'
        ]
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    main {
                        iconIdentifier = tanzpartnersuche-plugin-main
                        title = LLL:EXT:tanzpartnersuche/Resources/Private/Language/locallang_db.xlf:tx_tanzpartnersuche_main.name
                        description = LLL:EXT:tanzpartnersuche/Resources/Private/Language/locallang_db.xlf:tx_tanzpartnersuche_main.description
                        tt_content_defValues {
                            CType = list
                            list_type = tanzpartnersuche_main
                        }
                    }
                }
                show = *
            }
       }'
    );

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'tanzpartnersuche-plugin-main',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:tanzpartnersuche/Resources/Public/Icons/user_plugin_main.svg']
    );
});
