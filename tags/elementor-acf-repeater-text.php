<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once 'module.php';

/**
 * Defines the ACF Repeater Text dynamic tag.
 */
class Elementor_ACF_Repeater_Text extends ElementorPro\Modules\DynamicTags\ACF\Tags\ACF_Text {
	/**
	 * ACF fields supported by this tag.
	 *
	 * @var array $supported_fields
	 */
	public static $supported_fields = [
		'text',
		'textarea',
		'number',
		'email',
		'password',
		'wysiwyg',
		'select',
		'checkbox',
		'radio',
		'true_false',

		// Pro.
		'oembed',
		'google_map',
		'date_picker',
		'time_picker',
		'date_time_picker',
		'color_picker',
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
		return 'elementor-acf-repeater-text';
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
		return __( 'ACF Repeater Text', 'elementor-pro' );
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
				'data-class' => __CLASS__,
			]
		);
	}

	/**
	 * Render
	 *
	 * Prints out the value of the Dynamic tag
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function render() {
		// Get ACF repeater sub field key.
		$field_key = $this->get_settings( 'repeater_field' );

		// Bail early if no key available.
		if ( ! $field_key ) {
			return;
		}

		// Attempt to get the sub field value from the loop.
		$sub_field = get_sub_field( $field_key );

		// Get the field value another way if we are not in the loop.
		if ( ! $sub_field ) {
			// Store the repeater field rows.
			$post_id       = ( isset( $_REQUEST['post'] ) ) ? $_REQUEST['post'] : $_REQUEST['editor_post_id'];
			$repeater_key  = get_post_meta( $post_id, '_ear_field', true );
			$repeater_rows = get_field( $repeater_key, get_the_ID(), false );

			// Iterate over all the repeater field rows.
			foreach ( $repeater_rows as $row ) {
				// Set the sub field value if it is found.
				if ( isset( $row[ $field_key ] ) ) {
					$sub_field = $row[ $field_key ];
					break;
				}
			}
		}
		// Display the sub field value.
		echo wp_kses_post( $sub_field );
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
