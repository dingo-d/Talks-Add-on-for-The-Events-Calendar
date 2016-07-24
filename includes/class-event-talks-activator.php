<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    event_talks
 * @subpackage event_talks/includes
 * @author     Denis Zoljom <denis.zoljom@gmail.com>
 */
class event_talks_Activator {

	/**
	 * Plugin Activation Dependency Check
	 *
	 * Check if The Events Calendar plugin is active
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {

			include_once( ABSPATH . '/wp-admin/includes/plugin.php' );

		}

		if( current_user_can( 'activate_plugins' ) && ! function_exists( 'tribe_get_events' ) ){

			// Deactivate the plugin
            deactivate_plugins( plugin_basename( __FILE__ ) );

            // Throw an error in the WordPress admin console
        	$error_message = esc_html__('This plugin requires ', 'event-talks') . '<a href="'. esc_url('https://wordpress.org/plugins/the-events-calendar/') .'">The Events Calendar</a>' . esc_html__(' plugin to be active!', 'event-talks');
			die( $error_message );




		}

	}

}
