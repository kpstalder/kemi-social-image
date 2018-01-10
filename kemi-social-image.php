<?php
/*
Plugin Name:  Custom Social Images
Plugin URI:   https://weneedone.com
Description:  Override Yoast Social to have default social image
Version:      20171203
Author:       Kevin Stalder & Michael Schut
Author URI:   https://weneedone.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  KemiCreative
Domain Path:  /languages
*/




require_once( 'admin/admin-page.php' );
require_once( 'admin/post-meta-box.php' );

add_filter('wpseo_opengraph_image','add_custom_social_image' , 10, 2 );
add_filter('wpseo_twitter_image','add_custom_social_image' , 10, 2 );
function add_custom_social_image(){
  //Logic Order
    // 1st - Social Image on post
    // 2nd - Featured Image on post
    // 3rd - Default Image from theme settings
  $options = get_option( 'kemi_social_images_options' );
  $social_image = wp_get_attachment_url(  $options['kemi_social_images'] );

  return $social_image;

}
