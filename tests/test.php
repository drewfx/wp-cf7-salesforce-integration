<?php
// Retrieve token w/ Postman
// https://blog.mkorman.uk/using-postman-to-explore-salesforce-restful-web-services/

// sObjects Definition
// https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/resources_sobject_basic_info.htm

// Auth URL: https://login.salesforce.com/services/oauth2/authorize     [Get Auth Code]
// Token URL: https://login.salesforce.com/services/oauth2/token        [Exchange Auth Code For Access Token]
// Callback URI: https://www.getpostman.com/oauth2/callback             [URL to send Access Token]

use Drewfx\Salesforce\Exception\ConfigurationException;
use Drewfx\Salesforce\Plugin;
use Drewfx\Salesforce\Integration\Salesforce\Client;

defined('DB_HOST') or define('DB_HOST', 'localhost');
defined('DB_NAME') or define('DB_NAME', '');
defined('DB_USER') or define('DB_USER', '');
defined('DB_PASSWORD') or define('DB_PASSWORD', '');

if (any_empty(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD)) {
    throw new ConfigurationException("Check your DB_* Credentials.");
}

require_once '../vendor/autoload.php';

$plugin = new Plugin();
$container =  $plugin->getContainer();

$tokenService = $container->get(\Drewfx\Salesforce\Service\TokenService::class);

$db = $container->get(\Drewfx\Salesforce\Database\Database::class);

/** @var Client $client */
$client = $container->get(Client::class);

$tokenService->add(
    $client->getOAuthToken()
);

$token = $tokenService->getLast();

$response = $client->pushLead(
    $token,
    '{"FirstName":"John","Smith":"Gates","Company":"Example Company","Street":"123 N Main Street","Phone":"714-742-4444","Email":"test@example.com"}'
);

dd($response);
