<?php


add_action( 'admin_menu', 'tppdb_main_menu_page' );

function tppdb_main_menu_page(){
    add_menu_page( 'The Pageant Planet Database', 'TPP Database', 'manage_options', 'tppdb_home', 'tppdb_home_func', '', 10 );
    

    /* Pageants */

    /* Systems */
	add_submenu_page( 'tppdb_home', "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:1px; line-height:1px;background:#eee;"></span>', "manage_options", "#");


    add_submenu_page( 'tppdb_home', 'Requests', 'Requests', 'manage_options', 'tppdb_requests', 'tppdb_requests_func' );

    add_submenu_page( 'tppdb_home', "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:1px; line-height:1px;background:#eee;"></span>', "manage_options", "#");


    add_submenu_page( 'tppdb_home', 'Profile Roles', 'Profile Roles', 'manage_options', 'edit-tags.php?taxonomy=roles&post_type=tpp_profiles', '' );
    add_submenu_page( 'tppdb_home', 'Areas of Expertise', 'Areas of Expertise', 'manage_options', 'edit-tags.php?taxonomy=areas-of-expertise&post_type=tpp_profiles', '' );
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


       




        </div><!-- wrap -->

        <?php
}

function tppdb_admin_request_tabs( $current = 'profiles' ) {
    $tabs = array( 'profiles' => 'Profile Requests', 'pageants' => 'Pageant Requests', 'systems' => 'System Requests' );
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='".admin_url("admin.php?page=tppdb_requests&tab=$tab")."'>$name</a>";

    }
    echo '</h2>';
}

function tppdb_requests_func(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'tppdb_requests';
     if (!current_user_can('manage_options')) {  
            wp_die('You do not have sufficient permissions to access this page.');  
        } ?> 
        
         <div class="wrap">
                       <h2>Pending Requests</h2> 


         <?php
           if ( isset ( $_GET['tab'] ) ) tppdb_admin_request_tabs($_GET['tab']);
           else tppdb_admin_request_tabs('profiles');

           ?>

            <table class="widefat" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Request</th>
                       <th>Approve</th>   
                    <th>Deny</th>        
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Request</th>
                    <th>Approve</th>   
                    <th>Deny</th>       
    
                </tr>
            </tfoot>
            <tbody>

           <?php

            if(isset($_GET['tab'])) {
                $tab = $_GET['tab'];
                if($tab == "profiles") {
                    $post_type = "tpp_profiles";
                } elseif($tab == "pageants") {
                    $post_type = "pageant";
                } elseif($tab == "systems") {
                    $post_type = "pageants";
                }

            }

           $myrows = $wpdb->get_results( "SELECT * FROM $table_name" );

           foreach ( $myrows as $row ) {
               echo '<tr>';
               echo '<td>Post: ' . $row->post_id . ', User: ' . $row->user_id . ', Post type: ' . $row->type . '</td>';
               echo '<td></td>';
               echo '<td></td>';
               echo '</tr>';
           }

            $my_query = new WP_Query( array(
                'post_type' => $post_type
            ));

           // p2p_type( 'profile_to_user' )->each_connected( $my_query, array(), 'editors' );

            $items = 0;
            while ( $my_query->have_posts() ) : $my_query->the_post(); ?>


                <?php
                        $profile_title = get_the_title();
                        // echo $profile_title;

                        $editors = p2p_type( 'profile_to_user')->get_connected( $post->ID );
                       // echo "<pre>";
                        //print_r($editors);
                        if(!empty($editors->results)){
                            foreach ($editors->results as $user) {
                            // $name = $editors->query_vars->connected_items
                                $status = p2p_get_meta( $user->p2p_id, 'status', true );
                                if($status == "pending") { 
                                    $items = 1;
                                    ?>
                                <tr>
                                     <td><?php echo $user->display_name . " is requesting to claim the profile $profile_title."; ?></td>
                                     <td><?php echo  "Approve"; ?></td>
                                     <td><?php echo "Deny"; ?></td>
                               </tr>
                                <?php   }
                            }
                        } 
                        //echo "</pre>";

                // foreach ( $post->editors as $user ) :
                    
                //     $status = p2p_get_meta( $user->p2p_id, 'status', true );

                //     if($status == "pending") {

                //         echo $user->display_name . " is requesting to claim the profile $profile_title." ;

                //     }

                
                // endforeach;


                wp_reset_postdata();

                ?>

            <?php endwhile; 

            if($items == 0) { ?>

                <tr>
                    <td colspan="3"><?php echo "No pending requests."; ?></td>
                </tr>

            <?php }

            ?>
            </tbody>
            </table>

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