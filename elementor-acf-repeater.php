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

require_once __DIR__ . '/classes/acf-repeater-metabox.php';

/**
 * Responsible for setting up the plugin.
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

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_ACF_Repeater An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'elementor-test-extension' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 * Define additional WP hooks.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {
		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version.
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Initialize the plugin metabox.
		Elementor_ACF_Repeater_Metabox::init();

		// Register widget(s).
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

		// Modify Elementor Section behavrior.
		add_action( 'elementor/element/before_section_end', [ $this, 'modify_section_controls' ], 10, 3 );
		add_action( 'elementor/frontend/before_render', [ $this, 'modify_section_render' ], 10, 1 );

		// Add in new Dynamic Tags.
		add_action( 'elementor/dynamic_tags/register_tags', [ $this, 'register_tags' ] );

		// Setup method to populate widget dropdown control.
		add_filter( 'elementor_pro/query_control/get_autocomplete/library_widget_section_templates', [ $this, 'get_autocomplete_for_acf_repeater_widget' ], 10, 2 );
		add_filter( 'elementor_pro/query_control/get_value_titles/library_widget_section_templates', [ $this, 'get_value_title_for_acf_repeater_widget' ], 10, 2 );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {
		// include Widget files.
		require_once __DIR__ . '/widgets/elementor-acf-repeater-widget.php';

		// Register widget.
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_ACF_Repeater_Widget() );
	}

	/**
	 * Updates the html tag control with the 'a' tag and adds a url control
	 * that is visible only when the html tag is set to a.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @param Elementor\Element_Base $element The edited element.
	 * @param string                 $section_id Current section id.
	 * @param array                  $args The $args that sent to $element->start_controls_section.
	 *
	 * @return void
	 */
	public function modify_section_controls( $element, $section_id, $args ) {
		// Ensure we are modifying the correct section.
		if ( 'section_layout' === $section_id ) {
			// Store current html tag control.
			$html_tag_control = $element->get_controls( 'html_tag' );
			// Add a element to control's options array.
			$html_tag_control['options']['a'] = 'a';
			// Inject changes to the html tag control.
			$element->update_control(
				'html_tag',
				[
					'options' => $html_tag_control['options'],
				]
			);
			// Get position of the html tag control.
			$position = $element->get_control_index( 'html_tag' ) + 1;
			// Add a new url control right after the html tag control.
			$element->add_control(
				'section_link',
				[
					'label'     => __( 'Link', 'elementor' ),
					'type'      => 'url',
					'dynamic'   => [
						'active' => true,
					],
					'default'   => [
						'url' => '',
					],
					'condition' => [
						'html_tag' => 'a',
					],
				],
				[
					'position' => [
						'type' => 'control',
						'at'   => 'after',
						'of'   => 'html_tag',
					],
				]
			);
		}
	}

	/**
	 * Add render attributes to a section if link settings have been set.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @param Elementor\Element_Base $element The element instance.
	 * @return void
	 */
	public function modify_section_render( $element ) {
		// Make sure we are working with a section element.
		if ( 'section' !== $element->get_type() ) {
			return;
		}

		// Store the section settings.
		$settings = $element->get_settings_for_display();

		// Bail early if the html_tag is not an a or a link was never set.
		if ( 'a' !== $settings['html_tag'] || ! isset( $settings['section_link'] ) ) {
			return;
		}

		// Process only if a url is available.
		if ( ! empty( $settings['section_link']['url'] ) ) {
			// Add href render attribute with the url.
			$element->add_render_attribute( '_wrapper', 'href', $settings['section_link']['url'] );

			// Set target attribute.
			if ( $settings['section_link']['is_external'] ) {
				$element->add_render_attribute( '_wrapper', 'target', '_blank' );
			}

			// Set nofollow attribute.
			if ( ! empty( $settings['section_link']['nofollow'] ) ) {
				$element->add_render_attribute( '_wrapper', 'rel', 'nofollow' );
			}
		}
	}

	/**
	 * Registers new dynamic tags with Elementor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @param Elementor\Core\DynamicTags\Manager $dynamic_tags Manager instance.
	 * @return void
	 */
	public function register_tags( $dynamic_tags ) {
		// Register each tag class.
		foreach ( ACF_Repeater_Module::get_tag_classes_names() as $class ) {
			// Modify class name to match file name structure.
			$class_file = strtolower( str_replace( '_', '-', $class ) );
			// Include tag class file and register.
			include_once 'tags/' . $class_file . '.php';
			$dynamic_tags->register_tag( $class );
		}
	}

	/**
	 * Retrieve available Section templates for template widget.
	 * 
	 * @since 1.0.0
	 * 
	 * @access public
	 *
	 * @param array $results Empty array used to hold filtered query results.
	 * @param array $data Array of query data.
	 * @return array $results Autocomplete options to display.
	 */
	public function get_autocomplete_for_acf_repeater_widget( $results, $data ) {
		// Store all registered document types.
		$document_types = \Elementor\Plugin::instance()->documents->get_document_types(
			[
				'show_in_library' => true,
			]
		);

		// Setup WP_Query arguments to retrieve Elementor Section templates.
		$query_params = [
			's'              => $data['q'],
			'post_type'      => \Elementor\TemplateLibrary\Source_Local::CPT,
			'posts_per_page' => -1,
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => [
				[
					'key'     => \Elementor\Core\Base\Document::TYPE_META_KEY,
					'value'   => [ 'section' ],
					'compare' => 'IN',
				],
			],
		];

		// Store query result.
		$query = new \WP_Query( $query_params );

		// Iterate over the query results.
		foreach ( $query->posts as $post ) {
			// Get Elementor Document instance for the current post.
			$document = \Elementor\Plugin::instance()->documents->get( $post->ID );

			// Process if Document was actually found.
			if ( $document ) {
				// Append results.
				$results[] = [
					'id'   => $post->ID,
					'text' => $post->post_title . ' (' . $document->get_post_type_title() . ')',
				];
			}
		}

		return $results;
	}
}
