<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/locallang_db.xlf:tx_tanzpartnersuche_domain_model_user',
        'label' => 'username',
        'iconfile' => 'EXT:tanzpartnersuche/Resources/Public/Icons/User.svg',
    ],
    'columns' => [
        'username' => [
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/locallang_db.xlf:tx_tanzpartnersuche_domain_model_user.username',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,alphanum,lower,nospace,unique,required',
            ]
        ],
        'email' => [
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/locallang_db.xlf:tx_tanzpartnersuche_domain_model_user.email',
            'config' => [
                'type' => 'input',
                'eval' => 'email,required,unique,lower,trim'
            ]
        ]
    ],
    'types' => [
        '0' => ['showitem' => 'username, email'],
    ],
];