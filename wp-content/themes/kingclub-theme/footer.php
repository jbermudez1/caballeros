                </div>
            </div> 
             <?php 
			 	global $px_theme_option;
				echo px_show_partner();
				?>
        </div>
    	<!-- Inner Main -->
    </div>
    <div class="footer-widget">
    <?php $advertisingwidgets = wp_get_sidebars_widgets();?>
    <?php  if((isset($advertisingwidgets['footer-advertisement-widget']) && count($advertisingwidgets['footer-advertisement-widget'])>0) || (isset($advertisingwidgets['footer-widget']) && count($advertisingwidgets['footer-widget'])>0)){?>
    	<div class="container">
        	<?php  if(isset($advertisingwidgets['footer-advertisement-widget']) && count($advertisingwidgets['footer-advertisement-widget'])>0){?>
                     <div class="footer-advertising-area">
                        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-advertisement-widget')) : ?><?php endif; ?>
                     </div>
             <?php }?>
            <div class="footer-icons">
                <?php
                    if(isset($px_theme_option['footer_social_icons']) && $px_theme_option['footer_social_icons'] == 'on'){
                        px_social_network();
                    }
                ?>
            </div>
        </div>
        <?php }?>
        <!-- Container Start -->
        <?php if(isset($advertisingwidgets['footer-widget']) && count($advertisingwidgets['footer-widget'])>0){?>
        <div class="container">
            <!-- Footer Widgets Start -->
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-widget')) : ?><?php endif; ?>
            <!-- Footer Widgets End -->
        </div>
        <?php }?>
        <!-- Container End -->
    	<footer id="footer">
            <div class="container">
                <p class="coptyright">
                    <?php 
					if(!isset($px_theme_option['copyright'])){
						echo '&copy;'.gmdate("Y")." ".get_option("blogname")." Wordpress All rights reserved.";
					} else {
						if(isset($px_theme_option['copyright'])) echo do_shortcode(htmlspecialchars_decode($px_theme_option['copyright']));
						if(isset($px_theme_option['powered_by'])) echo do_shortcode(htmlspecialchars_decode($px_theme_option['powered_by']));
					}
					?>
                </p>
                <!--<a href="" class="btn btngotop"><i class="fa fa-arrow-circle-o-up"></i></a>-->
            </div>
        </footer>
    </div>
</div>
<!-- Wrapper End -->
<?php 
px_footer_settings();
wp_footer();?>
</body>
</html>
