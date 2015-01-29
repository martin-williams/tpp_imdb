<?php
/////
// HOW TO USE:
// https://github.com/bainternet/My-Meta-Box/blob/master/class-usage-demo.php
/////
add_action( 'admin_init', 'tppdb_system_meta_box' );


function tppdb_system_meta_box() {

  $prefix = 'tppdb_';
   /* 
   * General Meta box for general / personal information
   */
  $system_general_config = array(
    'id'             => 'tppdb_system_general',          // meta box id, unique per meta box
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
  $system_general =  new AT_Meta_Box($system_general_config);

  
  $system_general->addText($prefix.'twitter',array('name'=> 'Twitter '));

  // $repeater_fields_gen[] = $system_general->addText($prefix.'fun_facts',array('name'=> 'Fact: '),true);


  // $system_general->addRepeaterBlock($prefix.'re_facts',array(
  //   'inline'   => true, 
  //   'name'     => 'Fun Facts',
  //   'fields'   => $repeater_fields_gen, 
  //   'sortable' => true
  // ));

  //$system_general->addWysiwyg($prefix.'facts',array('name'=> 'Fun Facts '));



  $system_general->Finish();


  /* 
   * Social Media Meta box for contact information
   */
  $system_social_config = array(
    'id'             => 'tppdb_system_social',          // meta box id, unique per meta box
    'title'          => 'Social Media & Marketing',          // meta box title
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
  $system_social =  new AT_Meta_Box($system_social_config);


  $system_social->addText($prefix.'facebook',array('name'=> 'Facebook '));
  
  $system_social->addText($prefix.'twitter',array('name'=> 'Twitter '));

  $system_social->addText($prefix.'instagram',array('name'=> 'Instagram '));

  $system_social->addText($prefix.'website',array('name'=> 'Website '));


  $system_social->Finish();

    /* 
   * Contact Meta box for contact information
   * This is somewhat FUTURE functionality. It won't be shown initially
   */
  $system_contact_config = array(
    'id'             => 'tppdb_system_contact',          // meta box id, unique per meta box
    'title'          => 'Office Contact Information',          // meta box title
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
  $system_contact =  new AT_Meta_Box($system_contact_config);


  $system_contact->addText($prefix.'email_address',array('name'=> 'Email Address '));
  
  $system_contact->addText($prefix.'email_contact',array('name'=> 'Email Contact '));

  $system_contact->addText($prefix.'website',array('name'=> 'Website '));


  $system_contact->Finish();


}
