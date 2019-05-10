<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ElementorPro\Modules\QueryControl\Module as QueryControlModule;
use Elementor\Plugin;

class Elementor_ACF_Repeater_Widget extends ElementorPro\Modules\Library\Widgets\Template {
	public function get_name() {
		return 'acf-repeater-template';
	}

	public function get_title() {
		return __( 'ACF Repeater Template', 'elementor-pro' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_template',
			[
				'label' => __( 'Template', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'template_id',
			[
				'label'       => __( 'Choose Template', 'elementor-pro' ),
				'type'        => QueryControlModule::QUERY_CONTROL_ID,
				'filter_type' => 'library_widget_section_templates',
				'label_block' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$template_id = $this->get_settings( 'template_id' );

		if ( 'publish' !== get_post_status( $template_id ) ) {
			return;
		}

		$repeater_key = get_post_meta( $template_id, '_ear_field', true );

		while ( have_rows( $repeater_key, get_the_ID() ) ) {
			the_row();

			echo Plugin::instance()->frontend->get_builder_content_for_display( $template_id, true );
		}
	}

	public function render_plain_content() {}
}
