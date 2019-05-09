<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Defines the ACF Repeater URL dynamic tag.
 */
class Elementor_ACF_Repeater_URL extends ElementorPro\Modules\DynamicTags\ACF\Tags\ACF_URL {
	/**
	 * ACF fields supported by this tag.
	 *
	 * @var array $supported_fields
	 */
	public static $supported_fields = [
		'text',
		'email',
		'image',
		'file',
		'page_link',
		'post_object',
		'relationship',
		'taxonomy',
		'url',
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
		return 'acf-repeater-url';
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
		return __( 'ACF Repeater URL', 'elementor-pro' );
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
				'groups' => ACF_Repeater_Module::get_control_options( $this->get_supported_fields() ),
			]
		);

		$this->add_control(
			'fallback',
			[
				'label' => __( 'Fallback', 'elementor-pro' ),
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
	 * @return string
	 */
	public function get_value( array $options = [] ) {
		// Get ACF repeater sub field key.
		$field_key = $this->get_settings( 'repeater_field' );

		// Bail early if no key available.
		if ( ! $field_key ) {
			return;
		}

		// Attempt to get the sub field value from the loop.
		$sub_field_value = get_sub_field( $field_key, false );

		// Get the field value another way if we are not in the loop.
		if ( ! $sub_field_value ) {
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

		// Bail if no value found.
		if ( ! $sub_field_value ) {
			return '';
		}

		// Store instance of sub field object; and it's type.
		$sub_field_object = get_field_object( $field_key );
		$type             = $sub_field_object['type'];

		// Modify the sub field value based on the type of data stored.
		switch ( $type ) {
			case 'email':
				if ( $sub_field_value ) {
					$sub_field_value = 'mailto:' . $sub_field_value;
				}
				break;
			case 'image':
			case 'file':
				switch ( $sub_field_object['save_format'] ) {
					case 'object':
						$sub_field_value = $sub_field_value['url'];
						break;
					case 'id':
						if ( 'image' === $sub_field_object['type'] ) {
							$src             = wp_get_attachment_image_src( $sub_field_value, 'full' );
							$sub_field_value = $src[0];
						} else {
							$sub_field_value = wp_get_attachment_url( $sub_field_value );
						}
						break;
				}
				break;
			case 'post_object':
			case 'relationship':
				$sub_field_value = get_permalink( $sub_field_value );
				break;
			case 'taxonomy':
				$sub_field_value = get_term_link( $sub_field_value, $sub_field_object['taxonomy'] );
				break;
		}

		// Return the fallback value if no field value.
		if ( empty( $sub_field_value ) && $this->get_settings( 'fallback' ) ) {
			$sub_field_value = $this->get_settings( 'fallback' );
		}

		return wp_kses_post( $sub_field_value );
	}

	/**
	 * Render
	 *
	 * Prints out the value of the Dynamic tag
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array
	 */
	protected function get_supported_fields() {
		return self::$supported_fields;
	}
}
