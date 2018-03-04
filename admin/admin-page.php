<?php


function kemi_cabinet_csv_options_enqueue_scripts() {

  if ( 'settings_page_kemi_cabinet_csv' == get_current_screen() -> id ) {
    wp_enqueue_script('kemi-image-upload', plugin_dir_url( __FILE__ ) .'../js/image-upload.js',  array('jquery') );
    wp_enqueue_script('kemi-csv', plugin_dir_url( __FILE__ ) .'../js/csv.js',  array('jquery') );
  }

}
add_action('admin_enqueue_scripts', 'kemi_cabinet_csv_options_enqueue_scripts');
/**
* @internal never define functions inside callbacks.
* these functions could be run multiple times; this would result in a fatal error.
*/

/**
* custom option and settings
*/
function kemi_cabinet_csv_settings_init() {


// register a new setting for "kemi_cabinet_csv" page
register_setting( 'kemi_cabinet_csv', 'kemi_cabinet_csv_options' );

  // register a new section in the "kemi_cabinet_csv" page
  add_settings_section(
    'kemi_cabinet_csv_section_developers',
    __( 'Cabinet Product CSV', 'kemi_cabinet_csv' ),
    'kemi_cabinet_csv_section_developers_cb',
    'kemi_cabinet_csv'
  );

  // register a new field in the "kemi_cabinet_csv_section_developers" section, inside the "kemi_cabinet_csv" page
  add_settings_field(
    'kemi_cabinet_csv', // as of WP 4.6 this value is used only internally
    // use $args' label_for to populate the id inside the callback
    __( 'Cabinet Product CSV', 'kemi_cabinet_csv' ),
    'kemi_cabinet_csv_cb',
    'kemi_cabinet_csv',
    'kemi_cabinet_csv_section_developers',
    [
    'label_for' => 'kemi_cabinet_csv',
    'class' => 'kemi_cabinet_csv_row',
    'kemi_cabinet_csv_custom_data' => 'custom',
    ]
  );


}

/**
* register our kemi_cabinet_csv_settings_init to the admin_init action hook
*/
add_action( 'admin_init', 'kemi_cabinet_csv_settings_init' );

/**
* custom option and settings:
* callback functions
*/

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function kemi_cabinet_csv_section_developers_cb( $args ) {
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
function kemi_cabinet_csv_cb( $args ) {
  // get the value of the setting we've registered with register_setting()
  $options = get_option( 'kemi_cabinet_csv_options' );
  // output the field
  // Save attachment ID
  wp_enqueue_media();
  ?>
  <input type="hidden" id="<?php echo esc_attr( $args['label_for'] ); ?>"
  data-custom="<?php echo esc_attr( $args['kemi_cabinet_csv_custom_data'] ); ?>"
  name="kemi_cabinet_csv_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
  value="<?php echo $options['kemi_cabinet_csv']; ?>" />
  <div class='image-preview-wrapper'>
			<input id='image-preview' disabled name="image-preview" value='<?php echo wp_get_attachment_url(  $options['kemi_cabinet_csv'] ); ?>' >
		</div>
		<input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />

      <input id="create-new-csv" type="button" class="button" value="<?php _e( 'Create New CSV' ); ?>" csv="http://localhost:8888/dcc/wp-content/uploads/2018/03/ItemlistforcustomerwebimportResults_171018-1.xlsx-ItemlistforcustomerwebimportSML.csv" />
      <div id="ajax-loader" style="display:none;">
        <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="64px" height="64px" viewBox="0 0 128 128" xml:space="preserve"><g><path d="M75.4 126.63a11.43 11.43 0 0 1-2.1-22.65 40.9 40.9 0 0 0 30.5-30.6 11.4 11.4 0 1 1 22.27 4.87h.02a63.77 63.77 0 0 1-47.8 48.05v-.02a11.38 11.38 0 0 1-2.93.37z" fill="#0085ba" fill-opacity="1"/><animateTransform attributeName="transform" type="rotate" from="0 64 64" to="360 64 64" dur="1000ms" repeatCount="indefinite"></animateTransform></g></svg>
      </div>
      <div id="csv-status">
      </div>
  <?php
  if ( isset( $_POST['submit'] ) && isset( $_POST['image-preview'] ) ) :
		update_option( 'kemi_cabinet_csv_options',  wp_get_attachment_url(  $options['kemi_cabinet_csv'] )  );
    exit();
	endif;
}


/**
* top level menu
*/
function kemi_cabinet_csv_options_page() {
// add top level menu page
  add_options_page(
    'Cabinet Product CSV',
    'Cabinet Product CSV',
    'manage_options',
    'kemi_cabinet_csv',
    'kemi_cabinet_csv_options_page_html'
  );
}

/**
* register our kemi_cabinet_csv_options_page to the admin_menu action hook
*/
add_action( 'admin_menu', 'kemi_cabinet_csv_options_page' );

/**
* top level menu:
* callback functions
*/
function kemi_cabinet_csv_options_page_html() {
// check user capabilities
  if ( ! current_user_can( 'manage_options' ) ) {
  return;
}

// add error/update messages

// check if the user have submitted the settings
// wordpress will add the "settings-updated" $_GET parameter to the url
//if ( isset( $_GET['settings-updated'] ) ) {
// add settings saved message with the class of "updated"
//  add_settings_error( 'kemi_cabinet_csv_messages', 'kemi_cabinet_csv_message', __( 'Settings Saved', 'kemi_cabinet_csv' ), 'updated' );
//            }

// show error/update messages
settings_errors( 'kemi_cabinet_csv_messages' );
?>
<div class="wrap">
  <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
  <?php  $options = get_option( 'kemi_cabinet_csv_options' );
  print_r($options);
  ?>
  <form action="options.php" method="post">
    <?php
    // output security fields for the registered setting "kemi_cabinet_csv"
    settings_fields( 'kemi_cabinet_csv' );
    // output setting sections and their fields
    // (sections are registered for "kemi_cabinet_csv", each field is registered to a specific section)
    do_settings_sections( 'kemi_cabinet_csv' );


    // output save settings button
    submit_button( 'Save Settings' );
    ?>
  </form>
</div>
<?php
}
