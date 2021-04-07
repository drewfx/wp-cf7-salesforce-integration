<?php

namespace Drewfx\Salesforce\Admin;

use Drewfx\Salesforce\Integration\Salesforce\Configuration;
use Drewfx\Salesforce\Plugin;
use Drewfx\Salesforce\Service\LeadService;
use Drewfx\Salesforce\Service\QueueService;
use WP_Post;
use const SALESFORCE_BASE_PATH;

class Settings
{
    public const PAGE_NAME = 'gc_salesforce';
    protected const PAGE_SLUG = 'salesforce';
    protected const PAGE_TITLE = 'Salesforce Integration';
    protected const MENU_TITLE = 'Salesforce';

    public static function renderFormList() : void
    {
        $posts = get_posts(array(
            'post_type' => 'wpcf7_contact_form',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ));

        if (empty($posts)) {
            echo "<li>No forms found.</li>";
        }

        /** @var WP_Post $post */
        foreach ($posts as $post) {
            $enabled = Configuration::get('integrated_form_' . $post->ID) ? 'checked' : '';

            echo sprintf(
                "<li><label><input type='checkbox' name='%s[integrated_form_%d]' %s>%s</label></li>",
                Configuration::CONFIG_NAME,
                $post->ID,
                $enabled,
                $post->post_title
            );
        }
    }

    public static function renderCredentials() : void
    {
        $enabled = (bool) Configuration::get('api_enabled');

        echo sprintf(
            "<tr><td><label>Enabled: </label></td><td><select name='%s[api_enabled]' required>
                <option value='1' " . ($enabled ? 'selected' : '') . ">Yes</option>
                <option value='0' " . ($enabled ? '' : 'selected') . ">No</option>
            </select></td></tr>",
            Configuration::CONFIG_NAME
        );

        echo sprintf(
            "<tr><td><label>Username:</label></td><td><input type='text' id='username' name='%s[username]' value='%s' required></td></tr>",
            Configuration::CONFIG_NAME,
            Configuration::get('username')
        );

        echo sprintf(
            "<tr><td><label>User Password:</label></td><td><input type='password' id='password' name='%s[password]' value='%s' required></td></tr>",
            Configuration::CONFIG_NAME,
            Configuration::get('password')
        );

        echo sprintf(
            "<tr><td><label>Client ID:</label></td><td><input type='text' id='client_id' name='%s[client_id]' value='%s' required></td></tr>",
            Configuration::CONFIG_NAME,
            Configuration::get('client_id')
        );

        echo sprintf(
            "<tr><td><label>Client Secret:</label></td><td><input type='password' id='client_secret' name='%s[client_secret]' value='%s' required></td></tr>",
            Configuration::CONFIG_NAME,
            Configuration::get('client_secret')
        );

        echo sprintf(
            "<tr><td><label>Endpoint:</label></td><td><input type='text' id='api_url' name='%s[api_url]' value='%s' required></td></tr>",
            Configuration::CONFIG_NAME,
            Configuration::get('api_url')
        );

        echo sprintf(
            "<tr><td><label>Default Lead Owner ID:</label></td><td><input type='text' id='default_lead_owner' name='%s[default_lead_owner]' value='%s'></td></tr>",
            Configuration::CONFIG_NAME,
            Configuration::get('default_lead_owner')
        );

        echo sprintf(
            "<tr><td class='v-align-t'><label>DataMapping</label></td><td><textarea id='data_mappings' name='%s[data_mappings]' cols='51' rows='16'>%s</textarea></td></tr>",
            Configuration::CONFIG_NAME,
            Configuration::get('data_mappings')
        );
    }

    public static function renderStatistics() : void
    {
        $plugin = new Plugin();
        $container = $plugin->getContainer();

        /** @var QueueService $queueService */
        $queueService = $container->get(QueueService::class);
        /** @var LeadService $leadService */
        $leadService = $container->get(LeadService::class);

        $items = $queueService->get();
        $leads = $leadService->get();

        echo sprintf("<h4>PHP Version: %s</h4>", PHP_VERSION);
        echo sprintf("<h4>Queued Items: %d</h4>", count($items));
        echo sprintf("<h4>Leads Submitted: %d</h4>", count($leads));
    }

    public function enqueueStyles() : void
    {
        wp_enqueue_style(
            Plugin::PLUGIN_NAME,
            plugin_dir_url(__FILE__) . '/../../../../assets/css/salesforce-admin.css',
            array(),
            Plugin::VERSION,
            'all'
        );
    }

    public function enqueueScripts() : void
    {
        wp_enqueue_script(
            Plugin::PLUGIN_NAME,
            plugin_dir_url(__FILE__) . '/../../../../assets/js/salesforce-admin.js',
            array(),
            Plugin::VERSION,
            'all'
        );
    }

    public function addOptionsPage() : void
    {
        add_options_page(
            self::PAGE_TITLE,
            self::MENU_TITLE,
            'manage_options',
            self::PAGE_SLUG,
            [$this, 'renderOptionsPage']
        );
    }

    public function initOptionsPage() : void
    {
        register_setting(self::PAGE_NAME, Configuration::CONFIG_NAME);
    }

    public function renderOptionsPage() : void
    {
        include_once SALESFORCE_BASE_PATH . '/views/admin.php';
    }
}
