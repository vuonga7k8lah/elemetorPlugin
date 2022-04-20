<?php
/**
 * Plugin Name: Wiloke Card Table for Elementor
 * Plugin URI:        https://wiloke.com
 * Description:       Wiloke Testimonials Addon for Elementor
 * Version:           1.0.1
 * Author:            wiloke
 * Author URI:        https://wiloke.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wiloke-card
 * Domain Path:       /languages
 * Tested up to:      5.6.2
 */



define("WILOKE_CARD_VERSION", "1.0.3");
define("WILOKE_CARD_NAMESPACE", "wiloke-card");
define("WILOKE_CARD_VIEWS_PATH",plugin_dir_path(__FILE__));


add_action('plugins_loaded', 'wilokeCardTableLoadPluginDomain');
if (!function_exists('wilokeCardTableLoadPluginDomain')) {
	function wilokeCardTableLoadPluginDomain()
	{
		load_plugin_textdomain('wiloke-card', false, plugin_dir_path(__FILE__) . 'languages');
	}
}

require_once plugin_dir_path(__FILE__) . "vendor/autoload.php";

new \WilokeCard\Controllers\RegistrationController();

