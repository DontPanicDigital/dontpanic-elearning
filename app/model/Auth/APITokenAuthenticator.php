<?php

namespace DontPanic\Auth;

use DontPanic\Api\ApiTokenModel;
use Nette\Security\AuthenticationException;

/**
 * Authenticator class for API token authentication
 */
class APITokenAuthenticator extends BaseAuthenticator
{

    /** @var ApiTokenModel */
    protected $apiTokenModel;

    /**
     * TokenAuthenticator constructor.
     *
     * @param ApiTokenModel $apiTokenModel
     */
    public function __construct(ApiTokenModel $apiTokenModel)
    {
        $this->apiTokenModel = $apiTokenModel;
    }

    /**
     * @inheritdoc
     */
    public function authenticate(array $credentials)
    {
        list($tokenStr) = $credentials;
        $token = $this->apiTokenModel->findTokenByString($tokenStr, null);
        if (!$token) {
            throw new AuthenticationException('Token not found.');
        }

        return $this->createIdentity($token->getUser());
    }
}