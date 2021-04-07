<?php

namespace Drewfx\Salesforce\Service;

use Drewfx\Salesforce\Model\Factory\Factory;
use Drewfx\Salesforce\Model\QueueItem;
use Drewfx\Salesforce\Model\Repository\QueueRepository;

class QueueService
{
    /** @var Factory */
    protected $factory;

    /** @var QueueRepository */
    protected $queueRepository;

    public function __construct(Factory $factory, QueueRepository $queueRepository)
    {
        $this->factory = $factory;
        $this->queueRepository = $queueRepository;
    }

    public function add($data) : void
    {
        /** @var QueueItem $queue */
        $queue = $this->factory->new(QueueItem::class);
        $queue->setFields($data)->setCreatedAt();

        $this->queueRepository->save($queue);
    }

    public function remove($id)
    {
        return $this->queueRepository->delete('id', $id);
    }

    public function update(QueueItem $item) : void
    {
        $this->queueRepository->update($item);
    }

    public function get() : array
    {
        return $this->queueRepository->all();
    }
}
