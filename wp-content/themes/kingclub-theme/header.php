<?php
	global $px_theme_option, $px_page_builder, $px_meta_page, $px_node;
	$px_theme_option = get_option('px_theme_option');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>
	<?php
	    bloginfo('name'); ?> | 
    <?php 
		if ( is_home() or is_front_page() ) { bloginfo('description'); }
		else { wp_title(''); }
    ?>
    </title>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
	<link rel="shortcut icon" href="<?php echo $px_theme_option['fav_icon'] ?>" />
	
    <!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<?php 
	if(isset($px_theme_option['header_code']))
    	echo  htmlspecialchars_decode($px_theme_option['header_code']); 
	    if ( is_singular() && get_option( 'thread_comments' ) )
        	wp_enqueue_script( 'comment-reply' );  
         	wp_head(); 
    ?>
    </head>
	<body <?php body_class(); px_bg_image(); px_bgcolor_pattern();  ?> >
 		<?php  
			px_custom_styles();
			px_color_switcher();
		?>
		<div id="wrappermain-pix" class="wrapper <?php echo px_wrapper_class();?>">
		<!-- Header Start -->
        <header id="header">
            <!-- Top Head Start -->
            <div class="top-head-off">
            	<div class="container">
                    <!-- Logo -->
                    <div class="logo">
                        <?php
                             if(isset($px_theme_option['logo']) && $px_theme_option['logo'] <> ''){
                                  px_logo($px_theme_option['logo'], $px_theme_option['logo_width'], $px_theme_option['logo_height']);
                            } else {
								echo '<a href="'.home_url().'">';
                                	bloginfo('name');
								echo '</a>';
                            }
                         ?>
                    </div>
                    <!-- Logo Close -->

                </div>
            </div>
            <!-- Top Head End -->
            <div id="mainheader">
                <div class="container">
                    <!-- Right Header -->
                    <nav class="navigation">
                    	<a class="cs-click-menu"><i class="fa fa-bars"></i></a>
                        <?php px_navigation('main-menu'); ?>
                    </nav>
                    
                    <div class="rightheader">
                        <?php  if(isset($px_theme_option['header_search']) and $px_theme_option['header_search'] == 'on'){?>    
                                    <!-- Search Section -->    
                                    <div class="searcharea">
                                        <a class="btnsearch" href="#searchbox">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </div>
                                    <!-- Search Section Close--> 
                        <?php } ?>
                        <?php 
							 if(function_exists( 'is_woocommerce' ) && isset($px_theme_option['header_cart']) && $px_theme_option['header_cart'] == 'on'){
								px_woocommerce_header_cart();
							}
							 if ( function_exists('icl_object_id') ) {?>
                                <div class="language-sec">
                                    <!-- Wp Language Start -->
                                     <?php
									  if(isset($px_theme_option['header_languages']) and $px_theme_option['header_languages'] == 'on'){
										  echo do_action('icl_language_selector');
									  }
									?>
                                </div>
                              <?php 
                            }
                         ?>
                     </div>
                    <!-- Right Header Close --> 
                       <?php  if(isset($px_theme_option['header_search']) and $px_theme_option['header_search'] == 'on'){?>
                        <div id="searcharea">
                            <div class="searchform">
                                <?php echo px_search(); ?>
                               </div>
                        </div>
                        <?php } ?>
                    </div>
            </div>
        </header>
    <!-- Header Close -->
    <div class="clear"></div>
    <div id="main">
        <!-- Inner Main -->
        <div id="innermain">
             <?php
				if(isset($px_theme_option['announcement_fixtures_category']) && $px_theme_option['announcement_fixtures_category'] <> ''){ 
					$announcement_category =$px_theme_option['announcement_fixtures_category']; 
					fnc_announcement();
				}else{
					$announcement_category ='';
				}
			 ?>
              <?php 
				if(is_home() || is_front_page()){
					if(isset($advertisingwidgets['home-top-widget']) && count($advertisingwidgets['home-top-widget'])>0){?>
                        <div class="home-top-widget">
                        	<div class="container">
                            	
                        		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('home-top-widget')) : ?><?php endif; ?>
                            </div>
                        </div>
                 <?php }
			 }?>
           <div class="container">
                <div class="row">
					 <?php if(!is_home() and !is_front_page()) {
						 if(isset($px_theme_option['header_breadcrumbs']) && $px_theme_option['header_breadcrumbs'] <> ''){ 
						  ?>
                            <div class="breadcrumb"> 
                                <?php px_breadcrumbs(); ?>
                            </div>
                    	<?php }
					
					 }
					 ?>
                      
                    
                    
                  