<?php
get_header();
	global $px_node, $px_theme_option, $px_event_meta;
	$px_layout = '';
	$px_counter_events=1;
 while ( have_posts() ) : the_post();
 	$post_xml = get_post_meta($post->ID, "px_event_meta", true);	
	if ( $post_xml <> "" ) {
		$px_event_meta = new SimpleXMLElement($post_xml);
		$px_layout = $px_event_meta->sidebar_layout->px_layout;
		if ( $px_layout == "left") {
			$px_layout = "col-md-9";
		}
		else if ( $px_layout == "right" ) {
			$px_layout = "col-md-9";
		}
		else {
			$px_layout = "col-md-12";
		}
  	}
	$hours = '00';
	$mints = '00';
	$px_event_from_date = get_post_meta($post->ID, "px_event_from_date", true); 
	$px_event_from_date_time = get_post_meta($post->ID, "px_event_from_date_time", true);
	$year_event = date("Y", strtotime($px_event_from_date));
	$month_event = date("m", strtotime($px_event_from_date));
	$date_event = date("d", strtotime($px_event_from_date));
		$width = 1098;
		$height = 260;
		$image_url = px_get_post_img_src($post->ID, $width, $height);
		px_enqueue_countdown_script();
	?>
     <?php if ( $px_event_meta->sidebar_layout->px_layout <> '' and $px_event_meta->sidebar_layout->px_layout <> "none" and $px_event_meta->sidebar_layout->px_layout == 'left') : ?>
                <aside class="col-md-3">
                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_event_meta->sidebar_layout->px_sidebar_left) ) : endif; ?>
                 </aside>
        <?php wp_reset_query(); endif; ?>
     <div class="<?php echo $px_layout;?> lightbox">
     	<?php px_page_title();?>
                    	<div class="element_size_100">
                        	<div class="match-detail">
                                <!-- Featued Event -->
                                <div class="featured-event" <?php if($image_url <> ''){?>style="background-image: url(<?php echo $image_url;?>)"<?php }?>>
                                	<div class="featured-inn">
                                        <div class="pix-sc-team">
                                            <ul>
                                                <?php if(isset($px_event_meta->var_pb_event_team1) and $px_event_meta->var_pb_event_team1 <> '0' and $px_event_meta->var_pb_event_team1 <> ''){?>
                                                <li>
                                                <figure>
                                                        <?php
                                                        $team1_row = px_get_term_object($px_event_meta->var_pb_event_team1);
                                                        $team_img1 = px_team_data_front($team1_row->term_id);
                                                        if($team_img1[0] <> ''){
                                                        ?>
                                                            <img alt="" src="<?php echo $team_img1[0];?>">
                                                        <?php }?>
                                                    </figure>
                                                </li>
                                                <?php }?>
                                                <li class="sec-section">
                                                   <h2><?php the_title();?></h2>
                                                </li>
                                                <?php if(isset($px_event_meta->var_pb_event_team2) and $px_event_meta->var_pb_event_team2 <> '0' and $px_event_meta->var_pb_event_team2 <> ''){?>
                                                <li>
                                                    <figure>
                                                        <?php
                                                        $team2_row = px_get_term_object($px_event_meta->var_pb_event_team2);
                                                        $team_img2 = px_team_data_front($team2_row->term_id);
                                                        if($team_img2[0] <> ''){
                                                        ?>
                                                            <img alt="" src="<?php echo $team_img2[0];?>">
                                                        <?php }?>
                                                    </figure>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </div>  
                                        <header class="pix-cont-title">
                                            <h2 class="pix-section-title"><span>
                                            <?php if(isset($px_event_meta->event_time_title) && $px_event_meta->event_time_title <> ''){echo $px_event_meta->event_time_title.' ';}
                                                    if ( isset($px_event_meta->event_all_day) && $px_event_meta->event_all_day != "on" ) {
                                                        echo $px_event_meta->event_time;
                                                    }else{
                                                        _e("All",'Kings Club') . printf( __("%s day",'Kings Club'), ' ');
                                                    }
                                                 ?>
                                            </span></h2>
                                        </header>
                                        <div class="bottom-event-panel">
                                        	<?php 
												if($px_theme_option['trans_days'] and $px_theme_option['trans_days'] <> ''){
													$trans_days=$px_theme_option['trans_days'];
												}else{
													$trans_days = __('Days','Kingsclub');
												}
												if($px_theme_option['trans_hours'] and $px_theme_option['trans_hours'] <> ''){
													$trans_hours=$px_theme_option['trans_hours'];
												}else{
													$trans_hours = __('Hours','Kingsclub');
												}
												if($px_theme_option['trans_minutes'] and $px_theme_option['trans_minutes'] <> ''){
													$trans_minutes=$px_theme_option['trans_minutes'];
												}else{
													$trans_minutes = __('Minutes','Kingsclub');
												}
												if($px_theme_option['trans_seconds'] and $px_theme_option['trans_seconds'] <> ''){
													$trans_seconds=$px_theme_option['trans_seconds'];
												}else{
													$trans_seconds = __('Seconds','Kingsclub');
												}
                                                $dateBefore = date('m/d/Y', strtotime($px_event_from_date.' '.$px_event_meta->event_time));
												$dateAfter = date('m/d/Y H:i');
 													if(strtotime($px_event_from_date_time) > strtotime($dateAfter)){
														if ( $px_event_meta->event_all_day != "on" ) {
															$hours = date("H", strtotime($px_event_from_date_time));
															$mints = date("i", strtotime($px_event_from_date_time));										
														} else {
															$hours = '00';
															$mints = '00';
														}
														 ?>
                                                         <?php $random_id = px_generate_random_string();?>
                                                        <div class="defaultCountdown" id="defaultCountdown<?php echo $random_id;?>"></div>
                                                        <script>
                                                        jQuery(document).ready(function($) {
                                                            px_event_countdown('<?php echo $year_event;?>','<?php echo $month_event;?>','<?php echo $date_event;?>',<?php echo $hours;?>,<?php echo $mints;?>,'<?php echo $random_id;?>','<?php echo $trans_days; ?>','<?php echo $trans_hours; ?>','<?php echo $trans_minutes; ?>','<?php echo $trans_seconds; ?>');
                                                        });
                                                        </script>
                                                    <?php } else {?>
                                                    <div class="match-result">
                                                        <span>
                                                            <big><?php echo $px_event_meta->event_score;?></big>
                                                        </span>
                                                    </div>
                                                   <?php }?>
                                            </div>
                                            <div class="option-sec">
                                                <ul class="post-options">
                                                <?php
													if(isset($px_event_meta->var_pb_event_author) && $px_event_meta->var_pb_event_author == 'on'){
												 ?>
                                                <li><i class="fa fa-user"></i><time><?php  the_author();?></time></li>
                                                <?php } ?>
                                                    <li><i class="fa fa-calendar"></i><time><?php echo date_i18n(get_option('date_format'), strtotime($px_event_from_date));?></time></li>
                                                    <li><i class="fa fa-map-marker"></i><?php echo $px_event_meta->event_address;?></li>
                                                </ul>
                                                <?php if($px_event_meta->event_ticket_options <> ''){?> 
                                                    <a <?php if(isset($px_event_meta->event_ticket_color) && $px_event_meta->event_ticket_color <> ''){?>style=" background-color: <?php echo $px_event_meta->event_ticket_color;?>"<?php }?> class="btn pix-btn-open" href="<?php echo $px_event_meta->event_buy_now;?>"> <?php if(isset($px_event_meta->event_ticket_options) && $px_event_meta->event_ticket_options <> ''){echo $px_event_meta->event_ticket_options;}?></a>
                                                <?php }?>
                                            </div>
                                        </div>
                                </div>
                                <!-- Featued Event Close -->
                                <?php if(isset($px_event_meta->event_summary) && trim($px_event_meta->event_summary) <> ''){?>
                                    <!-- Match Info -->
                                    <div class="match-info">
                                        <?php echo'<p>'.$px_event_meta->event_summary.'</p>';?>
                                    </div>
                                    <!-- Match Info CLose -->
                                <?php }?>
                                <div class="pix-content-wrap">
                                    <div class="detail-text rich_editor_text">
										   <?php 
												the_content();
												wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'GreenPeace' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
                                           ?>
                                       </div>    
                                        <!-- Gallery Section -->
                                        <?php if(isset($px_event_meta->event_gallery) && $px_event_meta->event_gallery <> ''){?>
                                            <div class="element_size_100">
                                                <?php px_single_gallery($px_event_meta->event_gallery);?>
                                            </div>
                                         <?php 
                                        	}
                                         ?>
                                        <!-- Gallery Section Close -->
                                        <!-- Share Post -->
                                        <div class="share-post">
											<?php 
                                                if ($px_event_meta->event_social_sharing == "on"){
                                                    px_social_share();
                                                }
                                            ?>
                                        </div>
                                        <!-- Share Post Close -->
                                        <div class="prev-nex-btn">
											<?php  px_next_prev_custom_links('events');?>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="element_size_100">
                       		<?php 
  								if(isset($px_event_meta->var_pb_event_author) && $px_event_meta->var_pb_event_author == 'on'){
 									px_author_description();
								}
								// comments_template('', true); 
							?>
                        </div>
     	</div>
		<?php
        if ( $px_event_meta->sidebar_layout->px_layout <> '' and $px_event_meta->sidebar_layout->px_layout <> "none" and $px_event_meta->sidebar_layout->px_layout == 'right') : ?>
            <aside class="col-md-3">
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($px_event_meta->sidebar_layout->px_sidebar_right) ) : endif; ?>
             </aside>
        <?php 
        wp_reset_query();
    endif;	
 endwhile;
get_footer();