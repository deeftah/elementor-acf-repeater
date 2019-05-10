<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once 'module.php';

/**
 * Defines the ACF Repeater Image dynamic tag.
 */
class Elementor_ACF_Repeater_Image extends ElementorPro\Modules\DynamicTags\ACF\Tags\ACF_Image {
	/**
	 * ACF fields supported by this tag.
	 *
	 * @var array $supported_fields
	 */
	public static $supported_fields = [
		'image',
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
		return 'elementor-acf-repeater-image';
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
		return __( 'ACF Repeater Image', 'elementor-pro' );
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
	 * Prints out the value of the Dynamic tag
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $options
	 * @return array
	 */
	public function get_value( $options = [] ) {
		$field_key  = $this->get_settings( 'repeater_field' );
		$image_data = [
			'id'  => null,
			'url' => '',
		];

		if ( ! $field_key ) {
			return $image_data;
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
			return $image_data;
		}

		$sub_field_object = get_field_object( $field_key );
		$format           = $sub_field_object['return_format'];
		$domain           = get_site_url();

		switch ( $format ) {
			case 'object':
			case 'array':
			case 'id':
				$image_url    = wp_get_attachment_image_src( $sub_field_value );
				$relative_url = str_replace( $domain, '', $image_url[0] );

				$image_data['id']  = $sub_field_value;
				$image_data['url'] = $relative_url;
				break;
			case 'url':
				$relative_url = str_replace( $domain, '', $sub_field_value );

				$image_data['id']  = 0;
				$image_data['url'] = $relative_url;
				break;
		}

		return $image_data;
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
