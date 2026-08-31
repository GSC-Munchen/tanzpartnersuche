<?php

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    extensionName: 'tanzpartnersuche',
    pluginName: 'Tanzpartnersuche',
    pluginTitle: 'Tanzpartnersuche',
    pluginIcon: 'tx-tanzpartnersuche-svgicon',
    group: 'plugins',
    pluginDescription: 'Tanzpartnersuche des GSC München e.V.'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    table: 'tt_content',
    newFieldsString: 'pages',
    typeList: 'tanzpartnersuche_tanzpartnersuche',
    position: 'after:subheader'  
);