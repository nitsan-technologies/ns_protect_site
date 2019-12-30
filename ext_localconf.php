<?php
defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Nitsan.NsProtectSite',
    'Nsprotectsite',
    [
        'ProtectPages' => 'form,login,load'
    ],
    // non-cacheable actions
    [
        'ProtectPages' => 'login'
    ]
);
