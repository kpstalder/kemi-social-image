<?php
/*
Plugin Name:  Custom Social Images
Plugin URI:   https://www.sanctuarymg.com
Description:  Override Yoast Social to have default social image
Version:      20171203
Author:       Kevin Stalder
Author URI:   https://www.sanctuarymg.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wporg
Domain Path:  /languages
*/

add_filter( 'wpseo_opengraph_image', '__return_false' );
add_filter( 'wpseo_twitter_image', '__return_false' );

require_once( 'admin/admin-page.php' );
