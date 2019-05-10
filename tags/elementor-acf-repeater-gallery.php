<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once 'module.php';

/**
 * Defines the ACF Repeater Gallery dynamic tag.
 */
class Elementor_ACF_Repeater_Gallery extends ElementorPro\Modules\DynamicTags\ACF\Tags\ACF_Gallery {
	/**
	 * ACF fields supported by this tag.
	 *
	 * @var array $supported_fields
	 */
	public static $supported_fields = [
		'gallery',
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
		return 'elementor-acf-repeater-gallery';
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
		return __( 'ACF Repeater Gallery', 'elementor-pro' );
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
				'label'  => __( 'Key', 'elementor-pro' ),
				'type'   => \Elementor\Controls_Manager::SELECT,
				'groups' => Module::get_control_options( $this->get_supported_fields() ),
			]
		);
	}

	/**
	 * Render
	 *
	 * Returns the value of the Dynamic tag
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $options
	 * @return array
	 */
	public function get_value( $options = [] ) {
		$field_key = $this->get_settings( 'repeater_field' );
		$images    = [];

		if ( ! $field_key ) {
			return $images;
		}

		$sub_field_value = get_sub_field( $field_key, false );

		if ( ! $sub_field_value ) {
			$post_id       = ( isset( $_REQUEST['post'] ) ) ? $_REQUEST['post'] : $_REQUEST['editor_post_id'];
			$repeater_key  = get_post_meta( $post_id, '_ear_field', true );
			$repeater_rows = get_field( $repeater_key, get_the_ID(), false );

			foreach ( $repeater_rows as $row ) {
				if ( isset( $row[ $field_key ] ) ) {
					$sub_field_value = $row[ $field_key ];
					break;
				}
			}
		}

		if ( ! $sub_field_value ) {
			return $images;
		}

		if ( is_array( $sub_field_value ) && ! empty( $sub_field_value ) ) {
			foreach ( $sub_field_value as $image_id ) {
				$images[] = [
					'id' => $image_id,
				];
			}
		}

		return $images;
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
