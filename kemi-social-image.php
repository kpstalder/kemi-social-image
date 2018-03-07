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


function custom_social_image(){
  global $post;
  if(get_post_meta( $post->ID, '_listing_image_id', true )){
    $social_image = wp_get_attachment_image_url( get_post_meta( $post->ID, '_listing_image_id', true ), 'full' );
  } else if ( has_post_thumbnail($post->ID)){
      $social_image = get_the_post_thumbnail_url($post->ID);
  }else{
    $options = get_option( 'kemi_social_images_options' );
    $social_image = wp_get_attachment_url(  $options['kemi_social_images'] );
  }
  return $social_image;
}


add_action( 'wpseo_add_opengraph_images', 'add_custom_social_image', 10);
// add_filter('wpseo_twitter_image','testing_123' , 10, 2 );

function add_custom_social_image($object){
  //Logic Order
    // 1st - Social Image on post
    // 2nd - Featured Image on post
    // 3rd - Default Image from theme settings
  $social_image = custom_social_image();
  $object->add_image($social_image);

}


 add_filter('wpseo_opengraph_image','filter_custom_social_image' , 10, 2 );
 add_filter('wpseo_twitter_image','filter_custom_social_image' , 10, 2 );
  function filter_custom_social_image(){
   //Logic Order
     // 1st - Social Image on post
     // 2nd - Featured Image on post
     // 3rd - Default Image from theme settings
   $social_image = custom_social_image();
   return $social_image;

  }
