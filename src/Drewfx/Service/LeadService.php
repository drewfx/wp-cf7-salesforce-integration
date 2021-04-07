<?php

namespace Drewfx\Salesforce\Service;

use Drewfx\Salesforce\Model\Factory\Factory;
use Drewfx\Salesforce\Model\Lead;
use Drewfx\Salesforce\Model\Repository\LeadRepository;

class LeadService
{
    /** @var Factory */
    private $factory;

    /** @var LeadRepository */
    private $leadRepository;

    public function __construct(
        Factory $factory,
        LeadRepository $leadRepository
    )
    {
        $this->factory = $factory;
        $this->leadRepository = $leadRepository;
    }

    public function add(array $data) : void
    {
        /** @var Lead $lead */
        $lead = $this->factory->new(Lead::class);
        $lead->setUrl($data['url'])->setRequest($data['request'])
            ->setResponse($data['response'])->setCode($data['code'])
            ->setMessage($data['message'])->setCreatedAt();

        $this->leadRepository->save($lead);
    }

    public function remove($id)
    {
        return $this->leadRepository->delete('id', $id);
    }

    public function get() : array
    {
        return $this->leadRepository->all();
    }
}
