<?php

namespace WilokeElementCore\Controllers;


use WilokeElementCore\Controllers\PostControl\CustomPostControl;
use WilokeElementCore\Controllers\SliderControl\CustomSliderControl;
use WilokeElementCore\Share\App;
use function foo\func;

class RegistrationController
{
	public function __construct()
	{
		add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );
		$aConfigs = json_decode(file_get_contents(plugin_dir_path(__FILE__) . '../Configs/config.json'), true);
		App::bind('dataConfig', $aConfigs);
//		$aDefault=json_decode(file_get_contents(plugin_dir_path(__FILE__) . '../Configs/default.json'), true);
//		App::bind('dataDefault', $aDefault);
		add_action('elementor/widgets/register', [$this, 'registerAddon']);
		add_action('elementor/controls/register', [$this, 'registerControls']);
		add_action('wp_enqueue_scripts', [$this, 'registerScripts']);
		// Register Category
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_widget_categories' ] );
	}
	function add_elementor_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'wil-ok-category',
			[
				'title' =>'wiloke',
				'icon' => 'eicon-person',
			]
		);
	}
	public function registerScripts()
	{
		$aHandleCss=[];
		$aHandleJs=[];
		wp_register_style(
			WILOKE_WILOKEELEMENTCORE_NAMESPACE . md5(App::get('dataConfig')['css']),
			App::get('dataConfig')['css'],
			[],
			WILOKE_WILOKEELEMENTCORE_VERSION);
		$aHandleCss[]=WILOKE_WILOKEELEMENTCORE_NAMESPACE . md5(App::get('dataConfig')['css']);


//		wp_register_script(
//			WILOKE_WILOKEELEMENTCORE_NAMESPACE . md5(App::get('dataConfig')['js']),
//			App::get('dataConfig')['js'],
//			['elementor-frontend'],
//			WILOKE_WILOKEELEMENTCORE_VERSION,
//			true
//		);
		//$aHandleJs[]=WILOKE_WILOKEELEMENTCORE_NAMESPACE . md5(App::get('dataConfig')['js']);
		if (isset(App::get('dataConfig')['libs']['css']) && !empty($aLibCss = App::get('dataConfig')['libs']['css'])) {
			foreach ($aLibCss as $urlCss) {
				wp_register_style(
					WILOKE_WILOKEELEMENTCORE_NAMESPACE . md5($urlCss),
					$urlCss,
					[], WILOKE_WILOKEELEMENTCORE_VERSION);
				$aHandleCss[]=WILOKE_WILOKEELEMENTCORE_NAMESPACE . md5($urlCss);
			}
		}
		App::bind('handleCss',$aHandleCss);

		if (isset(App::get('dataConfig')['libs']['js']) && !empty($aLibJs = App::get('dataConfig')['libs']['js'])) {
			foreach ($aLibJs as $urlJs) {
				wp_register_script(
					WILOKE_WILOKEELEMENTCORE_NAMESPACE . md5($urlJs),
					$urlJs,
					[],
					WILOKE_WILOKEELEMENTCORE_VERSION,
					true
				);
			}
			$aHandleJs[]=WILOKE_WILOKEELEMENTCORE_NAMESPACE . md5($urlJs);
		}
		App::bind('handleJs',$aHandleJs);
	}

	public function registerAddon($oWidgetManager)
	{
		$oWidgetManager->register(new PluginAddon());
		$oWidgetManager->register(new TestAddon());
	}

	public function registerControls($oControlManager)
	{
		$oControlManager->register(new CustomPostControl());
		$oControlManager->register(new CustomSliderControl());
	}
}
