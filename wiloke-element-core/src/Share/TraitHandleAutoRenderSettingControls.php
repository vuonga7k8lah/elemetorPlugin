<?php

namespace WilokeElementCore\Share;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

trait TraitHandleAutoRenderSettingControls
{
	public static string $variantKey = 'variant1';
	public array         $convertTypeElementor
	                                 = [
			'text'            => Controls_Manager::TEXT,
			'number'          => Controls_Manager::NUMBER,
			'textarea'        => Controls_Manager::TEXTAREA,
			'wysiwyg'         => Controls_Manager::WYSIWYG,
			'code'            => Controls_Manager::CODE,
			'hidden'          => Controls_Manager::HIDDEN,
			'switcher'        => Controls_Manager::SWITCHER,
			'popover_toggle'  => Controls_Manager::POPOVER_TOGGLE,
			'select'          => Controls_Manager::SELECT,
			'select2'         => Controls_Manager::SELECT2,
			'choose'          => Controls_Manager::CHOOSE,
			'color'           => Controls_Manager::COLOR,
			'icons'           => Controls_Manager::ICONS,
			'image'           => Controls_Manager::MEDIA,
			'font'            => Controls_Manager::FONT,
			'dateTime'        => Controls_Manager::DATE_TIME,
			'gallery'         => Controls_Manager::GALLERY,
			'array'           => Controls_Manager::REPEATER,
			'tabs'            => Controls_Manager::TABS,
			'tab'             => Controls_Manager::TAB,
			'section'         => Controls_Manager::TAB_CONTENT,
			'media'           => Controls_Manager::MEDIA,
			'url'             => Controls_Manager::URL,
			'slider'          => Controls_Manager::SLIDER,
			'divider'         => Controls_Manager::DIVIDER,
			'wil_custom_post' => 'wil-custom-post',
			'wil_slider'      => 'wil-slider',
			'typography'      => 'typography',
			'text_shadow'     => 'text_shadow',
			'box_shadow'      => 'box_shadow',
			'border'          => 'border',
			'background'      => 'background',
			'image_size'      => 'image_size',
			'css_filter'      => 'css_filter',
		];
	public array         $groupControl
	                                 = ['typography', 'text_shadow', 'box_shadow', 'border', 'background', 'image_size',
	                                    'css_filter'];

	public function formatFieldGroup()
	{
		$this->convertTypeElementor['typography'] = Group_Control_Typography::get_type();
		$this->convertTypeElementor['text_shadow'] = Group_Control_Text_Shadow::get_type();
		$this->convertTypeElementor['box_shadow'] = Group_Control_Box_Shadow::get_type();
		$this->convertTypeElementor['border'] = Group_Control_Border::get_type();
		$this->convertTypeElementor['background'] = Group_Control_Background::get_type();
		$this->convertTypeElementor['image_size'] = Group_Control_Image_Size::get_type();
		$this->convertTypeElementor['css_filter'] = Group_Control_Css_Filter::get_type();
	}

	public function handleFieldsAddControls($aFields, $hadTypeRepeater = false): array
	{
		$aData = [];
		if (empty($aFields)) {
			return [];
		}
		if ($hadTypeRepeater) {
			$oRepeater = new Repeater();
		}
		foreach ($aFields as $aItem) {
			if ($hadTypeRepeater) {
				$aDataItems = $aItem;
				$aDataItems['default'] = $this->convertSwitchToBoolean($aItem['default'], 'in');
				if (isset($aItem['condition'])&&!empty($aItem['condition'])){
					foreach ($aItem['condition'] as $key=>$values){
						$aDataItems['condition'][$key]=$this->convertSwitchToBoolean($values, 'in');;
					}
				}
				//check control group
				if (in_array($aItem['type'], $this->groupControl)) {
					$oRepeater->add_group_control(
						$this->convertTypeElementor[$aItem['type']],
						$aDataItems
					);
				} else {
					$oRepeater->add_control(
						$aItem['id'],
						$aDataItems
					);
				}

			} else {
				$aRawItem = $aItem;
				$aRawItem['type'] = $this->convertTypeElementor[$aItem['type']];
				$aDataItems['default'] = $this->convertSwitchToBoolean($aItem['default'], 'in');
				if (isset($aItem['condition'])&&!empty($aItem['condition'])){
					foreach ($aItem['condition'] as $key=>$values){
						$aRawItem['condition'][$key]=$this->convertSwitchToBoolean($values, 'in');;
					}
				}
				$aData[] = $aRawItem;
			}
		}

		return $hadTypeRepeater ? $oRepeater->get_controls() : $aData;
	}

	protected function convertSwitchToBoolean($value, $status = 'out')
	{
		if ($status == 'in') {
			if (is_bool($value)) {
				return $value ? 'yes' : 'no';
			}

		} else {
			if (in_array($value, ['yes', 'no'])) {
				return $value == 'yes';
			}
		}
		return $value;
	}

	public function handle(array $aData, Widget_Base $that)
	{
		$this->formatFieldGroup();
		foreach ($aData as $aSession) {
			if ($aSession['type'] == 'section') {
				$aSessionField = $aSession;
				$aSessionField['tab'] = $this->convertTypeElementor[$aSession['type']];
				$that->start_controls_section(
					$aSession['id'],
					$aSessionField
				);

				if (!empty($aSession['fields'])) {
					foreach ($aSession['fields'] as $aFields) {

						if ($aFields['type'] == 'tabs') {
							$that->start_controls_tabs(
								$aFields['id']
							);

							if (!empty($aFields['fields'])) {

								foreach ($aFields['fields'] as $aItemFields) {

									if ($aItemFields['type'] == 'tab') {
										$that->start_controls_tab(
											$aItemFields['id'],
											$aItemFields
										);
										if (!empty($aItemFields['fields'])) {

											foreach ($aItemFields['fields'] as $aField) {
												$aDataFieldTab = $aField;
												$aDataFieldTab['default']
													= $this->convertSwitchToBoolean($aField['default'], 'in');
												if (isset($aField['condition'])&&!empty($aField['condition'])){
													foreach ($aField['condition'] as $key=>$values){
														$aDataFieldTab['condition'][$key]=$this->convertSwitchToBoolean($values, 'in');;
													}
												}
												$that->add_control(
													$aField['id'],
													$aDataFieldTab
												);
											}
										}
										$that->end_controls_tab();
									} else {
										$aFieldsControl = $aItemFields;
										$aFieldsControl['type'] = $this->convertTypeElementor[$aItemFields['type']];
										$aFieldsControl['fields']
											= $this->handleFieldsAddControls($aItemFields['fields'] ?? [],
											$aItemFields['type'] == 'repeater');
										if (isset($aFields['condition'])&&!empty($aFields['condition'])){
											foreach ($aFields['condition'] as $key=>$values){
												$aFieldsControl['condition'][$key]=$this->convertSwitchToBoolean($values, 'in');;
											}
										}

										$that->add_control(
											$aItemFields['id'],
											$aFieldsControl
										);
									}

								}
							}
							$that->end_controls_tabs();
						} else {
							$aFieldsControl = $aFields;
							$aFieldsControl['type'] = $this->convertTypeElementor[$aFields['type']];
							if ($aFieldsControl['type'] == 'popover_toggle' && !empty($aFieldsControl['fields'])) {
								$this->add_control(
									$aFieldsControl['id'],
									$aFieldsControl
								);
								$this->start_popover();
								foreach ($aFieldsControl['fields'] as $aFieldsItems) {
									$this->add_control(
										$aFieldsItems['id'],
										$aFieldsItems
									);
								}
								$this->end_popover();
							} else {
								$aFieldsControl['fields'] = $this->handleFieldsAddControls($aFields['fields'] ?? [],
									$aFieldsControl['type'] == 'repeater');
								$aFieldsControl['default'] = $this->convertSwitchToBoolean($aFields['default'] ?? '', 'in');
								if (isset($aFields['condition']) && !empty($aFields['condition'])) {
									foreach ($aFields['condition'] as $key => $values) {
										$aFieldsControl['condition'][$key] = $this->convertSwitchToBoolean($values,
											'in');
									}
								}
								//check control group
								//var_dump($aFieldsControl);die();
								if (in_array($aFieldsControl['type'], $this->groupControl)) {
									$that->add_group_control(
										$this->convertTypeElementor[$aFieldsControl['type']],
										$aFieldsControl
									);
								} else {
									$that->add_control(
										$aFieldsControl['id'],
										$aFieldsControl
									);
								}
							}
						}
					}
				}
				$that->end_controls_section();
			}
		}
	}

	public function handleDefaultFieldData($rawData, ...$aKey)
	{
		if (empty($rawData)) {
			$aDefaultData = App::get('dataDefault')[self::$variantKey] ?? [];
			$rawData = $this->handleRecursive($aDefaultData, $aKey);
		}
		return $rawData;
	}

	public function handleRecursive($aData, $aKey)
	{

		if (count($aKey) == 1) {
			return $aData[$aKey[0]];
		} else {
			$aData1 = $aData[$aKey[0]];
			unset($aKey[0]);
			return $this->handleRecursive($aData1, array_merge($aKey));
		}
	}

	public function parseItems($aSettings)
	{
		$aItems = [];
		$aConfigs = $this->getDataConfigField();
		foreach ($aConfigs as $aSection) {
			if (!empty($aSection['fields'])) {
				if ($aSection['type'] == 'divider') {
					continue;
				}
				foreach ($aSection['fields'] as $aFields) {
					if (is_array($aSettings[$aFields['id']])) {
						// data repeater
						$aResult = [];
						//get array field name
						if (!empty($aFields['fields'])) {
							$aNameField = [];
							foreach ($aFields['fields'] as $aItem) {
								if ($aItem['type'] == 'divider') {
									continue;
								}
								$aNameField[$aItem['name']] = [
									'type'=>$aItem['type'],
									'id'=>$aItem['id']
								];
							}
							foreach ($aSettings[$aFields['id']] as $aItemDataFields) {
								$aRawResult = [];
								foreach ($aNameField as $name => $aItemField) {
									if (is_array($aItemDataFields[$aItemField['id']])) {
										$aRawResult[$name] = $aItemDataFields[$aItemField['id']]['value'] ??
											$aItemDataFields[$aItemField['id']]['url'] ?? "";
									} else {
										$valueFields = ($aItemField['type'] == 'switcher')
											? isset($aItemDataFields[$aItemField['id']]) && !empty($aItemDataFields[$aItemField['id']]) :
											$aItemDataFields[$aItemField['id']];
										$aRawResult[$name] = $valueFields;
									}
								}

								$aResult[] = $aRawResult;

							}
							$aDataFields[$aFields['name']] = $aResult;
						} else {
							$aDataFields[$aFields['name']] = $aSettings[$aFields['id']]['value'] ??
								$aSettings[$aFields['id']]['url'] ?? $aSettings[$aFields['id']];
						}
					} else {
						$valueFields = ($aFields['type'] == 'switcher') ? isset($aSettings[$aFields['id']]) && !empty
							($aSettings[$aFields['id']]) && ($aSettings[$aFields['id']] != 'no')
							: $aSettings[$aFields['id']];
						$aDataFields[$aFields['name']] = $valueFields;
					}
				}
			}
			//var_dump($aDataFields);die();
			$aItems[$aSection['name']] = $aDataFields;
		}

		return $aItems;
	}

}
