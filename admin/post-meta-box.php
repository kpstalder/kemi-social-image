<?php

function kemi_social_images_enqueue_scripts( $hook ) {
  if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
    wp_enqueue_script('kemi-image-upload', plugin_dir_url( __FILE__ ) .'../js/image-upload-post-meta.js',  array('jquery') );
  }
}
add_action('admin_enqueue_scripts', 'kemi_social_images_enqueue_scripts');

/* --------------------------------------
	ADDING POST META FiELDS TO POST PAGES
-------------------------------------- */

/*
 * Huge thanks to HUGH LASHBROOKE for this code!
 * Url: https://hugh.blog/2015/12/18/create-a-custom-featured-image-box/
 */

add_action( 'add_meta_boxes', 'kemi_social_image_add_metabox' );
function kemi_social_image_add_metabox () {
	add_meta_box( 'listingimagediv', __( 'Social Image', 'text-domain' ), 'kemi_social_image_metabox', 'post', 'side', 'low');
}

function kemi_social_image_metabox ( $post ) {
	global $content_width, $_wp_additional_image_sizes;
	$image_id = get_post_meta( $post->ID, '_listing_image_id', true );
	$old_content_width = $content_width;
	$content_width = 254;
	if ( $image_id && get_post( $image_id ) ) {
		if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
			$thumbnail_html = wp_get_attachment_image( $image_id, array( $content_width, $content_width ) );
		} else {
			$thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
		}
		if ( ! empty( $thumbnail_html ) ) {
			$content = $thumbnail_html;
			$content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_listing_image_button" >' . esc_html__( 'Remove listing image', 'text-domain' ) . '</a></p>';
			$content .= '<input type="hidden" id="upload_listing_image" name="_listing_cover_image" value="' . esc_attr( $image_id ) . '" />';
		}
		$content_width = $old_content_width;
	} else {
		$content = '<img src="" style="width:' . esc_attr( $content_width ) . 'px;height:auto;border:0;display:none;" />';
		$content .= '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set listing image', 'text-domain' ) . '" href="javascript:;" id="upload_listing_image_button" id="set-listing-image" data-uploader_title="' . esc_attr__( 'Choose an image', 'text-domain' ) . '" data-uploader_button_text="' . esc_attr__( 'Set listing image', 'text-domain' ) . '">' . esc_html__( 'Set listing image', 'text-domain' ) . '</a></p>';
		$content .= '<input type="hidden" id="upload_listing_image" name="_listing_cover_image" value="" />';
	}
	echo $content;
}
add_action( 'save_post', 'listing_image_save', 10, 1 );
function listing_image_save ( $post_id ) {
	if( isset( $_POST['_listing_cover_image'] ) ) {
		$image_id = (int) $_POST['_listing_cover_image'];
		update_post_meta( $post_id, '_listing_image_id', $image_id );
	}
}
