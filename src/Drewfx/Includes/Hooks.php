<?php

namespace Drewfx\Salesforce\Includes;

use Drewfx\Salesforce\Integration\Salesforce\Configuration;
use Drewfx\Salesforce\Integration\Salesforce\Data\Filter;
use Drewfx\Salesforce\Integration\Salesforce\Data\Mapper;
use Drewfx\Salesforce\Service\QueueService;
use WPCF7_ContactForm;
use WPCF7_Submission;

class Hooks
{
    /** @var QueueService */
    protected $queue;

    public function __construct(QueueService $queue)
    {
        $this->queue = $queue;
    }

    public function queueLead(WPCF7_ContactForm $contact_form)
    {
        $post = WPCF7_Submission::get_instance()->get_posted_data();

        if ( ! $this->enabled($contact_form->id())) {
            return $post;
        }

        $map = new Mapper(
            new Filter($post)
        );

        $this->queue->add($map->getFields());

        return $post;
    }

    protected function enabled(int $id) : bool
    {
        $api = (bool) Configuration::get('api_enabled');
        $form = (bool) Configuration::get('integrated_form_' . $id);

        return $api && $form;
    }
}
