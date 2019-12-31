<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Configure new fields:
$fields = [
  'tx_nsprotectsite_protection' => [
    'label' => 'LLL:EXT:ns_protect_site/Resources/Private/Language/locallang_db.xlf:enableprotect',
    'exclude' => 1,
    'config' => [
      'type' => 'check',
      'default' => 0,
    ],
    'onChange' => 'reload'
  ],
  'tx_nsprotectsite_protect_password' => [
    'exclude' => 1,
    'displayCond' => 'FIELD:tx_nsprotectsite_protection:>:0',
    'label' => 'LLL:EXT:ns_protect_site/Resources/Private/Language/locallang_db.xlf:addpass',
    'config' => [
        'type' => 'input',
        'max' => 100,
        'eval' => 'trim,required,password,saltedPassword',
    ]
  ]
];

// Add new fields to pages:
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $fields);

if (version_compare(TYPO3_branch, '10.0', '<')) {
    // Make fields visible in the TCEforms:
  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages', // Table name
    '--div--;LLL:EXT:ns_protect_site/Resources/Private/Language/locallang_db.xlf:tx_nsprotectsite_domain_model_protectpages;tx_nsprotectsite', 
    '1' // List of specific types to add the field list to. (If empty, all type entries are affected)  
  );
} else {
  // Make fields visible in the TCEforms:
  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages', // Table name
    '--palette--;LLL:EXT:ns_protect_site/Resources/Private/Language/locallang_db.xlf:tx_nsprotectsite_domain_model_protectpages;tx_nsprotectsite', 
    '1' // List of specific types to add the field list to. (If empty, all type entries are affected)  
  );
}

// Add the new palette:
$GLOBALS['TCA']['pages']['palettes']['tx_nsprotectsite'] = [
  'showitem' => 'tx_nsprotectsite_protection,tx_nsprotectsite_protect_password'
];