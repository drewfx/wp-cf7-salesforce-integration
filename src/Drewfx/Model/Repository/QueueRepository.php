<?php

namespace Drewfx\Salesforce\Model\Repository;

use Drewfx\Salesforce\Model\QueueItem;

class QueueRepository extends AbstractRepository
{
    /** @var string */
    protected $model = QueueItem::class;

    /** @var string */
    protected $table = 'salesforce_integration_queue';

    /**
     * @param QueueItem $item
     * @todo: Move to abstract model, maybe dynamic using model attributes
     */
    public function save(QueueItem $item) : void
    {
        $this->database->insert(
            sprintf('insert into %s (fields, created_at) values (:fields, :created_at)', $this->table),
            [':fields' => $item->getFields(), ':created_at' => $item->getCreatedAt()]
        );
    }

    public function update(QueueItem $item) : void
    {
        $this->database->query(
            sprintf('updated %s set fields=:fields, attempts=:attempts, message=:message where id = :id', $this->table),
            [
                ':fields' => $item->getFields(), ':attempts' => $item->getAttempts(),
                ':message' => $item->getMessage(), ':id' => $item->getId()
            ]
        );
    }
}
