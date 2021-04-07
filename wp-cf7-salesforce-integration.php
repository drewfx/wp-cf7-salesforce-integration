<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              druppel.io
 * @since             1.0.0
 * @package           WP CF7 Salesforce Integration
 *
 * @wordpress-plugin
 * Plugin Name:       WP CF7 Salesforce Integration
 * Plugin URI:        druppel.io
 * Description:       This plugin adds integration functionality with Salesforce to push Leads
 * Version:           1.1.0
 * Author:            Drew Ruppel
 * Author URI:        druppel.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       salesforce-integration
 * Domain Path:       /languages
 */

use Drewfx\Salesforce\Plugin;
use Drewfx\Salesforce\Setup\Activator;
use Drewfx\Salesforce\Setup\Deactivator;

if ( ! defined('WPINC')) {
	exit('Uh Oh...');
}

/** Define Constants */
define('SALESFORCE_BASE_PATH', __DIR__ . DIRECTORY_SEPARATOR);

/** Autoload classes and files */
require_once(__DIR__ . '/vendor/autoload.php');

/** Activation Hook */
function activate_salesforce_integration()
{
    Activator::run();
}

/** Deactivation Hook */
function deactivate_salesforce_integration()
{
    Deactivator::run();
}

/** Register De/Activation Hooks */
register_activation_hook(__FILE__, 'activate_salesforce_integration');
register_deactivation_hook(__FILE__, 'deactivate_salesforce_integration');

/** Run Plugin */
Plugin::run();
