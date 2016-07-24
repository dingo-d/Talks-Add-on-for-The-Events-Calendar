<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    event_talks
 * @subpackage event_talks/includes
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
 * @package    event_talks
 * @subpackage event_talks/includes
 * @author     Denis Zoljom <denis.zoljom@gmail.com>
 */
class event_talks {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      event_talks_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $event_talks    The string used to uniquely identify this plugin.
	 */
	protected $event_talks;

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

		$this->event_talks = 'event-talks';
		$this->version = '1.0.1';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->event_talks_shortcode_tinymce_button();
		$this->run_shortcode();
		$this->run_widget();


	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - event_talks_Loader. Orchestrates the hooks of the plugin.
	 * - event_talks_i18n. Defines internationalization functionality.
	 * - event_talks_Admin. Defines all hooks for the admin area.
	 * - event_talks_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-talks-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-talks-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-event-talks-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-event-talks-public.php';

		/**
		 * The class responsible for rendering the public facing
		 * side of the site (shortcodes and widgets).
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/event-talks-public-display.php';

		$this->loader = new event_talks_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the event_talks_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new event_talks_i18n();

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

		$plugin_admin = new event_talks_Admin( $this->get_event_talks(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'event_talks_add_meta_box' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'event_talks_metabox_save' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new event_talks_Public( $this->get_event_talks(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Function that registers event talk shortcode button in TinyMCE editor
	 *
	 * @since    1.0.0
	 */
	public function event_talks_shortcode_tinymce_button() {

		$plugin_admin = new event_talks_Admin( $this->get_event_talks(), $this->get_version() );

		$this->loader->add_filter( 'mce_external_plugins', $plugin_admin, 'event_talks_add_buttons' );
		$this->loader->add_filter( 'mce_buttons', $plugin_admin, 'event_talks_register_buttons' );
		$this->loader->add_action( 'after_wp_tiny_mce', $plugin_admin, 'event_talks_tinymce_translation_object' );
		$this->loader->add_action( 'after_wp_tiny_mce', $plugin_admin, 'event_talks_tinymce_post_id_object' );

	}

	/**
	 * Including shortcodes
	 *
	 * @since    1.0.0
	 */
	private function run_shortcode() {

		$event_talks_event_talks_shortcode = new event_talks_Shortcode( $this->get_event_talks(), $this->get_version() );

        add_shortcode('event_talks', array($event_talks_event_talks_shortcode, 'event_talks_event_talks_shortcode'));

	}

	/**
	 * Including widget
	 *
	 * @since    1.0.0
	 */
	private function run_widget() {

		$event_talks_widget = new event_talks_Widget( $this->get_event_talks(), $this->get_version() );

		$this->loader->add_action( 'widgets_init', $event_talks_widget, 'event_talks_register_widget' );

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
	public function get_event_talks() {
		return $this->event_talks;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    event_talks_Loader    Orchestrates the hooks of the plugin.
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
