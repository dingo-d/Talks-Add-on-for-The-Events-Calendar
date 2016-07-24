<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    event_talks
 * @subpackage event_talks/public/partials
 */

class event_talks_Shortcode {

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
	 * @var      string    $event_talks       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $event_talks, $version ) {

		$this->event_talks = $event_talks;
		$this->version = $version;

	}

	/**
	 * Event talks shortcode
	 *
	 * @param array atts shortcode attributes
	 * @return Shortcode [event_talks post_id="" class=""]
	 */

	public function event_talks_event_talks_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'post_id' => '',
			'class'   => '',
		), $atts, 'event_talks' );

		$post_id = $atts['post_id'];
		$class   = esc_attr( $atts['class'] );

		$post_meta     = get_post_meta($post_id);
		$event_details = get_post_meta($post_id, 'event_details', true);

		$event_start = ( isset($post_meta['_EventStartDate'][0]) && $post_meta['_EventStartDate'][0] != '' ) ? date( 'F j @ g:i a', strtotime($post_meta['_EventStartDate'][0]) ) : '' ;
		$event_end = ( isset($post_meta['_EventEndDate'][0]) && $post_meta['_EventEndDate'][0] != '' ) ? date( 'F j @ g:i a', strtotime($post_meta['_EventEndDate'][0]) ) : '' ;

		$out = '';
		$out .= '<div id="event-'.$post_id.'" class="event_talk_wrapper post-'.$post_id.' '.$class.'">';
		$out .= '<h3 class="event_talk_title"><a href="'.get_the_permalink($post_id).'">'.get_the_title($post_id).'</a></h3>';
		$out .= '<div class="event_talk_date">'.$event_start.' - '.$event_end.'</div>';
		$out .= '<div class="event_talk_tab_container">';
		$i = 0;
		foreach ($event_details as $ev_key => $ev_value) {
			$i++;
			$active = ($i == 1) ? ' active' : '';
			$tab_day = $ev_value['day_tab'];
			$out .= '<div class="tab tab_day_'.$i.$active.'" data-tab="day_'.$i.'"><span>'.esc_html($tab_day).'</span></div>';
		}
		$out .= '</div>';
		$out .= '<div class="event_talk_content_container">';
		$j=0;
		foreach ($event_details as $ev_key => $ev_value) {
			$j++;

			$k=0;

			$active_content = ($j == 1) ? ' active' : '';

			$out .= '<div id="day_'.$j.'" class="tab_content tab_'.$j.$active_content.'">';
			array_shift($ev_value);
			foreach ($ev_value as $ev_val_key => $ev_val_value) {
				$k++;

				$talk_image = ( isset($ev_val_value['talk_image']) && $ev_val_value['talk_image'] != '' ) ? $ev_val_value['talk_image'] : '';
				$talk_title = ( isset($ev_val_value['talk_title']) && $ev_val_value['talk_title'] != '' ) ? $ev_val_value['talk_title'] : '';
				$talk_description = ( isset($ev_val_value['talk_description']) && $ev_val_value['talk_description'] != '' ) ? $ev_val_value['talk_description'] : '';
				$talk_start_hour = ( isset($ev_val_value['talk_start_hour']) && $ev_val_value['talk_start_hour'] != '' ) ? $ev_val_value['talk_start_hour'] : '';
				$talk_start_minute = ( isset($ev_val_value['talk_start_minute']) && $ev_val_value['talk_start_minute'] != '' ) ? $ev_val_value['talk_start_minute'] : '';
				$talk_start_meridian = ( isset($ev_val_value['talk_start_meridian']) && $ev_val_value['talk_start_meridian'] != '' ) ? $ev_val_value['talk_start_meridian'] : '';
				$talk_end_hour = ( isset($ev_val_value['talk_end_hour']) && $ev_val_value['talk_end_hour'] != '' ) ? $ev_val_value['talk_end_hour'] : '';
				$talk_end_minute = ( isset($ev_val_value['talk_end_minute']) && $ev_val_value['talk_end_minute'] != '' ) ? $ev_val_value['talk_end_minute'] : '';
				$talk_end_meridian = ( isset($ev_val_value['talk_end_meridian']) && $ev_val_value['talk_end_meridian'] != '' ) ? $ev_val_value['talk_end_meridian'] : '';
				$talk_place = ( isset($ev_val_value['talk_place']) && $ev_val_value['talk_place'] != '' ) ? $ev_val_value['talk_place'] : '';
				$talk_speaker = ( isset($ev_val_value['talk_speaker']) && $ev_val_value['talk_speaker'] != '' ) ? $ev_val_value['talk_speaker'] : '';

				if ($talk_image != '' ) {
				$out .= '<div id="day_'.$j.'_talk_'.$k.'" class="event_talk_talk_single talk_'.$k.'">
							<div class="event_talk_image_container">
								<img src="'.esc_url($talk_image).'" alt="'.esc_html($talk_title).'">
							</div>
							<div class="event_talk_talk_contents has_image">
								<div class="event_talk_title">'.esc_html($talk_title).'</div>
								<div class="event_talk_description">'.wp_kses_post($talk_description).'</div>
								<div class="event_talk_talk_meta_info">
									<div class="event_talk_talk_time"><i class="eticon-clock"></i>'.esc_attr($talk_start_hour).':'.esc_attr($talk_start_minute).' '.esc_attr($talk_start_meridian).' - '.esc_attr($talk_end_hour).':'.esc_attr($talk_end_minute).' '.esc_attr($talk_start_meridian).'</div>';
						if ($talk_place != '' ) {
							$out .= '<div class="event_talk_talk_place"><i class="eticon-hall"></i>'.esc_attr($talk_place).'</div>';
						}
						if ($talk_speaker != '' ) {
							$out .= '<div class="event_talk_talk_speaker"><i class="eticon-mic"></i>'.esc_attr($talk_speaker).'</div>';
						}
						$out .= '</div>
							</div>
						</div>';
				} else {
				$out .= '<div id="day_'.$j.'_talk_'.$k.'" class="event_talk_talk_single talk_'.$k.'">
							<div class="event_talk_talk_contents">
								<div class="event_talk_title">'.esc_html($talk_title).'</div>
								<div class="event_talk_description">'.wp_kses_post($talk_description).'</div>
								<div class="event_talk_talk_meta_info">
									<div class="event_talk_talk_time"><i class="eticon-clock"></i>'.esc_attr($talk_start_hour).':'.esc_attr($talk_start_minute).' '.esc_attr($talk_start_meridian).' - '.esc_attr($talk_end_hour).':'.esc_attr($talk_end_minute).' '.esc_attr($talk_start_meridian).'</div>';
						if ($talk_place != '' ) {
							$out .= '<div class="event_talk_talk_place"><i class="eticon-hall"></i>'.esc_attr($talk_place).'</div>';
						}
						if ($talk_speaker != '' ) {
							$out .= '<div class="event_talk_talk_speaker"><i class="eticon-mic"></i>'.esc_attr($talk_speaker).'</div>';
						}
						$out .= '</div>
							</div>
						</div>';
				}
			}
			$out .= '</div>';
		}
		$out .= '</div>';
		$out .= '<div class="event_talk_meta_share">
					<i class="eticon-share"></i><span>'.esc_html__( 'Share event:','event-talks' ).'</span>
					<div class="event_talk_meta_share_icons">
						<a class="event_talk-share-fb" title="'.esc_html__( 'Share on Facebook', 'event-talks' ).'" href="#" target="_blank" data-event="'.esc_url(get_the_permalink($post_id)).'"><i class="eticon-facebook"></i></a>
						<a class="event_talk-share-gplus" title="'.esc_html__( 'Share on Google Plus', 'event-talks' ).'" href="#" target="_blank" data-event="'.esc_url(get_the_permalink($post_id)).'"><i class="eticon-google-plus"></i></a>
						<a class="event_talk-share-tw" data-title="'. get_the_title($post_id) .'" title="'.esc_html__( 'Share on Twitter', 'event-talks' ).'" href="#" target="_blank" data-event="'.esc_url(get_the_permalink($post_id)).'"><i class="eticon-twitter"></i></a>
						<a class="event_talk-share-linked" title="'.esc_html__( 'Share on Linkedin', 'event-talks' ).'" href="#" target="_blank" data-event="'.esc_url(get_the_permalink($post_id)).'"><i class="eticon-linkedin"></i></a>
					</div>
				</div>';
		$out .= '</div>';

		return $out;
	}

}

class event_talks_Widget extends WP_widget{

	/**
	 * Sets up Event Talks widget instance.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'event-talks',
			'description' => esc_html__( 'Shows short description about talks.', 'event-talks' ),
		);
		$control_ops = array(
			'id_base' => 'event-talks',
		);
		parent::__construct( 'event-talks', esc_html__( 'Event talks widget', 'event-talks' ), $widget_ops, $control_ops);
	}

	/**
	 * Outputs the content for the current Event Talks widget instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Event Talks widget instance.
	 */
	public function widget($args, $instance){

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Event Talks', 'event-talks' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title        = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$post_id      = ( $instance['ev_id'] ) ? $instance['ev_id'] : '';
		$hide_desc    = ( $instance['hide_desc'] ) ? (bool) $instance['hide_desc'] : false;
		$hide_time    = ( $instance['hide_time'] ) ? (bool) $instance['hide_time'] : false;
		$hide_speaker = ( $instance['hide_speaker'] ) ? (bool) $instance['hide_speaker'] : false;
		$hide_place   = ( $instance['hide_place'] ) ? (bool) $instance['hide_place'] : false;

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] .'<a href="'.get_the_permalink( $post_id ).'" target="_blank">'. $title .'</a>'. $args['after_title'];
		}

		echo $args['after_widget'];

		$post_meta     = get_post_meta($post_id);
		$event_details = get_post_meta($post_id, 'event_details', true);

		$event_start = ( isset($post_meta['_EventStartDate'][0]) && $post_meta['_EventStartDate'][0] != '' ) ? date( 'F j @ g:i a', strtotime($post_meta['_EventStartDate'][0]) ) : '' ;
		$event_end = ( isset($post_meta['_EventEndDate'][0]) && $post_meta['_EventEndDate'][0] != '' ) ? date( 'F j @ g:i a', strtotime($post_meta['_EventEndDate'][0]) ) : '' ;

		$out = '';
		$out .= '<div id="event-'.$post_id.'" class="event_talk_widget post-'.$post_id.'">';
		$out .= '<h3 class="event_talk_title"><a href="'.get_the_permalink($post_id).'">'.get_the_title($post_id).'</a></h3>';
		$out .= '<div class="event_talk_date">'.$event_start.' - '.$event_end.'</div>';
		$out .= '<div class="event_talk_tab_container">';
		$i = 0;
		foreach ($event_details as $ev_key => $ev_value) {
			$i++;
			$active = ($i == 1) ? ' active' : '';
			$tab_day = $ev_value['day_tab'];
			$out .= '<div class="tab tab_day_'.$i.$active.'" data-tab="day_'.$i.'"><span>'.esc_html($tab_day).'</span></div>';
		}
		$out .= '</div><div class="event_talk_content_container">';
		$j=0;
		foreach ($event_details as $ev_key => $ev_value) {
			$j++;

			$k=0;

			$active_content = ($j == 1) ? ' active' : '';

			$out .= '<div id="widget_day_'.$j.'" class="tab_content tab_'.$j.$active_content.'">';
			array_shift($ev_value);
			foreach ($ev_value as $ev_val_key => $ev_val_value) {
				$k++;

				$talk_title = ( isset($ev_val_value['talk_title']) && $ev_val_value['talk_title'] != '' ) ? $ev_val_value['talk_title'] : '';
				$talk_description = ( isset($ev_val_value['talk_description']) && $ev_val_value['talk_description'] != '' ) ? $ev_val_value['talk_description'] : '';
				$talk_start_hour = ( isset($ev_val_value['talk_start_hour']) && $ev_val_value['talk_start_hour'] != '' ) ? $ev_val_value['talk_start_hour'] : '';
				$talk_start_minute = ( isset($ev_val_value['talk_start_minute']) && $ev_val_value['talk_start_minute'] != '' ) ? $ev_val_value['talk_start_minute'] : '';
				$talk_start_meridian = ( isset($ev_val_value['talk_start_meridian']) && $ev_val_value['talk_start_meridian'] != '' ) ? $ev_val_value['talk_start_meridian'] : '';
				$talk_end_hour = ( isset($ev_val_value['talk_end_hour']) && $ev_val_value['talk_end_hour'] != '' ) ? $ev_val_value['talk_end_hour'] : '';
				$talk_end_minute = ( isset($ev_val_value['talk_end_minute']) && $ev_val_value['talk_end_minute'] != '' ) ? $ev_val_value['talk_end_minute'] : '';
				$talk_end_meridian = ( isset($ev_val_value['talk_end_meridian']) && $ev_val_value['talk_end_meridian'] != '' ) ? $ev_val_value['talk_end_meridian'] : '';
				$talk_place = ( isset($ev_val_value['talk_place']) && $ev_val_value['talk_place'] != '' ) ? $ev_val_value['talk_place'] : '';
				$talk_speaker = ( isset($ev_val_value['talk_speaker']) && $ev_val_value['talk_speaker'] != '' ) ? $ev_val_value['talk_speaker'] : '';

				$out .= '<div id="day_'.$j.'_talk_'.$k.'" class="event_talk_talk_single talk_'.$k.'">
							<div class="event_talk_talk_contents">
								<div class="event_talk_title">'.esc_html($talk_title).'</div>';
					if($hide_desc != 1 ){
						$out .= '<div class="event_talk_description">'.wp_kses_post($talk_description).'</div>';
					}
					$out .= '</div>';
					$out .= '<div class="event_talk_talk_meta_info">';
					if($hide_time != 1){
						$out .= '<div class="event_talk_talk_time"><i class="eticon-clock"></i>'.esc_attr($talk_start_hour).':'.esc_attr($talk_start_minute).' '.esc_attr($talk_start_meridian).' - '.esc_attr($talk_end_hour).':'.esc_attr($talk_end_minute).' '.esc_attr($talk_start_meridian).'</div>';
					}
					if ($talk_place != '' && $hide_place != 1) {
						$out .= '<div class="event_talk_talk_place"><i class="eticon-hall"></i>'.esc_attr($talk_place).'</div>';
					}
					if ($talk_speaker != '' && $hide_speaker != 1 ) {
						$out .= '<div class="event_talk_talk_speaker"><i class="eticon-mic"></i>'.esc_attr($talk_speaker).'</div>';
					}
					$out .= '</div>';
				$out .= '</div>';
			}
			$out .= '</div>';
		}
		$out .= '</div>';
		$out .= '</div>';

		echo $out;

	}

	/**
	 * Handles updating settings for the Event Talks widget instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ){
		$instance = array();
		$instance['title']        = $new_instance['title'];
		$instance['ev_id']     	  = $new_instance['ev_id'];
		$instance['hide_desc']    = $new_instance['hide_desc'];
		$instance['hide_time']    = $new_instance['hide_time'];
		$instance['hide_speaker'] = $new_instance['hide_speaker'];
		$instance['hide_place']   = $new_instance['hide_place'];

		return $instance;
	}

	/**
	 * Outputs the settings form for the Event Talks widget.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ){
		$defaults = array(
			'title' => esc_html__( 'Event Talks', 'event-talks' ),
			'ev_id' => 0,
		);

		$hide_desc    = isset( $instance['hide_desc'] ) ? (bool) $instance['hide_desc'] : false;
		$hide_time    = isset( $instance['hide_time'] ) ? (bool) $instance['hide_time'] : false;
		$hide_speaker = isset( $instance['hide_speaker'] ) ? (bool) $instance['hide_speaker'] : false;
		$hide_place   = isset( $instance['hide_place'] ) ? (bool) $instance['hide_place'] : false;

		$instance = wp_parse_args((array) $instance, $defaults);

		$event_args = array(
			'post_type'      => 'tribe_events',
			'posts_per_page' => 999,
			'post_status'    => 'publish'
		);

		$events_query = get_posts( $event_args );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title: ', 'event-talks' );?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'ev_id' ); ?>"><?php echo esc_html__( 'Event: ', 'event-talks' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'ev_id' ); ?>" id="<?php echo $this->get_field_id( 'ev_id' ); ?>">
				<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'event-talks' ); ?></option><?php
			foreach ( $events_query as $ev_post ) {
				setup_postdata( $ev_post );
				$pevent_talk_id = $ev_post->ID;
				?>
				<option value="<?php echo esc_attr( $pevent_talk_id ); ?>" <?php selected( $instance['ev_id'], $pevent_talk_id ); ?>><?php echo esc_html( get_the_title( $pevent_talk_id ) ); ?></option>
			<?php
			}
			wp_reset_postdata(); ?>
			</select>
		</p>
		<p><input class="checkbox" type="checkbox"<?php checked( $hide_desc ); ?> id="<?php echo $this->get_field_id( 'hide_desc' ); ?>" name="<?php echo $this->get_field_name( 'hide_desc' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'hide_desc' ); ?>"><?php esc_html_e( 'Hide talk descriptions' , 'event-talks' ); ?></label></p>
		<p><input class="checkbox" type="checkbox"<?php checked( $hide_time ); ?> id="<?php echo $this->get_field_id( 'hide_time' ); ?>" name="<?php echo $this->get_field_name( 'hide_time' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'hide_time' ); ?>"><?php esc_html_e( 'Hide talk times', 'event-talks' ); ?></label></p>
		<p><input class="checkbox" type="checkbox"<?php checked( $hide_speaker ); ?> id="<?php echo $this->get_field_id( 'hide_speaker' ); ?>" name="<?php echo $this->get_field_name( 'hide_speaker' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'hide_speaker' ); ?>"><?php esc_html_e( 'Hide talk speakers', 'event-talks' ); ?></label></p>
		<p><input class="checkbox" type="checkbox"<?php checked( $hide_place ); ?> id="<?php echo $this->get_field_id( 'hide_place' ); ?>" name="<?php echo $this->get_field_name( 'hide_place' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'hide_place' ); ?>"><?php esc_html_e( 'Hide talk places', 'event-talks' ); ?></label></p>
		<?php
	}

	/**
	 * Event Talks widget register function
	 *
	 * @access public
	 */
	public function event_talks_register_widget() {
		register_widget( 'event_talks_Widget' );
	}

}