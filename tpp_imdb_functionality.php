<?php 
/*
Plugin Name: TPP IMDB-esk functionality
Plugin URI: https://github.com/martin-williams/tpp_imdb
Description: Functionality for ordering users and pageants in IMDB fashion
Version: 1.2
Author: Ingage
Author URI: http://weingage.com
*/

define( 'TPP_IMDB_PLUGIN_DIR', plugin_dir_path(__FILE__) );

if (!class_exists('Gamajo_Template_Loader')) {
    require TPP_IMDB_PLUGIN_DIR . 'class-gamajo-template-loader.php';
}
require TPP_IMDB_PLUGIN_DIR . 'class-tpp-imdb-template-loader.php';


// Include our files
// require_once(TPP_IMDB_PLUGIN_DIR . 'lib/wp-permastructure/wp-permastructure.php');

require_once(TPP_IMDB_PLUGIN_DIR . 'funcs/tpp-imdb-db-interactions.php');
require_once(TPP_IMDB_PLUGIN_DIR . 'funcs/tpp-imdb-shortcodes.php');
require_once(TPP_IMDB_PLUGIN_DIR . 'funcs/tpp-imdb-posttypes.php');
require_once(TPP_IMDB_PLUGIN_DIR . 'funcs/tpp-imdb-taxonomies.php');
require_once(TPP_IMDB_PLUGIN_DIR . 'funcs/tpp-imdb-relationships.php');
require_once(TPP_IMDB_PLUGIN_DIR . 'funcs/tpp-imdb-comments.php');
require_once(TPP_IMDB_PLUGIN_DIR . 'funcs/tpp-imdb-search.php');
require_once(TPP_IMDB_PLUGIN_DIR . 'funcs/tpp-imdb-gf-post-update.php');

require TPP_IMDB_PLUGIN_DIR . 'funcs/tppdb-meta-functions.php';

if(is_admin()) {
    require_once(TPP_IMDB_PLUGIN_DIR . 'admin/tppdb_admin_pages.php');
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

function tppdb_pageant_content_hook ($content) {
    if (is_singular( array('pageant-years', 'pageant', 'pageants')) && !types_render_field("enable-hit-or-miss-option", array("output" => "raw"))) {
        remove_filter( 'the_content', 'thumbs_rating_print');
        $content = '<div id="bio-wrapper">' . $content . '</div>';
        $content .= '<button type="button" id="expanderBtn" class="btn btn-default">Read More</button>';
        $content .= tppdb_getPageantFacts();

        // if (!is_singular( 'pageants' )) {
        //     $content .= thumbs_rating_getlink();
        // }
    }
    return $content;
}
add_filter( 'the_content', 'tppdb_pageant_content_hook', 9);

function restrict_to_system() {
    //And any other checks
    if (!is_admin() && is_tax( array('age-divisions', 'stages') )) {
        set_query_var('post_type','pageants');
    }
}
add_action( 'pre_get_posts', 'restrict_to_system' );

function orderby_tax_clauses( $clauses, $wp_query ) {
    global $wpdb;
    $taxonomies = get_taxonomies();
    foreach ($taxonomies as $taxonomy) {
        if ( isset( $wp_query->query['orderby'] ) && $taxonomy == $wp_query->query['orderby'] ) {
            $clauses['join'] .=<<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;
            $clauses['where'] .= " AND (taxonomy = '{$taxonomy}' OR taxonomy IS NULL)";
            $clauses['groupby'] = "object_id";
            $clauses['orderby'] = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC) ";
            $clauses['orderby'] .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
        }
    }
    return $clauses;
}
add_filter('posts_clauses', 'orderby_tax_clauses', 10, 2 );

function tppdb_notify_image_report () {
    global $current_user;
    get_currentuserinfo();

    //if (!current_user_can( 'administrator' )){// avoid sending emails when admin is reporting images
        $to = 'willm.mw@gmail.com,jay@weingage.com';
        $subject = 'poor image';
        $message = "the user : " .$current_user->display_name . " has reported an image.\n";
        $params = explode('&', $_POST['data']);
        foreach($params as $param){
            $pair = explode('=', $param);
            $message .= $pair[0] . ": ". urldecode($pair[1]) ."\n";
        }
        wp_mail( $to, $subject, $message);
    //}

    die();
}
add_action( 'wp_ajax_tppdb_image_report', 'tppdb_notify_image_report' );
add_action( 'wp_ajax_nopriv_tppdb_image_report', 'tppdb_notify_image_report' );

function tppdb_override_tax_template($template){
    // is a specific custom taxonomy being shown?
    $taxonomy_array = array('age-divisions','tax2');
    foreach ($taxonomy_array as $taxonomy_single) {
        if ( is_tax($taxonomy_single) ) {
            // allow the theme to override
            if(file_exists(trailingslashit(get_stylesheet_directory()) . 'tppdb/taxonomy-'.$taxonomy_single.'.php')) {
                $template = trailingslashit(get_stylesheet_directory()) . 'tppdb/taxonomy-'.$taxonomy_single.'.php';
            }
            // otherwise, use ours
            else {
                $template = plugin_dir_path(__FILE__) . 'templates/taxonomy-'.$taxonomy_single.'.php';
            }
            break;
        }
    }
    return $template;
}
add_filter('template_include','tppdb_override_tax_template');






function tppdb_install() {
    global $wpdb;
    global $tppdb_version;
    $installed_ver = get_option("tppdb_version");

    $table_name = $wpdb->prefix . "tppdb_requests";

    $wpdb->query( "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        requested datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        post_id mediumint(9) NOT NULL,
        user_id mediumint(9) NOT NULL,
        status text NOT NULL,
        type text NOT NULL,
        UNIQUE KEY id (id)
    )" );

    /*
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name
        || $installed_ver != $tppdb_version) {

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE " .$table_name." (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            requested datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            post_id mediumint(9) NOT NULL,
            user_id mediumint(9) NOT NULL,
            status text NOT NULL,
            type text NOT NULL,
            UNIQUE KEY id (id)
        )";

        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
        //dbDelta( $sql );
        //$wpdb->query($sql);
        maybe_create_table($table_name, $sql);

        update_option('tppdb_version', $tppdb_version);
    }
    */

    add_option("tppdb_version", $tppdb_version);
}
register_activation_hook( __FILE__, 'tppdb_install' );

function tppdb_update_db_check() {
    global $tppdb_version;
    if (get_site_option('tppdb_version') != $tppdb_version) {
        tppdb_install();
    }
}
add_action('plugins_loaded', 'tppdb_update_db_check');

function register_custom_db_table() {
    global $wpdb;
    $wpdb->tppdb_requests = $wpdb->prefix . 'tppdb_requests';
}
add_action('init', 'register_custom_db_table');


function init_scripts() {
    return true;
}
add_filter("gform_init_scripts_footer", "init_scripts");
