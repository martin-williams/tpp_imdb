<?php 
/*
Plugin Name: TPP IMDB-esk functionality
Plugin URI: http://thepageantplanet.com
Description: Functionality for ordering users and pageants in IMDB fashion
Version: 1.0
Author: Ingage
Author URI: http://weingage.com/
*/

require dirname( __FILE__ ) . '/tpp_imdb_shortcodes.php';

// turn off admin bar
add_filter('show_admin_bar', '__return_false');
