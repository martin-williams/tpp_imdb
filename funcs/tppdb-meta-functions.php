<?php

if (!function_exists('tppdb_getPageantFacts')) :
    function tppdb_getPageantFacts ($post_ID = '') {
        $post_ID = intval(sanitize_text_field( $post_ID ));
        if ($post_ID == '') $post_ID = get_the_ID();

       // $facts = get_post_meta($post_ID, 'tppdb_re_facts', true);

        $facts = get_post_meta(get_the_ID(), 'tppdb_system_facts', false);


        $html = '';
        if ($facts != '') {
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