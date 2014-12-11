<?php

function pageant_search_func ($atts) {
    $form = '<form role="form" id="pagenant-search">';



    $terms = get_terms('stages');

    foreach ($terms as $term) {
        $form .= '<div class="checkbox">';
        $form .= '<label><input type="checkbox" value="' . $term->slug . '" />' . $term->name . '</label>';
        $form .= '</div>';
    }

    $form .= '</form>';

    return $form;
}
add_shortcode( 'pageant_search', 'pageant_search_func' );