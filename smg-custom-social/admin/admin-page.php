<?php


function smg_social_images_options_enqueue_scripts() {
    wp_register_script( 'smg-image-upload', plugin_dir_url( __FILE__ ) .'../js/smg-image-upload.js', array('jquery','media-upload','thickbox') );

    if ( 'settings_page_smg_social_images' == get_current_screen() -> id ) {
        wp_enqueue_script('jquery');

        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');

        wp_enqueue_script('media-upload');
        wp_enqueue_script('smg-image-upload');

    }

}
add_action('admin_enqueue_scripts', 'smg_social_images_options_enqueue_scripts');
/**
* @internal never define functions inside callbacks.
* these functions could be run multiple times; this would result in a fatal error.
*/

/**
* custom option and settings
*/
function smg_social_images_settings_init() {


// register a new setting for "smg_social_images" page
register_setting( 'smg_social_images', 'smg_social_images_options' );

  // register a new section in the "smg_social_images" page
  add_settings_section(
    'smg_social_images_section_developers',
    __( 'Default Social Image', 'smg_social_images' ),
    'smg_social_images_section_developers_cb',
    'smg_social_images'
  );

  // register a new field in the "smg_social_images_section_developers" section, inside the "smg_social_images" page
  add_settings_field(
    'smg_social_images', // as of WP 4.6 this value is used only internally
    // use $args' label_for to populate the id inside the callback
    __( 'Social Default Image', 'smg_social_images' ),
    'smg_social_images_cb',
    'smg_social_images',
    'smg_social_images_section_developers',
    [
    'label_for' => 'smg_social_images',
    'class' => 'smg_social_images_row',
    'smg_social_images_custom_data' => 'custom',
    ]
  );

  add_settings_field('smg_social_images_setting_logo_preview',  __( 'Logo Preview', 'smg_social_images' ), 'smg_social_images_setting_logo_preview', 'smg_social_images', 'smg_social_images_settings_header');
}

function smg_social_images_setting_logo_preview() {
    $options = get_option( 'smg_social_images_options' );  ?>
    <div id="upload_logo_preview" style="min-height: 100px;">
        <img style="max-width:100%;" src="<?php echo esc_url( $options['logo'] ); ?>" />
    </div>
    <?php
}
/**
* register our smg_social_images_settings_init to the admin_init action hook
*/
add_action( 'admin_init', 'smg_social_images_settings_init' );

/**
* custom option and settings:
* callback functions
*/

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function smg_social_images_section_developers_cb( $args ) {
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
function smg_social_images_cb( $args ) {
  // get the value of the setting we've registered with register_setting()
  $options = get_option( 'smg_social_images_options' );
  // output the field
  ?>
  <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
  data-custom="<?php echo esc_attr( $args['smg_social_images_custom_data'] ); ?>"
  name="smg_social_images_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
  value="<?php echo $options['smg_social_images']; ?>"
  />
  <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="smg_social_images_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_url( $options['smg_social_images'] ); ?>" />
        <input id="upload_logo_button" type="button" class="button" value="<?php _e( 'Upload Logo', 'wptuts' ); ?>" />
        <span class="description"><?php _e('Upload a Default Social Image.', 'wptuts' ); ?></span>

  <?php
}


/**
* top level menu
*/
function smg_social_images_options_page() {
// add top level menu page
  add_options_page(
    'Social Default Image',
    'Social Default Image',
    'manage_options',
    'smg_social_images',
    'smg_social_images_options_page_html'
  );
}

/**
* register our smg_social_images_options_page to the admin_menu action hook
*/
add_action( 'admin_menu', 'smg_social_images_options_page' );

/**
* top level menu:
* callback functions
*/
function smg_social_images_options_page_html() {
// check user capabilities
  if ( ! current_user_can( 'manage_options' ) ) {
  return;
}

// add error/update messages

// check if the user have submitted the settings
// wordpress will add the "settings-updated" $_GET parameter to the url
if ( isset( $_GET['settings-updated'] ) ) {
// add settings saved message with the class of "updated"
  add_settings_error( 'smg_social_images_messages', 'smg_social_images_message', __( 'Settings Saved', 'smg_social_images' ), 'updated' );
}

// show error/update messages
settings_errors( 'smg_social_images_messages' );
?>
<div class="wrap">
  <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
  <form action="options.php" method="post">
    <?php
    // output security fields for the registered setting "smg_social_images"
    settings_fields( 'smg_social_images' );
    // output setting sections and their fields
    // (sections are registered for "smg_social_images", each field is registered to a specific section)
    do_settings_sections( 'smg_social_images' );


    // output save settings button
    submit_button( 'Save Settings' );
    ?>
  </form>
</div>
<?php
}
