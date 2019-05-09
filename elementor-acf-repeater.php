<?php
/**
 * Plugin Name: Elementor ACF Repeater
 * Description: Allows ACF repeater field values to be used in Elementor via Dynamic Tags.
 * Version:     1.0.0
 * Author:      Justin Kucerak
 * Text Domain: elementor-acf-repeater
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Responsible for 
 */
class Elementor_ACF_Repeater {
	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '5.6';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Elementor_ACF_Repeater The single instance of the class.
	 */
	private static $_instance = null;
}
