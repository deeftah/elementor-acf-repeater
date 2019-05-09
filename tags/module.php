<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module {
	/**
	 * Constructor.  Define hooks and filters.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );
	}

	/**
	 * Enqueue javascript for Elementor editor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_register_script( 'elementor-acf-repeater', plugin_dir_url( __DIR__ ) . '/assets/js/elementor-acf-repeater.js', [], '1.0.0', true );
		wp_enqueue_script( 'elementor-acf-repeater', plugin_dir_url( __DIR__ ) . '/assets/js/elementor-acf-repeater.js', [], '1.0.0', true );
	}

	public function register_ajax_actions() {
	}

	public function ajax_update( $params ) {
	}

	public static function get_tag_classes_names() {
	}

	public static function get_control_options( $types ) {
	}
}
