<?php

namespace Drewfx\Salesforce\Language;

use Drewfx\Salesforce\Plugin;

class Internationalization
{
    public function loadPluginTextDomain() : void
    {
        load_plugin_textdomain(
            Plugin::PLUGIN_NAME,
            false,
            dirname(plugin_basename(__FILE__), 2) . '/languages/'
        );
    }
}
