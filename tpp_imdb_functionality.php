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

// turn off admin bar
add_filter('show_admin_bar', '__return_false');
wp_register_style( 'tpp-imdb-styles', plugins_url( '/css/styles.css', __FILE__ ));

function create_competitor_relationship () {
    p2p_register_connection_type( array(
        'name' => 'pageant_competitors',
        'from' => 'pageant-years',
        'to'   => 'profiles',
        'title' => 'Connected Competitors'
    ));
}

function create_director_relationship () {
    p2p_register_connection_type( array(
        'name' => 'pageant_directors',
        'from' => 'pageant-years',
        'to'   => 'profiles',
        'title' => 'Connected Directors'
    ));
}

function create_post_relationship () {
    p2p_register_connection_type( array(
        'name' => 'recent_news',
        'from' => 'post',
        'to'   => 'pageant-years',
        'title' => 'Recent News'
    ));
}

function create_system_relationship () {
    p2p_register_connection_type( array(
        'name' => 'system',
        'from' => 'pageants',
        'to'   => 'pageant-years',
        'title' => 'Pageant System'
    ));
}

function create_relationships () {
    create_competitor_relationship();
    create_director_relationship();
    create_post_relationship();
    create_system_relationship();
}
add_action('init', 'create_relationships');

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
    $post_types = array ( 'profiles' );

    if (is_singular( $post_types ) && !file_exists( get_stylesheet_directory() . '/single-profile.php' )) {
        $template = plugin_dir_path(__FILE__) . 'includes/single-profile.php';
        wp_enqueue_style('tpp-imdb-styles');
    }

    return $template;
}
add_filter('template_include', 'single_profile_template');