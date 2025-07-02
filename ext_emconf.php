<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'TYPO3 Page Password Protection',
    'description' => 'Easily protect your TYPO3 pages with a password without the need for complex frontend user management. Ideal for temporary content access control during development or limited access pages.',
    'category' => 'plugin',
    'author' => 'Team T3Planet',
    'author_company' => 'T3Planet',
    'author_email' => 'info@t3planet.de',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '2.0.6',
    'constraints' => [
        'depends' => [
            'typo3' => '8.0.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
