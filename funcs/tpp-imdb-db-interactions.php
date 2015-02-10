<?php

global $tppdb_version;
$tppdb_version = '1.2';



function tppdb_claim_request() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tppdb_requests';
    $params = explode('&', $_POST['data']);

    $postId = explode('=', $params[0]);
    $userId = explode('=', $params[1]);
    $postType = explode('=', $params[2]);

    p2p_type( 'profile_to_user' )->connect( $postId[1], $userId[1], array('status' => 'pending') );

    $wpdb->insert(
        $table_name,
        array(
            'requested' => current_time('mysql'),
            'post_id' => $postId[1],
            'user_id' => $userId[1],
            'type' => $postType[1],
            'status' => 'pending'
        ),
        array(
            '%s',
            '%d',
            '%d',
            '%s',
            '%s'
        )
    );

    die();
}
add_action( 'wp_ajax_tppdb_claim_request', 'tppdb_claim_request' );
add_action( 'wp_ajax_nopriv_tppdb_claim_request', 'tppdb_claim_request' );