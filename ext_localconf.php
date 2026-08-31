<?php

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    extensionName: 'tanzpartnersuche',
    pluginName: 'Tanzpartnersuche',
    controllerActions: [
        \Gsc\Tanzpartnersuche\Controller\TanzpartnersucheController::class => 'main, search, help, new, login, detail, create, verify, verified, loggedin, logout, edit, update, changepassword, savepassword, delete, deleteconfirm, deletesurvey, deletesurveysend, deletesurveysent, status, mail, mailsent, resetpw, sendreset, newpassword, updatepassword',
    ],
    nonCacheableControllerActions: [
        \Gsc\Tanzpartnersuche\Controller\TanzpartnersucheController::class => 'main, search, new, login, detail, create, verify, verified, loggedin, logout, edit, update, changepassword, savepassword, delete, deleteconfirm, deletesurvey, deletesurveysend, deletesurveysent, status, mail, mailsent, resetpw, sendreset, newpassword, updatepassword',
    ],
    pluginType: \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);