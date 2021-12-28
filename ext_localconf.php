<?php
defined('TYPO3_MODE') || die('Access denied.');

if (version_compare(TYPO3_branch, '10.0', '>=')) {
    $moduleClass = \Nitsan\NsProtectSite\Controller\ProtectPagesController::class;
} else {
    $moduleClass = 'ProtectPages';
}
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Nitsan.NsProtectSite',
    'Nsprotectsite',
    [
        $moduleClass => 'form,login,load'
    ],
    // non-cacheable actions
    [
        $moduleClass => 'login'
    ]
);
