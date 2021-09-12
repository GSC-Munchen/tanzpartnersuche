<?php
defined('TYPO3_MODE') || die();

call_user_func(static function() {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_tanzpartnersuche_domain_model_main', 'EXT:tanzpartnersuche/Resources/Private/Language/locallang_csh_tx_tanzpartnersuche_domain_model_main.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_tanzpartnersuche_domain_model_main');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_tanzpartnersuche_domain_model_user', 'EXT:tanzpartnersuche/Resources/Private/Language/locallang_csh_tx_tanzpartnersuche_domain_model_user.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_tanzpartnersuche_domain_model_user');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_tanzpartnersuche_domain_model_offer', 'EXT:tanzpartnersuche/Resources/Private/Language/locallang_csh_tx_tanzpartnersuche_domain_model_offer.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_tanzpartnersuche_domain_model_offer');
});
