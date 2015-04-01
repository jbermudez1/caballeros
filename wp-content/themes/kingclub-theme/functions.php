<?php
    /* add action filter and theme support on theme setup */
	add_action( 'after_setup_theme', 'px_theme_setup' );
	function px_theme_setup() {
		/* Add theme-supported features. */		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();
		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain('Kings Club', get_template_directory() . '/languages');
		
		if (!isset($content_width)){
			$content_width = 1160;
		}

		$args = array('default-color' => '','default-image' => '',);
		add_theme_support('custom-background', $args);
		add_theme_support('custom-header', $args);
		// This theme uses post thumbnails
		add_theme_support('post-thumbnails');
		// Add default posts and comments RSS feed links to head
		add_theme_support('automatic-feed-links');
		// Post Formats
		add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
	) );
		/* Add custom actions. */
		global $pagenow;
		
		if (is_admin() && isset($_GET['activated']) && $pagenow == 'themes.php'){
			
			
			if(!get_option('px_theme_option')){
				add_action('admin_head', 'px_activate_widget');
				add_action('init', 'px_activation_data');
				wp_redirect( admin_url( 'admin.php?page=px_demo_importer' ) );
			}
		}

		if (!session_id()){
			add_action('init', 'session_start');
		}
		
		add_action('init', 'px_register_my_menus' );
		add_action('admin_enqueue_scripts', 'px_admin_scripts_enqueue');
		add_action('wp_enqueue_scripts', 'px_front_scripts_enqueue');
 		add_action('pre_get_posts', 'px_get_search_results');
		add_action('widgets_init', create_function('', 'return register_widget("px_widget_facebook");') );
		add_action('widgets_init', create_function('', 'return register_widget("px_gallery");'));
		add_action('widgets_init', create_function('', 'return register_widget("recentposts");') );
		add_action('widgets_init', create_function('', 'return register_widget("px_fixture_countdown");') );
		add_action('widgets_init', create_function('', 'return register_widget("px_twitter_widget");'));
		add_action('widgets_init', create_function('', 'return register_widget("px_pointstable");'));
		add_action('widgets_init', create_function('', 'return register_widget("px_MailChimp_Widget");') );
		/* Add custom filters. */
		add_filter('widget_text', 'do_shortcode');
		add_filter('the_password_form', 'px_password_form' );
		add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
		add_filter('wp_page_menu','px_add_menuid');
		add_filter('wp_page_menu', 'px_remove_div' );
		add_filter('nav_menu_css_class', 'px_add_parent_css', 10, 2);
		add_filter('pre_get_posts', 'px_change_query_vars');
		add_filter('user_contactmethods','px_contact_options',10,1);
		$home = get_page_by_title( 'Home' );
		if($home <> '' && get_option( 'page_on_front' ) == "0"){
			update_option( 'page_on_front', $home->ID );
			update_option( 'show_on_front', 'page' );
		}
	}
	
	if ( ! function_exists( 'px_register_required_plugins' ) ) { 
	// tgm class for (internal and WordPress repository) plugin activation start
	require_once dirname( __FILE__ ) . '/include/class-tgm-plugin-activation.php';
	add_action( 'tgmpa_register', 'px_register_required_plugins' );
	function px_register_required_plugins() {
		/**
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = array(
			// This is an example of how to include a plugin from the WordPress Plugin Repository
			
			array(
				'name'     				=> 'Revolution Slider',
				'slug'     				=> 'revslider',
				'source'   				=> get_template_directory_uri() . '/include/plugins/revslider.zip', 
				'required' 				=> false, 
				'version' 				=> '',
				'force_activation' 		=> false,
				'force_deactivation' 	=> false,
				'external_url' 			=> '',
			),
			array(
				'name' 		=> 'Contact Form 7',
				'slug' 		=> 'contact-form-7',
				'required' 	=> false,
			),
			array(
				'name' 		=> 'Woocommerce',
				'slug' 		=> 'woocommerce',
				'required' 	=> false,
			),
			
	
		);
		// Change this to your theme text domain, used for internationalising strings
		$theme_text_domain = 'Kings Club';
		/**
		 * Array of configuration settings. Amend each line as needed.
		 * If you want the default strings to be available under your own theme domain,
		 * leave the strings uncommented.
		 * Some of the strings are added into a sprintf, so see the comments at the
		 * end of each line for what each argument will be.
		 */
		$config = array(
			'domain'       		=> 'Kings Club',         	// Text domain - likely want to be the same as your theme.
			'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
			'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
			'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
			'menu'         		=> 'install-required-plugins', 	// Menu slug
			'has_notices'      	=> true,                       	// Show admin notices or not
			'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
			'message' 			=> '',							// Message to output right before the plugins table
			'strings'      		=> array(
				'page_title'                       			=> __( 'Install Required Plugins', 'Kings Club' ),
				'menu_title'                       			=> __( 'Install Plugins', 'Kings Club' ),
				'installing'                       			=> __( 'Installing Plugin: %s', 'Kings Club' ), // %1$s = plugin name
				'oops'                             			=> __( 'Something went wrong with the plugin API.', 'Kings Club' ),
				'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
				'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
				'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
				'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
				'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
				'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
				'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
				'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
				'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
				'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
				'return'                           			=> __( 'Return to Required Plugins Installer', 'Kings Club' ),
				'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'Kings Club' ),
				'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'Kings Club' ), // %1$s = dashboard link
				'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
			)
		);
		tgmpa( $plugins, $config );
	}
	// tgm class for (internal and WordPress repository) plugin activation end
	}

	/* adding custom images while uploading media start */
	
	// Banner, Blog Large
	add_image_size('px_media_1', 768, 403, true);
	// Spot Light, Gallery
	add_image_size('px_media_2', 470, 353, true);
	// Popular Players
	add_image_size('px_media_3', 390, 390, true);
	// Blog Medium, News
	add_image_size('px_media_4', 325, 244, true);
	// Admin scripts enqueue
	function px_admin_scripts_enqueue() {
		$template_path = get_template_directory_uri() . '/scripts/admin/media_upload.js';
		wp_enqueue_script('my-upload', $template_path, 
		array('jquery', 'media-upload', 'thickbox', 'jquery-ui-droppable', 'jquery-ui-datepicker', 'jquery-ui-slider', 'wp-color-picker'));
		wp_enqueue_script('custom_wp_admin_script', get_template_directory_uri() . '/scripts/admin/px_functions.js');
		wp_enqueue_style('custom_wp_admin_style', get_template_directory_uri() . '/css/admin/admin-style.css', array('thickbox'));
		wp_enqueue_style('custom_wp_admin_fontawesome_style', get_template_directory_uri() . '/css/admin/font-awesome.css', array('thickbox'));
		wp_enqueue_style('wp-color-picker');

	}

	// Backend functionality files
	require_once (TEMPLATEPATH . '/include/theme_activation.php');
	require_once (TEMPLATEPATH . '/include/admin_functions.php');
	require_once (TEMPLATEPATH . '/include/theme_colors.php');
 	require_once (TEMPLATEPATH . '/include/player.php');
	require_once (TEMPLATEPATH . '/include/pointtable.php');
	require_once (TEMPLATEPATH . '/include/event.php');
	require_once (TEMPLATEPATH . '/include/gallery.php');
	require_once (TEMPLATEPATH . '/include/page_builder.php');
	require_once (TEMPLATEPATH . '/include/post_meta.php');
	require_once (TEMPLATEPATH . '/include/widgets.php');
	require_once (TEMPLATEPATH . '/include/ical/iCalcreator.class.php');
	require_once (TEMPLATEPATH . '/include/mailchimpapi/mailchimpapi.class.php');
	require_once (TEMPLATEPATH . '/include/mailchimpapi/chimp_mc_plugin.class.php');

	
	/* Require Woocommerce */
	require_once (TEMPLATEPATH . '/include/config_woocommerce/config.php');
	require_once (TEMPLATEPATH . '/include/config_woocommerce/product_meta.php');
	/* Addmin Menu PX Theme Option */
	
	if (current_user_can('administrator')) {
		require_once (TEMPLATEPATH . '/include/theme_option.php');
		add_action('admin_menu', 'px_theme');
		function px_theme() {
			add_theme_page('PX Theme Option', 'PX Theme Option', 'read', 'px_theme_options', 'theme_option');
			add_theme_page( "PX Import Demo Data" , "Import Demo Data" ,'read', 'px_demo_importer' , 'px_demo_importer');
		}

	}
	$image_url = apply_filters( 'taxonomy-images-queried-term-image-url', '', array(
    'image_size' => 'medium'
    ) );

	// Template redirect in single Gallery and Slider page
	function px_slider_gallery_template_redirect(){
		
		if ( get_post_type() == "px_gallery" ) {
			global $wp_query;
			$wp_query->set_404();
			status_header( 404 );
			get_template_part( 404 );
			exit();
		}
	}

	// enque style and scripts
	function px_front_scripts_enqueue() {
		global $px_theme_option;
		
		if (!is_admin()) {
			//wp_enqueue_style('style_css', get_template_directory_uri() . '/style.css');
			wp_enqueue_style('style_css', get_stylesheet_uri());
			if ( isset($px_theme_option['color_switcher']) && $px_theme_option['color_switcher'] == "on" ) {
				wp_enqueue_style('color-switcher_css', get_template_directory_uri() . '/css/color-switcher.css');
			}
			wp_enqueue_style('prettyPhoto_css', get_template_directory_uri() . '/css/prettyphoto.css');
			wp_enqueue_style('bootstrap_css', get_template_directory_uri() . '/css/bootstrap.css');
			wp_enqueue_style('font-awesome_css', get_template_directory_uri() . '/css/font-awesome.css');

			// Enqueue stylesheet
			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script('jquery');
			wp_enqueue_script( 'wp-mediaelement' );
			wp_enqueue_script('bootstrap_js', get_template_directory_uri() . '/scripts/frontend/bootstrap.min.js', '', '', true);
			wp_enqueue_script('modernizr_js', get_template_directory_uri() . '/scripts/frontend/modernizr.js', '', '', true);
			wp_enqueue_script('prettyPhoto_js', get_template_directory_uri() . '/scripts/frontend/jquery.prettyphoto.js', '', '', true);
			wp_enqueue_script('functions_js', get_template_directory_uri() . '/scripts/frontend/functions.js', '0', '', false);
			
			
			if ( isset($px_theme_option['rtl_switcher']) && $px_theme_option['rtl_switcher'] == "on"){
				wp_enqueue_style('rtl_css', get_template_directory_uri() . '/css/rtl.css');
			}

			if ( isset($px_theme_option['responsive']) && $px_theme_option['responsive'] == "on") {
				echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">';
				wp_enqueue_style('responsive_css', get_template_directory_uri() . '/css/responsive.css');
			}
		}
	}
	function px_enqueue_flexslider_script(){
		wp_enqueue_style('flexslider_css', get_template_directory_uri() . '/css/flexslider.css');
		wp_enqueue_script('flexslider_js', get_template_directory_uri() . '/scripts/frontend/jquery.flexslider-min.js', '', '', true);
	}
	// cycle Script Enqueue
	function px_enqueue_cycle_script(){
		wp_enqueue_script('jquery.cycle2_js', get_template_directory_uri() . '/scripts/frontend/cycle2.js', '', '', true);
	}
	
	// rating script
	function px_enqueue_rating_style_script(){
		wp_enqueue_style('jRating_css', get_template_directory_uri() . '/css/jRating.jquery.css');
		wp_enqueue_script('jquery_rating_js', get_template_directory_uri() . '/scripts/frontend/jRating.jquery.js', '', '', true);
	}
	// Validation Script Enqueue
	function px_enqueue_validation_script(){
		wp_enqueue_script('jquery.validate.metadata_js', get_template_directory_uri() . '/scripts/admin/jquery.validate.metadata.js', '', '', true);
		wp_enqueue_script('jquery.validate_js', get_template_directory_uri() . '/scripts/admin/jquery.validate.js', '', '', true);
	}
	/* countdown enqueue */	
	function px_enqueue_countdown_script(){
		wp_enqueue_script('jquery.countdown_js', get_template_directory_uri() . '/scripts/frontend/jquery.countdown.js', '', '', true);
	}
		
	// add this share enqueue
	function px_addthis_script_init_method(){
		
		if( is_single()){
			wp_enqueue_script( 'px_addthis', 'http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e4412d954dccc64', '', '', true);
		}

	}
	// content class
	  
	  if ( ! function_exists( 'px_meta_content_class' ) ) {
		  function px_meta_content_class(){
			  global $px_meta_page;
			  
			  if ( $px_meta_page->sidebar_layout->px_layout == '' or $px_meta_page->sidebar_layout->px_layout == 'none' ) {
				  $content_class = "col-md-12";
				  
			  } else
			  if ( $px_meta_page->sidebar_layout->px_layout <> '' and $px_meta_page->sidebar_layout->px_layout == 'right' ) {
				  $content_class = "col-md-9";
				  
			  } else
			  if ( $px_meta_page->sidebar_layout->px_layout <> '' and $px_meta_page->sidebar_layout->px_layout == 'left' ) {
				  $content_class = "col-md-9";
				  
			  } else
			  if ( $px_meta_page->sidebar_layout->px_layout <> '' and ($px_meta_page->sidebar_layout->px_layout == 'both' or $px_meta_page->sidebar_layout->px_layout == 'both_left' or $px_meta_page->sidebar_layout->px_layout == 'both_right')) {
				  $content_class = "col-md-6";
				 
			  } else {
				  $content_class = "col-md-12";
			  }

			  return $content_class;
		  }

	  }
	  
	  // Content pages Meta Class

if ( ! function_exists( 'px_default_pages_meta_content_class' ) ) { 

	function px_default_pages_meta_content_class($layout){

			if ( $layout == '' or $layout == 'none' ) {
	
				echo "col-md-12";
	
			}
	
			else if ( $layout <> '' and $layout == 'right' ) {
	
				echo "content-left col-md-9";
	
			}
	
			else if ( $layout <> '' and $layout == 'left' ) {
	
				echo "content-right col-md-9";
	
			}
	
			else if ( $layout <> '' and $layout == 'both' ) {
	
				echo "content-right col-md-6";
	
			}
	
		}	
	
	}
	  
	  
	  
	  
	  
	// Favicon and header code in head tag//
	function px_footer_settings() {
		global $px_theme_option;
		if(isset($px_theme_option['analytics']))
			echo htmlspecialchars_decode($px_theme_option['analytics']);
	}

	/* Page Sub header title and subtitle */	
	function get_subheader_title(){
		global $post, $wp_query;
		$show_title=true;
  		$get_title = '';
		if (is_page() || is_single()) {
			
			if (is_page() ){
				$px_xmlObject = px_meta_page('px_page_builder');
				if (isset($px_xmlObject)) {
					if($px_xmlObject->page_title == "on"){
						echo '<h1 class="pix-page-title">' . get_the_title(). '</h1>';
					}
				}else{
					echo '<h1 class="pix-page-title">' . get_the_title(). '</h1>';
				}
			}elseif (is_single()) {
				
				$post_type = get_post_type($post->ID);
				if ($post_type == "events") {
					$post_type = "px_event_meta";
				} else if($post_type == "player"){
					$post_type = "player";
				} else {
					$post_type = "post";
				}
				$post_xml = get_post_meta($post->ID, $post_type, true);
				
				if ($post_xml <> "") {
					$px_xmlObject = new SimpleXMLElement($post_xml);
				}
				if (isset($px_xmlObject)) {
 					echo '<h1 class="pix-page-title px-single-page-title">' . get_the_title(). '</h1>';
				}else{
					echo '<h1 class="pix-page-title px-single-page-title">' . get_the_title(). '</h1>';
 				}
			}
			
		} else {
		?>
 			<h1 class="pix-page-title"><?php px_post_page_title(); ?></h1>
 		 <?php 
		}

	}



	// search varibales start
	function px_get_search_results($query) {
		
		if ( !is_admin() and (is_search())) {
			$query->set( 'post_type', array('post', 'events', 'player') );
			remove_action( 'pre_get_posts', 'px_get_search_results' );
		}

	}

	// Filter shortcode in text areas
	
	if ( ! function_exists( 'px_textarea_filter' ) ) {
		
		function px_textarea_filter($content=''){
			return do_shortcode($content);
		}

	}

	// woocommerce ajax add to Cart 
	function woocommerce_header_add_to_cart_fragment( $fragments ) {
		
		if ( class_exists( 'woocommerce' ) ){
			global $woocommerce;
			ob_start();
			?>
            <div class="cart-sec">
                <a href="<?php  echo $woocommerce->cart->get_cart_url(); ?>">
                    <i class="fa fa-shopping-cart"></i><span><?php  echo $woocommerce->cart->cart_contents_count; ?></span>
                </a>
            </div>
			<?php
			$fragments['div.cart-sec'] = ob_get_clean();
			return $fragments;
		}

	}
	// woocommerce default cart
	function px_woocommerce_header_cart() {
		
		if ( class_exists( 'woocommerce' ) ){
			global $woocommerce;
			?>
		<div class="cart-sec">
			<a href="<?php  echo $woocommerce->cart->get_cart_url(); ?>">
            	<i class="fa fa-shopping-cart"></i><span><?php  echo $woocommerce->cart->cart_contents_count; ?></span>
            </a>
		</div>
		<?php
		}

	}

	// Display navigation to next/previous for single posts
	
	if ( ! function_exists( 'px_next_prev_post' ) ) {
		
		function px_next_prev_post(){
 			global $post;
			posts_nav_link();
			// Don't print empty markup if there's nowhere to navigate.
			$previous = ( is_attachment() ) ? get_post( $post->post_parent ) :
			get_adjacent_post( false, '', true );
			$next     = get_adjacent_post( false, '', false );
			echo '<div class="prev-nex-btn">';
				previous_post_link( '%link', '<i class="fa fa-angle-double-left"></i>' );
				next_post_link( '%link','<i class="fa fa-angle-double-right"></i>' );
			echo '</div>';
      		}

	}
	function px_posts_link_next_class($format){
		 $format = str_replace('href=', 'class="post-next" href=', $format);
		 return $format;
	}
	add_filter('next_post_link', 'px_posts_link_next_class');
	
	function px_posts_link_prev_class($format) {
		 $format = str_replace('href=', 'class="post-prev" href=', $format);
		 return $format;
	}
	add_filter('previous_post_link', 'px_posts_link_prev_class');
 	//	Add Featured/sticky text/icon for sticky posts.
 	if ( ! function_exists( 'px_featured()' ) ) {
		function px_featured(){
			global $px_transwitch,$px_theme_option;
		
			if ( is_sticky() ){
				?>
                <li class="featured">
                    <?php 
                        if(!isset($px_theme_option) || (!isset($px_theme_option['lotrans_featuredgo']))){
                                _e('Featured','Kings Club');
                        } else {
                            if(isset($px_theme_option['trans_switcher']) && $px_theme_option['trans_switcher'] == "on"){
                                _e('Featured','Kings Club');
                            } else {
                                if(isset($px_theme_option['trans_featured']))
                                    echo $px_theme_option['trans_featured'];
                            }
                        }
                    ?>		         
                 </li>
		<?php
			}

		}

	}

	/* display post page title */	
	function px_post_page_title(){
		
		if ( is_author() ) {
			global $author;
			$userdata = get_userdata($author);
			echo __('Author', 'Kings Club') . " " . __('Archives', 'Kings Club') . ": ".$userdata->display_name;
		}
 		elseif ( is_tag() || is_tax('event-tag') || is_tax('portfolio-tag') || is_tax('sermon-tag') ) {
			echo __('Tags', 'Kings Club') . " " . __('Archives', 'Kings Club') . ": " . single_cat_title( '', false );
		}
 		elseif ( is_category() || is_tax('event-category') || is_tax('portfolio-category')  || is_tax('season-category')  || 
		is_tax('sermon-series')  || is_tax('sermon-pastors') ) {
			echo __('Categories', 'Kings Club') . " " . __('Archives', 'Kings Club') . ": " . single_cat_title( '', false );
		}
 		elseif( is_search()){
			printf( __( 'Search Results %1$s %2$s', 'Kings Club' ), ': ','<span>' . get_search_query() . '</span>' );
		}
 		elseif ( is_day() ) {
			printf( __( 'Daily Archives: %s', 'Kings Club' ), '<span>' . get_the_date() . '</span>' );
		}
 		elseif ( is_month() ) {
			printf( __( 'Monthly Archives: %s', 'Kings Club' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'Kings Club' ) ) . '</span>' );
		}
 		elseif ( is_year() ) {
			printf( __( 'Yearly Archives: %s', 'Kings Club' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'Kings Club' ) ) . '</span>' );
		}
 		elseif ( is_404()){
			_e( 'Error 404', 'Kings Club' );
		}
 		

	}

	// Custom excerpt function 
	function px_get_the_excerpt($limit,$readmore = '', $dottedline = '') {
		global $px_theme_option;
		$readmore = '';
		if(isset($px_theme_option['trans_switcher']) && $px_theme_option['trans_switcher'] == "on"){
			$readmore = __('Read More','Kings Club');
		} else {
			if(isset($px_theme_option['trans_read_more']))
				$readmore = $px_theme_option['trans_read_more'];
		}
		if(!isset($limit) || $limit == ''){ $limit = '255';}
		$get_the_excerpt = trim(preg_replace('/<a[^>]*>(.*)<\/a>/iU', '', get_the_excerpt()));
		
		if(isset($dottedline) && $dottedline <> ''){
			echo '<p>'.substr($get_the_excerpt, 0, "$limit");
			echo $dottedline;	
			echo '</p>';
		} else {
			echo '<p>'.substr($get_the_excerpt, 0, "$limit").'</p>';
			if (strlen($get_the_excerpt) > "$limit") {
				
				if($readmore == "true"){
					echo '... <a href="' . get_permalink() . '" class="colr">' . $readmore . '</a>';
				}
				
	
			}
		}

	}

	// change the default query variable start
	function px_change_query_vars($query) {
		
		if (is_search() || is_home()) {
			
			if (empty($_GET['page_id_all']))$_GET['page_id_all'] = 1;
			$query->query_vars['paged'] = $_GET['page_id_all'];
		}
 		return $query;
		// Return modified query variables
	}

	/* custom pagination start */
	
	if ( ! function_exists( 'px_pagination' ) ) {
		function px_pagination($total_records, $per_page, $qrystr = '') {
			$html = '';
			$dot_pre = '';
			$dot_more = '';
			$previous = __("Previous",'Kings Club');
			if(isset($px_theme_option["trans_switcher"]) && $px_theme_option["trans_switcher"] == "on") { $previous = __("Previous",'Kings Club'); }elseif(isset($px_theme_option["trans_previous"]) && $px_theme_option["trans_previous"] <> ''){  $previous = $px_theme_option["trans_previous"];}
			$total_page = ceil($total_records / $per_page);
			$loop_start = $_GET['page_id_all'] - 2;
			$loop_end = $_GET['page_id_all'] + 2;
			
			if ($_GET['page_id_all'] < 3) {
				$loop_start = 1;
				
				if ($total_page < 5)$loop_end = $total_page; else $loop_end = 5;
			} else
			if ($_GET['page_id_all'] >= $total_page - 1) {
				
				if ($total_page < 5)$loop_start = 1; else $loop_start = $total_page - 4;
				$loop_end = $total_page;
			}

			
			if ($_GET['page_id_all'] > 1)$html .= "<li  class='prev'>
			<a href='?page_id_all=" . ($_GET['page_id_all'] - 1) . "$qrystr' ><i class='fa fa-long-arrow-left'></i>".__('Previous','Kings Club')."</a></li>";
			
			if ($_GET['page_id_all'] > 3 and $total_page > 5)$html .= "<li><a href='?page_id_all=1$qrystr'>1</a></li>";
			
			if ($_GET['page_id_all'] > 4 and $total_page > 6)$html .= "<li> <a>. . .</a> </li>";
			
			if ($total_page > 1) {
				for ($i = $loop_start; $i <= $loop_end; $i++) {
					
					if ($i <> $_GET['page_id_all'])$html .= "<li><a href='?page_id_all=$i$qrystr'>" . $i . "</a></li>"; else $html .= "<li>
					<span class='active'>" . $i . "</span></li>";
				}

			}
 			
			if ($loop_end <> $total_page and $loop_end <> $total_page - 1)$html .= "<li> <a>. . .</a> </li>";
			
			if ($loop_end <> $total_page)$html .= "<li><a href='?page_id_all=$total_page$qrystr'>$total_page</a></li>";
			
			if ($_GET['page_id_all'] < $total_records / $per_page)$html .= "<li class='next'><a href='?page_id_all=" . ($_GET['page_id_all'] + 1) . "$qrystr' >".__('Next','Kings Club')."<i class='fa fa-long-arrow-right'></i></a></li>";
			return $html;
		}

	}
	// pagination end
	// Social Share Function
	
	if ( ! function_exists( 'px_social_share' ) ) {
		function px_social_share($icon_type = '', $title='true') {
			global $px_theme_option;
			px_addthis_script_init_method();
			if (isset($px_theme_option['social_share']) && $px_theme_option['social_share'] == "on"){
				if(isset($px_theme_option['trans_switcher']) && $px_theme_option["trans_switcher"] == "on") { $html1= __("Share this post",'Kings Club'); }else{  $html1 =  $px_theme_option["trans_share_this_post"];}
				$html = '';
					$html .='<ul class="social-network">';
					$html .='<a class="addthis_button_compact btn share-now pix-bgcolr"><i class="fa fa-share-square-o"></i>'.$html1.'</a>';
					$html .='</ul>';
					
					echo $html;
				
				 
			}
		}

	}

	// Social network
	
	if ( ! function_exists( 'px_social_network' ) ) {
		function px_social_network($icon_type='',$tooltip = ''){
			global $px_theme_option;
			$tooltip_data='';
			if($icon_type=='large'){
				$icon = '2x';
			} else {
				$icon = 'icon';
			}
			echo '<div class="followus">';
			if(isset($tooltip) && $tooltip <> ''){
				$tooltip_data='data-placement-tooltip="tooltip"';
			}
  			if ( isset($px_theme_option['social_net_url']) and count($px_theme_option['social_net_url']) > 0 ) {
				$i = 0;
				foreach ( $px_theme_option['social_net_url'] as $val ){
					if($val != ''){ ?>
                    	<a title="" href="<?php  echo $val; ?>" data-original-title="<?php  echo $px_theme_option['social_net_tooltip'][$i]; ?>" data-placement="top" <?php  echo $tooltip_data; ?> class="colrhover"  target="_blank">
						<?php  if($px_theme_option['social_net_awesome'][$i] <> '' && isset($px_theme_option['social_net_awesome'][$i])){ ?> 
                    <i class="fa <?php  echo $px_theme_option['social_net_awesome'][$i]; ?> <?php  echo $icon; ?>"></i><?php  } else { ?>
                    <img src="<?php  echo $px_theme_option['social_net_icon_path'][$i]; ?>" alt="<?php  echo $px_theme_option['social_net_tooltip'][$i]; ?>" /><?php  } ?></a>
					<?php 
					}
					$i++;
				}
			}
 			echo '</div>';
		}
	}

	// Post image attachment function
	function px_attachment_image_src($attachment_id, $width, $height) {
		$image_url = wp_get_attachment_image_src($attachment_id, array($width, $height), true);
		
		if ($image_url[1] == $width and $image_url[2] == $height); else        
		$image_url = wp_get_attachment_image_src($attachment_id, "full", true);
		$parts = explode('/uploads/',$image_url[0]);
		
		if ( count($parts) > 1 ) return $image_url[0];
	}

	// Post image attachment source function
	function px_get_post_img_src($post_id, $width, $height) {
		
		if(has_post_thumbnail()){
			$image_id = get_post_thumbnail_id($post_id);
			$image_url = wp_get_attachment_image_src($image_id, array($width, $height), true);
			
			if ($image_url[1] == $width and $image_url[2] == $height) {
				return $image_url[0];
			} else {
				$image_url = wp_get_attachment_image_src($image_id, "full", true);
				return $image_url[0];
			}

		}

	}

	// Get Post image attachment
	function px_get_post_img($post_id, $width, $height) {
		$image_id = get_post_thumbnail_id($post_id);
		$image_url = wp_get_attachment_image_src($image_id, array($width, $height), true);
		if ($image_url[1] == $width and $image_url[2] == $height) {
			return get_the_post_thumbnail($post_id, array($width, $height));
		} else {
			return get_the_post_thumbnail($post_id, "full");
		}
	}
	// custom sidebar start
	$px_theme_option = get_option('px_theme_option');
	
	if ( isset($px_theme_option['sidebar']) and !empty($px_theme_option['sidebar'])) {
		foreach ( $px_theme_option['sidebar'] as $sidebar ){
			register_sidebar(
				array(
					'name' => $sidebar,
					'id' => $sidebar,
					'description' => 'This widget will be displayed on right side of the page.',
					'before_widget' => '<div class="widget %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<header class="pix-heading-title"><h2 class="pix-section-title heading-color">',
					'after_title' => '</h2></header>'
				)
			);
		}

	}
	register_sidebar( 
		array(
			'name' => 'Sidebar Widget',
			'id' => 'sidebar-1',
			'description' => 'This Widget Show the Content in Blog Listing page.',
			'before_widget' => '<div class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<header class="pix-heading-title"><h2 class="pix-section-title">',
			'after_title' => '</h2></header>'
		) 
	);
	// Home top widget area
	register_sidebar( 
		array(
			'name' => 'Home Top Widget',
			'id' => 'home-top-widget',
			'description' => 'This Widget Show the Content in Hom Page',
			'before_widget' => '<div class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<header class="pix-heading-title"><h2 class="pix-section-title">',
			'after_title' => '</h2></header>'
		) 
	);
	//footer widget

	register_sidebar( array(
	
		'name' => 'Footer Widget',
	
		'id' => 'footer-widget',
	
		'description' => 'This Widget Show the Content in Footer Area.',
	
		'before_widget' => '<div class="widget %2$s">',
	
		'after_widget' => '</div>',
	
		'before_title' => '<header class="px-heading-title"><h2 class="px-section-title">',
	
		'after_title' => '</h2></header>'
	
	) );
	
	register_sidebar( array(
	
		'name' => 'Header Advertisement Widget',
	
		'id' => 'header-advertisement-widget',
	
		'description' => 'This Widget Show the Content in Header Area.',
	
		'before_widget' => '<div class="widget %2$s">',
	
		'after_widget' => '</div>',
	
		'before_title' => '<header class="px-heading-title"><h2 class="px-section-title">',
	
		'after_title' => '</h2></header>'
	
	) );
	register_sidebar( array(
	
		'name' => 'Footer Advertisement Widget',
	
		'id' => 'footer-advertisement-widget',
	
		'description' => 'This Widget Show the Content in Footer Area.',
	
		'before_widget' => '<div class="widget %2$s">',
	
		'after_widget' => '</div>',
	
		'before_title' => '<header class="px-heading-title"><h2 class="px-section-title">',
	
		'after_title' => '</h2></header>'
	
	) );

	
	
	function px_add_menuid($ulid) {
		return preg_replace('/<ul>/', '<ul id="menus">', $ulid, 1);
	}
	function px_remove_div ( $menu ){
		return preg_replace( array( '#^<div[^>]*>#', '#</div>$#' ), '', $menu );
	}

	
	function px_register_my_menus() {
		register_nav_menus(array('main-menu'  => __('Main Menu','Kings Club') )  );
	}

	
	function px_add_parent_css($classes, $item) {
		global $px_menu_children;
		
		if ($px_menu_children)        $classes[] = 'parent';
		return $classes;
	}
	
	// map shortcode with various options
		if ( ! function_exists( 'px_map_page' ) ) {
			function px_map_page(){
				global $px_node, $px_counter_node;
  				if ( !isset($px_node->map_lat) or $px_node->map_lat == "" ) { $px_node->map_lat = 0; }
				if ( !isset($px_node->map_lon) or $px_node->map_lon == "" ) { $px_node->map_lon = 0; }
				if ( !isset($px_node->map_zoom) or $px_node->map_zoom == "" ) { $px_node->map_zoom = 11; }
				if ( !isset($px_node->map_info_width) or $px_node->map_info_width == "" ) { $px_node->map_info_width = 200; }
				if ( !isset($px_node->map_info_height) or $px_node->map_info_height == "" ) { $px_node->map_info_height = 100; }
				if ( !isset($px_node->map_show_marker) or $px_node->map_show_marker == "" ) { $px_node->map_show_marker = 'true'; }
				if ( !isset($px_node->map_controls) or $px_node->map_controls == "" ) { $px_node->map_controls = 'false'; }
				if ( !isset($px_node->map_scrollwheel) or $px_node->map_scrollwheel == "" ) { $px_node->map_scrollwheel = 'true'; }
				if ( !isset($px_node->map_draggable) or $px_node->map_draggable == "" )  { $px_node->map_draggable = 'true'; }
				if ( !isset($px_node->map_type) or $px_node->map_type == "" ) { $px_node->map_type = 'ROADMAP'; }
				if ( !isset($px_node->map_info)) { $px_node->map_info = ''; }
				if( !isset($px_node->map_marker_icon)){ $px_node->map_marker_icon = ''; }
				if( !isset($px_node->map_title)){ $px_node->map_title ='';}
				if( !isset($px_node->map_element_size) or $px_node->map_element_size == ""){ $px_node->map_element_size ='default';}
				if( !isset($px_node->map_height) || empty($px_node->map_height)){ $px_node->map_height ='360';}
 				$map_show_marker = '';
				if ( $px_node->map_show_marker == "true" ) { 
					$map_show_marker = " var marker = new google.maps.Marker({
								position: myLatlng,
								map: map,
								title: '',
								icon: '".$px_node->map_marker_icon."',
								shadow:''
							});
					";
				}
				$html = '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=true"></script>';
				
				$html .= '<div class="element_size_'.$px_node->map_element_size.' px-map">';
					$html .= '<div class="contact-us rich_editor_text"><div class="map-sec">';
					
					$html .= '<div class="mapcode iframe mapsection gmapwrapp" id="map_canvas'.$px_counter_node.'" style="height:'.$px_node->map_height.'px;"> </div>';
				$html .= '</div>';
				
				if($px_node->map_title <> ''){$html .= '<h2 class="pix-post-title">'.$px_node->map_title.'</h2>'; }

                   $html .= '<p>'.$px_node->map_text.'</p>';
				   $html .= '</div>';
				$html .= '</div>';   
				//mapTypeId: google.maps.MapTypeId.".$px_node->map_type." ,
				if($px_node->map_type == "STYLED"){
					$px_node->map_type = 'ROADMAP';
					$html .= "<script type='text/javascript'>
							function initialize() {
								var styles = [
									{
									  stylers: [
										{ hue: '#000000' },
										{ saturation: -100 }
									  ]
									},{
									  featureType: 'road',
									  elementType: 'geometry',
									  stylers: [
										{ lightness: -40 },
										{ visibility: 'simplified' }
									  ]
									},{
									  featureType: 'road',
									  elementType: 'labels',
									  stylers: [
										{ visibility: 'on' }
									  ]
									}
								  ];
								var styledMap = new google.maps.StyledMapType(styles,
								{name: 'Styled Map'});
								var myLatlng = new google.maps.LatLng(".$px_node->map_lat.", ".$px_node->map_lon.");
								var mapOptions = {
									zoom: ".$px_node->map_zoom.",
									panControl: false,
									scrollwheel: ".$px_node->map_scrollwheel.",
									draggable: ".$px_node->map_draggable.",
									center: myLatlng,
									disableDefaultUI: true,
									disableDefaultUI: ".$px_node->map_controls.",
									mapTypeControlOptions: {
									  mapTypeIds: [google.maps.MapTypeId.ROADMAP.".$px_node->map_type.", 'map_style']
									}
								}
								var map = new google.maps.Map(document.getElementById('map_canvas".$px_counter_node."'), mapOptions);
								map.mapTypes.set('map_style', styledMap);
								map.setMapTypeId('map_style');
								var infowindow = new google.maps.InfoWindow({
									content: '".$px_node->map_info."',
									maxWidth: ".$px_node->map_info_width.",
									maxHeight:".$px_node->map_info_height.",
								});
								".$map_show_marker."
								//google.maps.event.addListener(marker, 'click', function() {
			
									if (infowindow.content != ''){
									  infowindow.open(map, marker);
									   map.panBy(1,-60);
									   google.maps.event.addListener(marker, 'click', function(event) {
										infowindow.open(map, marker);
			
									   });
									}
								//});
							}
						
						google.maps.event.addDomListener(window, 'load', initialize);
						</script>";
				}else{
					$html .= "<script type='text/javascript'>
						function initialize() {
							var myLatlng = new google.maps.LatLng(".$px_node->map_lat.", ".$px_node->map_lon.");
							var mapOptions = {
								zoom: ".$px_node->map_zoom.",
								scrollwheel: ".$px_node->map_scrollwheel.",
								draggable: ".$px_node->map_draggable.",
								center: myLatlng,
								mapTypeId: google.maps.MapTypeId.".$px_node->map_type." ,
								disableDefaultUI: ".$px_node->map_controls.",
							}
							var map = new google.maps.Map(document.getElementById('map_canvas".$px_counter_node."'), mapOptions);
							var infowindow = new google.maps.InfoWindow({
								content: '".$px_node->map_info."',
								maxWidth: ".$px_node->map_info_width.",
								maxHeight:".$px_node->map_info_height.",
							});
							".$map_show_marker."
							//google.maps.event.addListener(marker, 'click', function() {
		
								if (infowindow.content != ''){
								  infowindow.open(map, marker);
								   map.panBy(1,-60);
								   google.maps.event.addListener(marker, 'click', function(event) {
									infowindow.open(map, marker);
		
								   });
								}
							//});
						}
 						google.maps.event.addDomListener(window, 'load', initialize);
						</script>";
				}
				return $html;
			}
		}
	
	if (!function_exists('pixFill_comment')) :
	/**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own pixFill_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     */
	function pixFill_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		$args['reply_text'] = '<i class="fa fa-share"></i> Reply';
		switch ( $comment->comment_type ) :
		case '' :
			?>
        <li  <?php  comment_class(); ?> id="li-comment-<?php  comment_ID(); ?>">
            <div class="thumblist" id="comment-<?php  comment_ID(); ?>">
                <ul>
                    <li>
                        <figure>
                            <a href="#"><?php  echo get_avatar( $comment, 65 ); ?></a>
                        </figure>
                         <div class="text">
                          <header>
                                <?php  printf( __( '%s', 'Kings Club' ), sprintf( '<h5><a class="colrhover">%s</a></h5><br>', get_comment_author_link() ) ); 						/* translators: 1: date, 2: time */								printf( __( '<span>%1$s</span><br/>', 'Kings Club' ), get_comment_date());
	 							?>
                          </header>
                          <div class="bottom-comment">
							  <?php  comment_text(); ?>
                              <?php  edit_comment_link( __( '(Edit)', 'GreenPeace' ), ' ' ); ?>
                                    <?php  comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );                                	if ( $comment->comment_approved == '0' ) : ?>
                                    <div class="comment-awaiting-moderation colr">
                                        <?php  _e( 'Your comment is awaiting moderation.', 'GreenPeace' ); ?>
                                    </div>
                            <?php  endif; ?>
                           </div>
                        </div>
                    </li>
                </ul>
            </div>
         </li>
	<?php
    	break;
			case 'pingback'  :
			case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php  comment_author_link(); ?><?php  edit_comment_link( __( '(Edit)', 'Kings Club' ), ' ' ); ?></p>
		<?php
		break;
		endswitch;
		}
		endif;
			// password protect post/page
			
			if ( ! function_exists( 'px_password_form' ) ) {
				function px_password_form() {
					global $post,$px_theme_option;
					$label = 'pwbox-'.( empty( $post->ID ) ? rand() :
					$post->ID );
					$o = '<div class="password_protected single-password pix-content-wrap">
									<h5>' . __( "This post is password protected. To view it please enter your password below:",'Kings Club' ) . '</h5>';
									$o .= '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
												<label><input name="post_password" id="' . $label . '" type="password" size="20" /></label>
												<input class="backcolr" type="submit" name="Submit" value="'.__("Submit", "Kings Club").'" />
											</form></div>';
					return $o;
			}

		}

		// breadcrumb function
		
		if ( ! function_exists( 'px_breadcrumbs' ) ) {
			
			function px_breadcrumbs() {
				global $wp_query;
				/* === OPTIONS === */
				$text['home']     = 'Home';
				// text for the 'Home' link
				$text['category'] = '%s';
				// text for a category page
				$text['search']   = '%s';
				// text for a search results page
				$text['tag']      = '%s';
				// text for a tag page
				$text['author']   = '%s';
				// text for an author page
				$text['404']      = 'Error 404';
				// text for the 404 page
				$showCurrent = 1;
				// 1 - show current post/page title in breadcrumbs, 0 - don't show
				$showOnHome  = 1;
				// 1 - show breadcrumbs on the homepage, 0 - don't show
				$delimiter   = '';
				// delimiter between crumbs
				$before      = '<li class="pix-active">';
				// tag before the current crumb
				$after       = '</li>';
				// tag after the current crumb
				/* === END OF OPTIONS === */
				global $post,$px_theme_option;
				$current_page = __("Current Page",'Kings Club');;
				if(isset($px_theme_option["trans_switcher"]) && $px_theme_option["trans_switcher"] == "on") {  $current_page = __("Current Page",'Kings Club'); }else if(isset($px_theme_option["trans_currentpage"])){  $current_page = $px_theme_option["trans_currentpage"];}
				$homeLink = home_url() . '/';
				$linkBefore = '<li>';
				$linkAfter = '</li>';
				$linkAttr = '';
				$link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;
				$linkhome = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;
				
				if (is_home() || is_front_page()) {
					
					if ($showOnHome == "1") echo '<div class="breadcrumbs"><ul>'.$before.'<a href="' . $homeLink . '">' . $text['home'] . '</a>'.$after.'</ul></div>';
				} else {
					echo '<div class="breadcrumbs"><ul>' . sprintf($linkhome, $homeLink, $text['home']) . $delimiter;
					
					if ( is_category() ) {
						$thisCat = get_category(get_query_var('cat'), false);
						
						if ($thisCat->parent != 0) {
							$cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
							$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
							$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
							echo $cats;
						}

						echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;
					}

					elseif ( is_search() ) {
						echo $before . sprintf($text['search'], get_search_query()) . $after;
					}

					elseif ( is_day() ) {
						echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
						echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
						echo $before . get_the_time('d') . $after;
					}

					elseif ( is_month() ) {
						echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
						echo $before . get_the_time('F') . $after;
					}

					elseif ( is_year() ) {
						echo $before . get_the_time('Y') . $after;
					}

					elseif ( is_single() && !is_attachment() ) {
						
						if ( get_post_type() != 'post' ) {
							$post_type = get_post_type_object(get_post_type());
							$slug = $post_type->rewrite;
							printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
							
							if ($showCurrent == 1) echo $delimiter . $before . 'Current Page' . $after;
						} else {
							$cat = get_the_category();
							$cat = $cat[0];
							$cats = get_category_parents($cat, TRUE, $delimiter);
							
							if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
							$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
							$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
							echo $cats;
							
							if ($showCurrent == 1) echo $before .'Current Page' . $after;
						}

					}

					elseif ( !is_single() && !is_page() && get_post_type() <> '' && get_post_type() != 'post' && get_post_type() <> 'events' && get_post_type() <> 'player' && get_post_type() <> 'pointtable' && !is_404() ) {
						$post_type = get_post_type_object(get_post_type());
						echo $before . $post_type->labels->singular_name . $after;
					}

					elseif (isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy'])){
						$taxonomy = $taxonomy_category = '';
						$taxonomy = $wp_query->query_vars['taxonomy'];
						echo $before . $wp_query->query_vars[$taxonomy] . $after;
					}

					elseif ( is_page() && !$post->post_parent ) {
						
						if ($showCurrent == 1) echo $before . get_the_title() . $after;
					}

					elseif ( is_page() && $post->post_parent ) {
						$parent_id  = $post->post_parent;
						$breadcrumbs = array();
						while ($parent_id) {
							$page = get_page($parent_id);
							$breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
							$parent_id  = $page->post_parent;
						}

						$breadcrumbs = array_reverse($breadcrumbs);
						for ($i = 0; $i < count($breadcrumbs); $i++) {
							echo $breadcrumbs[$i];
							
							if ($i != count($breadcrumbs)-1) echo $delimiter;
						}

						
						if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
					}

					elseif ( is_tag() ) {
						echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
					}

					elseif ( is_author() ) {
						global $author;
						$userdata = get_userdata($author);
						echo $before . sprintf($text['author'], $userdata->display_name) . $after;
					}

					elseif ( is_404() ) {
						echo $before . $text['404'] . $after;
					}

					//echo "<pre>"; print_r($wp_query->query_vars); echo "</pre>";
					
					if ( get_query_var('paged') ) {
						// if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
						// echo __('Page') . ' ' . get_query_var('paged');
						// if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
					}

					echo '</ul></div>';
				}

			}

		}
 		
		if ( ! function_exists( 'px_logo' ) ) {
			function px_logo($logo_url, $log_width, $logo_height){
			?>
				<a href="<?php  echo home_url(); ?>">
                	<img src="<?php  echo $logo_url; ?>"  style="width:<?php  echo $log_width; ?>px; height:<?php  echo $logo_height; ?>px" 
                    alt="<?php  echo bloginfo('name'); ?>" />
                </a>
	 		<?php
			}

		}
		/*Top and Main Navigation*/
		if ( ! function_exists( 'px_navigation' ) ) {
			function px_navigation($nav='', $menus = 'menus'){
				//sf_mega_menu_walker
				//new px_mega_menu_walker()
				global $px_theme_option;
				// Menu parameters	
				if ( has_nav_menu( $nav ) ) {
				$defaults = array('theme_location' => "$nav",'menu' => '','container' => '','container_class' => '','container_id' => '','menu_class' => '','menu_id' => "$menus",'echo' => false,'fallback_cb' => 'wp_page_menu','before' => '','after' => '','link_before' => '','link_after' => '','items_wrap' => '<ul id="%1$s">%3$s</ul>','depth' => 0,'walker' => '');
				} else {
					$defaults = array('theme_location' => "primary",'menu' => '','container' => '','container_class' => '','container_id' => '','menu_class' => '','menu_id' => "$menus",'echo' => false,'fallback_cb' => 'wp_page_menu','before' => '','after' => '','link_before' => '','link_after' => '','items_wrap' => '<ul id="%1$s">%3$s</ul>','depth' => 0,'walker' => '');
				}
				
				echo do_shortcode(wp_nav_menu($defaults));
			}

		}
	  // Column shortcode with 2/3/4 column option even you can use shortcode in column shortcode
	  
	  if ( ! function_exists( 'px_column_page' ) ) {
		  function px_column_page(){
			  global $px_node;
			  $html = '<div class="element_size_'.$px_node->column_element_size.' column">';
			  $html .= do_shortcode($px_node->column_text);
			  $html .= '</div>';
			  echo $html;
		  }

	  }

  // Get post meta in xml form
  function px_meta_page($meta) {
	  global $px_meta_page;
	  $meta = get_post_meta(get_the_ID(), $meta, true);
	  if ($meta <> '') {
		  $px_meta_page = new SimpleXMLElement($meta);
		  return $px_meta_page;
	  }
	  
  }
  // woocommerce shop meta
  function px_meta_shop_page($meta, $id) {
	  global $px_meta_page;
	  $meta = get_post_meta($id, $meta, true);
		  if ($meta <> '') {
			  $px_meta_page = new SimpleXMLElement($meta);
			  return $px_meta_page;
		  }
	  }




function px_author_description(){
	if (get_the_author_meta('description')){ ?>
    	<!-- About Author -->
        <div class="pix-content-wrap">   
        	<div class="about-author">
                <!-- Thumbnail List Start -->
                <!-- Thumbnail List Item Start -->
                 <figure><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="float-left"><?php echo get_avatar(get_the_author_meta('user_email'), apply_filters('PixFill_author_bio_avatar_size', 90)); ?></a></figure>
                 <div class="text">
                    <h2><a class="colrhover" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author_meta('nicename'); ?></a></h2>
                    <span></span>
                    <p><?php the_author_meta('description'); ?></p>
                    <div class="followus">
                        <?php if(get_the_author_meta('flicker') <> ''){?><a href="<?php the_author_meta('flicker'); ?>"><i class="fa fa-flickr"></i></a><?php }?>
                        <?php if(get_the_author_meta('twitter') <> ''){?><a href="<?php the_author_meta('twitter'); ?>"><i class="fa fa-twitter"></i></a><?php }?>
                        <?php if(get_the_author_meta('facebook') <> ''){?><a href="<?php the_author_meta('facebook'); ?>"><i class="fa fa-facebook"></i></a><?php }?>
                        <?php if(get_the_author_meta('googleplus') <> ''){?><a href="<?php the_author_meta('googleplus'); ?>"><i class="fa fa-google-plus"></i></a><?php }?>
                        <?php if(get_the_author_meta('linkedin') <> ''){?><a href="<?php the_author_meta('linkedin'); ?>"><i class="fa fa-linkedin"></i></a><?php }?>
            		</div>
                </div>
            </div>
        </div>    
       <!-- About Author End -->
    <?php	 
	} 
}

//
 function px_next_prev_custom_links($post_type = 'pointable'){
	 	global $post;
		$previd = $nextid = '';
		$post_categoryy = '';	
		if($post_type == 'events'){
			$post_categoryy = 'event-category';	
		} else if($post_type == 'events'){
			$post_categoryy = 'season-category';	
		} else if($post_type == 'player'){
			$post_categoryy = 'team-category';	
		} else {
			$post_categoryy = 'category';	
		}
		
		$count_posts = wp_count_posts( "$post_type" )->publish;
		$px_postlist_args = array(
		   'posts_per_page'  => -1,
		   'order'           => 'ASC',
		   'post_type'       => "$post_type",
		); 
		$px_postlist = get_posts( $px_postlist_args );

		$ids = array();
		foreach ($px_postlist as $px_thepost) {
		   $ids[] = $px_thepost->ID;
		}
		$thisindex = array_search($post->ID, $ids);
		if(isset($ids[$thisindex-1])){
			$previd = $ids[$thisindex-1];
		} 
		if(isset($ids[$thisindex+1])){
			$nextid = $ids[$thisindex+1];
		} 
		?>
        <div class="single-paginate">
			<?php 
            if (isset($previd) &&  !empty($previd) && $previd >=0 ) {
               ?>
               <div class="next-post-paginate">
                <a href="<?php echo get_permalink($previd); ?>" class="pix-colr"><i class="fa fa-arrow-left"></i>
                    <?php echo __('Previous Post','Kings Club');?>
               </a>
               <h2 class="px-single-page-title">
                   <?php echo get_the_title($previd);?>
               </h2>
               <ul>
               <?php  $before_cat = "<li>";
				$categories_list = get_the_term_list ( $previd, $post_categoryy, $before_cat, ' ', '</li>' );
				if ( $categories_list ){
					printf( __( '%1$s', 'Kings Club'),$categories_list );
				}
				?>
                <li><?php echo date_i18n(get_option('date_format'),strtotime(get_the_date()));?></li>
               </ul>
               </div>
                <?php
            }
            
            if (isset($nextid) &&   !empty($nextid) ) {
                ?>
                <div class="next-post-paginate">
                <a href="<?php echo get_permalink($nextid); ?>" class="pix-colr"><i class="fa fa-arrow-right"></i>
                    <?php echo __('Next Post','Kings Club');?>
                </a>
                 <h2 class="px-single-page-title"><?php echo get_the_title($nextid);?></h2>
               <ul>
               <?php  $before_cat = "<li>";
				$categories_list = get_the_term_list ( $nextid, $post_categoryy, $before_cat, ' ', '</li>' );
				if ( $categories_list ){
					printf( __( '%1$s', 'Kings Club'),$categories_list );
				}
				?>
                <li><?php echo date_i18n(get_option('date_format'),strtotime(get_the_date()));?></li>
               </ul>
                </div>
                <?php	
            }
            ?>
        </div>
        <?php
	 wp_reset_query();
 }
 
// news announcement 
if ( ! function_exists( 'fnc_announcement' ) ) {
	function fnc_announcement(){
		global $post,$px_theme_option;
		date_default_timezone_set('UTC');
		$current_time = strtotime(current_time('m/d/Y H:i', $gmt = 0));
		$image_url = '';
         	if(isset($px_theme_option['announcement_fixtures_category']) && $px_theme_option['announcement_fixtures_category'] <> '0'){
				$fixture_category = $px_theme_option['announcement_fixtures_category'];
        		$announcement_no_posts = $px_theme_option['announcement_no_posts'];
				if (empty($announcement_no_posts)){ $announcement_no_posts  = 10;}
				$args = array(
                    'posts_per_page'			=> "$announcement_no_posts",
					'paged'						=> '1',
                    'post_type'					=> 'events',
                    'post_status'				=> 'publish',
					'meta_key'                  => 'px_event_from_date_time',
					'meta_value'				=> $current_time,
					'meta_compare'				=> ">",
                    'orderby'					=> 'meta_value',
                    'order'						=> 'ASC',
                );
				if(isset($fixture_category) && $fixture_category <> '' && $fixture_category <> '0' && $fixture_category <> 'All' ){
					$event_category_array = array('event-category' => "$fixture_category");
					$args = array_merge($args, $event_category_array);
				}
				$custom_query = new WP_Query($args);
				$count_post = $custom_query->post_count;
				if($custom_query->have_posts()):
			    px_enqueue_cycle_script();
			//	echo '<pre>';
				//print_r($custom_query );
			//	echo '</per>';
	?>
     <div id="carouselarea">
     	<div class="container">
    		<div class="news-carousel">
            	
                    <div class="center">
                        <span class="cycle-prev" id="cycle-next"><i class="fa fa-arrow-left"></i></span>
                        <span class="cycle-next" id="cycle-prev"><i class="fa fa-arrow-right"></i></span>
                    </div>
                    
                     <div class="cycle-slideshow news-section"
                    data-cycle-fx=carousel 
                    data-cycle-next="#cycle-next"
                    data-cycle-prev="#cycle-prev"
                    data-cycle-slides=">article"
                    data-cycle-timeout=0>
					<?php 
                        while ($custom_query->have_posts()) : $custom_query->the_post();
		
						$event_from_date = get_post_meta($post->ID, "px_event_from_date", true); 
						$post_xml = get_post_meta($post->ID, "px_event_meta", true);	
						if ( $post_xml <> "" ) {
							$px_event_meta = new SimpleXMLElement($post_xml);
						}
						$dateAfter = date('m/d/Y');
						$var_pb_event_team2 = $var_pb_event_team1 = '';
						if(isset($px_event_meta->var_pb_event_team1)and $px_event_meta->var_pb_event_team1 <> '0' and $px_event_meta->var_pb_event_team1 <> ''){
							$var_pb_event_team1 = px_get_term_object($px_event_meta->var_pb_event_team1);
							
						
						}
						if(isset($px_event_meta->var_pb_event_team2) and $px_event_meta->var_pb_event_team2 <> '0' and $px_event_meta->var_pb_event_team2 <> ''){
							 $var_pb_event_team2 = px_get_term_object($px_event_meta->var_pb_event_team2);
						}
                        ?>
                        <article>
                        	<time datetime="<?php echo date_i18n('d-m-Y', strtotime($event_from_date));?>"><?php echo date_i18n(get_option('date_format'), strtotime($event_from_date));?></time>
                            <div class="text">
                                
                                 <?php if(isset($px_event_meta->event_score) && $px_event_meta->event_score <> '' && strtotime($event_from_date) < strtotime($dateAfter)){
					
								 	$event_score = explode('-',$px_event_meta->event_score);
								?>
                                       		
                                            <div class="match-result">
                                            <a href="<?php the_permalink();?>">
                                            	<?php if(isset($var_pb_event_team1->name) && $var_pb_event_team1->name <> ''){?>
                                                <span>
                                                	<?php echo substr($var_pb_event_team1->name, 0, 3);?>
                                                    <big><?php echo $event_score['0'];?></big>
                                                    <br/>
                                                 </span>
                                                 <?php }?>
                                                 <?php if(isset($var_pb_event_team2->name) && $var_pb_event_team2->name <> ''){?>
                                                 <span>
                                                	<?php echo substr($var_pb_event_team2->name, 0, 3);?>
                                                    <big><?php echo $event_score['1'];?></big>
                                                 </span>
                                                 <?php }?>
                                                 </a>
                                            </div>
                                            
                                        <?php }else{ ?>
                                        
                                        	<div class="match-info">
                                            	<a href="<?php the_permalink();?>">
                                            	<?php if(isset($var_pb_event_team1->name) && $var_pb_event_team1->name <> ''){?>
                                                <span>
                                                	<?php echo substr($var_pb_event_team1->name, 0, 3);?>
                                                    
                                                 </span>
                                                 <?php }?>
                                                 <?php if(isset($var_pb_event_team2->name) && $var_pb_event_team2->name <> ''){?>
                                                                                                  <?php if(isset($px_theme_option['trans_switcher']) && $px_theme_option['trans_switcher'] == "on"){ _e('Vs','Kings Club');}else{ echo $px_theme_option['trans_event_vs']; } ?>

                                                 <span>
                                                	<?php echo substr($var_pb_event_team2->name, 0, 3);?>
                                                 </span>
                                                 <?php }?>
                                                 </a>
                                            </div>
                                            
											<?php if(isset($px_event_meta->event_ticket_options) && $px_event_meta->event_ticket_options <> ''){?> 
                                               <a class="pix-btn-open" href="<?php echo $px_event_meta->event_buy_now;?>"> <?php if(isset($px_event_meta->event_ticket_options) && $px_event_meta->event_ticket_options <> ''){echo $px_event_meta->event_ticket_options;}?></a>
                                            <?php }
										}?>
                            </div>
                        </article>
                    <?php endwhile;?>
         		</div>
          	</div>
    	</div>
    </div>
    <?php endif; wp_reset_query(); 
	}
	}
}
// posts/pages title lenght limit
function px_title_lenght($str ='',$start =0,$length =30){
	return substr($str,$start,$length);
}
// Default pages listing article
function px_defautlt_artilce(){
	global $post,$px_theme_option;
	$img_class = '';
	$image_url = px_attachment_image_src(get_post_thumbnail_id($post->ID), 325, 244);
	if($image_url == ""){
		$img_class = 'no-image';
	}
	?>
         <article id="post-<?php the_ID(); ?>" <?php post_class($img_class); ?> >
          <?php if($image_url <> ""){?>
                <figure><a href="<?php the_permalink(); ?>"><img src="<?php echo $image_url;?>" alt=""></a></figure>
            <?php }?>
            <div class="text">
                <h2 class="pix-post-title"><a href="<?php the_permalink(); ?>" class="pix-colrhvr"><?php the_title(); ?></a></h2>
                <p><?php echo px_get_the_excerpt(255,false); ?></p>
               <div class="blog-bottom">
			   <?php px_posted_on(true,false,false,false,true,false);?>
               <a href="<?php the_permalink(); ?>" class="btnreadmore btn pix-bgcolrhvr"><i class="fa fa-plus"></i><?php if(isset($px_theme_option["trans_switcher"]) && $px_theme_option["trans_switcher"] == "on") {  _e("READ MORE",'Kings Club'); }elseif(isset($px_theme_option["trans_read_more"])){  echo $px_theme_option["trans_read_more"];}?></a>
               </div>
            </div>
        </article>

    <?php
	
}
// header search function
function px_search(){
	?>
	<form id="searchform" method="get" action="<?php echo home_url()?>"  role="search">
		<button> <i class="fa fa-search"></i></button>
        <input name="s" id="searchinput" value="<?php _e('Search for:', 'Kings Club'); ?>" type="text" />
    </form>
<?php

}
// post date/categories/tags
if ( ! function_exists( 'px_posted_on' ) ) {
	function px_posted_on($cat=true,$tag=true,$comment=true,$date=true,$author=true,$icon=true,$date=true){
		global $px_theme_option;
		?>
 		<ul class="post-options">
        	<?php px_featured();?>
        	<?php if($date==true){?>
                 <li>
                 	<?php if($icon==true){ echo '<i class="fa fa-calendar"></i>'; } ?>
                    <time datetime="<?php echo date('d-m-y',strtotime(get_the_date()));?>"><?php echo get_the_date();?></time>
                </li>
				<?php
				}
				/* translators: used between list items, there is a space after the comma */
				$trans_in = "";
				if($cat==true){
					if(isset($px_theme_option['trans_switcher']) && $px_theme_option['trans_switcher'] == "on"){ $trans_in =__('in','Kings Club');}else{ if(isset($px_theme_option['trans_listed_in'])) $trans_in = $px_theme_option['trans_listed_in']; }
					  $before_cat = "<li><span>".$trans_in."</span> ";
					$categories_list = get_the_term_list ( get_the_id(), 'category', $before_cat, ', ', '</li>' );
					if ( $categories_list ){
						printf( __( '%1$s', 'Kings Club'),$categories_list );
					}
				}
				/* translators: used between list items, there is a space after the comma */
				if($tag == true){
					$before_tag = "<li>".__( 'tags ','Kings Club')."";
					$tags_list = get_the_term_list ( get_the_id(), 'post_tag', $before_tag, ', ', '</li>' );
					if ( $tags_list ){
						printf( __( '%1$s', 'Kings Club'),$tags_list );
					} // End if categories 
				}
				if($comment == true){
					if ( comments_open() ) {  
						echo "<li>"; comments_popup_link( __( '0 Comment', 'Kings Club' ) , __( '1 Comment', 'Kings Club' ), __( '% Comments', 'Kings Club' ) ); 
					}
				}
				
				
				
				edit_post_link( __( 'Edit', 'Kings Club'), '<li>', '</li>' ); 
			?>
		</ul>
	<?php
	}
}
// footer show partner
function px_show_partner(){
		global $px_theme_option;
		$gal_album_db = '0';
		if(isset($px_theme_option['partners_gallery']))
			$gal_album_db =$px_theme_option['partners_gallery'];
		?>
        <?php if($gal_album_db <> "0" and $gal_album_db <> ''){?>
        <div class="our-sponcers">
        	<?php  
				if($px_theme_option['partners_title'] <> ''){ ?>
            		<header class="sponcer-title">
                        <h3><?php  echo $px_theme_option['partners_title']; ?></h3>
                    </header>
            <?php  } 
				if($gal_album_db <> "0" and $gal_album_db <> ''){
			?>
        	<div class="container">
            
            <div class="center">
                <span class="cycle-prev" id="cycle-nexto"><i class="fa fa-angle-left"></i></span>
                <span class="cycle-next" id="cycle-prevt"><i class="fa fa-angle-right"></i></span>
            </div>
           	<div class="cycle-slideshow"
                    data-cycle-fx=carousel
                    data-cycle-next="#cycle-nexto"
                    data-cycle-prev="#cycle-prevt"
                    data-cycle-slides=">article"
                    data-cycle-timeout=0>
            	
                <?php
                    // galery slug to id start
                    $args=array(
                    'name' => (string)$gal_album_db,
                    'post_type' => 'px_gallery',
                    'post_status' => 'publish',
                    'showposts' => 2,
                    );
                    $get_posts = get_posts($args);
                    if($get_posts){
                    $gal_album_db = (int)$get_posts[0]->ID;
                    }
                    // galery slug to id end	
                    $px_meta_gallery_options = get_post_meta($gal_album_db, "px_meta_gallery_options", true);
                    // pagination start
                    if ( $px_meta_gallery_options <> "" ) {
						px_enqueue_cycle_script();
                    $xmlObject = new SimpleXMLElement($px_meta_gallery_options);
                    $limit_start = 0;
                    $limit_end = count($xmlObject);
                        for ( $i = $limit_start; $i < $limit_end; $i++ ) {
                            $path = $xmlObject->gallery[$i]->path;
                            $title = $xmlObject->gallery[$i]->title;
                            $description = $xmlObject->gallery[$i]->description;
                            $use_image_as = $xmlObject->gallery[$i]->use_image_as;
                            $video_code = $xmlObject->gallery[$i]->video_code;
                            $link_url = $xmlObject->gallery[$i]->link_url;
                            $image_url = px_attachment_image_src($path, 150, 150);
                            $image_url_full = px_attachment_image_src($path, 0, 0);
                            ?>
                            <article>
                                <a <?php if($use_image_as==2){?>href="<?php echo $link_url;?>" 
                                target="<?php if($use_image_as==2) { echo '_blank'; } else {echo '_self'; }?>" <?php }?>>
                                <?php  echo "<img src='".$image_url."' alt='".$title."' />"; ?>
                                </a>
                            </article>
                            <?php
                        }
                    } else {
                      echo '<h4 class="pix-heading-color">'.__( 'No results found.', 'Kings Club' ).'</h4>';
                    }
                ?>
               	
        	</div>
         	
                
           <?php } ?>     
        </div>
    </div>
  <?php }  
	}
//
function px_footer_tweets($username = '', $numoftweets = ''){
	global $px_theme_option;
	if($numoftweets == '' or !is_numeric($numoftweets)){$numoftweets = 1;}
		
		echo "<div class='twitter_sign'>";
			if(strlen($username) > 1){
				echo "<figure><i class='fa fa-twitter'></i></figure>";
				$text ='';
				$return = '';
				require_once "include/twitteroauth/twitteroauth.php"; //Path to twitteroauth library
				$consumerkey = $px_theme_option['consumer_key'];
				$consumersecret = $px_theme_option['consumer_secret'];
				$accesstoken = $px_theme_option['access_token'];
				$accesstokensecret = $px_theme_option['access_token_secret'];
				$connection = new TwitterOAuth($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
				$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=".$numoftweets);
 				?>
                <?php  px_enqueue_flexslider_script(); ?>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery(".twitter_sign .flexslider").flexslider({
							animation: "fade",
							prevText: "",
							nextText: "",
							slideshowSpeed: 3000
						});
					});
				</script>
                <?php
					if(!is_wp_error($tweets) and is_array($tweets)){
 						$return .= "<div class='flexslider'><ul class='slides'>";
						foreach($tweets as $tweet) {
							$text = $tweet->{'text'};
							foreach($tweet->{'user'} as $type => $userentity) {
							if($type == 'profile_image_url') {	
								$profile_image_url = $userentity;
							} else if($type == 'screen_name'){
								$screen_name = '<a href="https://twitter.com/' . $userentity . '" target="_blank" class="cs-colrhvr" title="' . $userentity . '">@' . $userentity . '</a>';
							}
						}
						foreach($tweet->{'entities'} as $type => $entity) {
						if($type == 'urls') {						
							foreach($entity as $j => $url) {
								$display_url = '<a href="' . $url->{'url'} . '" target="_blank" title="' . $url->{'expanded_url'} . '">' . $url->{'display_url'} . '</a>';
								$update_with = 'Read more at '.$display_url;
								$text = str_replace('Read more at '.$url->{'url'}, '', $text);
								$text = str_replace($url->{'url'}, '', $text);
							}
						} else if($type == 'hashtags') {
							foreach($entity as $j => $hashtag) {
								$update_with = '<a href="https://twitter.com/search?q=%23' . $hashtag->{'text'} . '&src=hash" target="_blank" title="' . $hashtag->{'text'} . '">#' . $hashtag->{'text'} . '</a>';
								$text = str_replace('#'.$hashtag->{'text'}, $update_with, $text);
							}
						} else if($type == 'user_mentions') {
							foreach($entity as $j => $user) {
								  $update_with = '<a href="https://twitter.com/' . $user->{'screen_name'} . '" target="_blank" title="' . $user->{'name'} . '">@' . $user->{'screen_name'} . '</a>';
								  $text = str_replace('@'.$user->{'screen_name'}, $update_with, $text);
							}
						}
					} 
					$large_ts = time();
					$n = $large_ts - strtotime($tweet->{'created_at'});
					if($n < (60)){ $posted = sprintf(__('%d seconds ago','Kings Club'),$n); }
					elseif($n < (60*60)) { $minutes = round($n/60); $posted = sprintf(_n('About a Minute Ago','%d Minutes Ago',$minutes,'Kings Club'),$minutes); }
					elseif($n < (60*60*16)) { $hours = round($n/(60*60)); $posted = sprintf(_n('About an Hour Ago','%d Hours Ago',$hours,'Kings Club'),$hours); }
					elseif($n < (60*60*24)) { $hours = round($n/(60*60)); $posted = sprintf(_n('About an Hour Ago','%d Hours Ago',$hours,'Kings Club'),$hours); }
					elseif($n < (60*60*24*6.5)) { $days = round($n/(60*60*24)); $posted = sprintf(_n('About a Day Ago','%d Days Ago',$days,'Kings Club'),$days); }
					elseif($n < (60*60*24*7*3.5)) { $weeks = round($n/(60*60*24*7)); $posted = sprintf(_n('About a Week Ago','%d Weeks Ago',$weeks,'Kings Club'),$weeks); } 
					elseif($n < (60*60*24*7*4*11.5)) { $months = round($n/(60*60*24*7*4)) ; $posted = sprintf(_n('About a Month Ago','%d Months Ago',$months,'Kings Club'),$months);}
					elseif($n >= (60*60*24*7*4*12)){$years=round($n/(60*60*24*7*52)) ; $posted = sprintf(_n('About a year Ago','%d years Ago',$years,'Kings Club'),$years);} 
					$user = $tweet->{'user'};
					$return .="<li><article><div class='text'>";
					$return .= " <h2 class='cs-post-title'>" . $text . "<time datetime='2011-01-12'> (" . $posted. ")</time></h2>";
					$return .="</div>";
  					$return .= " </article></li>";
					}
					echo $return;
					echo '</ul></div>';
 					
		}else{
			if(isset($tweets->errors[0]) && $tweets->errors[0] <> ""){
				echo '<div class="flexslider"><div class="messagebox alert alert-info align-left">'.$tweets->errors[0]->message.".Please enter valid Twitter API Keys".'</div></div><div class="clear"></div>';
			}else{
				px_no_result_found(false);
			}
		}
	}
	echo '</div>';
}	

// Player Detail Gallery

function px_single_gallery($px_gallery_id=''){
 	$args=array(
		'name' => (string)$px_gallery_id,
		'post_type' => 'px_gallery',
		'post_status' => 'publish',
		'showposts' => 1,
	);
	$get_posts = get_posts($args);
	
	if($get_posts){
	
		
		$gal_album_db = $get_posts[0]->ID;
		
		
	}
	if(isset($gal_album_db) && $gal_album_db <> '')
	{
		$px_cause_gallery = get_post_meta((int)$gal_album_db, "px_meta_gallery_options", true);
	
		if ( $px_cause_gallery <> "" ) {
			$px_image_per_gallery = '';
			$px_xmlObject_gallery = new SimpleXMLElement($px_cause_gallery);
				$limit_start = 0;
				$limit_end = $limit_start+$px_image_per_gallery;
				if($limit_end < 1){
					$limit_end = count($px_xmlObject_gallery);
			}
				$count_post = count($px_xmlObject_gallery);
	?>
	 <header class="pix-heading-title">
        <h2 class="pix-section-title"><?php echo get_the_title((int)$gal_album_db);?></h2>
     </header>
	<div class="gallery ">
      <ul class="lightbox gallery-four-col">
		<?php for ( $i = 0; $i < $limit_end; $i++ ) {

                $path = $px_xmlObject_gallery->gallery[$i]->path;

                $title = $px_xmlObject_gallery->gallery[$i]->title;

                $social_network = $px_xmlObject_gallery->gallery[$i]->social_network;

                $use_image_as = $px_xmlObject_gallery->gallery[$i]->use_image_as;

                $video_code = $px_xmlObject_gallery->gallery[$i]->video_code;

                $link_url = $px_xmlObject_gallery->gallery[$i]->link_url;

                $gallery_image_url = px_attachment_image_src($path, 470, 353);

                if($gallery_image_url <> ''){

					$image_url_full = px_attachment_image_src($path, 0, 0);
	
					?>
					<li>
						<figure>
							<img src="<?php echo $gallery_image_url;?>" alt="#">
							<figcaption>
                            	  <a  data-rel="<?php if($use_image_as==1)echo "prettyPhoto";  elseif($use_image_as==2) echo ""; else echo "prettyPhoto[gallery1]"?>" href="<?php if($use_image_as==1)echo $video_code; elseif($use_image_as==2) echo $link_url; else echo $image_url_full;?>" data-title="<?php if ( $title <> "" ) { echo $title; }?>" >
                            <?php 
							  if($use_image_as==1){
								  echo '<i class="fa fa-video-camera"></i>';
							  }elseif($use_image_as==2){
								  echo '<i class="fa fa-link"></i>';	
							  }else{
								  echo '<i class="fa fa-plus"></i>';
							  }
							?>
                            </a>
							
							</figcaption>
						</figure>
					</li>

        <?php 	}
		}?>
            
      </ul>
   </div>	
<?php   

	}
  }
}
// Get object by slug
function px_get_term_object($var_pb_event_category = ''){
		global $wpdb;
		return $row_cat = $wpdb->get_row("SELECT * from ".$wpdb->prefix."terms WHERE slug = '" . $var_pb_event_category ."'" );	
	}
function px_fixtures_page($page_section_title = ''){
	global $px_node,$post, $px_theme_option,$px_counter_node;
	
	if($px_node->var_pb_fixtures_cat <> '' && $px_node->var_pb_fixtures_cat <> '0'){ 
	if(isset($px_theme_option["trans_switcher"]) && $px_theme_option["trans_switcher"] == "on") {  $start_fixtures = __("Kick-off",'Kings Club'); }else{  if(isset($px_theme_option["trans_event_start"]))$start_fixtures = $px_theme_option["trans_event_start"];}
	?>
    		<div class="element_size_<?php echo $px_node->fixtures_element_size;?>">
                        
                        <?php if($px_node->var_pb_fixtures_view == 'countdown'){
								$hours = '00';
								$mints = '00';
								$featured_args = array(
                                            'posts_per_page'			=> "1",
                                       //     'paged'						=> $_GET['page_id_all'],
                                            'post_type'					=> 'events',
                                            'event-category' 			=> "$px_node->var_pb_fixtures_cat",
                                            'meta_key'					=> 'px_event_from_date',
                                            'meta_value'				=> date('m/d/Y'),
                                            'meta_compare'				=> ">=",
                                            'orderby'					=> 'meta_value',
                                            'post_status'				=> 'publish',
                                            'order'						=> 'ASC',
                                         );
                                $px_featured_post= new WP_Query($featured_args);
							while ($px_featured_post->have_posts()) : $px_featured_post->the_post();	
                                    $event_from_date = get_post_meta($post->ID, "px_event_from_date", true);
                                        $year_event = date("Y", strtotime($event_from_date));
                                        $month_event = date("m", strtotime($event_from_date));
                                        $date_event = date("d", strtotime($event_from_date));
									 $px_featured_meta = get_post_meta($post->ID, "px_event_meta", true);	
                                    if ( $px_featured_meta <> "" ) {
                                        $px_featured_event_meta = new SimpleXMLElement($px_featured_meta);
										if ( $px_featured_event_meta->event_all_day != "on" ) {
											$time = $px_featured_event_meta->event_time;
											
											$time_param = str_replace("PM", '', $px_featured_event_meta->event_time);
											$time_param = str_replace("AM", '', $time_param);
											$time_param_array = explode(':', $time_param);
											$pos = strpos($px_featured_event_meta->event_time, 'PM');
											if ($pos === false) {
													$hours = $time_param_array['0'];
													$mints = $time_param_array['1'];
											} else {
												$hours = $time_param_array['0']+12;
												$mints = $time_param_array['1'];
											}
											
										} else {
											$hours = '00';
											$mints = '00';
											
										}
											
                                    }
									$image_url = px_get_post_img_src($post->ID, '530', '398');
                                    px_enqueue_countdown_script();
									
							
							?>
                        <?php if($px_node->var_pb_fixtures_title <> '' && $page_section_title == ''){?>
                                <header class="pix-heading-title">
                                    <h2 class="pix-section-title"><a href="<?php the_permalink();?>"><?php echo $px_node->var_pb_fixtures_title;?></a></h2>
                                </header>
                              <?php }?>
                           			 <div class="widget widget_countdown">
                                <div class="countdown-section">
                                <?php if($image_url <> '' && $page_section_title == ''){?>
                                    <figure>
                                        <img src="<?php echo $image_url;?>" alt="">
                                    </figure>
                                <?php }?>
                                <!-- Pix Label Strat -->
                                <div class="pix-label">
                                	<span class="pix-tittle"><?php echo $px_node->var_pb_fixtures_title;?></span>
                                	<time>
									<?php echo date_i18n(get_option('date_format'), strtotime($event_from_date));?>
                                    <?php 
										if ( $px_featured_event_meta->event_all_day != "on" ) {
											echo $px_featured_event_meta->event_time;
										}else{
											_e("All",'Kings Club') . printf( __("%s day",'Kings Club'), ' ');
										}
									?>
                                    </time>
                                </div>
                                <!-- Pix Label Strat -->
                                <div class="text">
                                    <div class="pix-sc-team">
                                        <ul>
                                        	<?php if(isset($px_featured_event_meta->var_pb_event_team1) && $px_featured_event_meta->var_pb_event_team1 <> '' && $px_featured_event_meta->var_pb_event_team1 <> '0'){?>
                                            <li>
                                                <figure>
                                                    <?php
                                                    $team1_row = px_get_term_object($px_featured_event_meta->var_pb_event_team1);
													
                                                      $team_img1 = px_team_data_front($team1_row->term_id);
                                                    if($team_img1[0] <> ''){
                                                    ?>
                                                        <img alt="" src="<?php echo $team_img1[0];?>">
                                                    <?php }?>
                                                </figure>
                                            </li>
                                            <?php }?>
                                            <?php if(isset($px_featured_event_meta->var_pb_event_team2) && $px_featured_event_meta->var_pb_event_team2 <> '' && $px_featured_event_meta->var_pb_event_team2 <> '0'){?>
                                            <li>
                                                <figure>
                                                    <?php
													 $px_featured_event_meta->var_pb_event_team2;
                                                    $team2_row = px_get_term_object($px_featured_event_meta->var_pb_event_team2);
                                                    $team_img2 = px_team_data_front($team2_row->term_id);
													
                                                    if($team_img2[0] <> ''){
                                                    ?>
                                                        <img alt="" src="<?php echo $team_img2[0];?>">
                                                    <?php }?>
                                                </figure>
                                            </li>
                                            <?php }?>
                                        </ul>
                                        <div class="pix-sc-team-info">
                                        	<p>
                                            	<?php 
													if(isset($team1_row->name)){echo $team1_row->name;}
											   ?>
                                               <span class="vs"><?php if(isset($px_theme_option["trans_switcher"]) && $px_theme_option["trans_switcher"] == "on") {  _e("VS",'Kings Club'); }else{  echo $px_theme_option["trans_event_vs"];}?></span>
                                            	<?php 
														if(isset($team2_row->name)){echo $team2_row->name;}
												   ?>
                                                   <span class="time-sec"><?php echo ''.$px_featured_event_meta->event_address;?></span>
                                            </p>
                                        </div>
                                    </div>
                                    <header class="pix-cont-title">
                                        <h2 class="pix-section-title"><span>
										<?php if(isset($px_featured_event_meta->event_time_title) && $px_featured_event_meta->event_time_title <> ''){echo $px_featured_event_meta->event_time_title.' ';}
												if ( isset($px_featured_event_meta->event_all_day) && $px_featured_event_meta->event_all_day != "on" ) {
													echo $px_featured_event_meta->event_time;
												}else{
													_e("All",'Kings Club') . printf( __("%s day",'Kings Club'), ' ');
												}
										?>
										</span></h2>
                                    </header>
                                    <?php $random_id = px_generate_random_string();?>
                                    <div class="defaultCountdown" id="defaultCountdown<?php echo $random_id;?>"></div>
                                   	<script>
										jQuery(document).ready(function($) {
										   px_event_countdown('<?php echo $year_event;?>','<?php echo $month_event;?>','<?php echo $date_event;?>',<?php echo $hours;?>,<?php echo $mints;?>,'<?php echo $random_id;?>');
										});
									</script>
                                    <div class="countdown-buttons">
                                        <?php
											add_to_calender(); 
											if($px_featured_event_meta->event_ticket_options <> ''){?> 
                                            <div class="buy-ticket-button">
                                               <a class="btn pix-btn-open" href="<?php echo $px_featured_event_meta->event_buy_now;?>"> <?php if(isset($px_featured_event_meta->event_ticket_options) && $px_featured_event_meta->event_ticket_options <> ''){echo $px_featured_event_meta->event_ticket_options;}?></a>
                                             </div>
                                        <?php }?>
                                        
                                     </div>
                                    
                                  
                                </div>
                                </div>
                            </div>
                            
                            <?php 
								 endwhile; 
							 
							} else {
								$featured_args = array(
                                            'posts_per_page'			=> "$px_node->var_pb_fixtures_per_page",
                                       //     'paged'						=> $_GET['page_id_all'],
                                            'post_type'					=> 'events',
                                            'event-category' 			=> "$px_node->var_pb_fixtures_cat",
                                            'meta_key'					=> 'px_event_from_date',
                                            'meta_value'				=> date('m/d/Y'),
                                            'meta_compare'				=> ">=",
                                            'orderby'					=> 'meta_value',
                                            'post_status'				=> 'publish',
                                            'order'						=> 'ASC',
                                         );
                                $px_featured_post= new WP_Query($featured_args);
								
								?>
                            	<?php if($px_node->var_pb_fixtures_title <> ''){?> 
                                    <header class="pix-heading-title">
                                        <h2 class="pix-section-title">
                                            <?php echo $px_node->var_pb_fixtures_title;?>
                                        </h2>
                                    </header>
                                    
                                    <?php if ( $px_featured_post->have_posts() <> "" ) {?>
                                        <div class="event event-listing event-listing-v2">
                                        <?php
                                                while ( $px_featured_post->have_posts() ): $px_featured_post->the_post();
                                                $event_from_date = get_post_meta($post->ID, "px_event_from_date", true);
                                                
                                                $post_xml = get_post_meta($post->ID, "px_event_meta", true);	
                                                if ( $post_xml <> "" ) {
                                                    $px_event_meta = new SimpleXMLElement($post_xml);
                                                    $team1_row = px_get_term_object($px_event_meta->var_pb_event_team1);
                                                    $team2_row = px_get_term_object($px_event_meta->var_pb_event_team2);
                                                }
                                                ?>
                                        
                                            <article>
                                                <div class="text">
                                                    <div class="top-event">
                                                        <h2 class="pix-post-title">
                                                            <a href="<?php the_permalink();?>"><?php the_title(); ?></a>
                                                        </h2>
                                                    </div>
                                                     <?php 
														 if($px_event_meta->event_venue <> '' and $px_event_meta->event_venue  <> '0'){
														 echo '<span class="match-category cat-'.$px_event_meta->event_venue.'">'.substr($px_event_meta->event_venue,0,1).'</span>';
										  				 } ?>
													
                                                    
                                                    <ul class="post-options">
                                                        <li> <i class="fa fa-calendar"></i>
                                                            <?php echo date_i18n(get_option('date_format'), strtotime($event_from_date));?>
                                                        </li>
                                                        <li><i class="fa fa-clock-o"></i>
                                                         <?php 
                                                            if ( $px_event_meta->event_all_day != "on" ) {
                                                                echo $px_event_meta->event_time;
                                                            }else{
                                                                _e("All",'Kings Club') . printf( __("%s day",'Kings Club'), ' ');
                                                            }
                                                        ?>
                                                            </li>
                                                        <?php if($px_event_meta->event_ticket_options <> ''){?> <li><i class="fa fa-map-marker"></i><?php echo $px_event_meta->event_address;?></li><?php }?>
                                                    </ul>
                                                </div>
                                            </article>
                                            <?php endwhile;?>
                                            <?php if($px_node->var_pb_fixtures_viewall_title <> ''){?> <a href="<?php echo $px_node->var_pb_fixtures_viewall_link;?>" class="btn btn-viewall pix-bgcolrhvr"><i class="fa fa-calendar"></i><?php echo $px_node->var_pb_fixtures_viewall_title;?>l</a><?php }?>
                                        </div>
                        <?php }?>
                        	
                        
                        <?php }?>
                            <?php }?>
                        
                    </div>
    
    <?php
	
	wp_reset_query();
	}
}


// team images
function px_team_data_front($team_id){
		$team_data = get_option("team_$team_id");
		if (isset($team_data)){
			$data[] = stripslashes($team_data['icon']);
		}
		return $data;
}

// Flexslider function

if ( ! function_exists( 'px_flex_slider' ) ) {

	function px_flex_slider($width,$height,$slider_id, $single_slider = ''){

		global $px_node,$px_theme_option,$px_counter_node;
		

		$px_counter_node++;

		if($slider_id == ''){

			$slider_id = $px_node->slider;

		}


			$px_meta_slider_options = get_post_meta($slider_id, "px_meta_gallery_options", true); 

		?>

		<!-- Flex Slider -->





		  <div class="flexslider">

			  <ul class="slides">

				<?php 

					$px_counter = 1;

					$px_xmlObject_flex = new SimpleXMLElement($px_meta_slider_options);
					echo '';
					$gallery_count = $px_xmlObject_flex->gallery;
					foreach ( $px_xmlObject_flex->children() as $as_node ){
 						$image_url = px_attachment_image_src($as_node->path,$width,$height); 
						if(isset($as_node->link) && $as_node->link <> ''){$link = $as_node->link;} else {$link = '';}
						?>
                        <li>
                            <figure>
                                <img src="<?php echo $image_url ?>" alt="">   
                                    <?php if($as_node->title <> ''){?>
                                    <figcaption>
                                    	<h2 class="cs-bgcolr"><a <?php if(isset($as_node->link) && $as_node->link <> ''){?>href="<?php echo $as_node->link;?>" target="<?php echo $as_node->link_target;?>" <?php }?>><?php echo $as_node->title;?></a></h2>
                                      
                                    </figcaption><?php }?>
                                
                            </figure>
                        </li>
					<?php 
					$px_counter++;
					}
				?>
                
			  </ul>
             
		  </div>
		<?php px_enqueue_flexslider_script(); ?>
		<!-- Slider height and width -->

		<!-- Flex Slider Javascript Files -->

		<script type="text/javascript">
			jQuery(document).ready(function($) {
				<?php if(isset($single_slider) && $single_slider == 'single'){?>
					px_flexsliderGallery();
				<?php } else {?>
					px_flexsliderBannerGallery(); 
				<?php } ?>
			});
		</script>

	<?php

	}

}



if ( ! function_exists( 'px_player_slider' ) ) {

	function px_player_slider($width,$height,$slider_id, $single_slider = ''){

		global $px_node,$px_theme_option,$px_counter_node;
		
		 if($px_theme_option["trans_switcher"] == "on") { $out_of = __("Out of",'Kings Club'); }else{  $out_of = $px_theme_option["trans_out_of"];}
		
		$px_counter_node++;
		if($slider_id == ''){
			$slider_id = $px_node->slider;
		}
		$px_meta_slider_options = get_post_meta($slider_id, "px_meta_gallery_options", true); 
		?>
		<!-- Flex Slider -->
		  <div class="flexslider">
			  <ul class="slides lightbox">
				<?php 
					$px_counter = 1;
					$px_xmlObject_flex = new SimpleXMLElement($px_meta_slider_options);
					$gallery_count = count($px_xmlObject_flex->gallery);
					foreach ( $px_xmlObject_flex->children() as $as_node ){
						$image_url_full = px_attachment_image_src($as_node->path,'',''); 
 						$image_url = px_attachment_image_src($as_node->path,$width,$height); 
						if(isset($as_node->link) && $as_node->link <> ''){$link = $as_node->link;} else {$link = '';}
						$link_target = '';
						if($as_node->use_image_as==2){
							$link_target = 	'target="_blank"';
						}
						?>
                        <li>
                            <figure>
                            	<img src="<?php echo $image_url ?>" alt=""> 
                                <a class="pix-zoom"  data-rel="<?php if($as_node->use_image_as==1)echo "prettyPhoto";  elseif($as_node->use_image_as==2) echo ""; else echo "prettyPhoto[gallery1]"?>" href="<?php if($as_node->use_image_as==1)echo $video_code; elseif($as_node->use_image_as==2) echo $link; else echo $image_url_full;?>" <?php echo $link_target;?> data-title="<?php if ( $as_node->title <> "" ) { echo $as_node->title; }?>" ><i class="fa fa-arrows"></i></a>
                                    <figcaption>
                                    <?php 
									
                                      if($as_node->use_image_as==1){
                                          echo '<i class="fa fa-video-camera"></i>';
                                      }elseif($as_node->use_image_as==2){
                                          echo '<i class="fa fa-link"></i>';
                                      }else{
                                           echo '<i class="fa fa-camera"></i>';
                                      }
                                    ?>
                                    <h2 class="cs-bgcolr"><a <?php if(isset($as_node->link) && $as_node->link <> ''){?>href="<?php echo $as_node->link;?>" target="<?php echo $as_node->link_target;?>" <?php }?>><?php echo $as_node->title;?></a></h2>
									
                                    <span class="px-count">
									<?php if($single_slider == 'player'){
                                            echo $px_counter.' '.$out_of.' '.$gallery_count;
                                        }
                                    ?></span>
                                    </figcaption>
                                
                            </figure>
                        </li>
					<?php 
					$px_counter++;
					}
				?>
                
			  </ul>
             
		  </div>
		<?php px_enqueue_flexslider_script(); ?>
		<!-- Slider height and width -->

		<!-- Flex Slider Javascript Files -->

		<script type="text/javascript">
			jQuery(document).ready(function($) {
					px_flexsliderGallery();
			});
		</script>

	<?php

	}

}


// CycleSlider function

if ( ! function_exists( 'px_cycle_slider' ) ) {

	function px_cycle_slider($width,$height,$slider_id){
		 $px_meta_slider_options = get_post_meta($slider_id, "px_meta_gallery_options", true);
			?>
            <script type="text/javascript">
				jQuery(document).ready(function($) {
					jQuery('#slideshow').cycle({
						fx:       'fade',
						timeout:   2000,
						after:     onAfter
					});
				});
				
				function onAfter(curr,next,opts) {
					var caption = 'Image ' + (opts.currSlide + 1) + ' of ' + opts.slideCount;
					jQuery('#caption').html(caption);
				}
			</script>
		<div class="teamdetail-carousel"> 
         <div class="center">
                <span class="cycle-prev" id="cycle-next<?php echo $slider_id;?>"><i class="fa fa-chevron-left"></i></span>
                <span class="cycle-next" id="cycle-prev<?php echo $slider_id;?>"><i class="fa fa-chevron-right"></i></span>
            </div>
             <div id="slideshow" class="cycle-slideshow"
                data-cycle-fx=carousel
                data-cycle-next="#cycle-next<?php echo $slider_id;?>"
                data-cycle-prev="#cycle-prev<?php echo $slider_id;?>"
                data-cycle-slides=">figure"
                data-cycle-timeout=0>
						<?php 
                        $px_counter = 1;
                        $px_xmlObject_flex = new SimpleXMLElement($px_meta_slider_options);
                        
                        foreach ( $px_xmlObject_flex->children() as $as_node )
                        {
                            $image_url = px_attachment_image_src($as_node->path,$width,$height); 
                            if(isset($as_node->link) && $as_node->link <> ''){$link = $as_node->link;} else {$link = '';}
                       		 ?>
                        <figure>
                        <img src="<?php echo $image_url ?>" alt="">   
                        <?php if($as_node->title <> ''){?>
                            <figcaption>
                                <i class="fa fa-camera"></i><h2 class="cs-bgcolr"><a <?php if(isset($as_node->link) && $as_node->link <> ''){?>href="<?php echo $as_node->link;?>" target="<?php echo $as_node->link_target;?>" <?php }?>><?php echo $as_node->title;?></a></h2>
                            </figcaption><?php }?>
                        </figure>
                                
                        <?php 
                            $px_counter++;
                        }
                        ?>
                </div>
                <p id="caption"></p>
			</div>
		<?php
		}

}






function px_page_title(){
	if(function_exists("is_shop") and is_shop()){
		$px_shop_id = woocommerce_get_page_id( 'shop' );
		echo "<div class=\"subtitle\"><h1 class=\"cs-page-title\">".get_the_title($px_shop_id)."</h1></div>";
	}else if(function_exists("is_shop") and !is_shop()){
		echo '<div class="subtitle">';
			get_subheader_title();
		echo '</div>';
	}else{
		echo '<div class="subtitle">';
			get_subheader_title();
		echo '</div>';
	}                        	
}

// Calendar time
function calender_time($event_time) {

	$mints = $mints = $seconds = '';
	$seconds = '00';
	$time = $event_time;
	$time_param = str_replace("PM", '', $event_time);
	$time_param = str_replace("AM", '', $time_param);
	$time_param_array = explode(':', $time_param);
	$pos = strpos($time, 'PM');
	
	if ($pos === false) {
			$hours = $time_param_array['0'];
			$mints = $time_param_array['1'];
	} else {
		if(isset($time_param_array['0']) && $time_param_array['0'] < 12){
			$hours = $time_param_array['0']+12;
		} else {
			$hours = $time_param_array['0'];
		}
		$mints = $time_param_array['1'];
	}
	
   return $hours.':'.$mints.':'.$seconds;

}

function get_formated_date($date)

{

	return mysql2date(get_option('date_format'), $date);

}

function get_formated_time($time)

{

	return mysql2date(get_option('time_format'), $time, $translate=true);;

}

// Calendar

function add_to_calender()

{	global $post;

	$px_theme_option = get_option('px_theme_option');

	$calendar_args=array('outlook'=>1,'google_calender'=>1,'yahoo_calender'=>1,'ical_cal'=>1);

	if($calendar_args)

	{

		$calendar_url = px_event_calendar($post->ID);

			?>

    <div class="add-calender"><a class="bgcolrhvr btn add_calendar_toggle<?php echo $post->ID;?> btn-toggle_cal" href="#inline-<?php echo $post->ID;?>"><i class="fa fa-plus"></i> <?php if($px_theme_option["trans_switcher"] == "on") { _e("Add to Calendar",'Kings Club'); }else{  echo $px_theme_option["trans_add_calendar"];} ?></a>

      <ul class="add_calendar add_calendar<?php echo $post->ID;?>" id="inline-<?php echo $post->ID;?>" >

        <?php if($calendar_args['outlook']){?>

        <li class="i_calendar">

        <a href="<?php echo $calendar_url['ical']; ?>"> 

          <img src="<?php echo get_template_directory_uri(); ?>/images/calendar-icon.png" alt="" width="24" />

        </a> 

        </li>

        <?php }?>

        <?php if($calendar_args['google_calender']){?>

        <li class="i_google"><a href="<?php echo $calendar_url['google']; ?>" target="_blank"> 

          <img src="<?php echo get_template_directory_uri(); ?>/images/google-icon.png" alt="" width="25" />

        </a> 

        </li>

        <?php }?>

        <?php if($calendar_args['yahoo_calender']){?>

        <li class="i_yahoo"><a href="<?php echo $calendar_url['yahoo']; ?>" target="_blank">

          <img src="<?php echo get_template_directory_uri(); ?>/images/yahoo-icon.png" alt="" width="24" />

        </a> 

        </li>

        <?php }?>

      </ul>

    </div>

<?php

	}

}



/*	Function to get the events info on calander -- START	*/

function px_event_calendar($post_id = '') {

	

	if(!isset($post_id) && $post_id == ''){

		global $post;

		$post_id = $post->ID;

	}

	$cal_post = get_post($post_id);

	if ($cal_post) {

		$event_from_date = get_post_meta($post_id, "px_event_from_date", true);

		$px_event_to_date = '';

		$px_event_meta = get_post_meta($post_id, "px_event_meta", true);

			if ( $px_event_meta <> "" ) {

				$px_event_meta = new SimpleXMLElement($px_event_meta);

				if(isset($px_event_meta->event_address) && $px_event_meta->event_address <> ''){
					$location = (string)$px_event_meta->event_address;	
				}else{
					$location = '';
				}
			}
		$start_year = date('Y',strtotime($event_from_date));

		$start_month = date('m',strtotime($event_from_date));

		$start_day = date('d',strtotime($event_from_date));

		$end_year = '';


		$end_month = '';

		$end_day = '';

		if ( $px_event_meta->event_all_day != "on" ) {

			$start_time = calender_time($px_event_meta->event_time);

		} else {

			$start_time = $end_time = '';

		}

		if (($start_time != '') && ($start_time != ':')) { $event_start_time = explode(":",$start_time); }

		$post_title = get_the_title($post_id);
		
		$post_title = html_entity_decode($post_title);

		$px_vcalendar = new vcalendar();                          

		$px_vevent = new vevent();  

		$site_info = get_bloginfo('name').'Events';

		$px_vevent->setProperty( 'categories' , $site_info );                   

		

		if (isset( $event_start_time)) { @$px_vevent->setProperty( 'dtstart' 	,  @$start_year, @$start_month, @$start_day, @$event_start_time[0], @$event_start_time[1], 00 ); } else { $px_vevent->setProperty( 'dtstart' ,  $start_year, $start_month, $start_day ); } // YY MM dd hh mm ss

		/*if (isset($event_end_time)) { @$px_vevent->setProperty( 'dtend'   	,  $end_year, $end_month, $end_day, $event_end_time[0], $event_end_time[1], 00 );  } else { $px_vevent->setProperty( 'dtend' , $end_year, $end_month, $end_day );  }*/ // YY MM dd hh mm ss

		$px_vevent->setProperty( 'description' 	, strip_tags($cal_post->post_excerpt)); 

		if (isset($location)) { $px_vevent->setProperty( 'location'	, $location ); } 

		$px_vevent->setProperty( 'summary'	, $post_title );                 

		$px_vcalendar->addComponent( $px_vevent );                        

		$templateurl = get_template_directory_uri().'/cache/';

		//makeDir(get_bloginfo('template_directory').'/cache/');

		$home = home_url();

		$dir = str_replace($home,'',$templateurl);

		$dir = str_replace('/wp-content/','wp-content/',$dir);
		
		
		$directory_url =  get_template_directory_uri();
		$directorypath = explode('/', $directory_url);
		$themefolderName = $directorypath[count($directorypath)-1];

		$px_vcalendar->setConfig( 'directory', ABSPATH .'wp-content/themes/'.$themefolderName.'/cache' ); 

		$px_vcalendar->setConfig( 'filename', 'event-'.$post_id.'.ics' ); 

		$px_vcalendar->saveCalendar(); 

		////OUT LOOK & iCAL URL//

		$output_calendar_url['ical'] = $templateurl.'event-'.$post_id.'.ics';

		////GOOGLE URL//

		$google_url = "http://www.google.com/calendar/event?action=TEMPLATE";
		$post_title = strip_tags($post_title);
		$google_url .= "&text=".urlencode($post_title);
	
		if (isset($event_start_time) ) { 
			$Start_time = str_replace('.','',@$event_start_time[0]).str_replace('.','',@$event_start_time[1]).str_replace('.','',@$event_start_time[2]);
			$Start_time = str_replace(' ','',$Start_time);
			$google_url .= "&dates=".@$start_year.@$start_month.@$start_day."T".$Start_time.'/'.@$start_year.@$start_month.@$start_day."T".$Start_time; 

		} else { 
			$google_url .= "&dates=".$start_year.$start_month.$start_day."/".$start_year.$start_month.$start_day; 
		}


		$google_url .= "&sprop=website:".get_permalink($post_id);

		$google_url .= "&details=".strip_tags($cal_post->post_excerpt);
		if (isset($location)) { $google_url .= "&location=".$location; } else { $google_url .= "&location=Unknown"; }

		$google_url .= "&trp=true";

		$output_calendar_url['google'] = $google_url;

		////YAHOO CALENDAR URL///

		$yahoo_url = "http://calendar.yahoo.com/?v=60&view=d&type=20";

		$yahoo_url .= "&title=".str_replace(' ','+',$post_title);

		if (isset($event_start_time)) 

		{ 

			$yahoo_url .= "&st=".@$start_year.@$start_month.@$start_day."T".@$event_start_time[0].@$event_start_time[1]."00"; 

		}

		else

		{ 

			$yahoo_url .= "&st=".$start_year.$start_month.$start_day;

		}

		if(isset($event_end_time))

		{

			//$yahoo_url .= "&dur=".$event_start_time[0].$event_start_time[1];

		}

		$yahoo_url .= "&desc=".str_replace(' ','+',strip_tags($cal_post->post_excerpt)).' -- '.get_permalink($post_id);

		$yahoo_url .= "&in_loc=".str_replace(' ','+',$location);

		$output_calendar_url['yahoo'] = $yahoo_url;

	}

	return $output_calendar_url;

} 

// Get Main background
	
	function px_bg_image(){
	
		global $px_theme_option;
	
		$bg_img = '';
		
		
		if ( isset($_POST['bg_img']) ) {
	
			$_SESSION['kcsess_bg_img'] = $_POST['bg_img'];
	
			echo $bg_img = get_template_directory_uri()."/images/background/bg".$_SESSION['kcsess_bg_img'].".png";
	
		}
	
		else if ( isset($_SESSION['kcsess_bg_img']) and !empty($_SESSION['kcsess_bg_img'])){
	
			$bg_img = get_template_directory_uri()."/images/background/bg".$_SESSION['kcsess_bg_img'].".png";
	
		}
	
		else {
	
			if (isset($px_theme_option['bg_img_custom']) and $px_theme_option['bg_img_custom'] == "" ) {
	
				if (isset($px_theme_option['bg_img']) and $px_theme_option['bg_img'] <> 0 ){
	
					$bg_img = get_template_directory_uri()."/images/background/bg".$px_theme_option['bg_img'].".png";
	
				}
	
			}
	
			else  { 
	
				if(isset($px_theme_option['bg_img_custom']))
					$bg_img = $px_theme_option['bg_img_custom'];
	
			}
	
		}
	
		if ( $bg_img <> "" ) {
	
			echo ' style="background:url('.$bg_img.') ' . $px_theme_option['bg_repeat'] . ' top  ' . $px_theme_option['bg_position'] . ' 		' . $px_theme_option['bg_attach'].'"';
	
		}
	
	}
	
	// Main wrapper class function
	
	function px_wrapper_class(){
	
		global $px_theme_option;
		
		
		if ( isset($_POST['layout_option']) ) {
	
			echo $_SESSION['kcsess_layout_option'] = $_POST['layout_option'];
	
		}
	
		elseif ( isset($_SESSION['kcsess_layout_option']) and !empty($_SESSION['kcsess_layout_option'])){
	
			echo $_SESSION['kcsess_layout_option'];
	
		}
	
		else {
			
			if ( isset($px_theme_option['layout_option']) )
				echo $px_theme_option['layout_option'];
	
			$_SESSION['kcsess_layout_option']='';
	
		}
	
	}
	
	// Get Background color Pattren
	
	function px_bgcolor_pattern(){
	
		global $px_theme_option;
	
		// pattern start
		
		$pattern = '';
	
		$bg_color = '';
	
		if ( isset($_POST['custome_pattern']) ) {
	
			$_SESSION['kcsess_custome_pattern'] = $_POST['custome_pattern'];
	
			$pattern = get_template_directory_uri()."/images/pattern/pattern".$_SESSION['kcsess_custome_pattern'].".png";
	
		}
	
		else if ( isset($_SESSION['kcsess_custome_pattern']) and !empty($_SESSION['kcsess_custome_pattern'])){
	
			$pattern = get_template_directory_uri()."/images/pattern/pattern".$_SESSION['kcsess_custome_pattern'].".png";
	
		}
	
		else {
	
			if (isset($px_theme_option['custome_pattern']) and $px_theme_option['custome_pattern'] == "" ) {
	
				if (isset($px_theme_option['pattern_img']) and $px_theme_option['pattern_img'] <> 0 ){
	
					$pattern = get_template_directory_uri()."/images/pattern/pattern".$px_theme_option['pattern_img'].".png";
	
				}
	
			}
	
			else { 
				if ( isset($px_theme_option['custome_pattern']) )
					$pattern = $px_theme_option['custome_pattern'];
	
			}
	
		}
	
		// pattern end
	
		// bg color start
	
		if ( isset($_POST['bg_color']) ) {
	
			$_SESSION['kcsess_bg_color'] = $_POST['bg_color'];
	
			$bg_color = $_SESSION['kcsess_bg_color'];
	
		}
	
		else if ( isset($_SESSION['kcsess_bg_color']) ){
	
			$bg_color = $_SESSION['kcsess_bg_color'];
	
		}
	
		else {
			if ( isset($px_theme_option['bg_color']) )
				$bg_color = $px_theme_option['bg_color'];
	
		}
	
		// bg color end
		if($bg_color <> '' or $pattern <> ''){
			echo ' style="background:'.$bg_color.' url('.$pattern.')" ';
		}
	
	}

	function px_no_result_found(){
		 _e("No results found.",'KingsClub');
	}
	
	// rating function

	function px_user_rating(){
		global $post;
		$user_rating = 0;
		$rating_vote_counter = get_post_meta($post->ID, "rating_vote_counter", true);
		$rating_value = get_post_meta($post->ID, "rating_value", true);
		if ( $rating_value <> 0 and $rating_vote_counter <> 0 ) {
			$user_rating =  ( $rating_value / $rating_vote_counter  ) ;
		}
		return $user_rating = number_format( $user_rating);
	}
	
	function px_player_pointtable($pointtable){
		global $post,$px_theme_option;
		if(isset($pointtable) && $pointtable <> ''){
			$args=array(
				'name' => (string)$pointtable,
				'post_type' => 'pointtable',
				'post_status' => 'publish',
				'showposts' => 1,
			);
			$get_posts = get_posts($args);
			if($get_posts){
				$gal_pointtable_id = (int)$get_posts[0]->ID;
				$pointtable_counter=1;
				$px_pointtable = get_post_meta($gal_pointtable_id, "px_pointtable", true);
				if ( $px_pointtable <> "" ) {
					$px_xmlObject = new SimpleXMLElement($px_pointtable);
					$var_pb_record_per_post =$px_xmlObject->var_pb_record_per_post;
				}else{
					$var_pb_record_per_post= '';
				}
			?>
            <header class="pix-heading-title">
                <h2 class="pix-section-title"><?php echo get_the_title((int)$gal_pointtable_id);?></h2>
             </header>
             <div class="points-table fullwidth">
             	
                    <table class="table table-condensed table_D3D3D3">
                        <thead>
                            <tr>
                            <th>
                                <span class="box1">
                                    <?php if($px_theme_option["trans_switcher"] == "on") { _e("Pos",'Kings Club'); }else{  echo $px_theme_option["trans_pos"];} ?>
                                </span>
                            </th>
                             <th>
                                <span class="box2">
                                    <?php if($px_theme_option["trans_switcher"] == "on") { _e("Team",'Kings Club'); }else{  echo $px_theme_option["trans_team"];} ?>
                                 </span>
                            </th>
                            <th>
                                <span class="box3">
                                    <?php if($px_theme_option["trans_switcher"] == "on") { _e("Play",'Kings Club'); }else{  echo $px_theme_option["trans_play"];} ?>
                                </span>
                            </th>
                            <th>
                                <span class="box4">
                                    <?php if($px_theme_option["trans_switcher"] == "on") { _e(" +/-",'Kings Club'); }else{  echo $px_theme_option["trans_plusminus"];} ?>
                                </span>
                            </th>
                            <th>
                                <span class="box5">
                                    <?php if($px_theme_option["trans_switcher"] == "on") { _e("Points",'Kings Club'); }else{  echo $px_theme_option["trans_totalpoints"];} ?>  			 	</span>
                            </th>
                            </tr>
                         </thead>
                         <tbody>
                     
                  <?php
                  if($px_xmlObject->var_pb_record_per_post <> '' and $px_xmlObject->var_pb_record_per_post > 0){

                    foreach ( $px_xmlObject->track as $track ){
                        if(($pointtable_counter-1) < $px_xmlObject->var_pb_record_per_post){
                            
                            if(isset($track->var_pb_pointtable_team) && $track->var_pb_pointtable_team <> ''){
                                $row_cat = px_get_term_object($track->var_pb_pointtable_team);
                                $teamname = $row_cat->name;	
                            } else {
                                $teamname = '';	
                            }
                        echo '<tr>
                              <td>'.$pointtable_counter.'</td>
                              <td>'.$teamname.'</td>
                              <td>'.$track->var_pb_match_played.'</td>
                              <td>'.$track->var_pb_pointtable_plusminus_points.'</td>
                              <td>'.$track->var_pb_pointtable_totalpoints.'</td>
                        </tr>';
                       }
                          $pointtable_counter++;
                      }
                  }else{
                      foreach ( $px_xmlObject->track as $track ){
                          if(isset($track->var_pb_pointtable_team) && $track->var_pb_pointtable_team <> ''){
                                $row_cat = px_get_term_object($track->var_pb_pointtable_team);
                                $teamname = $row_cat->name;	
                            } else {
                                $teamname = '';	
                            }
                        echo '<tr>
                              <td>'.$pointtable_counter.'</td>
                              <td>'.$teamname.'</td>
                              <td>'.$track->var_pb_match_played.'</td>
                              <td>'.$track->var_pb_pointtable_plusminus_points.'</td>
                              <td>'.$track->var_pb_pointtable_totalpoints.'</td>
                        </tr>';
                       }
                          $pointtable_counter++;
                  }
                 ?>
                </tbody>
             </table>
           </div>
			<?php
			}
		}
	}
	// review criteria check
	function px_criteria_check($value) {
		global $px_theme_option;
		$html = '';
		for ( $j = 1; $j <= 10; $j++ ) {
			if ( $value >= $px_theme_option['review_criteria_'.$j.'_1'] and $value <= $px_theme_option['review_criteria_'.$j.'_2'] ) {
				$html = $px_theme_option['review_criteria_text_'.$j.''];
			}
		}
		return $html;
	}
	function px_rating_section($px_xmlObject){
		global $post;
		$image_url_small = px_get_post_img_src($post->ID, 470, 353);	
		?>
        	<div class="px-review-section <?php echo $px_xmlObject->var_pb_review_section_position;?>">
                   <?php if($px_xmlObject->var_pb_review_section_title <> '') {?>
                            <header class="pix-heading-title">
                                <h2 class="pix-section-title"><?php echo $px_xmlObject->var_pb_review_section_title; ?></h2>
                            </header>
                	<?php }?>
                    <!-- Blog Rating Section Start -->
                      <div class="blog-rating-sec">
                      	   
                            <figure>
                            	<?php if($image_url_small <> ''){?>
                                	<img src="<?php echo $image_url_small;?>" alt="<?php the_title();?>">
                                <?php }?>
                          <figcaption>
                          	<?php
								$rating_value = get_post_meta($post->ID, "rating_value", true);
								if($rating_value == ''){
								 $rating_value = 0;
								}
							 ?>
							<script type="text/javascript">
								  jQuery(document).ready(function(){
										jQuery(".basic ").jRating({
												bigStarsPath : '<?php echo get_template_directory_uri(); ?>/images/stars.png', // path of the icon stars.png
												smallStarsPath : '<?php echo get_template_directory_uri(); ?>/images/small.png', // path of the icon small.png
												phpPath : '<?php echo get_template_directory_uri()."/include/review_save.php?id=".$post->ID?>', // path of the php file jRating.php
												rateMax : 10,
												length : 5
										});
								  });
							</script>
							<?php px_enqueue_rating_style_script();?>
							<strong>User Rating: </strong>
							<div id="rating_saved">
								<div id="rating_saved">
									<h6 class="heading-color">
										<?php 
										echo px_user_rating();
										if ( get_post_meta(get_the_id(), "rating_vote_counter", true) > 0 ) {
											$rating_vote_counter = get_post_meta(get_the_id(), "rating_vote_counter", true);
										}
										else {
											$rating_vote_counter = 0;
										}
										echo " ( " . $rating_vote_counter . " Votes )";
										?>
									</h6>
								</div>
							</div>
							<div id="rating_loading" style="display:none"><i class='fa fa-spinner fa-spin fa-1x'></i></div>
							<div class="px-star-rating basic <?php if ( isset($_COOKIE["rating_vote_counter".$post->ID ]) ){echo "jDisabled"; }?>" data="<?php px_user_rating()*10;?>"><span style="width:<?php px_user_rating()*10;?>%"></span></div>
                            <?php 
								$rating = px_user_rating();
								echo px_criteria_check($rating*10);
							?>
                          </figcaption>
                        </figure>
                       <?php if(isset($px_xmlObject->reviews)) {?>
                            <ul>
                             <?php foreach($px_xmlObject->reviews as $reviews){?>
                              <li>
                                <?php if($reviews->var_pb_review_title <> ''){?><span><?php echo $reviews->var_pb_review_title;?></span><?php }?>
                                <?php if($reviews->var_pb_review_points <> ''){?><span><?php echo $reviews->var_pb_review_points;?></span><?php }?>
                                  <div class="progress-wrap">
                                    <div data-loadbar-text="<?php echo round($reviews->var_pb_review_points);?>%" data-loadbar="<?php echo round($reviews->var_pb_review_points*10);?>" class="progress-bar-charity">
                                      <div class="px-bgcolr"></div>
                                    </div>
                                  </div>
                              </li>
                              <?php }?>
                            </ul>
                        <?php }?>
                      </div>
                      <!-- Blog Rating Section End -->
                   </div>
        <?php	
	}
	


function px_generate_random_string($length = 3) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function px_subval_sort_array($a,$subkey) {
	foreach($a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	arsort($b);
	foreach($b as $key=>$val) {
		$c[] = $a[$key];
	}
	return $c;
}

// review criteria check
function cs_criteria_check($value) {
	global $px_theme_option;
	$html = '';
	for ( $j = 1; $j <= 10; $j++ ) {
		if ( $value >= $px_theme_option['review_criteria_'.$j.'_1'] and $value <= $px_theme_option['review_criteria_'.$j.'_2'] ) {
			$html = $px_theme_option['review_criteria_text_'.$j.''];
		}
	}
	return $html;
}


// Front End Functions END