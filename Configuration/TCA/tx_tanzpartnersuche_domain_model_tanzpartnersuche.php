<?php

// DB config for plugin Tanzpartnersuche

return [
    'ctrl' => [
        'title' => 'Tanzpartnersuche',
        'label' => 'username',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'default_sortby' => 'ORDER BY tstamp',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'security' => [
            // true = allow to place the table anywhere in the page tree
            'ignorePageTypeRestriction' => true,
        ],
        'typeicon_classes' => [
            'default' => 'tx-tanzpartnersuche-svgicon',
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'username, password, passwordconfirmation, email, height, age, gender, role, category, level, bio, verificationcode, created, hidden,'],
    ],
    'columns' => [
        'username' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_username',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'required' => true,
                'eval' => 'trim',
            ],
        ],
        'password' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_password',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'required' => true,
                'eval' => 'nospace,password',
            ],
        ],
        'passwordconfirmation' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_passwordconfirmation',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'required' => true,
                'eval' => 'nospace,password',
            ],
        ],
        'email' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_email',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'required' => true,
                'eval' => 'nospace,email',
            ],
        ],
        'height' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_height',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'required' => true,
                'eval' => 'int',
            ],
        ],
        'age' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_age',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'required' => true,
                'eval' => 'int',
            ],
        ],
        'gender' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_gender',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'required' => true,
                'items' => [
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:select', 'value' => 0],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:gender-woman', 'value' => 1],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:gender-man', 'value' => 2],
                ],
            ],
        ],
        'level' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_level',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'required' => true,
                'items' => [
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:select', 'value' => 0],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:level-00', 'value' => 1],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:level-01', 'value' => 2],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:level-02', 'value' => 3],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:level-03', 'value' => 4],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:level-04', 'value' => 5],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:level-05', 'value' => 6],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:level-06', 'value' => 7],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:level-07', 'value' => 8],
                ],
            ],
        ],
        'category' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_category',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'required' => true,
                'items' => [
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:select', 'value' => 0],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:category-00', 'value' => 1],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:category-01', 'value' => 2],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:category-02', 'value' => 3],
                ],
            ],
        ],
        'bio' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_bio',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'required' => false,
            ],
        ],
        'role' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_role',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'required' => true,
                'items' => [
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:select', 'value' => 0],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:role-00', 'value' => 1],
                    ['label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:role-01', 'value' => 2],
                ],
            ],
        ],
        'verificationcode' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_verificationcode',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'loggedin' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_loggedin',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => '0',
            ],
        ],
        'created' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_created',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'size' => 30,
                'required' => true,
            ],
        ],
        'changed' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_changed',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'size' => 30,
                'required' => true,
            ],
        ],
        'resetcode' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_resetcode',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'resetcodecreated' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:tanzpartnersuche/Resources/Private/Language/de.tanzpartnersuche.xlf:db_resetcodecreated',
            'config' => [
                'type' => 'input',
                'size' => 15,
                'eval' => 'int',
            ],
        ],
    ],
];