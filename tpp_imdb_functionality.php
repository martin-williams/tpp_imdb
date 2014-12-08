<?php 
/*
Plugin Name: TPP IMDB-esk functionality
Plugin URI: https://github.com/martin-williams/tpp_imdb
Description: Functionality for ordering users and pageants in IMDB fashion
Version: 1.0
Author: Martin Williams
Author URI: http://github.com/martin-williams
*/

define( 'TPP_IMDB_PLUGIN_DIR', plugin_dir_path(__FILE__) );

if (!class_exists('Gamajo_Template_Loader')) {
    require TPP_IMDB_PLUGIN_DIR . 'class-gamajo-template-loader.php';
}
require TPP_IMDB_PLUGIN_DIR . 'class-tpp-imdb-template-loader.php';


// Include our files
require_once(TPP_IMDB_PLUGIN_DIR . 'lib/wp-permastructure/wp-permastructure.php');

require_once(TPP_IMDB_PLUGIN_DIR . 'funcs/tpp-imdb-posttypes.php');
require_once(TPP_IMDB_PLUGIN_DIR . 'funcs/tpp-imdb-taxonomies.php');
require_once(TPP_IMDB_PLUGIN_DIR . 'funcs/tpp-imdb-relationships.php');

if(is_admin()) {
    require_once(TPP_IMDB_PLUGIN_DIR . 'lib/meta-box-class/my-meta-box-class.php');
    require_once(TPP_IMDB_PLUGIN_DIR . 'funcs/tpp-system-meta-boxes.php');
    require_once(TPP_IMDB_PLUGIN_DIR . 'funcs/tpp-pageant-meta-boxes.php');
    require_once(TPP_IMDB_PLUGIN_DIR . 'funcs/tpp-profile-meta-boxes.php');
}

// turn off admin bar
add_filter('show_admin_bar', '__return_false');
wp_register_style( 'tpp-imdb-styles', plugins_url( '/css/styles.css', __FILE__ ));

function single_pageant_template ($template) {
    $post_types = array ( 'pageants', 'pageant-years' );

    if (is_singular( $post_types ) && !file_exists( get_stylesheet_directory() . '/single-pageant.php' )) {
        $template = plugin_dir_path(__FILE__) . 'includes/single-pageant.php';
        wp_enqueue_style('tpp-imdb-styles');
    }

    return $template;
}
add_filter('template_include', 'single_pageant_template');

function single_profile_template ($template) {
    $post_types = array ( 'tpp_profiles' );

    if (is_singular( $post_types ) && !file_exists( get_stylesheet_directory() . '/single-profile.php' )) {
        $template = plugin_dir_path(__FILE__) . 'includes/single-profile.php';
        wp_enqueue_style('tpp-imdb-styles');
    }

    return $template;
}
add_filter('template_include', 'single_profile_template');

add_filter( 'attachments_default_instance', '__return_false' ); // disable the default instance