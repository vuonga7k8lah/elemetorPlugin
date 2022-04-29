<?php

namespace WilokeElementCore\Controllers;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use WilokeElementCore\Share\App;
use WilokeElementCore\Share\TraitHandleAutoRenderSettingControls;

class TestAddon extends Widget_Base
{
	use TraitHandleAutoRenderSettingControls;

	public function get_name()
	{
		return "abc";
	}

	public function get_title()
	{
		return "abc";
	}

	public function get_icon()
	{
		return App::get('dataConfig')['icon'];
	}

	public function get_style_depends()
	{
		return App::get('handleCss');
	}

	public function get_categories()
	{
		return ['wil-ok-category'];
	}

	protected function register_controls()
	{
		$this->start_controls_section(
			'text_content_section2323',
			[
				'label' => esc_html__('Team Content', 'meet-the-team'),
				'tab'   => Controls_Manager::TAB_CONTENT,
				"id"    => "general_1",
				"type"  => "section",
				"name"  => "general1",
				"default" => [],
			]
		);
		$this->add_control(
			'232323sdsd', [
				'label'     => esc_html__('FONT TEST', 'meet-the-team'),
				'type'      => Controls_Manager::FONT,
				'default'   => "'Open Sans', sans-serif",
				'name'      => "ccssscccc",
				'selectors' => [
					'{{WRAPPER}} .title555555' => 'font-family: {{VALUE}}',
				]
			]
		);
		$this->end_controls_section();
	}

	protected function render()
	{
		?>
        <h3>hello</h3>
		<?php
	}
}