<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once 'elementor-acf-repeater-image.php';

/**
 * Defines the ACF Repeater File dynamic tag.
 */
class Elementor_ACF_Repeater_File extends Elementor_ACF_Repeater_Image {
	/**
	 * ACF fields supported by this tag.
	 *
	 * @var array $supported_fields
	 */
	public static $supported_fields = [
		'file',
	];

	/**
	 * Get Name
	 *
	 * Returns the Name of the tag
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_name() {
		return 'elementor-acf-repeater-file';
	}

	/**
	 * Get Title
	 *
	 * Returns the title of the Tag
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'ACF Repeater File', 'elementor-pro' );
	}

	/**
	 * Register Controls
	 *
	 * Registers the Dynamic tag controls
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return void
	 */
	protected function _register_controls() {
		$this->add_control(
			'repeater_field',
			[
				'label'      => __( 'Repeater Field', 'elementor-pro' ),
				'type'       => \Elementor\Controls_Manager::SELECT,
				'groups'     => Module::get_control_options( self::$supported_fields ),
			]
		);
	}

	/**
	 * Render
	 *
	 * Returns the supported fields for the tag.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_supported_fields() {
		return self::$supported_fields;
	}
}
