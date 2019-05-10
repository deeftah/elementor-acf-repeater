<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Defines the plugin's metabox.
 */
class Elementor_ACF_Repeater_Metabox {
	/**
	 * Defines necessary action hooks.
	 */
	public static function init() {
		add_action( 'add_meta_boxes', [ 'Elementor_ACF_Repeater_Metabox', 'add' ] );
		add_action( 'save_post', [ 'Elementor_ACF_Repeater_Metabox', 'save' ] );
	}

	/**
	 * Adds the metabox to the proper screens.
	 *
	 * @return void
	 */
	public static function add() {
		$screens = [ 'elementor_library' ];
		foreach ( $screens as $screen ) {
			add_meta_box(
				'wporg_box_id',          // Unique ID
				'ACF Repeater Selection', // Box title
				[ self::class, 'html' ],   // Content callback, must be of type callable
				$screen                  // Post type
			);
		}
	}

	/**
	 * Saves the metabox data.
	 *
	 * @param int $post_id
	 * @return void
	 */
	public static function save( $post_id ) {
		if ( array_key_exists( 'ear_field', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_ear_field',
				$_POST['ear_field']
			);
		}
	}

	/**
	 * Output the metabox html.
	 *
	 * @param WP_Post $post
	 * @return void
	 */
	public static function html( $post ) {
		$value = get_post_meta( $post->ID, '_ear_field', true );

		$acf_groups = acf_get_field_groups();

		echo '<label for="ear_field">Select an ACF repeater field to use in this template.</label>' .
			'<select name="ear_field" id="ear_field" class="postbox">' .
			'<option value="">Select...</option>';

		foreach ( $acf_groups as $group ) {
			$fields = acf_get_fields( $group['key'] );

			foreach ( $fields as $field ) {
				if ( 'repeater' === $field['type'] ) {
					$selected = selected( $value, $field['key'] );
					echo "<option value='{$field['key']}' {$selected}>{$field['label']}</option>";
				}
			}
		}

		echo '</select>';
	}
}
