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

function create_competitor_relationship () {
    p2p_register_connection_type( array(
        'name' => 'pageant_competitors',
        'from' => 'pageants',
        'to'   => 'user'
    ));
}
add_action('init', 'create_competitor_relationship');

function single_pageant_template ($template) {
    if ('pageants' == get_post_type(get_queried_object_id()) && !$template) {
        $template = dirname(__FILE__) . '/includes/single-pageant.php';
    }

    $template = dirname(__FILE__) . '/includes/single-pageant.php';
}
add_filter('single_template', 'single_pageant_template');