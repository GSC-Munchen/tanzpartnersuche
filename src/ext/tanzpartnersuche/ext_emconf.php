<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Tanzpartnersuche',
    'description' => 'This TYPO3 extension helps users find a dance partner.',
    'category' => 'plugin',
    'author' => 'GSC München',
    'author_company' => 'GSC München',
    'author_email' => 'ias@gsc-muenchen.de',
    'state' => 'alpha',
    'clearCacheOnLoad' => true,
    'version' => '0.1-SNAPSHOT',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.15-10.99.99',
        ]
    ],
    'autoload' => [
    ],
];