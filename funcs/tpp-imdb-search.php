<?php

function pageant_search_func ($atts) {
    wp_enqueue_script(
        'pageant-search',
        plugins_url( '/js/pageant_search.js', dirname(__FILE__) ),
        array( 'jquery' )
    );

    $form = '<form role="form" id="pageant-search" action="' . admin_url('admin-ajax.php') . '">';
    $form .= '<p>Show pageants containing:</p>';

    $terms = get_terms('stages');
    foreach ($terms as $term) {
        $form .= '<div class="checkbox">';
        $form .= '<label><input type="checkbox" name="' . $term->slug . '" />' . $term->name . '</label>';
        $form .= '</div>';
    }

    $form .= '<input type="submit" value="Search" />';
    $form .= '</form>';

    $form .= '<div class="search-results"></div>';
    return $form;
}
add_shortcode( 'pageant_search', 'pageant_search_func' );

function pageant_search_submit () {
    $terms = explode(',', $_POST['data']);

    $args = array(
        'post_type' => 'pageants',
        'stages' => $terms
    );

    $pageants = new WP_Query($args);
    $html = "";
    while ($pageants->have_posts()) : $pageants->the_post();
        $html .= '<div id="post-' . get_the_ID() . '" ' . get_post_class() . '>
            <h2><a href="' . get_permalink() . '" rel="bookmark">' . get_the_title() . '</a></h2>
            <div class="entry-content">' . get_the_content() . '</div>
        </div>';
    endwhile;

    wp_reset_query();
    
    die($html);
}
add_action( 'wp_ajax_tppdb_pageant_search', 'pageant_search_submit' );
add_action( 'wp_ajax_nopriv_tppdb_pageant_search', 'pageant_search_submit' );