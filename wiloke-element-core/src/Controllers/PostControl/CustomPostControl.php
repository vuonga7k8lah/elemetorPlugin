<?php

namespace WilokeElementCore\Controllers\PostControl;
class CustomPostControl extends \Elementor\Base_Control
{
	public function get_type()
	{
		return 'wil-custom-post';
	}

	public function enqueue()
	{
		wp_register_script(WILOKE_WILOKEELEMENTCORE_NAMESPACE . '_custom_post_script',
			WILOKE_WILOKEELEMENTCORE_VIEWS_URL . 'src/Controllers/PostControl/CustomPostControl.js',
			['jquery'],
			WILOKE_WILOKEELEMENTCORE_VERSION,
			true);
		wp_enqueue_script(WILOKE_WILOKEELEMENTCORE_NAMESPACE . '_custom_post_script');
	}

	public function content_template()
	{

		?>
        <label for="elementor-control-default-c1218" class="elementor-control-title">Wil Post</label>
        <br>
        <div class="elementor-control-field">

            <label class="elementor-control-title">{{{ data.label }}}</label>

            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <select class="post-select" style="width:100%"></select>
            </div>
        </div>
        <br>
        <div class="elementor-control-field">

            <label for="elementor-control-default-c1218" class="elementor-control-title">Number Post</label>

            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <input id="elementor-control-default-c1218"
                       type="number"
                       name="postNumber"
                       value="20">
            </div>
        </div>
		<?php

	}
}