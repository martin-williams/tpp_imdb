<?php

function pageant_search_func () {
    wp_enqueue_script(
        'pageant-search',
        plugins_url( '/js/pageant_search.js', dirname(__FILE__) ),
        array( 'jquery' )
    );

    wp_enqueue_style('tpp-imdb-styles');

    $form = '<div class="panel-group" id="accordion">';
    $form .= '<div class="panel panel-primary">';
    $form .= '<div class="panel-heading" id=searchHeading">';
    $form .= '<h4 class="panel-title">';
    $form .= '<a data-toggle="collapse" data-parent="#accordion" href="#searchCollapse">Search</a>';
    $form .= '</h4></div>';
    $form .= '<div id="searchCollapse" class="panel-collapse collapse in">';

    $form .= '<form role="form" id="pageant-search" action="' . admin_url('admin-ajax.php') . '">';

    $form .= '<div class="panel-body">';

    $form .= '<fieldset class="stages">';
    $form .= '<legend>Phases of Competition</legend>';

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

    $form .= '</div>';
    $form .= '<div class="panel-footer">';

    $form .= '<input type="submit" class="btn btn-primary" value="Search" />';
    $form .= '</div>';
    $form .= '</form>';

    $form .= '</div>';
    $form .= '</div>';

    $form .= '<div class="panel panel-primary">';
    $form .= '<div class="panel-heading" id="resultsHeading">';
    $form .= '<h4 class="panel-title">';
    $form .= '<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#resultsCollapse">Search Results</a>';
    $form .= '</h4></div>';
    $form .= '<div id="resultsCollapse" class="panel-collapse collapse">';
    $form .= '<div class="panel-body search-results"></div>';

    $form .= '</div></div>';
    $form .= '</div>';
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
    while ($pageants->have_posts()) : $pageants->the_post();
        $post_stages = wp_get_post_terms( get_the_ID(), 'stages');
        $post_ages = wp_get_post_terms( get_the_ID(), 'age-divisions');

        $html = '<div id="post-' . get_the_ID() . '" ' . get_post_class() . '>';
        $html .= '<h4><a href="' . get_permalink() . '" rel="bookmark">' . get_the_title() . '</a></h4>';
        if (sizeof($post_stages) > 0) {
            $html .= '<p>Phases of Competition: ';

            foreach($post_stages as $index=>$stage) {
                if ($index != 0) {
                    $html .= ', ';
                }
                $html .= '<a href="' . esc_url(get_term_link($stage)) . '">' . $stage->name . '</a>';
            }

            $html .= '</p>';
        }

        if (sizeof($post_ages) > 0)  {
            $html .= '<p>Age divisions: ';

            foreach($post_ages as $index=>$age) {
                if ($index != 0) {
                    $html .= ', ';
                }
                $html .= '<a href="' . esc_url(get_term_link($age)) . '">' . $age->name . '</a>';
            }

            $html .= '</p>';
        }

        $html .= '<div class="entry-content">' . get_the_content() . '</div>';
        $html .= '</div>';
    endwhile;

    wp_reset_query();
    
    die($html);
}
add_action( 'wp_ajax_tppdb_pageant_search', 'pageant_search_submit' );
add_action( 'wp_ajax_nopriv_tppdb_pageant_search', 'pageant_search_submit' );