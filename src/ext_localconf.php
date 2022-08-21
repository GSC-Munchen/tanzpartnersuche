<?php
defined('TYPO3_MODE') || die();

(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Tanzpartnersuche',
        'Tanzpartnersuche',
        [
            \GSC\Tanzpartnersuche\Controller\TanzpartnersucheController::class => 'index, show, new, create, edit, update, delete, deleted, verify, search, help, detail, login, logout, status, mail, loggedin, loginMenu, changepw, updatepw'
        ],
        // non-cacheable actions
        [
            \GSC\Tanzpartnersuche\Controller\TanzpartnersucheController::class => 'new, create, update, delete, deleted, verify, status, detail, search, mail, login, loggedin, loginMenu, logout, edit, changepw, updatepw'
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
        ['source' => 'EXT:tanzpartnersuche/Resources/Public/Icons/GSClogo.svg']
    );
})();
