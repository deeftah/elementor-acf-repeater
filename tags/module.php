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

	public function enqueue_scripts() {
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
