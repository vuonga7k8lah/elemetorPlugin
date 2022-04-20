<?php

namespace WilokeCard\Controllers;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Timber\Timber;
use WilokeCard\Share\App;
use WilokeCard\Share\TraitHandleAutoRenderSettingControls;

class CardAddon extends Widget_Base
{
	use TraitHandleAutoRenderSettingControls;

	public static $aSettings               = [];
	public function get_name()
	{
		return App::get('dataConfig')['name'];
	}

	public function get_stack($with_common_controls = true)
	{
		return parent::get_stack(false);
	}

	public function get_title()
	{
		return App::get('dataConfig')['title'];
	}

	public function get_script_depends()
	{
		return [App::get('dataConfig')['name'].'-script', App::get('dataConfig')['name'].'-script-swiper-bundle'];
	}

	public function get_icon()
	{
		return 'eicon-call-to-action';
	}

	public function get_style_depends()
	{
		return [App::get('dataConfig')['name'].'-style', App::get('dataConfig')['name'].'-bundle.min'];
	}

	public function get_categories()
	{
		return ['basic'];
	}

	public function get_keywords()
	{
		return App::get('dataConfig')['keywords'];
	}

	private function parseItems($aSettings)
	{
		$aItems = [];
		$aDataFields = [];
		$aConfigs = $this->getDataConfigField();
		foreach ($aConfigs as $aSection) {
			if (!empty($aSection['fields'])) {
				foreach ($aSection['fields'] as $aFields) {

					if (is_array($aSettings[$aFields['id']])) {
						// data cua cac repeater
						$aResult = [];
						//lay array field name
						$aNameField=[];
						foreach ($aFields['fields'] as $aItem){
							$aNameField[$aItem['name']] =$aItem['type'];
						}
						foreach ($aSettings[$aFields['id']] as $aItemDataFields) {
							$aRawResult = [];
							foreach ($aNameField as $name=>$type) {
								if (is_array($aItemDataFields[$name])) {
									$aRawResult[$name] = $aItemDataFields[$name]['value'] ??
										$aItemDataFields[$name]['url'] ?? "";
								} else {
									$valueFields=($type=='switcher')
										?isset($aItemDataFields[$name])&&!empty($aItemDataFields[$name]) :$aItemDataFields[$name];
									$aRawResult[$name] = $valueFields;
								}
							}

							$aResult[] = $aRawResult;

						}
						$aDataFields[$aFields['name']] = $aResult;
					} else {
						$valueFields=($aFields['type']=='switcher')?isset($aSettings[$aFields['id']])&&!empty($aSettings[$aFields['id']])
							:$aSettings[$aFields['id']];
						$aDataFields[$aFields['name']] = $valueFields;
					}
				}
			}
			$aItems[$aSection['name']] = $aDataFields;
			$aDataFields = [];
		}
		//var_dump($aItems);die();
		//var_dump(json_encode($aItems));die();
		return $aItems;
	}


	protected function register_controls()
	{
		$aConfigs = $this->getDataConfigField();
		$this->handle($aConfigs, $this);
	}

	public function getDataConfigField(): array
	{
		return json_decode(file_get_contents(plugin_dir_path(__FILE__) . '../Assets/New/schema.json'), true);
		//return json_decode(file_get_contents(plugin_dir_path(__FILE__) . '../Assets/test.json'), true);
	}

	protected function render()
	{
		Timber::$locations = WILOKE_CARD_VIEWS_PATH . 'src/Views';
		self::$aSettings = $this->get_settings_for_display();
		//var_dump(self::$aSettings);
		Timber::render(plugin_dir_path(__FILE__) . "../Views/section.twig", [
			"data" => $this->parseItems(self::$aSettings)
		]);
	}
}