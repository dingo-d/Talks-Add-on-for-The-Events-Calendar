<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    event_talks
 * @subpackage event_talks/admin
 * @author     Denis Zoljom <denis.zoljom@gmail.com>
 */
class event_talks_Admin {

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
	 * @var      string    $version		The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $event_talks		The name of this plugin.
	 * @param    string    $version		The version of this plugin.
	 */
	public function __construct( $event_talks, $version ) {

		$this->event_talks = $event_talks;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->event_talks, plugin_dir_url( __FILE__ ) . 'css/event-talks-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->event_talks, plugin_dir_url( __FILE__ ) . 'js/event-talks-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Function that adds event talk TinyMCE button - adds a new key to the existing $plugin_array array
	 *
	 * @since    1.0.0
	 * @param    array    $plugin_array		Existing array of TinyMCE plugins
	 */
	public function event_talks_add_buttons( $plugin_array ) {

		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
            return;
        }

        if ( get_user_option( 'rich_editing' ) !== 'true' ) {
            return;
        }

		$plugin_array['event_talks'] = plugin_dir_url( __FILE__ ) . '/js/event-talks-tinymce.js';
        return $plugin_array;

	}

	/**
	 * Function that registers event talk TinyMCE button - pushes it to the end of $buttons array
	 *
	 * @since    1.0.0
	 * @param    array    $buttons		Existing array of TinyMCE buttons
	 */
	public function event_talks_register_buttons( $buttons ) {

		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
            return;
        }

        if ( get_user_option( 'rich_editing' ) !== 'true' ) {
            return;
        }

		array_push( $buttons, 'event_talks' );
        return $buttons;

	}

	/**
	 * Function that adds object with translations to the TinyMCE
	 *
	 * @since    1.0.0
	 */
	public function event_talks_tinymce_translation_object() {
		?>
		<script type="text/javascript">
			var event_talks_tinyMCE_object = <?php echo json_encode(
					array(
						'event_talks_label' => esc_html__('Event Talks', 'event-talks'),
						'post_id_label'    => esc_html__('Post ID', 'event-talks'),
						'post_class_label' => esc_html__('Class', 'event-talks'),
					)
				);
		?>;
		</script>
		<?php
	}

	/**
	 * Function that adds object with Event posts - id's and post names to use in a dropdown
	 *
	 * @since    1.0.0
	 */
	public function event_talks_tinymce_post_id_object() {

		$event_args = array(
			'post_type'      => 'tribe_events',
			'posts_per_page' => 999,
			'post_status'    => 'publish'
		);

		$events_query = get_posts( $event_args );

		$array_pid = array();
		$i = 0;

		foreach ( $events_query as $ev_post ) {
			setup_postdata( $ev_post );
				$array_pid[$i]['text']  = get_the_title( $ev_post->ID );
				$array_pid[$i]['value'] = $ev_post->ID;
				$i++;
		}
		wp_reset_postdata();

		?>
		<script type="text/javascript">
			var event_talks_tinyMCE_pid_object = <?php echo json_encode($array_pid); ?>;
		</script>
		<?php
	}

	/**
	 * Callback function that adds metabox to the Events page
	 *
	 * @since    1.0.0
	 */
	public function event_talks_add_meta_box() {

		add_meta_box(
			'event_talks_meta_box',
			esc_html__('Event Talks', 'event-talks'),
			array( $this, 'render_event_talks_meta_box' ),
			'tribe_events',
			'advanced',
			'high'
		);

	}

	/**
	 * Render metabox function
	 *
	 * @since    1.0.0
	 */
	public function render_event_talks_meta_box() {

		require_once plugin_dir_path( __FILE__ ) . 'partials/event-talks-metabox.php';

	}

	/**
	 * Metabox save function
	 *
	 * @since    1.0.0
	 */
	public function event_talks_metabox_save() {

		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
			return;
		}

		if ( !current_user_can('edit_post', get_the_ID()) ){
			return;
		}

		if ( !isset( $_POST['talk_fields_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['talk_fields_meta_box_nonce'], 'talk_field_nonce' ) ){
			return;
		}

		$date_no = ( isset( $_POST['day_tab'] ) && $_POST['day_tab'] != '' ) ? count( $_POST['day_tab'] ) : 0 ;

		if ($date_no > 0) {

			$old_events = get_post_meta(get_the_ID(), 'event_details', true);

			$new_events = array();

			for($i = 1; $i <= $date_no; $i++ ){

				$talk_day_no = $_POST['day_'.$i.'_talk_no'];

				$new_events[$i]['day_tab'] = sanitize_text_field( wp_unslash( $_POST['day_tab'][$i-1] ) );

				for($j = 1; $j <= $talk_day_no; $j++){

					$talk_image          = $_POST['talk_image_'.$i]; //Array 0 -> 1st talk 1-> 2nd talk... $i = day
					$talk_title          = $_POST['talk_title_'.$i];
					$talk_description    = $_POST['talk_description_'.$i];
					$talk_place          = $_POST['talk_place_'.$i];
					$talk_speaker        = $_POST['talk_speaker_'.$i];
					$talk_start_hour     = $_POST['talk_start_hour_'.$i];
					$talk_start_minute   = $_POST['talk_start_minute_'.$i];
					$talk_start_meridian = $_POST['talk_start_meridian_'.$i];
					$talk_end_hour       = $_POST['talk_end_hour_'.$i];
					$talk_end_minute     = $_POST['talk_end_minute_'.$i];
					$talk_end_meridian   = $_POST['talk_end_meridian_'.$i];

					if ( $talk_image != '') {
						$new_events[$i][$j]['talk_image'] = sanitize_text_field( wp_unslash( $talk_image[$j-1] ) );
					}

					if ( $talk_title != '') {
						$new_events[$i][$j]['talk_title'] = sanitize_text_field( wp_unslash( $talk_title[$j-1] ) );
					}

					if ( $talk_description != '') {
						$new_events[$i][$j]['talk_description'] = sanitize_text_field( wp_unslash( $talk_description[$j-1] ) );
					}

					if ( $talk_start_hour != '') {
						$new_events[$i][$j]['talk_start_hour'] = sanitize_text_field( wp_unslash( $talk_start_hour[$j-1] ) );
					}

					if ( $talk_start_minute != '') {
						$new_events[$i][$j]['talk_start_minute'] = sanitize_text_field( wp_unslash( $talk_start_minute[$j-1] ) );
					}

					if ( $talk_start_meridian != '') {
						$new_events[$i][$j]['talk_start_meridian'] = sanitize_text_field( wp_unslash( $talk_start_meridian[$j-1] ) );
					}

					if ( $talk_end_hour != '') {
						$new_events[$i][$j]['talk_end_hour'] = sanitize_text_field( wp_unslash( $talk_end_hour[$j-1] ) );
					}

					if ( $talk_end_minute != '') {
						$new_events[$i][$j]['talk_end_minute'] = sanitize_text_field( wp_unslash( $talk_end_minute[$j-1] ) );
					}

					if ( $talk_end_meridian != '') {
						$new_events[$i][$j]['talk_end_meridian'] = sanitize_text_field( wp_unslash( $talk_end_meridian[$j-1] ) );
					}

					if ( $talk_place != '') {
						$new_events[$i][$j]['talk_place'] = sanitize_text_field( wp_unslash( $talk_place[$j-1] ) );
					}

					if ( $talk_speaker != '') {
						$new_events[$i][$j]['talk_speaker'] = sanitize_text_field( wp_unslash( $talk_speaker[$j-1] ) );
					}

				}

			}


			$post_event_start = ( isset($_POST['EventStartDate']) && $_POST['EventStartDate'] != '' ) ? strtotime( $_POST['EventStartDate'] ) : '' ;
			$post_event_end = ( isset($_POST['EventEndDate']) && $_POST['EventEndDate'] != '' ) ? strtotime( $_POST['EventEndDate'] ) : '' ;

			$duration = ( ( isset($post_event_start) && $post_event_start != '' ) && ( isset($post_event_end) && $post_event_end != '' ) ) ? floor( ($post_event_end-$post_event_start)/(60*60*24) ) + 1 : 0;

			$new_duration = count($new_events);

			if( $duration < $new_duration ) {
				delete_post_meta( get_the_ID(), 'event_details');
				$sliced_array = array_slice($new_events, 0, $duration, true);
				update_post_meta( get_the_ID(), 'event_details', $sliced_array );
			} elseif ( !empty( $new_events ) && $new_events != $old_events ) {
				update_post_meta( get_the_ID(), 'event_details', $new_events );
			} elseif ( empty($new_events) && $old_events ){
				delete_post_meta( get_the_ID(), 'event_details', $old_events );
			}

		}

	}

}
