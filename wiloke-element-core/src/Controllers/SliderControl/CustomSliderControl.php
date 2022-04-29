<?php

namespace WilokeElementCore\Controllers\SliderControl;


use Elementor\Base_Data_Control;
use Elementor\Control_Base_Units;
use Elementor\Modules\DynamicTags\Module as TagsModule;

class CustomSliderControl extends Base_Data_Control
{

	public function get_type()
	{
		return 'wil-slider';
	}

	/**
	 * Get slider control default settings.
	 *
	 * Retrieve the default settings of the slider control. Used to return the
	 * default settings while initializing the slider control.
	 *
	 * @return array Control default settings.
	 * @since 1.0.0
	 * @access protected
	 *
	 */
	protected function get_default_settings()
	{

		return array_merge(
			parent::get_default_settings(), [
				'label_block' => true,
				'labels'      => [],
				'scales'      => 0,
				'isMultiple'  => false,
				'handles'     => 'default',
				'dynamic'     => [
					'categories' => [TagsModule::NUMBER_CATEGORY],
					'property'   => 'size',
				],
			]
		);
	}

	/**
	 * Render slider control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template()
	{

		?>
        <div class="elementor-control-field">
            <label for="<?php $this->print_control_uid(); ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper elementor-control-dynamic-switcher-wrapper elementor-clearfix elementor-control-tag-area">
                <input class="noUi-handle noUi-handle-lower" id="<?php $this->print_control_uid(); ?>" type="range"
                       min="{{ data.min }}"
                       max="{{ data.max }}" step="{{ data.step }}" data-setting="size"/>
                <div class="elementor-slider-input">
                    <input id="<?php $this->print_control_uid(); ?>" type="text" value="200"
                           data-setting="size">
                </div>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
		<?php
	}
}