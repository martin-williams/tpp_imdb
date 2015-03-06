<?php

function pageant_search_func () {
    wp_enqueue_script(
        'pageant-search',
        plugins_url( '/js/pageant_search.js', dirname(__FILE__) ),
        array( 'jquery' )
    );

    wp_enqueue_style('tpp-imdb-styles');

    ob_start();
    $templates = new TPP_IMDB_Template_Loader;
    $templates->get_template_part('search');
    return ob_get_clean();
}
add_shortcode( 'pageant_search', 'pageant_search_func' );

function pageant_search_submit () {
    ob_start();
    $templates = new TPP_IMDB_Template_Loader;
    $templates->get_template_part('pageant-search-results');
    die(ob_get_clean());
}
add_action( 'wp_ajax_tppdb_pageant_search', 'pageant_search_submit' );
add_action( 'wp_ajax_nopriv_tppdb_pageant_search', 'pageant_search_submit' );

function profile_search_submit () {
    ob_start();
    $template = new TPP_IMDB_Template_Loader;
    $template->get_template_part('profile_search_results');
    die(ob_get_clean());
}
add_action( 'wp_ajax_tppdb_profile_search', 'profile_search_submit' );
add_action( 'wp_ajax_nopriv_tppdb_profile_search', 'profile_search_submit' );

function coach_search_submit () {
    ob_start();
    $template = new TPP_IMDB_Template_Loader;
    $template->get_template_part('coach_search_results');
    die(ob_get_clean());
}
add_action( 'wp_ajax_tppdb_coach_search', 'coach_search_submit' );
add_action( 'wp_ajax_nopriv_tppdb_coach_search', 'coach_search_submit' );