<?php

/* --------------------------------------
	ADDING POST META FiELDS TO POST PAGES
-------------------------------------- */

/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'fl_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'fl_post_meta_boxes_setup' );


/* Create one or more meta boxes to be displayed on the post editor screen. */
function fl_add_post_meta_boxes() {

  /*
  add_meta_box(
    string $id,
    string $title,
    callable $callback,
    string|array|WP_Screen $screen = null,
    string $context = 'advanced',
    string $priority = 'default',
    array $callback_args = null
  );
  */

  add_meta_box(
    'fl_post_security',      // Unique ID
    esc_html__( 'Social Image', 'KemiCreative' ),    // Title
    'fl_post_security_meta_box',   // Callback function
    null, // Admin page (or post type)
    'side', // Context
    'high' // Priority
  );
}

/* Display the post meta box. */
function fl_post_security_meta_box( $post ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'fl_post_security_nonce' ); ?>
	<?php $value = esc_attr( get_post_meta( $post->ID, 'fl_post_security', true ) ); ?>

  <p class="hide-if-no-js">
    <a href="http://localhost:8888/kc/wp-admin/media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=1" id="set-post-thumbnail" aria-describedby="set-post-thumbnail-desc" class="thickbox"><img width="266" height="216" src="http://localhost:8888/kc/wp-content/uploads/2017/12/todd-quackenbush-701.jpg" class="attachment-266x266 size-266x266" alt="" srcset="http://localhost:8888/kc/wp-content/uploads/2017/12/todd-quackenbush-701.jpg 2000w, http://localhost:8888/kc/wp-content/uploads/2017/12/todd-quackenbush-701-300x244.jpg 300w, http://localhost:8888/kc/wp-content/uploads/2017/12/todd-quackenbush-701-768x624.jpg 768w, http://localhost:8888/kc/wp-content/uploads/2017/12/todd-quackenbush-701-1024x832.jpg 1024w" sizes="(max-width: 266px) 100vw, 266px">
    </a>
  </p>
  <p class="hide-if-no-js howto" id="set-post-thumbnail-desc">Click the image to edit or update</p><p class="hide-if-no-js"><a href="#" id="remove-post-thumbnail">Remove featured image</a></p><input type="hidden" id="_thumbnail_id" name="_thumbnail_id" value="12">

  <p class="hide-if-no-js"><a href="http://localhost:8888/kc/wp-admin/media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=1" id="set-post-thumbnail" class="thickbox">Set featured image</a></p>
  <input type="hidden" id="_thumbnail_id" name="_thumbnail_id" value="-1">

	<input class="" type="checkbox" name="fl_post_security" id="fl_post_security" value="checked" <?php echo esc_attr( get_post_meta( $post->ID, 'fl_post_security', true ) ); ?> /> <?php echo ($value == 'checked' ? 'Locked Down' : 'Unlocked'); ?>
<?php }

/* Save post meta on the 'save_post' hook. */
add_action( 'save_post', 'fl_save_post_class_meta', 10, 2 );

/* Meta box setup function. */
function fl_post_meta_boxes_setup() {

  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'fl_add_post_meta_boxes' );

  /* Save post meta on the 'save_post' hook. */
  add_action( 'save_post', 'fl_save_post_class_meta', 10, 2 );
}

/* Save the meta box's post metadata. */
function fl_save_post_class_meta( $post_id, $post ) {

  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['fl_post_security_nonce'] ) || !wp_verify_nonce( $_POST['fl_post_security_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = ( isset( $_POST['fl_post_security'] ) ? sanitize_html_class( $_POST['fl_post_security'] ) : '' );

  /* Get the meta key. */
  $meta_key = 'fl_post_security';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );
}
?>
