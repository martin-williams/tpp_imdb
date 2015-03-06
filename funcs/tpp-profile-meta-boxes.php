<?php

/////
// HOW TO USE:
// https://github.com/bainternet/My-Meta-Box/blob/master/class-usage-demo.php
/////

add_action( 'admin_init', 'tppdb_profile_meta_boxes' );


function tppdb_profile_meta_boxes() {

  $prefix = 'tppdb_';
  /* 
   * General Meta box for general / personal information
   */
  $profiles_general_config = array(
    'id'             => 'tppdb_profile_general',          // meta box id, unique per meta box
    'title'          => 'Profile Information',          // meta box title
    'pages'          => array('tpp_profiles'),      // post types, accept custom post types as well, default is array('post'); optional
    'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
    'priority'       => 'high',            // order of meta box: high (default), low; optional
    'fields'         => array(),            // list of meta fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
  );
  
  
  /*
   * Initiate your meta box
   */
  $profile_general =  new AT_Meta_Box($profiles_general_config);
  
  $profile_general->addDate($prefix.'dob',array('name'=> 'Date of Birth '));

  $profile_general->addText($prefix.'city',array('name'=> 'City of Birth '));
  
  $profile_general->addText($prefix.'state',array('name'=> 'State of Birth '));

  $profile_general->addText($prefix.'country',array('name'=> 'Country of Birth '));


  $profile_general->Finish();

  /* 
   * Social Media Meta box for contact information
   */
  $profiles_social_config = array(
    'id'             => 'tppdb_profile_social',          // meta box id, unique per meta box
    'title'          => 'Social Media & Marketing',          // meta box title
    'pages'          => array('tpp_profiles'),      // post types, accept custom post types as well, default is array('post'); optional
    'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
    'priority'       => 'high',            // order of meta box: high (default), low; optional
    'fields'         => array(),            // list of meta fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
  );
  
  
  /*
   * Initiate your meta box
   */
  $profile_social =  new AT_Meta_Box($profiles_social_config);


  $profile_social->addText($prefix.'facebook',array('name'=> 'Facebook '));
  
  $profile_social->addText($prefix.'twitter',array('name'=> 'Twitter '));

  $profile_social->addText($prefix.'instagram',array('name'=> 'Instagram '));

  $profile_social->addText($prefix.'website',array('name'=> 'Website '));


  $profile_social->Finish();


  /* 
   * Contact Meta box for contact information
   * This is somewhat FUTURE functionality. It won't be shown initially
   */
  $profiles_contact_config = array(
    'id'             => 'tppdb_profile_contact',          // meta box id, unique per meta box
    'title'          => 'Office Contact Information',          // meta box title
    'pages'          => array('tpp_profiles'),      // post types, accept custom post types as well, default is array('post'); optional
    'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
    'priority'       => 'high',            // order of meta box: high (default), low; optional
    'fields'         => array(),            // list of meta fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
  );
  
  
  /*
   * Initiate your meta box
   */
  $profile_contact =  new AT_Meta_Box($profiles_contact_config);


  $profile_contact->addText($prefix.'email_address',array('name'=> 'Email Address '));
  
  $profile_contact->addText($prefix.'email_contact',array('name'=> 'Email Contact '));

  // $profile_contact->addText($prefix.'country',array('name'=> 'Country of Birth '));


  $profile_contact->Finish();

    /*
     * Interesting Facts meta box
     */
    // $profiles_interesting_facts_config = array (
    //     'id' => 'tppdb_profile_interesting',
    //     'title' => 'Profile Interesting Facts',
    //     'pages' => array ('tpp_profiles'),
    //     'context' => 'normal',
    //     'priority' => 'high',
    //     'fields' => array(),
    //     'local_images' => false,
    //     'use_with_theme' => false
    // );

    // /*
    //  * Initialize meta box
    //  */
    // $profile_interesting = new AT_Meta_Box($profiles_interesting_facts_config);


    // $repeater_fields_pi[] = $profile_interesting->addText($prefix.'int_facts',array('name'=> 'Fact: '),true);


    // $profile_interesting->addRepeaterBlock($prefix.'interesting_facts',array(
    //   'inline'   => true, 
    //   'name'     => 'Interesting Facts',
    //   'fields'   => $repeater_fields_pi, 
    //   'sortable' => true
    // ));


    // $profile_interesting->Finish();

    /*
     * Personal info meta box
     */

    $profiles_personal_info_config = array (
        'id'                =>  'tppdb_profile_personal',
        'title'             =>  'Profile Personal Information',
        'pages'          =>  array ('tpp_profiles'),
        'context'        => 'normal',
        'priority'         =>   'high',
        'fields'            =>  array(),
        'local_images'  =>  false,
        'use_with_theme'    =>  false
    );

    /*
     * Initiate your meta box
     */
    $profile_personal = new AT_Meta_Box($profiles_personal_info_config);

    $profile_personal->addText($prefix.'height',array('name'=>'Height'));
    $profile_personal->addText($prefix.'weight',array('name'=>'Weight'));
    $profile_personal->addText($prefix.'zodiac-sign',array('name'=>'Zodiac Sign'));
    $profile_personal->addText($prefix.'pageant-platform',array('name'=>'Pageant Platform'));
    $profile_personal->addText($prefix.'talent',array('name'=>'Talent'));
    $profile_personal->addText($prefix.'college-major',array('name'=>'College Major'));
    $profile_personal->addText($prefix.'birthplace',array('name'=>'Birthplace'));

    $profile_personal->Finish();
}

function profile_gallery_attachments ($attachments) {
  $fields = array (
      array (
          'name'      => 'title',
          'type'      => 'text',
          'label'     => __( 'Title', 'attachments' ),
          'default'   => 'title'
      ),
      array (
          'name'      => 'caption',
          'type'      => 'textarea',
          'label'     => __( 'Caption', 'attachments' ),
          'default'   => 'caption'
      )
  );

  $args = array (
      'label'     => 'Gallery Attachments',
      'post_type' => array( 'tpp_profiles' ),
      'position'  => 'normal',
      'priority'  => 'high',
      'filetype'  => null,
      'note'      => 'Attach gallery files here!',
      'append'    => true,
      'button_text'   =>  __( 'Attach Files', 'attachments' ),
      'modal_text'    =>  __( 'Attach', 'attachments' ),
      'router'        =>  'browse',
      'post_parent'   =>  false,
      'fields'        =>  $fields
  );

  $attachments->register( 'profile_gallery_attachments', $args );
}
add_action( 'attachments_register', 'profile_gallery_attachments' );