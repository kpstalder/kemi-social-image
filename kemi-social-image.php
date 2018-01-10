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

add_action( 'wpseo_add_opengraph_images', 'add_custom_social_image', 10);
// add_filter('wpseo_twitter_image','testing_123' , 10, 2 );

function add_custom_social_image($object){
  //Logic Order
    // 1st - Social Image on post
    // 2nd - Featured Image on post
    // 3rd - Default Image from theme settings
  global $post;
  if(get_post_meta( $post->ID, '_listing_image_id', true )){
    $social_image = wp_get_attachment_image_url( get_post_meta( $post->ID, '_listing_image_id', true ), 'full' );
    $object->add_image($social_image);
  } else if ( has_post_thumbnail($post->ID)){
  } else {
    $options = get_option( 'kemi_social_images_options' );
    $social_image = wp_get_attachment_url(  $options['kemi_social_images'] );
    $object->add_image($social_image);
  }


 }
 function my_own_og_function() {
     /*
      Put the logic to determine which image to display here (set the $my_image_url variable)
     */
     global $post;
     do {
       if ( has_post_thumbnail($post->ID) ){
           continue;
       } else if(get_post_meta( $post->ID, '_listing_image_id', true )){
         $social_image = wp_get_attachment_image_url( get_post_meta( $post->ID, '_listing_image_id', true ), 'full' );
         $GLOBALS['wpseo_og']->image_output( $social_image ); // This will echo out the og tag in line with other WPSEO og tags
       }else{
         $options = get_option( 'kemi_social_images_options' );
         $social_image = wp_get_attachment_url(  $options['kemi_social_images'] );
         $GLOBALS['wpseo_og']->image_output( $social_image ); // This will echo out the og tag in line with other WPSEO og tags
       }
     } while (0);

//     $GLOBALS['wpseo_og']->options->og_default_image = $social_image."2" ;
     //$GLOBALS['wpseo_tw']->image_output( $social_image ); // This will echo out the og tag in line with

 }
 //add_action( 'wpseo_opengraph', 'my_own_og_function', 29 );
 function my_own_twitter_function() {
     /*
      Put the logic to determine which image to display here (set the $my_image_url variable)
     */
     global $post;
     do {
       if ( has_post_thumbnail($post->ID)){
           break;
       } else if(get_post_meta( $post->ID, '_listing_image_id', true )){
         $social_image = wp_get_attachment_image_url( get_post_meta( $post->ID, '_listing_image_id', true ), 'full' );
          echo '<meta name="twitter:image" content="'.$social_image.'" />';
       } else{
         $options = get_option( 'kemi_social_images_options' );
         $social_image = wp_get_attachment_url(  $options['kemi_social_images'] );
          echo '<meta name="twitter:image" content="'.$social_image.'" />';
       }
     } while (0);

 }
//  add_action( 'wpseo_twitter', 'my_own_twitter_function', 29 );
