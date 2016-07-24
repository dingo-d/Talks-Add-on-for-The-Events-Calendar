<?php

/**
 * Events Calendar Talks Add-on
 *
 *
 * @since             1.0.0
 * @package           event_talks
 *
 * @wordpress-plugin
 * Plugin Name:       Events Calendar Talks Add-on
 * Plugin URI:        http://madebydenis.com/event-calendar-talks-add-on/
 * Description:       Events Calendar Talks Add-on adds the ability to create multiple talks in a single event created with The Events Calendar plugin
 * Version:           1.0.1
 * Author:            Denis Zoljom
 * Author URI:        http://madebydenis.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       event_talks
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_event_talks() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-event-talks-activator.php';
	event_talks_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_event_talks' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-event-talks.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_event_talks() {

	$plugin = new event_talks();
	$plugin->run();

}
run_event_talks();
