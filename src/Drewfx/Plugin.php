<?php

namespace Drewfx\Salesforce;

use Drewfx\Salesforce\Admin\Settings;
use Drewfx\Salesforce\Includes\Hooks;
use Drewfx\Salesforce\Includes\Loader;
use Drewfx\Salesforce\Language\Internationalization;

class Plugin
{
    public const VERSION = '1.0.0';
    public const PLUGIN_NAME = 'salesforce-integration';
    public const PLUGIN_DISPLAY_NAME = 'Salesforce Integration';

    /** @var string */
    protected $version;

    /** @var string */
    protected $plugin_name;

    /** @var string */
    protected $plugin_display_name;

    /** @var Loader */
    protected $loader;

    /** @var Container */
    protected $container;

    public function __construct()
    {
        $this->container = new Container();
        $this->loader = new Loader();
        $this->version = self::VERSION;
        $this->plugin_name = self::PLUGIN_NAME;
        $this->plugin_display_name = self::PLUGIN_DISPLAY_NAME;
        $this->setLocale();
        $this->defineAdminHooks();
        $this->definePublicHooks();
    }

    private function setLocale() : void
    {
        $i18n = $this->container->get(Internationalization::class);

        $this->loader->addAction('plugins_loaded', $i18n, 'loadPluginTextdomain');
    }

    private function defineAdminHooks() : void
    {
        $settings = $this->container->get(Settings::class);

        $this->loader->addAction('admin_menu', $settings, 'addOptionsPage');
        $this->loader->addAction('admin_init', $settings, 'initOptionsPage');
        $this->loader->addAction('admin_init', $settings, 'enqueueStyles');
        $this->loader->addAction('admin_init', $settings, 'enqueueScripts');
    }

    private function definePublicHooks() : void
    {
        $hooks = $this->container->get(Hooks::class);

        $this->loader->addFilter('wpcf7_mail_sent', $hooks, 'queueLead', 10, 1);
    }

    public static function run() : void
    {
        $plugin = new static();
        $plugin->loader->run();
    }

    public function getPluginName() : string
    {
        return $this->plugin_name;
    }

    public function getPluginDisplayName() : string
    {
        return $this->plugin_display_name;
    }

    public function getPluginVersion() : string
    {
        return $this->version;
    }

    public function getLoader() : Loader
    {
        return $this->loader;
    }

    public function getContainer() : Container
    {
        return $this->container;
    }
}
