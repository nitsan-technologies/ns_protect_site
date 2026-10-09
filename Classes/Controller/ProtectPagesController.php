<?php
namespace Nitsan\NsProtectSite\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Crypto\PasswordHashing\InvalidPasswordHashException;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Install\Service\SessionService;

$request = $GLOBALS['TYPO3_REQUEST'] ?? false;
if ($request && ApplicationType::fromRequest($request)->isFrontend()) {
    GeneralUtility::makeInstance(SessionService::class)->startSession();
}

/***
 *
 * This file is part of the "[Nitsan] Protect site" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019-2025
 *
 ***/

class ProtectPagesController extends ActionController
{
    public function processRequest(RequestInterface $request): ResponseInterface
    {
        $response = parent::processRequest($request);
        if ($response instanceof RedirectResponse) {
            return $response;
        }

        // Return nothing when page is not password protected or password was entered correctly, even if hacky
        $nothing = $this->htmlResponse('');

        $redirectOrForm = $this->loadAction();

        return $redirectOrForm ?? $nothing;
    }

    public function loadAction(): ?ResponseInterface
    {
        $tsFeController = $GLOBALS['TSFE'];
        $data = $tsFeController->page;
        $pageUid = $data['uid'];

        // Page is not password protected, let them through
        if (!$data['tx_nsprotectsite_protection']) {
            return null;
        }
        // Password was provided, let them through
        if (isset($_SESSION['password-' . $pageUid . '-protect'])) {
            return null;
        }
        // Password form was requested, show it
        if ($this->request->getAttribute('routing')->getPageType() === '88889') {
            return $this->htmlResponse();
        }

        if ($this->uriBuilder === null) {
            $this->uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        }
        $uriBuilder = $this->uriBuilder;
        $uri = $uriBuilder
            ->setTargetPageUid($pageUid)
            ->setArguments(['type' => '88889'])
            ->setCreateAbsoluteUri(true)
            ->build();
        return $this->redirectToUri($uri);
    }

    /**
     * @throws InvalidPasswordHashException
     */
    public function loginAction(): ResponseInterface
    {
        $data = $GLOBALS['TSFE']->page;
        $saltedPassword = $data['tx_nsprotectsite_protect_password'];

        $password = $this->request->getArguments()['pass'] ?? null;

        // Treat a missing password (e.g. a GET request to the form target) like a wrong one
        $success = false;
        if (is_string($password) && $password !== '') {
            $objSalt = (new PasswordHashFactory)->get($saltedPassword, 'BE');
            $success = $objSalt->checkPassword($password, $saltedPassword);
        }

        $pageUid = $data['uid'];
        $uriBuilder = $this->uriBuilder;
        $uri = $uriBuilder->setTargetPageUid($pageUid)->setCreateAbsoluteUri(true);
        if ($success === true) {
            $_SESSION['password-' . $pageUid . '-protect'] = 'Yes';
        } else {
            $uri->setArguments(['type' => '88889', 'invalid' => '1']);
        }

        return $this->redirectToUri($uri->build(), statusCode: 307);
    }

    public function formAction(): ResponseInterface
    {
        $params = $this->request->getQueryParams();

        if (array_key_exists('invalid', $params) && $params['invalid'] === '1') {
            $this->view->assign('invalid', 1);
        }

        return $this->htmlResponse();
    }
}
