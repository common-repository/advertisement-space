<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       chandan
 * @since      1.0.0
 *
 * @package    Advertisement_Space
 * @subpackage Advertisement_Space/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Advertisement_Space
 * @subpackage Advertisement_Space/includes
 * @author     Chandan <chandanaug13@gmail.com>
 */
class Advertisement_Space {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Advertisement_Space_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'ADVERTISEMENT_SPACE_VERSION' ) ) {
			$this->version = ADVERTISEMENT_SPACE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'advertisement-space';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Advertisement_Space_Loader. Orchestrates the hooks of the plugin.
	 * - Advertisement_Space_i18n. Defines internationalization functionality.
	 * - Advertisement_Space_Admin. Defines all hooks for the admin area.
	 * - Advertisement_Space_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advertisement-space-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advertisement-space-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-advertisement-space-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-advertisement-space-public.php';

		$this->loader = new Advertisement_Space_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Advertisement_Space_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Advertisement_Space_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Advertisement_Space_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'advertisement_space' );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_boxes' );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'admin_footer' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_post' );
		$this->loader->add_filter( 'manage_advertisement_posts_columns', $plugin_admin, 'advertisement_filter_posts_columns' );
		$this->loader->add_action( 'manage_advertisement_posts_custom_column', $plugin_admin, 'advertisement_column',10,2 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_advertisement_form_meta_box' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'advertisment_add_meta_box' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'advertisment_save' );


		// add_action('add_meta_boxes', 'add_contact_form_meta_box');


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Advertisement_Space_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_shortcode('advt',$plugin_public,'showAdvt');
		$this->loader->add_shortcode('advt_popup',$plugin_public,'advertisement_space_popup');
		//$this->loader->add_action( 'wp_footer', $plugin_public, 'advertisement_space_popup' );

		$this->loader->add_action( 'wp_ajax_set_advertisement_space', $plugin_public, 'set_advertisement_space_cookie' );
		$this->loader->add_action( 'wp_ajax_nopriv_set_advertisement_space', $plugin_public, 'set_advertisement_space_cookie' );
		$this->loader->add_action( 'wp_ajax_set_advertisement_click', $plugin_public, 'set_advertisement_space_click' );
		$this->loader->add_action( 'wp_ajax_nopriv_set_advertisement_click', $plugin_public, 'set_advertisement_space_click' );


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Advertisement_Space_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
