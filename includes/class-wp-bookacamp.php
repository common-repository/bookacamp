<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://bookacamp.de/
 * @since      1.0.0
 *
 * @package    Wp_Bookacamp
 * @subpackage Wp_Bookacamp/includes
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
 * @package    Wp_Bookacamp
 * @subpackage Wp_Bookacamp/includes
 * @author     Mathias Methner, Bookacamp <support@bookacamp.de>
 */
class Wp_Bookacamp {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Bookacamp_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
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
		if ( defined( 'WP_BOOKACAMP_VERSION' ) ) {
			$this->version = WP_BOOKACAMP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-bookacamp';

		$this->load_dependencies();
		//$this->set_locale();
		$this->register_settings();
		$this->register_blocks();
		$this->define_admin_hooks();
		//$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Bookacamp_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Bookacamp_i18n. Defines internationalization functionality.
	 * - Wp_Bookacamp_Admin. Defines all hooks for the admin area.
	 * - Wp_Bookacamp_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-bookacamp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-bookacamp-i18n.php';

		/**
		 * The class responsible for defining all blocks and shortcodes that are useable for the authors.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-bookacamp-blocks.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-bookacamp-admin.php';

		/**
		 * The class responsible for defining all settings that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-bookacamp-admin-settings.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-bookacamp-public.php';

		$this->loader = new Wp_Bookacamp_Loader();

	}

	/**
	 * Register all settings for the plugin
	 *
	 * Uses the Wp_Bookacamp_Admin_Settings class in order to register the necessary section, settings and html code
	 *
	 * @since    1.0.0
	 * @access    private
	 */
	private function register_settings() {
		$plugin_settings = new Wp_Bookacamp_Admin_Settings( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_init', $plugin_settings, 'register' );
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register the block types and shortcodes for the plugin
	 *
	 * Uses the Wp_Bookacamp_RegisterBlocks class in order to register all necessary block types and shortcodes in WordPress.
	 *
	 * @since    1.0.0
	 * @access    private
	 */
	private function register_blocks() {

		$plugin_blocks = new Wp_Bookacamp_Blocktypes();

		$this->loader->add_action( 'init', $plugin_blocks, 'register_blocks' );
		$this->loader->add_action( 'init', $plugin_blocks, 'register_shortcodes' );
		$this->loader->add_action( 'wp_ajax_get_bookacamp_iframe', $plugin_blocks, 'ajax_get_bookacamp_iframe' );
		$this->loader->add_action( 'wp_ajax_get_bookacamp_filters', $plugin_blocks, 'ajax_get_bookacamp_filters' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Bookacamp_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu_add' );

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
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Wp_Bookacamp_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Bookacamp_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Bookacamp_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Bookacamp_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

}
