<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    event_talks
 * @subpackage event_talks/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    event_talks
 * @subpackage event_talks/public
 * @author     Denis Zoljom <denis.zoljom@gmail.com>
 */
class event_talks_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $event_talks    The ID of this plugin.
	 */
	private $event_talks;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $event_talks       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $event_talks, $version ) {

		$this->event_talks = $event_talks;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->event_talks, plugin_dir_url( __FILE__ ) . 'assets/css/event-talks-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->event_talks, plugin_dir_url( __FILE__ ) . 'assets/js/event-talks-public.js', array( 'jquery' ), $this->version, false );

	}

}
