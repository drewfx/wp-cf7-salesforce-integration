<?php

namespace Drewfx\Salesforce\Service;

use Drewfx\Salesforce\Exception\GatewayServiceException;
use Drewfx\Salesforce\Integration\Salesforce\Response;
use Drewfx\Salesforce\Model\Factory\Factory;
use Drewfx\Salesforce\Model\ModelInterface;
use Drewfx\Salesforce\Model\Repository\TokenRepository;
use Drewfx\Salesforce\Model\Token;

class TokenService
{
    /** @var TokenRepository */
    protected $tokenRepository;

    /** @var Factory */
    private $factory;

    public function __construct(Factory $factory, TokenRepository $tokenRepository)
    {
        $this->factory = $factory;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * @param $data
     * @throws GatewayServiceException
     */
    public function add($data) : void
    {
        if ($data instanceof Response) {
            $data = $data->getAttributes();
        }

        if (isset($data['error'])) {
            throw GatewayServiceException::responseError($data['error_description']);
        }

        /** @var Token $token */
        $token = $this->factory->new(Token::class);

        $token->setAttributes($data)
            ->setActive(Token::ACTIVE)
            ->setCreatedAt()
            ->setIssuedAt($data['issued_at'] ?? time());

        $this->tokenRepository->save($token);
    }

    public function remove($id)
    {
        return $this->tokenRepository->delete('id', $id);
    }

    public function get() : array
    {
        return $this->tokenRepository->all();
    }

    public function getLast() : ?ModelInterface
    {
        return $this->tokenRepository->findBy(
            sprintf('select * from %s where active = :active order by id desc', $this->tokenRepository->getTable()),
            [':active' => Token::ACTIVE]
        );
    }
}
