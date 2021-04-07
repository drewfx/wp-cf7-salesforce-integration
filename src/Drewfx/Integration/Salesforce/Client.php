<?php

namespace Drewfx\Salesforce\Integration\Salesforce;

use Drewfx\Salesforce\Model\ModelInterface;
use Drewfx\Salesforce\Integration\AbstractClient;

class Client extends AbstractClient
{
    protected $token;

    public function getOAuthToken() : Response
    {
        $this->setEndpoint('https://login.salesforce.com')
            ->setPath('/services/oauth2/token')
            ->setMethod(self::METHOD_POST)
            ->setHeaders()
            ->setBody([
                'grant_type' => self::GRANT_TYPE_PASSWORD,
                'client_id' => Configuration::get('client_id'),
                'client_secret' => Configuration::get('client_secret'),
                'username' => Configuration::get('username'),
                'password' => Configuration::get('password')
            ]);

        return $this->call();
    }

    public function pushLead(ModelInterface $token, $data) : Response
    {
        $this->setEndpoint($token->getInstanceUrl())
            ->setPath('/services/data/v49.0/sobjects/lead/')
            ->setMethod(self::METHOD_POST)
            ->setToken($token->getAccessToken())
            ->setHeaders([
                sprintf('Authorization: Bearer %s', $this->getToken()),
                'Cache-Control: no-cache',
                'Content-Type: application/json',
                sprintf('Host: %s', $token->getInstanceHost())
            ])
            ->setBody($data);

        return $this->call();
    }

    public function getToken() : string
    {
        return $this->token;
    }

    public function setToken(string $token) : self
    {
        $this->token = $token;

        return $this;
    }
}
