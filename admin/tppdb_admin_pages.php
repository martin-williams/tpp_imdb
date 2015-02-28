<?php


add_action('admin_menu', 'tppdb_main_menu_page');

function tppdb_main_menu_page()
{
    add_menu_page('The Pageant Planet Database', 'TPP Database', 'edit_pages', 'tppdb_home', 'tppdb_home_func', '', 8);


    /* Pageants */

    /* Systems */
    add_submenu_page('tppdb_home', "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:1px; line-height:1px;background:#eee;"></span>', "edit_pages", "#");

    add_submenu_page('tppdb_home', 'Requests', 'Requests', 'edit_pages', 'tppdb_requests', 'tppdb_requests_func');

    add_submenu_page('tppdb_home', "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:1px; line-height:1px;background:#eee;"></span>', "edit_pages", "#");


    add_submenu_page('tppdb_home', 'Profile Roles', 'Profile Roles', 'edit_pages', 'edit-tags.php?taxonomy=roles&post_type=tpp_profiles', '');
    add_submenu_page('tppdb_home', 'Areas of Expertise', 'Areas of Expertise', 'edit_pages', 'edit-tags.php?taxonomy=areas-of-expertise&post_type=tpp_profiles', '');
    add_submenu_page('tppdb_home', 'Pageant Years', 'Pageant Years', 'edit_pages', 'edit-tags.php?taxonomy=years&post_type=pageant-years', '');

    add_submenu_page('tppdb_home', 'Organizations', 'Organizations', 'edit_pages', 'edit-tags.php?taxonomy=organizations&post_type=pageants', '');

    add_submenu_page('tppdb_home', 'Age Divisions', 'Age Divisions', 'edit_pages', 'edit-tags.php?taxonomy=age-divisions&post_type=pageant-years', '');
    add_submenu_page('tppdb_home', 'Stages', 'Stages', 'edit_pages', 'edit-tags.php?taxonomy=stages&post_type=pageants', '');


}

function tppdb_home_func()
{
    if (!current_user_can('edit_pages')) {
        wp_die('You do not have sufficient permissions to access this page.');
    } ?>

    <div class="wrap">
        <div id="icon-tools" class="icon32"></div>
        <h2>The Pageant Planet Database</h2>


    </div><!-- wrap -->

<?php
}

function tppdb_admin_request_tabs($current = 'profiles')
{
    $tabs = array('profiles' => 'Profile Requests', 'pageants' => 'Pageant Requests', 'systems' => 'System Requests');
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach ($tabs as $tab => $name) {
        $class = ($tab == $current) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='" . admin_url("admin.php?page=tppdb_requests&tab=$tab") . "'>$name</a>";

    }
    echo '</h2>';
}

function tppdb_requests_func()
{
    wp_enqueue_script('tppdb-approve', plugins_url( '/js/tppdb-approve.js', dirname(__FILE__) ), array( 'jquery' ));

    global $wpdb;
    $table_name = $wpdb->prefix . 'tppdb_requests';
    if (!current_user_can('edit_pages')) {
        wp_die('You do not have sufficient permissions to access this page.');
    } ?>

    <div class="wrap">
        <h2>Pending Requests</h2>


        <?php
        if (isset ($_GET['tab'])) tppdb_admin_request_tabs($_GET['tab']);
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

            if (isset($_GET['tab'])) {
                $tab = $_GET['tab'];
                if ($tab == "profiles") {
                    $post_type = "tpp_profiles";
                    $label = "profile";
                } elseif ($tab == "pageants") {
                    $post_type = "pageant";
                    $label = "pageant";
                } elseif ($tab == "systems") {
                    $post_type = "pageants";
                    $label = "system";
                }
            } else {
                $post_type = "tpp_profiles";
            }

            $myrows = $wpdb->get_results("SELECT * FROM $table_name WHERE status='pending' AND type='$post_type'");

            $items = 0;
            foreach ($myrows as $row) : $items++; ?>
                <tr data-id="<?php echo $row->id; ?>" data-user-id="<?php echo $row->user_id; ?>" data-post-id="<?php echo $row->post_id; ?>">
                    <td><?php echo get_userdata($row->user_id)->display_name; ?> is requesting to claim
                        the <?php echo $label; ?> <?php echo get_the_title($row->post_id); ?>.
                    </td>
                    <td><button class="approve-link">Approve</button></td>
                    <td><button class="deny-link">Deny</button></td>
                </tr>
            <?php endforeach; ?>

            <?php if ($items == 0) : ?>

                <tr>
                    <td colspan="3"><?php echo "No pending requests."; ?></td>
                </tr>

            <?php endif; ?>
            </tbody>
        </table>

    </div><!-- wrap -->

<?php
}

if (!function_exists('mbe_set_current_menu')) {

    function mbe_set_current_menu($parent_file)
    {
        global $submenu_file, $current_screen, $pagenow;

        // Set the submenu as active/current while anywhere in your Custom Post Type (nwcm_news)
        if ($current_screen->post_type == 'pageants' || $current_screen->post_type == 'pageant-years' || $current_screen->post_type == 'tpp_profiles') {

            if ($pagenow == 'post.php') {
                $submenu_file = 'edit.php?post_type=' . $current_screen->post_type;
            }

            if ($pagenow == 'edit-tags.php') {
                $submenu_file = 'edit-tags.php?taxonomy=nwcm_news_category&post_type=' . $current_screen->post_type;
            }

            $parent_file = 'tppdb_home';

        }

        return $parent_file;

    }

    add_filter('parent_file', 'mbe_set_current_menu');

}

function tppdb_submit_request () {
    $params = array();
    parse_str($_POST['data'], $params);

    $connection_id = p2p_type( 'profile_to_user' )->get_p2p_id( $params[postId], $params[userId] );
    p2p_update_meta( $connection_id, 'status', $params[status] );

    global $wpdb;
    $table_name = $wpdb->prefix . 'tppdb_requests';
    $wpdb->delete($table_name, array( 'ID' => $params[id]));
    die();
}
add_action( 'wp_ajax_tppdb_submit_request', 'tppdb_submit_request' );
add_action( 'wp_ajax_nopriv_tppdb_submit_request', 'tppdb_submit_request' );