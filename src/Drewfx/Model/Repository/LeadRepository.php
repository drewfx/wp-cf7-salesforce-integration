<?php

namespace Drewfx\Salesforce\Model\Repository;

use Drewfx\Salesforce\Model\Lead;

class LeadRepository extends AbstractRepository
{
    /** @var string */
    protected $model = Lead::class;

    /** @var string */
    protected $table = 'salesforce_integration_lead';

    /**
     * @param Lead $lead
     * @todo: Move to abstract model, maybe dynamic using model attributes
     */
    public function save(Lead $lead) : void
    {
        $columns = $lead->getStringifiedKeys();

        $this->database->insert(
            sprintf(
                'insert into %s (%s) values (:url, :request, :response, :code, :message, :created_at)',
                $this->table,
                $columns
            ),
            [
                ':url' => $lead->getUrl(), ':request' => $lead->getRequest(),
                ':response' => $lead->getResponse(), ':code' => $lead->getCode(),
                ':message' => $lead->getMessage(), ':created_at' => $lead->getCreatedAt()
            ]
        );
    }
}
