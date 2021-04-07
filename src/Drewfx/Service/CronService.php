<?php

namespace Drewfx\Salesforce\Service;

use Drewfx\Salesforce\Exception\GatewayServiceException;
use Drewfx\Salesforce\Integration\Salesforce\Client;
use Drewfx\Salesforce\Integration\Salesforce\Response;
use Drewfx\Salesforce\Model\QueueItem;
use Drewfx\Salesforce\Model\Token;

class CronService
{
    /** @var TokenService */
    private $tokenService;

    /** @var QueueService */
    private $queueService;

    /** @var LeadService */
    private $logService;

    /** @var Client */
    private $api;

    public function __construct(
        TokenService $tokenService,
        QueueService $queueService,
        LeadService $logService,
        Client $api
    )
    {
        $this->tokenService = $tokenService;
        $this->queueService = $queueService;
        $this->logService = $logService;
        $this->api = $api;
    }

    public function execute() : void
    {
        $this->pushLeads();
    }

    /**
     * Per Salesforce documentation there's no way to check if an access token is viable, which makes sense.
     * We must attempt a push of the first item of data, if we get a 401 Unauthorized we know the token is bad.
     * Refresh the token, and re-attempt the push.
     *
     * @throws GatewayServiceException
     */
    protected function pushLeads() : void
    {
        /** @var Token $token */
        $token = $this->tokenService->getLast();
        $items = $this->queueService->get(); // @todo: collection?

        if (empty($items)) {
            exit;
        }

        if ( ! $token) {
            try {
                $this->tokenService->add(
                    $this->api->getOAuthToken()
                );
            } catch (GatewayServiceException $e) {
                throw $e;
            }

            $token = $this->tokenService->getLast();
        }

        /* Push each item in the queue, add result. */
        foreach ($items as $item) {
            /** @var QueueItem $item */
            $response = $this->api->pushLead(
                $token,
                $item->getFields()
            );

            /* If token unviable, remove old, refresh new token */
            if ($response->isUnauthorized()) {
                $this->tokenService->remove($token->getId());

                $this->tokenService->add(
                    $this->api->getOAuthToken()
                );

                /** @var Token $token */
                $token = $this->tokenService->getLast();

                $response = $this->api->pushlead(
                    $token,
                    $item->getFields()
                );
            }

            if ($response->isValid()) {
                $data = $this->extractLeadData($item, $response);
                $this->logService->add($data);
                $this->queueService->remove($item->getId());
            } else {
                $item->incrementAttempts();
                $item->setMessage($response->getMessage());
                $this->queueService->update($item);
            }
        }
    }

    protected function extractLeadData(QueueItem $item, Response $response) : array
    {
        return [
            'url' => $response->getUrl(),
            'request' => $item->getFields(),
            'response' => $response->getRaw(),
            'code' => $response->getCode(),
            'message' => $response->getMessage()
        ];
    }
}
