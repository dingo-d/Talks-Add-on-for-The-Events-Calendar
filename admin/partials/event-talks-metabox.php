<?php

/**
 * The file were we define metabox for Event Talks add on
 *
 * @since      1.0.0
 *
 */
//Get post meta
$post_meta = get_post_meta(get_the_ID());

//Get start and end date from Events Calendar metabox
$event_start = ( isset($post_meta['_EventStartDate'][0]) && $post_meta['_EventStartDate'][0] != '' ) ? strtotime( $post_meta['_EventStartDate'][0] ) : '' ;
$event_end = ( isset($post_meta['_EventEndDate'][0]) && $post_meta['_EventEndDate'][0] != '' ) ? strtotime( $post_meta['_EventEndDate'][0] ) : '' ;

$date_no = ( ( isset($event_start) && $event_start != '' ) && ( isset($event_end) && $event_end != '' ) ) ? floor( ($event_end-$event_start)/(60*60*24) )+1 : 0;

if ($date_no > 0) {
	$event_details = get_post_meta(get_the_ID(), 'event_details', true);
}

wp_nonce_field( 'talk_field_nonce', 'talk_fields_meta_box_nonce' );

if($date_no <= 0): ?>
<div class="event_metabox_splash_screen">
	<h3 class="event_notice"><?php esc_html_e('Please put the event details in the Events Calendar and save your event to be able to add talk details in.', 'event-talks'); ?></h3>
</div>
<?php else: ?>
<div class="event_days">
	<?php
	if ($event_details) :
		$number_of_days = count($event_details); ?>
	<div class="day_tabs">
	<?php $i = 1;
	foreach ($event_details as $date_key => $date_value){
		$first = ( $i== 1) ? 'active' : ''; ?>
		<div class="day_<?php echo esc_attr($i); ?> tab <?php echo esc_attr($first); ?>" data-tab="day_<?php echo esc_attr($i); ?>" data-tab_no="<?php echo esc_attr($i); ?>">
			<input name="day_tab[]" type="text" value="<?php echo esc_html($date_value['day_tab']); ?>" />
		</div>
	<?php $i++;
	}
	if ($date_no > $number_of_days) {
	 	for( $k = $number_of_days+1; $k <= $date_no; $k++){
	 	?>
	 	<div class="day_<?php echo esc_attr($k); ?> tab" data-tab="day_<?php echo esc_attr($k); ?>" data-tab_no="<?php echo esc_attr($i); ?>">
			<input name="day_tab[]" type="text" value="Day <?php echo esc_attr($k); ?>" />
		</div>
	 	<?php
	}
	}?>
	</div>
	<div class="tab_content_holder">
	<?php $j = 1; foreach ($event_details as $date_key => $date_value){
		$first = ( $j == 1 ) ? 'active' : ''; ?>
		<div class="tab_<?php echo esc_attr($j); ?> tab_content <?php echo esc_attr($first); ?>" id="day_<?php echo esc_attr($j); ?>">
			<input name="day_<?php echo esc_attr($j); ?>_talk_no" class="number_of_talks" type="hidden" value="<?php echo (count($date_value)>1) ? count($date_value)-1 : 1; ?>" />
			<?php array_shift($date_value);
			if ( !empty($date_value) ) :
				foreach ( $date_value as $dv_key => $dv_value ) { ?>
			<div class="event_part_content">
				<div class="image_container">
					<img src="<?php echo esc_url( $dv_value['talk_image'] ); ?>" class="event_part_image">
					<input name="talk_image_<?php echo esc_attr($j); ?>[]" class="talk_image_url" type="hidden" value="<?php echo esc_url($dv_value['talk_image']); ?>">
					<div class="button add_talk_image"><?php esc_html_e('Add Image', 'event-talks'); ?></div><div class="button remove_talk_image"><?php esc_html_e('Remove Image', 'event-talks'); ?></div>
				</div>
				<div class="content_container">
					<label for="talk_title_<?php echo esc_attr($j); ?>[]"><?php esc_html_e('Talk name/title', 'event-talks'); ?></label>
					<input type="text" class="talk_title" name="talk_title_<?php echo esc_attr($j); ?>[]" value="<?php echo esc_html($dv_value['talk_title']); ?>">
					<label class="talk_desc_label" for="talk_description_<?php echo esc_attr($j); ?>[]"><?php esc_html_e('Talk description', 'event-talks'); ?></label>
					<textarea name="talk_description_<?php echo esc_attr($j); ?>[]" rows="10"><?php echo wp_kses_post($dv_value['talk_description']); ?></textarea>
					<div class="additional_talk_meta">
						<div class="talk_time">
							<label for="start_talk_time_<?php echo esc_attr($j); ?>"><?php esc_html_e('Start time', 'event-talks'); ?></label>
							<select name="talk_start_hour_<?php echo esc_attr($j); ?>[]">
								<option value="12" <?php selected( $dv_value['talk_start_hour'], '12' ); ?>>12</option>
								<option value="01" <?php selected( $dv_value['talk_start_hour'], '01' ); ?>>01</option>
								<option value="02" <?php selected( $dv_value['talk_start_hour'], '02' ); ?>>02</option>
								<option value="03" <?php selected( $dv_value['talk_start_hour'], '03' ); ?>>03</option>
								<option value="04" <?php selected( $dv_value['talk_start_hour'], '04' ); ?>>04</option>
								<option value="05" <?php selected( $dv_value['talk_start_hour'], '05' ); ?>>05</option>
								<option value="06" <?php selected( $dv_value['talk_start_hour'], '06' ); ?>>06</option>
								<option value="07" <?php selected( $dv_value['talk_start_hour'], '07' ); ?>>07</option>
								<option value="08" <?php selected( $dv_value['talk_start_hour'], '08' ); ?>>08</option>
								<option value="09" <?php selected( $dv_value['talk_start_hour'], '09' ); ?>>09</option>
								<option value="10" <?php selected( $dv_value['talk_start_hour'], '10' ); ?>>10</option>
								<option value="11" <?php selected( $dv_value['talk_start_hour'], '11' ); ?>>11</option>
							</select>
							<select name="talk_start_minute_<?php echo esc_attr($j); ?>[]">
								<option value="00" <?php selected( $dv_value['talk_start_minute'], '00' ); ?>>00</option>
								<option value="05" <?php selected( $dv_value['talk_start_minute'], '05' ); ?>>05</option>
								<option value="10" <?php selected( $dv_value['talk_start_minute'], '10' ); ?>>10</option>
								<option value="15" <?php selected( $dv_value['talk_start_minute'], '15' ); ?>>15</option>
								<option value="20" <?php selected( $dv_value['talk_start_minute'], '20' ); ?>>20</option>
								<option value="25" <?php selected( $dv_value['talk_start_minute'], '25' ); ?>>25</option>
								<option value="30" <?php selected( $dv_value['talk_start_minute'], '30' ); ?>>30</option>
								<option value="35" <?php selected( $dv_value['talk_start_minute'], '35' ); ?>>35</option>
								<option value="40" <?php selected( $dv_value['talk_start_minute'], '40' ); ?>>40</option>
								<option value="45" <?php selected( $dv_value['talk_start_minute'], '45' ); ?>>45</option>
								<option value="50" <?php selected( $dv_value['talk_start_minute'], '50' ); ?>>50</option>
								<option value="55" <?php selected( $dv_value['talk_start_minute'], '55' ); ?>>55</option>
							</select>
							<select name="talk_start_meridian_<?php echo esc_attr($j); ?>[]">
								<option value="am" <?php selected( $dv_value['talk_start_meridian'], 'am' ); ?>>am</option>
								<option value="pm" <?php selected( $dv_value['talk_start_meridian'], 'pm' ); ?>>pm</option>
							</select>
							<label for="end_talk_time_<?php echo esc_attr($j); ?>"><?php esc_html_e('End time', 'event-talks'); ?></label>
							<select name="talk_end_hour_<?php echo esc_attr($j); ?>[]">
								<option value="12" <?php selected( $dv_value['talk_end_hour'], '12' ); ?>>12</option>
								<option value="01" <?php selected( $dv_value['talk_end_hour'], '01' ); ?>>01</option>
								<option value="02" <?php selected( $dv_value['talk_end_hour'], '02' ); ?>>02</option>
								<option value="03" <?php selected( $dv_value['talk_end_hour'], '03' ); ?>>03</option>
								<option value="04" <?php selected( $dv_value['talk_end_hour'], '04' ); ?>>04</option>
								<option value="05" <?php selected( $dv_value['talk_end_hour'], '05' ); ?>>05</option>
								<option value="06" <?php selected( $dv_value['talk_end_hour'], '06' ); ?>>06</option>
								<option value="07" <?php selected( $dv_value['talk_end_hour'], '07' ); ?>>07</option>
								<option value="08" <?php selected( $dv_value['talk_end_hour'], '08' ); ?>>08</option>
								<option value="09" <?php selected( $dv_value['talk_end_hour'], '09' ); ?>>09</option>
								<option value="10" <?php selected( $dv_value['talk_end_hour'], '10' ); ?>>10</option>
								<option value="11" <?php selected( $dv_value['talk_end_hour'], '11' ); ?>>11</option>
							</select>
							<select name="talk_end_minute_<?php echo esc_attr($j); ?>[]">
								<option value="00" <?php selected( $dv_value['talk_end_minute'], '00' ); ?>>00</option>
								<option value="05" <?php selected( $dv_value['talk_end_minute'], '05' ); ?>>05</option>
								<option value="10" <?php selected( $dv_value['talk_end_minute'], '10' ); ?>>10</option>
								<option value="15" <?php selected( $dv_value['talk_end_minute'], '15' ); ?>>15</option>
								<option value="20" <?php selected( $dv_value['talk_end_minute'], '20' ); ?>>20</option>
								<option value="25" <?php selected( $dv_value['talk_end_minute'], '25' ); ?>>25</option>
								<option value="30" <?php selected( $dv_value['talk_end_minute'], '30' ); ?>>30</option>
								<option value="35" <?php selected( $dv_value['talk_end_minute'], '35' ); ?>>35</option>
								<option value="40" <?php selected( $dv_value['talk_end_minute'], '40' ); ?>>40</option>
								<option value="45" <?php selected( $dv_value['talk_end_minute'], '45' ); ?>>45</option>
								<option value="50" <?php selected( $dv_value['talk_end_minute'], '50' ); ?>>50</option>
								<option value="55" <?php selected( $dv_value['talk_end_minute'], '55' ); ?>>55</option>
							</select>
							<select name="talk_end_meridian_<?php echo esc_attr($j); ?>[]">
								<option value="am" <?php selected( $dv_value['talk_end_meridian'], 'am' ); ?>>am</option>
								<option value="pm" <?php selected( $dv_value['talk_end_meridian'], 'pm' ); ?>>pm</option>
							</select>
						</div>
						<div class="talk_place">
							<label for="talk_place_<?php echo esc_attr($j); ?>[]"><?php esc_html_e('Place/room', 'event-talks'); ?></label>
							<input type="text" class="talk_place" name="talk_place_<?php echo esc_attr($j); ?>[]" value="<?php echo esc_html($dv_value['talk_place']); ?>">
						</div>
						<div class="talk_speaker">
							<label for="talk_speaker_<?php echo esc_attr($j); ?>[]"><?php esc_html_e('Speaker(s)', 'event-talks'); ?></label>
							<input type="text" class="talk_speaker" name="talk_speaker_<?php echo esc_attr($j); ?>[]" value="<?php echo esc_html($dv_value['talk_speaker']); ?>">
						</div>
					</div>
				</div>
				<div class="control_container">
					<div class="button add_talk"><?php esc_html_e('Add Talk', 'event-talks'); ?></div>
					<div class="button remove_talk"><?php esc_html_e('Remove Talk', 'event-talks'); ?></div>
				</div>
			</div>
			<?php }
			else: ?>
			<div class="event_part_content">
				<div class="image_container">
					<img src="" class="event_part_image">
					<input name="talk_image_<?php echo esc_attr($j); ?>[]" class="talk_image_url" type="hidden" value="">
					<div class="button add_talk_image"><?php esc_html_e('Add Image', 'event-talks'); ?></div><div class="button remove_talk_image"><?php esc_html_e('Remove Image', 'event-talks'); ?></div>
				</div>
				<div class="content_container">
					<label for="talk_title_<?php echo esc_attr($j); ?>[]"><?php esc_html_e('Talk name/title', 'event-talks'); ?></label>
					<input type="text" class="talk_title" name="talk_title_<?php echo esc_attr($j); ?>[]" value="">
					<label class="talk_desc_label" for="talk_description_<?php echo esc_attr($j); ?>[]"><?php esc_html_e('Talk description', 'event-talks'); ?></label>
					<textarea name="talk_description_<?php echo esc_attr($j); ?>[]" rows="10"></textarea>
					<div class="additional_talk_meta">
						<div class="talk_time">
							<label for="start_talk_time_<?php echo esc_attr($j); ?>"><?php esc_html_e('Start time', 'event-talks'); ?></label>
							<select name="talk_start_hour_<?php echo esc_attr($j); ?>[]">
								<option value="12">12</option>
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
							</select>
							<select name="talk_start_minute_<?php echo esc_attr($j); ?>[]">
								<option value="00">00</option>
								<option value="05">05</option>
								<option value="10">10</option>
								<option value="15">15</option>
								<option value="20">20</option>
								<option value="25">25</option>
								<option value="30">30</option>
								<option value="35">35</option>
								<option value="40">40</option>
								<option value="45">45</option>
								<option value="50">50</option>
								<option value="55">55</option>
							</select>
							<select name="talk_start_meridian_<?php echo esc_attr($j); ?>[]">
								<option value="am">am</option>
								<option value="pm">pm</option>
							</select>
							<label for="end_talk_time_<?php echo esc_attr($j); ?>"><?php esc_html_e('End time', 'event-talks'); ?></label>
							<select name="talk_end_hour_<?php echo esc_attr($j); ?>[]">
								<option value="12">12</option>
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
							</select>
							<select name="talk_end_minute_<?php echo esc_attr($j); ?>[]">
								<option value="00">00</option>
								<option value="05">05</option>
								<option value="10">10</option>
								<option value="15">15</option>
								<option value="20">20</option>
								<option value="25">25</option>
								<option value="30">30</option>
								<option value="35">35</option>
								<option value="40">40</option>
								<option value="45">45</option>
								<option value="50">50</option>
								<option value="55">55</option>
							</select>
							<select name="talk_end_meridian_<?php echo esc_attr($j); ?>[]">
								<option value="am">am</option>
								<option value="pm">pm</option>
							</select>
						</div>
						<div class="talk_place">
							<label for="talk_place_<?php echo esc_attr($j); ?>[]"><?php esc_html_e('Place/room', 'event-talks'); ?></label>
							<input type="text" class="talk_place" name="talk_place_<?php echo esc_attr($j); ?>[]" value="">
						</div>
						<div class="talk_speaker">
							<label for="talk_speaker_<?php echo esc_attr($j); ?>[]"><?php esc_html_e('Speaker(s)', 'event-talks'); ?></label>
							<input type="text" class="talk_speaker" name="talk_speaker_<?php echo esc_attr($j); ?>[]" value="">
						</div>
					</div>
				</div>
				<div class="control_container">
					<div class="button add_talk"><?php esc_html_e('Add Talk', 'event-talks'); ?></div>
					<div class="button remove_talk"><?php esc_html_e('Remove Talk', 'event-talks'); ?></div>
				</div>
			</div>
		<?php endif; ?>
		</div>
		<?php $j++; }
		if ($date_no > $number_of_days) {
	 		for( $k = $number_of_days+1; $k <= $date_no; $k++){
	 			?>
	 			<div class="tab_<?php echo esc_attr($k); ?> tab_content" id="day_<?php echo esc_attr($k); ?>">
					<input name="day_<?php echo esc_attr($k); ?>_talk_no" class="number_of_talks" type="hidden" value="1" />
					<div class="event_part_content">
						<div class="image_container">
							<img src="" class="event_part_image">
							<input name="talk_image_<?php echo esc_attr($k); ?>[]" class="talk_image_url" type="hidden" value="">
							<div class="button add_talk_image"><?php esc_html_e('Add Image', 'event-talks'); ?></div><div class="button remove_talk_image"><?php esc_html_e('Remove Image', 'event-talks'); ?></div>
						</div>
						<div class="content_container">
							<label for="talk_title_<?php echo esc_attr($k); ?>[]"><?php esc_html_e('Talk name/title', 'event-talks'); ?></label>
							<input type="text" class="talk_title" name="talk_title_<?php echo esc_attr($k); ?>[]" value="">
							<label class="talk_desc_label" for="talk_description_<?php echo esc_attr($k); ?>[]"><?php esc_html_e('Talk description', 'event-talks'); ?></label>
							<textarea name="talk_description_<?php echo esc_attr($k); ?>[]" rows="10"></textarea>
							<div class="additional_talk_meta">
						<div class="talk_time">
							<label for="start_talk_time_<?php echo esc_attr($k); ?>"><?php esc_html_e('Start time', 'event-talks'); ?></label>
							<select name="talk_start_hour_<?php echo esc_attr($k); ?>[]">
								<option value="12">12</option>
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
							</select>
							<select name="talk_start_minute_<?php echo esc_attr($k); ?>[]">
								<option value="00">00</option>
								<option value="05">05</option>
								<option value="10">10</option>
								<option value="15">15</option>
								<option value="20">20</option>
								<option value="25">25</option>
								<option value="30">30</option>
								<option value="35">35</option>
								<option value="40">40</option>
								<option value="45">45</option>
								<option value="50">50</option>
								<option value="55">55</option>
							</select>
							<select name="talk_start_meridian_<?php echo esc_attr($k); ?>[]">
								<option value="am">am</option>
								<option value="pm">pm</option>
							</select>
							<label for="end_talk_time_<?php echo esc_attr($k); ?>"><?php esc_html_e('End time', 'event-talks'); ?></label>
							<select name="talk_end_hour_<?php echo esc_attr($k); ?>[]">
								<option value="12">12</option>
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
							</select>
							<select name="talk_end_minute_<?php echo esc_attr($k); ?>[]">
								<option value="00">00</option>
								<option value="05">05</option>
								<option value="10">10</option>
								<option value="15">15</option>
								<option value="20">20</option>
								<option value="25">25</option>
								<option value="30">30</option>
								<option value="35">35</option>
								<option value="40">40</option>
								<option value="45">45</option>
								<option value="50">50</option>
								<option value="55">55</option>
							</select>
							<select name="talk_end_meridian_<?php echo esc_attr($k); ?>[]">
								<option value="am">am</option>
								<option value="pm">pm</option>
							</select>
						</div>
						<div class="talk_place">
							<label for="talk_place_<?php echo esc_attr($k); ?>[]"><?php esc_html_e('Place/room', 'event-talks'); ?></label>
							<input type="text" class="talk_place" name="talk_place_<?php echo esc_attr($k); ?>[]" value="">
						</div>
						<div class="talk_speaker">
							<label for="talk_speaker_<?php echo esc_attr($k); ?>[]"><?php esc_html_e('Speaker(s)', 'event-talks'); ?></label>
							<input type="text" class="talk_speaker" name="talk_speaker_<?php echo esc_attr($k); ?>[]" value="">
						</div>
					</div>
						</div>
						<div class="control_container">
							<div class="button add_talk"><?php esc_html_e('Add Talk', 'event-talks'); ?></div>
							<div class="button remove_talk"><?php esc_html_e('Remove Talk', 'event-talks'); ?></div>
						</div>
					</div>
				</div>
			<?php
			}
		}
		?>
	</div><?php
	else:
	?>
	<div class="day_tabs">
	<?php for($i = 1; $i <= $date_no; $i++ ){
		$first = ( $i== 1) ? 'active' : ''; ?>
		<div class="day_<?php echo esc_attr($i); ?> tab <?php echo $first; ?>" data-tab="day_<?php echo esc_attr($i); ?>" data-tab_no="<?php echo esc_attr($i); ?>">
			<input name="day_tab[]" type="text" value="Day <?php echo esc_attr($i); ?>" />
		</div>
	<?php } ?>
	</div>
	<div class="tab_content_holder">
	<?php for($i = 1; $i <= $date_no; $i++ ){
		$first = ( $i== 1) ? 'active' : ''; ?>
		<div class="tab_<?php echo esc_attr($i); ?> tab_content <?php echo $first; ?>" id="day_<?php echo esc_attr($i); ?>">
			<input name="day_<?php echo esc_attr($i); ?>_talk_no" class="number_of_talks" type="hidden" value="1" />
			<div class="event_part_content">
				<div class="image_container">
					<img src="" class="event_part_image">
					<input name="talk_image_<?php echo esc_attr($i); ?>[]" class="talk_image_url" type="hidden" value="">
					<div class="button add_talk_image"><?php esc_html_e('Add Image', 'event-talks'); ?></div><div class="button remove_talk_image"><?php esc_html_e('Remove Image', 'event-talks'); ?></div>
				</div>
				<div class="content_container">
					<label for="talk_title_<?php echo esc_attr($i); ?>[]"><?php esc_html_e('Talk name/title', 'event-talks'); ?></label>
					<input type="text" class="talk_title" name="talk_title_<?php echo esc_attr($i); ?>[]" value="">
					<label class="talk_desc_label" for="talk_description_<?php echo esc_attr($i); ?>[]"><?php esc_html_e('Talk description', 'event-talks'); ?></label>
					<textarea name="talk_description_<?php echo esc_attr($i); ?>[]" rows="10"></textarea>
					<div class="additional_talk_meta">
						<div class="talk_time">
							<label for="start_talk_time_<?php echo esc_attr($i); ?>"><?php esc_html_e('Start time', 'event-talks'); ?></label>
							<select name="talk_start_hour_<?php echo esc_attr($i); ?>[]">
								<option value="12">12</option>
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
							</select>
							<select name="talk_start_minute_<?php echo esc_attr($i); ?>[]">
								<option value="00">00</option>
								<option value="05">05</option>
								<option value="10">10</option>
								<option value="15">15</option>
								<option value="20">20</option>
								<option value="25">25</option>
								<option value="30">30</option>
								<option value="35">35</option>
								<option value="40">40</option>
								<option value="45">45</option>
								<option value="50">50</option>
								<option value="55">55</option>
							</select>
							<select name="talk_start_meridian_<?php echo esc_attr($i); ?>[]">
								<option value="am">am</option>
								<option value="pm">pm</option>
							</select>
							<label for="end_talk_time_<?php echo esc_attr($i); ?>"><?php esc_html_e('End time', 'event-talks'); ?></label>
							<select name="talk_end_hour_<?php echo esc_attr($i); ?>[]">
								<option value="12">12</option>
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
							</select>
							<select name="talk_end_minute_<?php echo esc_attr($i); ?>[]">
								<option value="00">00</option>
								<option value="05">05</option>
								<option value="10">10</option>
								<option value="15">15</option>
								<option value="20">20</option>
								<option value="25">25</option>
								<option value="30">30</option>
								<option value="35">35</option>
								<option value="40">40</option>
								<option value="45">45</option>
								<option value="50">50</option>
								<option value="55">55</option>
							</select>
							<select name="talk_end_meridian_<?php echo esc_attr($i); ?>[]">
								<option value="am">am</option>
								<option value="pm">pm</option>
							</select>
						</div>
						<div class="talk_place">
							<label for="talk_place_<?php echo esc_attr($i); ?>[]"><?php esc_html_e('Place/room', 'event-talks'); ?></label>
							<input type="text" class="talk_place" name="talk_place_<?php echo esc_attr($i); ?>[]" value="">
						</div>
						<div class="talk_speaker">
							<label for="talk_speaker_<?php echo esc_attr($i); ?>[]"><?php esc_html_e('Speaker(s)', 'event-talks'); ?></label>
							<input type="text" class="talk_speaker" name="talk_speaker_<?php echo esc_attr($i); ?>[]" value="">
						</div>
					</div>
				</div>
				<div class="control_container">
					<div class="button add_talk"><?php esc_html_e('Add Talk', 'event-talks'); ?></div>
					<div class="button remove_talk"><?php esc_html_e('Remove Talk', 'event-talks'); ?></div>
				</div>
			</div>
		</div>
	<?php } ?>
	</div>
	<?php endif; ?>
	<div class="empty event_part_content">
		<div class="image_container">
			<img src="" class="event_part_image">
			<input name="talk_image_N[]" class="talk_image_url" type="hidden" value="">
			<div class="button add_talk_image"><?php esc_html_e('Add Image', 'event-talks'); ?></div><div class="button remove_talk_image"><?php esc_html_e('Remove Image', 'event-talks'); ?></div>
		</div>
		<div class="content_container">
			<label for="talk_title_N[]"><?php esc_html_e('Talk name/title', 'event-talks'); ?></label>
			<input type="text" class="talk_title" name="talk_title_N[]" value="">
			<label class="talk_desc_label" for="talk_description_N[]"><?php esc_html_e('Talk description', 'event-talks'); ?></label>
			<textarea name="talk_description_N[]" rows="10"></textarea>
			<div class="additional_talk_meta">
				<div class="talk_time">
					<label for="start_talk_time_N"><?php esc_html_e('Start time', 'event-talks'); ?></label>
					<select name="talk_start_hour_N[]">
						<option value="12">12</option>
						<option value="01">01</option>
						<option value="02">02</option>
						<option value="03">03</option>
						<option value="04">04</option>
						<option value="05">05</option>
						<option value="06">06</option>
						<option value="07">07</option>
						<option value="08">08</option>
						<option value="09">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
					</select>
					<select name="talk_start_minute_N[]">
						<option value="00">00</option>
						<option value="05">05</option>
						<option value="10">10</option>
						<option value="15">15</option>
						<option value="20">20</option>
						<option value="25">25</option>
						<option value="30">30</option>
						<option value="35">35</option>
						<option value="40">40</option>
						<option value="45">45</option>
						<option value="50">50</option>
						<option value="55">55</option>
					</select>
					<select name="talk_start_meridian_N[]">
						<option value="am">am</option>
						<option value="pm">pm</option>
					</select>
					<label for="end_talk_time_N"><?php esc_html_e('End time', 'event-talks'); ?></label>
					<select name="talk_end_hour_N[]">
						<option value="12">12</option>
						<option value="01">01</option>
						<option value="02">02</option>
						<option value="03">03</option>
						<option value="04">04</option>
						<option value="05">05</option>
						<option value="06">06</option>
						<option value="07">07</option>
						<option value="08">08</option>
						<option value="09">09</option>
						<option value="10">10</option>
						<option value="11">11</option>
					</select>
					<select name="talk_end_minute_N[]">
						<option value="00">00</option>
						<option value="05">05</option>
						<option value="10">10</option>
						<option value="15">15</option>
						<option value="20">20</option>
						<option value="25">25</option>
						<option value="30">30</option>
						<option value="35">35</option>
						<option value="40">40</option>
						<option value="45">45</option>
						<option value="50">50</option>
						<option value="55">55</option>
					</select>
					<select name="talk_end_meridian_N[]">
						<option value="am">am</option>
						<option value="pm">pm</option>
					</select>
				</div>
				<div class="talk_place">
					<label for="talk_place_N[]"><?php esc_html_e('Place/room', 'event-talks'); ?></label>
					<input type="text" class="talk_place" name="talk_place_N[]" value="">
				</div>
				<div class="talk_speaker">
					<label for="talk_speaker_N[]"><?php esc_html_e('Speaker(s)', 'event-talks'); ?></label>
					<input type="text" class="talk_speaker" name="talk_speaker_N[]" value="">
				</div>
			</div>
		</div>
		<div class="control_container">
			<div class="button add_talk"><?php esc_html_e('Add Talk', 'event-talks'); ?></div>
			<div class="button remove_talk"><?php esc_html_e('Remove Talk', 'event-talks'); ?></div>
		</div>
	</div>
</div>
<?php endif;