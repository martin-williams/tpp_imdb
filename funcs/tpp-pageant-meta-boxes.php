<?php



add_action( 'admin_init', 'tppdb_pageant_meta_box' );


function tppdb_pageant_meta_box() {

  $prefix = 'tppdb_';
  /* 
   * configure your meta box
   */
  $pageants_config = array(
    'id'             => 'tppdb_pageant_meta_box',          // meta box id, unique per meta box
    'title'          => 'Pageant Information',          // meta box title
    'pages'          => array('pageants'),      // post types, accept custom post types as well, default is array('post'); optional
    'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
    'priority'       => 'high',            // order of meta box: high (default), low; optional
    'fields'         => array(),            // list of meta fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
  );
  
  
  /*
   * Initiate your meta box
   */
  $pageant_meta_box =  new AT_Meta_Box($pageants_config);

  $pageant_meta_box->addText($prefix.'text_field_id',array('name'=> 'My Text '));
  $pageant_meta_box->addText($prefix.'text_field_id2',array('name'=> 'My Text '));


  $pageant_meta_box->Finish();


}
