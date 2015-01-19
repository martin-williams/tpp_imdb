<?php


add_action( 'admin_menu', 'tppdb_main_menu_page' );

function tppdb_main_menu_page(){
    add_menu_page( 'The Pageant Planet Database', 'TPP Database', 'manage_options', 'tppdb_home', 'tppdb_home_func', '', 5 );
    

    /* Pageants */

    /* Systems */
	add_submenu_page( 'tppdb_home', "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:1px; line-height:1px;background:#eee;"></span>', "manage_options", "#");

        add_submenu_page( 'tppdb_home', 'Profile Roles', 'Profile Roles', 'manage_options', 'edit-tags.php?taxonomy=roles&post_type=tpp_profiles', '' );
    add_submenu_page( 'tppdb_home', 'Pageant Years', 'Pageant Years', 'manage_options', 'edit-tags.php?taxonomy=pageant-year&post_type=pageant-years', '' );

    add_submenu_page( 'tppdb_home', 'Organizations', 'Organizations', 'manage_options', 'edit-tags.php?taxonomy=organizations&post_type=pageants', '' );
	
	add_submenu_page( 'tppdb_home', 'Age Divisions', 'Age Divisions', 'manage_options', 'edit-tags.php?taxonomy=age-divisions&post_type=pageant-years', '' );
    add_submenu_page( 'tppdb_home', 'Stages', 'Stages', 'manage_options', 'edit-tags.php?taxonomy=stages&post_type=pageants', '' );


}

function tppdb_home_func(){
     if (!current_user_can('manage_options')) {  
            wp_die('You do not have sufficient permissions to access this page.');  
        } ?> 
        
         <div class="wrap">
	        <div id="icon-tools" class="icon32"></div>
	        <h2>The Pageant Planet Database</h2> 


	        <p>This page currently has no function. Check back soon!</p>

        </div><!-- wrap -->

        <?php
}


if(!function_exists('mbe_set_current_menu')){

    function mbe_set_current_menu($parent_file){
        global $submenu_file, $current_screen, $pagenow;

        // Set the submenu as active/current while anywhere in your Custom Post Type (nwcm_news)
        if($current_screen->post_type == 'pageants'||$current_screen->post_type == 'pageant-years'||$current_screen->post_type == 'tpp_profiles') {

            if($pagenow == 'post.php'){
                $submenu_file = 'edit.php?post_type='.$current_screen->post_type;
            }

            if($pagenow == 'edit-tags.php'){
                $submenu_file = 'edit-tags.php?taxonomy=nwcm_news_category&post_type='.$current_screen->post_type;
            }

            $parent_file = 'tppdb_home';

        }

        return $parent_file;

    }

    add_filter('parent_file', 'mbe_set_current_menu');

}