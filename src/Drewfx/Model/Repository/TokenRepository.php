<?php

namespace Drewfx\Salesforce\Model\Repository;

use Drewfx\Salesforce\Model\Token;

class TokenRepository extends AbstractRepository
{
    /** @var string */
    protected $model = Token::class;

    /** @var string */
    protected $table = 'salesforce_integration_token';

    /**
     * @param Token $token
     * @todo: Move to abstract model, maybe dynamic using model attributes
     */
    public function save(Token $token) : void
    {
        $columns = $token->getStringifiedKeys();

        $this->database->insert(
            sprintf(
                'insert into %s (%s) values (:instance_url, :token_type, :access_token, :active, :signature, :issued_at, :created_at)',
                $this->table,
                $columns
            ),
            [
                ':instance_url' => $token->getInstanceUrl(), ':token_type' => $token->getTokenType(),
                ':access_token' => $token->getAccessToken(), ':active' => $token->getActive(),
                ':signature' => $token->getSignature(), ':issued_at' => $token->getIssuedAt(),
                ':created_at' => $token->getCreatedAt()
            ]
        );
    }
}
