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
      'supports' => array('title', 'editor', 'thumbnail','revisions',  'custom-fields')

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
      'show_in_menu' => 'tppdb_home'
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
      'supports' => array('title', 'editor', 'thumbnail','revisions', 'custom-fields')
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