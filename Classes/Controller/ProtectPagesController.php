<?php
namespace Nitsan\NsProtectSite\Controller;

session_start();

/***
 *
 * This file is part of the "[Nitsan] Protect site" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019
 *
 ***/
/**
 * ProtectPagesController
 */
class ProtectPagesController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * action list
     *
     * @return void
     */
    public function loadAction()
    {        
        $data = $GLOBALS['TSFE']->page;
        $pageUid = $data['uid'];

        if (isset($_SESSION['password-' . $pageUid . '-protect'])) {
            return true;
        } else {
            $isActive = $data['tx_nsprotectsite_protection'];
            if ($isActive) {
                $pageUid = $data['uid'];
                $uriBuilder = $this->uriBuilder;
                $uri = $uriBuilder
                    ->setTargetPageUid($pageUid)
                    ->setArguments(['type' => '88889'])
                    ->setCreateAbsoluteUri(true)
                    ->build();
                $this->redirectToUri($uri);
            }
        }
        return true;
    }

    /**
     * action login
     *
     * @return void
     */
    public function loginAction()
    {
        $params = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_nsprotectsite_nsprotectsite');

        $data = $GLOBALS['TSFE']->page;
        $saltedPassword = $data['tx_nsprotectsite_protect_password'];
        $pass = $params['pass'];

        $success = false;

        if ($GLOBALS['TYPO3_CONF_VARS']['BE']['loginSecurityLevel'] == 'rsa') {
            if ($saltedPassword == $pass) {
                $success = true;
            }
        } elseif ($GLOBALS['TYPO3_CONF_VARS']['BE']['loginSecurityLevel'] == 'md5') {
            $password == md5($pass);
            if ($saltedPassword == $password) {
                $success = true;
            }
        } elseif ($GLOBALS['TYPO3_CONF_VARS']['BE']['loginSecurityLevel'] === 'sha1') {
            $password == sha1($pass);
            if ($saltedPassword == $password) {
                $success = true;
            }
        } else {            
            $objSalt = \TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory::get($saltedPassword,'BE');
            if (is_object($objSalt)) {
                $success = $objSalt->checkPassword($pass, $saltedPassword);
            }
        }

        if ($success === true) {
            $pageUid = $data['uid'];
            $uriBuilder = $this->uriBuilder;
            $_SESSION['password-' . $pageUid . '-protect'] = 'Yes';

            $uri = $uriBuilder
                ->setTargetPageUid($pageUid)
                ->setCreateAbsoluteUri(true)
                ->build();
            $this->redirectToUri($uri);
        } else {
            $pageUid = $data['uid'];
            $uriBuilder = $this->uriBuilder;
            $uri = $uriBuilder
                ->setTargetPageUid($pageUid)
                ->setArguments(['type' => '88889', 'inavlid' => '1'])
                ->setCreateAbsoluteUri(true)
                ->build();
            $this->redirectToUri($uri);
        }
    }

    /**
     * action form
     *
     * @return void
     */
    public function formAction()
    {
        if ($_REQUEST['inavlid']) {
            $this->view->assign('inavlid', 1);
        }
    }
}
