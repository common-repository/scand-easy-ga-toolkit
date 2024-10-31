<?php
/*
Plugin name: Scand Easy Google Analytics Toolkit
Plugin URI: https://wordpress.org/plugins/scand-easy-ga-toolkit
Description: Scand Easy Google Analytics Toolkit makes it super easy to integrate Google Analytics tracking code in your web-site
Text Domain: scand-easy-ga-toolkit
Domain Path: /languages
Version: 1.0.0
Author: SCAND Ltd.
Author email: info@scand.com
Author URI: http://scand.com
License: GPLv2 or later
*/

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'SCAND_EASY_GA_TOOLKIT_NAME', 'Easy Google Analytics Toolkit' );
define( 'SCAND_EASY_GA_TOOLKIT_VERSION', '1.0.0' );
define( 'SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN', 'scand-easy-ga-toolkit' );
define( 'SCAND_EASY_GA_TOOLKIT_OPTIONS', 'scand_easy_ga_toolkit_options' );
define( 'SCAND_EASY_GA_TOOLKIT_INPUT', 'easy_ga' );

define( 'SCAND_EASY_GA_TOOLKIT_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'SCAND_EASY_GA_TOOLKIT_INCLUDE_DIR_PATH', SCAND_EASY_GA_TOOLKIT_DIR_PATH . 'includes/' );
define( 'SCAND_EASY_GA_TOOLKIT_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'SCAND_EASY_GA_TOOLKIT_INCLUDE_DIR_URL', SCAND_EASY_GA_TOOLKIT_DIR_URL . 'includes/' );
define( 'SCAND_EASY_GA_TOOLKIT_NONCE', 'created_by_scand' );

/**
 * Main class Scand_Easy_GA_Toolkit
 */
class Scand_Easy_GA_Toolkit
{
	protected $page;

	/**
	 * Scand_Easy_GA_Toolkit constructor.
	 */
	public function __construct()
	{
		if ( is_admin() ) {
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_menu', array( $this, 'set_admin_menu' ), 4 );
			add_action( 'wp_ajax_easy_ga_load_preview', array( $this, 'load_preview' ) );
		}
		// Add GA code
		add_action( 'wp_footer', array( $this, 'print_ga_script' ), 3 );
	}

	/**
	 * Added translation files to plugoins UI
	 */
	public function load_textdomain()
	{
		load_plugin_textdomain(
			SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN,
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);

	}

	/**
	 * Add css files to plugins admin page
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style(
			SCAND_EASY_GA_TOOLKIT_NAME,
			SCAND_EASY_GA_TOOLKIT_INCLUDE_DIR_URL . 'css/scand-easy-ga-toolkit-admin.css',
			array(),
			SCAND_EASY_GA_TOOLKIT_VERSION,
			'all'
		);
	}

	/**
	 * Add js scripts to plugins admin page
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script(
			SCAND_EASY_GA_TOOLKIT_NAME,
			SCAND_EASY_GA_TOOLKIT_INCLUDE_DIR_URL . 'js/scand-easy-ga-toolkit-admin.js',
			array( 'jquery' ),
			SCAND_EASY_GA_TOOLKIT_VERSION,
			false
		);
		$option = get_option( SCAND_EASY_GA_TOOLKIT_OPTIONS );
		wp_localize_script(
			SCAND_EASY_GA_TOOLKIT_NAME,
			'scand_js_obj',
			array(
				'ajax_url'                       => admin_url( 'admin-ajax.php' ),
				'nonce'                          => wp_create_nonce( 'ajax_check' ),
				'tracking'                       => $option['tracking'],
				'check_category_field_label'     => __( 'Check category field in your custom events', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'show_desc_btn_caption'          => __( 'Show description', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'hide_desc_btn_caption'          => __( 'Hide description', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'show_example_btn_caption'       => __( 'Show example', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'hide_example_btn_caption'       => __( 'Hide example', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'javascript_items_label'         => __( 'JavaScript items', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'field_type_caption'             => __( 'Field type', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'string_caption'                 => __( 'string', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'integer_caption'                => __( 'Integer', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'variable_caption'               => __( 'js variable or expression', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'area_for_js_caption'            => __( 'Area for javascript', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'ga_items_label'                 => __( 'GA items', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'hit_type_label'                 => __( 'Hit type', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'event_type_label'               => __( 'Bound event', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'prevent_default_caption'        => __( 'Prevent default', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'css_selector_label'             => __( 'CSS selector', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'event_category_label'           => __( 'Category', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'event_action_label'             => __( 'Action', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'event_label_label'              => __( 'Label', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'event_value_label'              => __( 'Value', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'social_interaction_caption'     => __( 'NI', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'remove_button_caption'          => __( 'Remove', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'yes_caption'                    => __( 'Yes', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'no_caption'                     => __( 'No', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'custom_event_title'             => __( 'Event: {{EVENT}} for {{SELECTOR}}', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'custom_event_title_not_defined' => __( 'Event: not defined', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
				'preview_template_key'           => Scand_Easy_GA_Toolkit_Admin::TEMPLATE_KEY,
				'input_name'                     => SCAND_EASY_GA_TOOLKIT_INPUT,
			)
		);
	}

	/**
	 * Creates admin menu
	 */
	public function set_admin_menu()
	{
		$this->page = add_menu_page(
			__( SCAND_EASY_GA_TOOLKIT_NAME, SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
			__( 'Easy GA Toolkit', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
			'manage_options',
			'scand-easy-ga-toolkit-menu',
			array( $this, 'easy_ga_toolkit_options_page' ),
			'dashicons-chart-line'
		);
		add_action( 'admin_footer-' . $this->page, array( $this, 'footer_scripts' ) );
		wp_enqueue_script( 'postbox' );
	}

	/**
	 * Prints the jQuery script to initiliase the metaboxes
	 * Called on admin_footer-*
	 */
	public function footer_scripts()
	{
		?>
		<script> postboxes.add_postbox_toggles(pagenow);</script>
		<?php
	}

	/**
	 * Create options page in admin section
	 */
	public function default_setup()
	{
		add_options_page(
			__( SCAND_EASY_GA_TOOLKIT_NAME, SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
			__( SCAND_EASY_GA_TOOLKIT_NAME, SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ),
			'manage_options',
			'scand_page_settings',
			array( $this, 'scand_page_settings' )
		);
	}

	/**
	 * Render Js code snippet in page's footer
	 */
	public function print_ga_script()
	{
		$obj = new Scand_Easy_GA_Toolkit_Admin();

		print $obj->drawJSSnippet();
	}

	/**
	 * Admin section setting page. Custom front controller
	 */
	public function easy_ga_toolkit_options_page()
	{
		$objAdmin = new Scand_Easy_GA_Toolkit_Admin();
		$objAdmin->showForm();
	}

	public function load_preview()
	{
		if ( check_ajax_referer( 'ajax_check' ) && isset( $_POST['form_data'][ SCAND_EASY_GA_TOOLKIT_INPUT ] ) ) {
			$form_data = $_POST['form_data'][ SCAND_EASY_GA_TOOLKIT_INPUT ];
			$mode = $form_data['mode'];
			$tracking = $form_data['tracking'];
			if ( $tracking == 1 ) {
				$oStrategy = new Scand_Easy_GA_Toolkit_Gtag();
			} else {
				$oStrategy = new Scand_Easy_GA_Toolkit_Analytics();
			}

			$events = is_array( $form_data['custom_event'] ) ? $form_data['custom_event'] : array();
			$previewData = Scand_Easy_GA_Toolkit_Admin::getDefaultPreviewData();
			$events[ Scand_Easy_GA_Toolkit_Admin::TEMPLATE_KEY ] = $previewData[ Scand_Easy_GA_Toolkit_Admin::TEMPLATE_KEY ];

			$preview = $oStrategy->buildFunctionArray( $events, $mode );
			wp_send_json_success( array( 'status' => 'OK', 'preview' => $preview ) );
		}
		wp_die();
	}
}

require_once SCAND_EASY_GA_TOOLKIT_INCLUDE_DIR_PATH . 'class-scand-easy-ga-toolkit-autoloader.php';
Scand_Easy_GA_Toolkit_Autoloader::register();

new Scand_Easy_GA_Toolkit();