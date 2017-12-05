<?php


function kemi_social_images_options_enqueue_scripts() {

    if ( 'settings_page_kemi_social_images' == get_current_screen() -> id ) {
        wp_enqueue_script('kemi-image-upload', plugin_dir_url( __FILE__ ) .'../js/image-upload.js',  array('jquery') );

    }

}
add_action('admin_enqueue_scripts', 'kemi_social_images_options_enqueue_scripts');
/**
* @internal never define functions inside callbacks.
* these functions could be run multiple times; this would result in a fatal error.
*/

/**
* custom option and settings
*/
function kemi_social_images_settings_init() {


// register a new setting for "kemi_social_images" page
register_setting( 'kemi_social_images', 'kemi_social_images_options' );

  // register a new section in the "kemi_social_images" page
  add_settings_section(
    'kemi_social_images_section_developers',
    __( 'Default Social Image', 'kemi_social_images' ),
    'kemi_social_images_section_developers_cb',
    'kemi_social_images'
  );

  // register a new field in the "kemi_social_images_section_developers" section, inside the "kemi_social_images" page
  add_settings_field(
    'kemi_social_images', // as of WP 4.6 this value is used only internally
    // use $args' label_for to populate the id inside the callback
    __( 'Social Default Image', 'kemi_social_images' ),
    'kemi_social_images_cb',
    'kemi_social_images',
    'kemi_social_images_section_developers',
    [
    'label_for' => 'kemi_social_images',
    'class' => 'kemi_social_images_row',
    'kemi_social_images_custom_data' => 'custom',
    ]
  );


}

/**
* register our kemi_social_images_settings_init to the admin_init action hook
*/
add_action( 'admin_init', 'kemi_social_images_settings_init' );

/**
* custom option and settings:
* callback functions
*/

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function kemi_social_images_section_developers_cb( $args ) {
?>
<?php
}

// pill field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function kemi_social_images_cb( $args ) {
  // get the value of the setting we've registered with register_setting()
  $options = get_option( 'kemi_social_images_options' );
  // output the field
  // Save attachment ID

  wp_enqueue_media();
  ?>
  <input type="hidden" id="<?php echo esc_attr( $args['label_for'] ); ?>"
  data-custom="<?php echo esc_attr( $args['kemi_social_images_custom_data'] ); ?>"
  name="kemi_social_images_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
  value="<?php echo $options['kemi_social_images']; ?>" />
  <div class='image-preview-wrapper'>
			<img id='image-preview' src='<?php echo wp_get_attachment_url(  $options['kemi_social_images'] ); ?>' height='100'>
		</div>
		<input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />
		<input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo $options['kemi_social_images']; ?>'>
  <?php
  if ( isset( $_POST['submit'] ) && isset( $_POST['image_attachment_id'] ) ) :
		update_option( 'kemi_social_images_options', absint( $_POST['image_attachment_id'] ) );
    exit();
	endif;
}


/**
* top level menu
*/
function kemi_social_images_options_page() {
// add top level menu page
  add_options_page(
    'Social Default Image',
    'Social Default Image',
    'manage_options',
    'kemi_social_images',
    'kemi_social_images_options_page_html'
  );
}

/**
* register our kemi_social_images_options_page to the admin_menu action hook
*/
add_action( 'admin_menu', 'kemi_social_images_options_page' );

/**
* top level menu:
* callback functions
*/
function kemi_social_images_options_page_html() {
// check user capabilities
  if ( ! current_user_can( 'manage_options' ) ) {
  return;
}

// add error/update messages

// check if the user have submitted the settings
// wordpress will add the "settings-updated" $_GET parameter to the url
if ( isset( $_GET['settings-updated'] ) ) {
// add settings saved message with the class of "updated"
  add_settings_error( 'kemi_social_images_messages', 'kemi_social_images_message', __( 'Settings Saved', 'kemi_social_images' ), 'updated' );
}

// show error/update messages
settings_errors( 'kemi_social_images_messages' );
?>
<div class="wrap">
  <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
  <form action="options.php" method="post">
    <?php
    // output security fields for the registered setting "kemi_social_images"
    settings_fields( 'kemi_social_images' );
    // output setting sections and their fields
    // (sections are registered for "kemi_social_images", each field is registered to a specific section)
    do_settings_sections( 'kemi_social_images' );


    // output save settings button
    submit_button( 'Save Settings' );
    ?>
  </form>
</div>
<?php
}
