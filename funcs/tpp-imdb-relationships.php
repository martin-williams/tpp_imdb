<?php


// Register and add Relationships

add_action('init', 'tppdb_create_relationships');


function tppdb_create_relationships () {
    create_competitor_relationship();
    create_director_relationship();
    create_post_pageant_relationship();
    create_post_profile_relationship();
    create_system_relationship();
    create_winner_relationship();
}


// Relationship Definitions

function create_competitor_relationship () {
    p2p_register_connection_type( array(
        'name' => 'pageant_competitors',
        'from' => 'pageant-years',
        'to'   => 'tpp_profiles',
        'title' => array( 'from' => 'Competitors', 'to' => 'Competed In' )
    ));
}

function create_director_relationship () {
    p2p_register_connection_type( array(
        'name' => 'pageant_directors',
        'from' => 'pageants',
        'to'   => 'tpp_profiles',
        'sortable' => 'any',
        'title' => array( 'from' => 'Director', 'to' => 'Directed' ),
        'fields' => array(
            'count' => array(
                'title' => 'Since',
                'type' => 'text',
            )
        )
    ));
}

function create_post_pageant_relationship () {
    p2p_register_connection_type( array(
        'name' => 'news_to_pageant',
        'from' => 'post',
        'to'   => 'pageant-years',
        'title' => array( 'from' => 'Pageants Mentioned', 'to' => 'Connected News' ),
    ));
}

function create_post_profile_relationship () {
    p2p_register_connection_type( array(
        'name' => 'news_to_profile',
        'from' => 'post',
        'to' => 'tpp_profiles',
        'title' => array( 'from' => 'Profiles Mentioned', 'to' => 'Connected News' ),
    ));
}

function create_system_relationship () {
    p2p_register_connection_type( array(
        'name' => 'organization',
        'from' => 'pageants',
        'to'   => 'pageant-years',
        'title' => array( 'from' => 'Pageants', 'to' => 'Pageant (System)' ),
    ));
}

function create_winner_relationship () {
    p2p_register_connection_type( array(
        'name'  => 'winner',
        'from'  => 'pageant-years',
        'to'    => 'tpp_profiles',
        'title' => array( 'from' => 'Winning Competitors', 'to' => 'Pageants Won' ),
    ));
}

