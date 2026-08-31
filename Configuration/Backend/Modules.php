<?php

use Gsc\Tanzpartnersuche\Controller\Backend\UserAdministrationController;

return [
    'web_tanzpartnersuche' => [
        'parent' => 'web',
        'position' => [],
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/web/tanzpartnersuche',
        'iconIdentifier' => 'tx-tanzpartnersuche-svgicon',
        'labels' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/locallang_mod.xlf',
        'extensionName' => 'Tanzpartnersuche',
        'controllerActions' => [
            UserAdministrationController::class => ['index', 'detail', 'delete', 'edit', 'update', 'passwordReset'],
        ],
    ],
];
