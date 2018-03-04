<?php
/*
Plugin Name:  Cabinet Selector CSV importer
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

/* Alert Bar */
add_action( 'wp_ajax_nopriv_convert_csv', 'convert_csv_function' );
add_action( 'wp_ajax_convert_csv', 'convert_csv_function' );
function convert_csv_function(){
  $csv = $_POST['csv'];
  $row = 1;
  $handle = fopen($csv, "r");
  if (($handle = fopen($csv, "r")) !== FALSE) {
    echo 'open';
    $class = array();
    $colors = array();
    while (($data = fgetcsv($handle)) !== FALSE) {
      if($row == 1){
        $row++; continue;
      }
      if( array_key_exists($data[3], $class)){
        if (!in_array($data[9], $colors, true) && $data[9]){
          array_push($colors, $data[9]);
          $class[$data[3]]= $colors;
        }
      }
      else{
        $colors = array();
        array_push($colors, $data[9]);
        $class[$data[3]]= $colors;

      }
      $row++;
    }
    fclose($handle);
    echo '<pre>';
    print_r($class);
    echo '</pre>';
  }
  die();
}
require_once( 'admin/admin-page.php' );
