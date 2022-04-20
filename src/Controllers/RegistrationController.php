<?php

namespace WilokeCard\Controllers;

use WilokeCard\Share\App;

class RegistrationController
{
	public function __construct()
	{
		//var_dump(plugin_dir_path(__FILE__).'../Assets/Image/placeholder.jpeg');die();
		$aConfigs = json_decode(file_get_contents(plugin_dir_path(__FILE__) . '../Assets/New/config.json'), true);
		App::bind('dataConfig', $aConfigs);
		add_action('elementor/widgets/register', [$this, 'registerAddon'], 100);
		add_action('wp_enqueue_scripts', [$this, 'registerScripts']);

	}

	public function registerScripts()
	{
		wp_register_style(App::get('dataConfig')['name'].'-style', App::get('dataConfig')['css'], [], WILOKE_CARD_VERSION);
		wp_register_style(App::get('dataConfig')['name'].'-bundle.min', plugin_dir_url(__FILE__) . '../Assets/swiper-bundle.min.css',
			[], WILOKE_CARD_VERSION);
		wp_register_script(
			App::get('dataConfig')['name'].'-script',
			App::get('dataConfig')['js'],
			['elementor-frontend'],
			WILOKE_CARD_VERSION,
			true
		);
		wp_register_script(
			App::get('dataConfig')['name'].'-swiper-bundle',
			plugin_dir_url(__FILE__) . '../Assets/swiper-bundle.min.js',
			[],
			WILOKE_CARD_VERSION,
			true
		);
	}

	public function registerAddon($oWidgetManager)
	{
		$oWidgetManager->register(new CardAddon());
	}
}