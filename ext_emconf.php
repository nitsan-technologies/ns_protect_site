<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Password Protected TYPO3',
    'description' => 'This extension will help you to quickly password protected TYPO3 pages without frontend users management, Check manual for more details.',
    'category' => 'plugin',
    'author' => 'NITSAN Technologies Pvt Ltd',
    'author_company' => 'NITSAN Technologies Pvt Ltd',
    'author_email' => 'sanjay@nitsan.in',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '3.1.1',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
