<?php

function pageant_search_func ($atts) {
    wp_enqueue_script(
        'pageant-search',
        plugins_url( '/js/pageant_search.js', dirname(__FILE__) ),
        array( 'jquery' )
    );

    $form = '<form role="form" id="pageant-search" action="' . admin_url('admin-ajax.php') . '">';
    $form .= '<fieldset class="stages">';
    $form .= '<legend>Stages</legend>';

    $stages = get_terms('stages');
    foreach ($stages as $stage) {
        $form .= '<div class="checkbox">';
        $form .= '<label><input type="checkbox" name="' . $stage->slug . '" />' . $stage->name . '</label>';
        $form .= '</div>';
    }
    $form .= '</fieldset>';

    $form .= '<fieldset class="ages">';
    $form .= '<legend>Age Divisions</legend>';

    $ages = get_terms('age-divisions');
    foreach ($ages as $age) {
        $form .= '<div class="checkbox">';
        $form .= '<label><input type="checkbox" name="' . $age->slug . '" />' . $age->name . '</label>';
        $form .= '</div>';
    }
    $form .= '</fieldset>';

    $form .= '<input type="submit" value="Search" />';
    $form .= '</form>';

    $form .= '<div class="search-results"></div>';
    return $form;
}
add_shortcode( 'pageant_search', 'pageant_search_func' );

function pageant_search_submit () {
    $terms = explode('&', $_POST['data']);

    $stages_arr = explode('=', $terms[0]);
    $ages_arr = explode('=', $terms[1]);

    $stages = explode(',', $stages_arr[1]);
    $ages = explode(',', $ages_arr[1]);

    $args = array(
        'post_type' => 'pageants',
        'stages' => $stages,
        'age-divisions' => $ages
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