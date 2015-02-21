<?php

add_action( 'init', 'tppdb_create_post_types' );


function tppdb_create_post_types() {
	tppdb_create_pageants_type();
	tppdb_create_pageant_type();
	tppdb_create_profile_type();

}

function tppdb_create_pageants_type() {
  register_post_type( 'pageants',
    array(
      'labels' => array(
        'name' => __( 'Systems' ),
        'singular_name' => __( 'System' )
      ),
      'public' => true,
      'has_archive' => true,
      'show_in_menu' => 'tppdb_home',
      'supports' => array('title', 'editor', 'thumbnail','revisions',  'custom-fields'),
      // 'capability_type' => 'pageant',
      // 'capabilities' => array(
      //   'publish_posts' => 'publish_pageants',
      //   'edit_posts' => 'edit_pageants',
      //   'edit_others_posts' => 'edit_others_pageants',
      //   'delete_posts' => 'delete_pageants',
      //   'delete_others_posts' => 'delete_others_pageants',
      //   'read_private_posts' => 'read_private_pageants',
      //   'edit_post' => 'edit_pageant',
      //   'delete_post' => 'delete_pageant',
      //   'read_post' => 'read_pageant',
      // ),

    )
  );
}

function tppdb_create_pageant_type() {
  register_post_type( 'pageant-years',
    array(
      'labels' => array(
        'name' => __( 'Pageants' ),
        'singular_name' => __( 'Pageant' )
      ),
      'public' => true,
      'has_archive' => false,
      'rewrite' => array('slug' => 'pageant'),
      'supports' => array('title', 'editor',  'thumbnail', 'comments','revisions', 'custom-fields'),
      'show_in_menu' => 'tppdb_home',
      // 'capability_type' => 'year',
      // 'capabilities' => array(
      //   'publish_posts' => 'publish_years',
      //   'edit_posts' => 'edit_years',
      //   'edit_others_posts' => 'edit_others_years',
      //   'delete_posts' => 'delete_years',
      //   'delete_others_posts' => 'delete_others_years',
      //   'read_private_posts' => 'read_private_years',
      //   'edit_post' => 'edit_year',
      //   'delete_post' => 'delete_year',
      //   'read_post' => 'read_year',
      // ),
     //  'menu_position' => "0"
    )
  );
}

function tppdb_create_profile_type() {
  register_post_type( 'tpp_profiles',
    array(
      'labels' => array(
        'name' => __( 'Profiles' ),
        'singular_name' => __( 'Profile' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'profiles'),
      'show_in_menu' => 'tppdb_home',

     // 'rewrite' => array(
     //   'permastruct' => '/profile/%post_id%/'
     // ),
      'supports' => array('title', 'editor', 'thumbnail','revisions', 'custom-fields'),
      // 'capability_type' => 'profile',
      // 'capabilities' => array(
      //   'publish_posts' => 'publish_profiles',
      //   'edit_posts' => 'edit_profiles',
      //   'edit_others_posts' => 'edit_others_profiles',
      //   'delete_posts' => 'delete_profiles',
      //   'delete_others_posts' => 'delete_others_profiles',
      //   'read_private_posts' => 'read_private_profiles',
      //   'edit_post' => 'edit_profile',
      //   'delete_post' => 'delete_profile',
      //   'read_post' => 'read_profile',
      // ),
    )
  );
}


// Remove Add New from the systems submenu

// function tpp_remove_system_add_new() {
//   // if(!current_user_can('activate_plugins')) {
// 	    global $submenu;
// 	    unset($submenu['edit.php?post_type=pageants'][10]); // Removes 'Add New'.
// //	}
// }
// add_action('admin_menu', 'tpp_remove_system_add_new');
