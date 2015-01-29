<?php
/////
// HOW TO USE:
// https://github.com/bainternet/My-Meta-Box/blob/master/class-usage-demo.php
/////


add_action( 'admin_init', 'tppdb_pageant_meta_box' );


function tppdb_pageant_meta_box() {

  $prefix = 'tppdb_';
  /* 
   * configure your meta box
   */
  $pageants_general_config = array(
    'id'             => 'tppdb_pageant_general',          // meta box id, unique per meta box
    'title'          => 'Pageant Information',          // meta box title
    'pages'          => array('pageant-years'),      // post types, accept custom post types as well, default is array('post'); optional
    'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
    'priority'       => 'high',            // order of meta box: high (default), low; optional
    'fields'         => array(),            // list of meta fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
  );
  
  
  /*
   * Initiate your meta box
   */
  $pageant_general =  new AT_Meta_Box($pageants_general_config);

  $pageant_general->addDate($prefix.'pageant_date',array('name'=> 'Pageant Date '));
  
  $pageant_general->addText($prefix.'pageant_venue',array('name'=> 'Pageant Venue '));

  // $repeater_fields_gen[] = $pageant_general->addText($prefix.'fun_facts', array('name' => 'Fact: '), true);

  // $pageant_general->addRepeaterBlock($prefix.'re_facts', array(
  //     'inline'      => true,
  //     'name'        => 'Fun Facts',
  //     'fields'      => $repeater_fields_gen,
  //     'sortable'    => true
  // ));

  //$pageant_general->addWysiwyg($prefix.'pageant_fun_facts',array('name'=> 'Fun Facts '));


  $pageant_general->Finish();



  /* 
   * Contact Meta box for contact information
   * This is somewhat FUTURE functionality. It won't be shown initially
   */
  $pageants_contact_config = array(
    'id'             => 'tppdb_pageant_contact',          // meta box id, unique per meta box
    'title'          => 'Office Contact Information',          // meta box title
    'pages'          => array('pageant-years'),      // post types, accept custom post types as well, default is array('post'); optional
    'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
    'priority'       => 'high',            // order of meta box: high (default), low; optional
    'fields'         => array(),            // list of meta fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
  );
  
  
  /*
   * Initiate your meta box
   */
  $pageant_contact =  new AT_Meta_Box($pageants_contact_config);


  $pageant_contact->addText($prefix.'email_address',array('name'=> 'Email Address '));
  
  $pageant_contact->addText($prefix.'email_contact',array('name'=> 'Email Contact '));

  $pageant_contact->addText($prefix.'website',array('name'=> 'Website '));


  $pageant_contact->Finish();


}

function gallery_attachments ($attachments) {
    $fields = array (
        array (
            'name'      =>  'title',
            'type'      =>  'text',
            'label'     =>  __( 'Title', 'attachments' ),
            'default'   =>  'title'
        ),
        array (
            'name'      =>  'caption',
            'type'      =>  'textarea',
            'label'     =>  __( 'Caption', 'attachments' ),
            'default'   =>  'caption'
        )
    );

    $args = array (
        'label'     =>  'Gallery Attachments',
        'post_type' =>  array( 'pageant-years' ),
        'position'  =>  'normal',
        'priority'  =>  'high',
        'filetype'  =>  null,
        'note'      =>  'Attach gallery files here!',
        'append'    =>  true,
        'button_text'   =>  __( 'Attach Files', 'attachments' ),
        'modal_text'    =>  __( 'Attach', 'attachments' ),
        'router'        =>  'browse',
        'post_parent'   =>  false,
        'fields'        =>  $fields
    );

    $attachments->register( 'gallery_attachments', $args );
}

add_action( 'attachments_register', 'gallery_attachments' );