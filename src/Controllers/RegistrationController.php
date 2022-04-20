<?php

namespace WilokeCard\Controllers;

use WilokeCard\Share\App;

class RegistrationController
{
	public function __construct()
	{
		$aConfigs = json_decode(file_get_contents(plugin_dir_path(__FILE__) . '../Configs/config.json'), true);
		App::bind('dataConfig', $aConfigs);
		add_action('elementor/widgets/register', [$this, 'registerAddon'], 100);
		add_action('wp_enqueue_scripts', [$this, 'registerScripts']);

	}

	public function registerScripts()
	{
		$aHandleCss=[];
		$aHandleJs=[];
		wp_register_style(
			WILOKE_CARD_NAMESPACE . md5(App::get('dataConfig')['css']),
			App::get('dataConfig')['css'],
			[],
			WILOKE_CARD_VERSION);
		$aHandleCss[]=WILOKE_CARD_NAMESPACE . md5(App::get('dataConfig')['css']);


		wp_register_script(
			WILOKE_CARD_NAMESPACE . md5(App::get('dataConfig')['js']),
			App::get('dataConfig')['js'],
			['elementor-frontend'],
			WILOKE_CARD_VERSION,
			true
		);
		$aHandleJs[]=WILOKE_CARD_NAMESPACE . md5(App::get('dataConfig')['js']);
		if (isset(App::get('dataConfig')['libs']['css']) && !empty($aLibCss = App::get('dataConfig')['libs']['css'])) {
			foreach ($aLibCss as $urlCss) {
				wp_register_style(
					WILOKE_CARD_NAMESPACE . md5($urlCss),
					$urlCss,
					[], WILOKE_CARD_VERSION);
				$aHandleCss[]=WILOKE_CARD_NAMESPACE . md5($urlCss);
			}
		}
		App::bind('handleCss',$aHandleCss);

		if (isset(App::get('dataConfig')['libs']['js']) && !empty($aLibJs = App::get('dataConfig')['libs']['js'])) {
			foreach ($aLibJs as $urlJs) {
				wp_register_script(
					WILOKE_CARD_NAMESPACE . md5($urlJs),
					$urlJs,
					[],
					WILOKE_CARD_VERSION,
					true
				);
			}
			$aHandleJs[]=WILOKE_CARD_NAMESPACE . md5($urlJs);
		}
		App::bind('handleJs',$aHandleJs);
	}

	public function registerAddon($oWidgetManager)
	{
		$oWidgetManager->register(new CardAddon());
	}
}