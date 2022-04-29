<?php

// @Information
/**
 * Tested up to:        5.6.2
 * Domain Path:         /languages
 * Text Domain:         wil-WILOKEELEMENTCORE
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * License:             GPL-2.0+
 * Author URI:          https://WILOKEELEMENTCORE.com
 * Author:              wiloke
 * Version:             1.0.0
 * Description:         WILOKEELEMENTCORE
 * Plugin URI:          https://wiloke.com
 * Plugin Name:        WILOKEELEMENTCORE
 */

define("WILOKE_WILOKEELEMENTCORE_VERSION", uniqid());
define("WILOKE_WILOKEELEMENTCORE_NAMESPACE", "wiloke-element-core");
define("WILOKE_WILOKEELEMENTCORE_VIEWS_PATH", plugin_dir_path(__FILE__));
define("WILOKE_WILOKEELEMENTCORE_VIEWS_URL", plugin_dir_url(__FILE__));


add_action("plugins_loaded", "loadPluginDomain");
if (!function_exists("loadPluginDomain")) {
	function loadPluginDomain()
	{
		load_plugin_textdomain("wiloke-element-core", false, plugin_dir_path(__FILE__) . "languages");
	}
}

require_once plugin_dir_path(__FILE__) . "vendor/autoload.php";

new \WilokeElementCore\Controllers\RegistrationController();
new \WilokeElementCore\Controllers\HandleAjaxController();

