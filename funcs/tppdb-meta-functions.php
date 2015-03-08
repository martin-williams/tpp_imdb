<?php

if (!function_exists('tppdb_getPageantFacts')) :
    function tppdb_getPageantFacts ($post_ID = '') {
        $post_ID = intval(sanitize_text_field( $post_ID ));
        if ($post_ID == '') $post_ID = get_the_ID();

       // $facts = get_post_meta($post_ID, 'tppdb_re_facts', true);

        $facts = get_post_meta(get_the_ID(), 'tppdb_system_facts', false);


        $html = '';
        if ($facts != '' && count($facts) > 0) {
            $html .= '<div class="fun-facts">';
            $html .= '<h6 class="post-meta-key">Interesting Facts</h6>';
            $html .= '<ul class="facts">';

            foreach($facts as $fact) {
                $html .= '<li>' . $fact . '</li>';
            }

            $html .= '</ul>';
            $html .= '</div>';
        }

        return $html;
    }
endif;



if(!function_exists('tppdb_getPageantRating')):
function tppdb_getPageantRating($pageantID) {

    $reviews = get_comments(array(
        'post_id' => $pageantID,
        'status' => 'approve'
    ));

    $totalavg = array(); 

    foreach($reviews as $review){
        $id = $review->comment_ID;

        if(get_comment_meta($id, 'tppdb_review_rating_total', true)){
            $totalavg[] = get_comment_meta($id, 'tppdb_review_rating_total', true);
        }
    }

    if(count($totalavg) > 0){

        return array_sum($totalavg) / count($totalavg);

    } else {

        return false;

    }

}
endif;