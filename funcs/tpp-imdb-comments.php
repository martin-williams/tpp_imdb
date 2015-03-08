<?php
/**
* Template for comments and pingbacks.
*
* Used as a callback by wp_list_comments() for displaying the comments.
*
*/
if ( ! function_exists( 'tppdb_comment' ) ) {

function tppdb_comment( $comment, $args, $depth ) {
$GLOBALS['comment'] = $comment;

//print_r($comment);

if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
    <div class="comment-body">
        <?php _e( 'Pingback:', 'yeahthemes' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'yeahthemes' ), '<span class="edit-link">', '</span>' ); ?>
    </div>

    <?php else : ?>

<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
    <?php echo (isset($comment->comment_number) ? '<span class="comment-number">' . $comment->comment_number . '</span>' : ''); ?>

    <article id="div-comment-<?php comment_ID(); ?>" class="comment-body" itemprop="comment" itemscope="itemscope" itemtype="http://schema.org/Comment">
        <footer class="comment-meta row">
            <div class="comment-author vcard col-md-8" itemprop="creator" itemscope itemtype="http://schema.org/Person">
                <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
						<?php printf( '<cite class="fn" itemprop="name">%s</cite>', get_comment_author_link() ); ?>
            </div><!-- .comment-author -->

            <div class="comment-metadata col-md-4">
                <time datetime="<?php comment_time( 'c' ); ?>" itemprop="dateCreated">
                    <?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'yeahthemes' ), get_comment_date( 'M j, Y' ), get_comment_time() ); ?>
                </time>

            </div><!-- .comment-metadata -->

            <?php 

             //    var_dump(get_comment_meta(get_comment_ID()));
           ?>

            <?php if ( '0' == $comment->comment_approved ) : ?>
            <p class="comment-awaiting-moderation clear"><em><?php _e( 'Your rating is awaiting approval.', 'yeahthemes' ); ?></em></p>
            <?php endif; ?>
        </footer><!-- .comment-meta -->

        <?php do_action('yt_after_comment_meta', $comment); ?>

          <div class="col-xs-7 pull-left">
            <div class="comment-content" itemprop="commentText">
                <?php comment_text(); ?>
            </div><!-- .comment-content -->
        </div>
        

           <?php   echo '<div class="row"><div class="col-xs-5 pull-right">';


             echo '<div class="row">';
            if ($commentrating = get_comment_meta(get_comment_ID(), 'tppdb_review_rating_org', true)) : ?>
                <div class="comment-rating col-xs-6">
                    <div><strong>Organization of the Pageant</strong></div>
                    <span class="text-primary">
                        <?php
                        for ($i = 1; $i < 6; $i++) {
                            $class = ($i <= $commentrating) ? 'fa fa-star' : 'fa fa-star-o';
                            echo '<i class="' . $class . '"></i>';
                        }
                        ?>
                    </span>
                </div>
            <?php endif; ?>
            <?php if ($commentrating = get_comment_meta(get_comment_ID(), 'tppdb_review_rating_dir', true)) : ?>
                <div class="comment-rating col-xs-6">
                    <div><strong>Director Professionalism</strong></div>

                    <span class="text-primary">
                        <?php
                        for ($i = 1; $i < 6; $i++) {
                            $class = ($i <= $commentrating) ? 'fa fa-star' : 'fa fa-star-o';
                            echo '<i class="' . $class . '"></i>';
                        }
                        ?>
                    </span>
                </div>
            <?php endif; 

             echo '</div><div class="row" style="margin-top: 10px;">';


            ?>


                        <?php if ($commentrating = get_comment_meta(get_comment_ID(), 'tppdb_review_rating_prod', true)) : ?>
                <div class="comment-rating col-xs-6">
                                    <div><strong>Production Quality</strong></div>

                    <span class="text-primary">
                        <?php
                        for ($i = 1; $i < 6; $i++) {
                            $class = ($i <= $commentrating) ? 'fa fa-star' : 'fa fa-star-o';
                            echo '<i class="' . $class . '"></i>';
                        }
                        ?>
                    </span>
                </div>
            <?php endif; ?>
                        <?php if ($commentrating = get_comment_meta(get_comment_ID(), 'tppdb_review_rating_ovr', true)) : ?>
                <div class="comment-rating col-xs-6">
                    <div><strong>Overall Pageant Experience</strong></div>

                    <span class="text-primary">
                        <?php
                        for ($i = 1; $i < 6; $i++) {
                            $class = ($i <= $commentrating) ? 'fa fa-star' : 'fa fa-star-o';
                            echo '<i class="' . $class . '"></i>';
                        }
                        ?>
                    </span>
                </div>
            <?php endif; ?>

        </div></div>
      

        </div><!-- end ratings/comment row-->

        <div class="comment-reply-edit">
            <?php

             do_action('yt_comment_reply_edit_start', $comment);

             comment_reply_link( array_merge( $args, array( 'reply_text' => apply_filters('yt_icon_reply_comment', '<i class="fa fa-reply"></i>') . ' ' . __('Reply', 'yeahthemes'), 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );

             edit_comment_link(  __( 'Edit', 'yeahthemes' ) );

             do_action('yt_comment_reply_edit_end', $comment);

            ?>
        </div><!-- .reply -->

    </article><!-- .comment-body -->

<?php
endif;
}
} // ends check for tppdb_comment()

function additional_fields() {
    if (is_singular('pageants')) :
    wp_enqueue_script('tppdb-stars', plugins_url( '/js/stars.js', dirname(__FILE__) ), array( 'jquery' ));

    echo '<p class="comment-form-rating col-xs-3">'.
        '<label>'. __('Organization of the Pageant') . '</label><br />'.
        '<input type="hidden" id="rating_org" name="rating_org" value="0" />'.
        '<span class="text-primary">';

    for ($i = 1; $i <= 5; $i++) {
        echo '<i class="fa fa-star-o"></i>';
    }

    echo '</span></p>';



       echo '<p class="comment-form-rating col-xs-3">'.
        '<label>'. __('Director\'s Professionalism') . '</label><br />'.
        '<input type="hidden" id="rating_dir" name="rating_dir" value="0" />'.
        '<span class="text-primary">';

    for ($i = 1; $i <= 5; $i++) {
        echo '<i class="fa fa-star-o"></i>';
    }

    echo '</span></p>';


       echo '<p class="comment-form-rating col-xs-3">'.
        '<label>'. __('Production Quality') . '</label><br />'.
        '<input type="hidden" id="rating_prod" name="rating_prod" value="0" />'.
        '<span class="text-primary">';

    for ($i = 1; $i <= 5; $i++) {
        echo '<i class="fa fa-star-o"></i>';
    }

    echo '</span></p>';


       echo '<p class="comment-form-rating col-xs-3">'.
        '<label>'. __('Overall Pageant Experience') . '</label><br />'.
        '<input type="hidden" id="rating_ovr" name="rating_ovr" value="0" />'.
        '<span class="text-primary">';

    for ($i = 1; $i <= 5; $i++) {
        echo '<i class="fa fa-star-o"></i>';
    }

    echo '</span></p>';
    endif;
}
add_action('comment_form_logged_in_after', 'additional_fields');
add_action('comment_form_after_fields', 'additional_fields');

function add_rating_metadata($comment_id) {

    $rates = array();
    if (isset($_POST['rating_org']) && $_POST['rating_org'] != '') {
        $rating_org = $_POST['rating_org'];
        $rates[] = $rating_org;
        add_comment_meta($comment_id, 'tppdb_review_rating_org', $rating_org);
    }
    if (isset($_POST['rating_dir']) && $_POST['rating_dir'] != '') {
        $rating_dir = $_POST['rating_dir'];
        $rates[] = $rating_dir;
        add_comment_meta($comment_id, 'tppdb_review_rating_dir', $rating_dir);
    }
    if (isset($_POST['rating_prod']) && $_POST['rating_prod'] != '') {
        $rating_prod = $_POST['rating_prod'];
        $rates[] = $rating_prod;
        add_comment_meta($comment_id, 'tppdb_review_rating_prod', $rating_prod);
    }
    if (isset($_POST['rating_ovr']) && $_POST['rating_ovr'] != '') {
        $rating_ovr = $_POST['rating_ovr'];
        $rates[] = $rating_ovr;
        add_comment_meta($comment_id, 'tppdb_review_rating_ovr', $rating_ovr);
    }

    if(!empty($rates) && count($rates) == 4) {
        $avg = array_sum($rates) / count($rates);
        add_comment_meta($comment_id, 'tppdb_review_rating_total', $avg);
    }
    

}
add_action('comment_post', 'add_rating_metadata');

function extend_comment_add_meta_box() {
    add_meta_box('title', __('Comment Metadata - Extend Comment'), 'extend_comment_meta_box', 'comment', 'normal', 'high');
}
add_action('add_meta_box_comment', 'extend_comment_add_meta_box');

function extend_comment_meta_box($comment) {
    $rating_org = get_comment_meta($comment->comment_ID, 'tppdb_review_rating_org', true);
    $rating_dir = get_comment_meta($comment->comment_ID, 'tppdb_review_rating_dir', true);
    $rating_prod = get_comment_meta($comment->comment_ID, 'tppdb_review_rating_prod', true);
    $rating_ovr = get_comment_meta($comment->comment_ID, 'tppdb_review_rating_ovr', true);

    wp_nonce_field('extend_comment_update', 'extend_comment_update', false);
    ?>
    <p>
        <label for="rating_org"><?php _e('Organization of the Pageant: '); ?></label>
        <span class="commentratingbox">
            <?php for ($i=1; $i <= 5; $i++) {
                echo '<span class="commentrating"><input type="radio" name="rating_org" id="rating_org" value="' . $i . '"';
                if ($rating_org == $i) echo ' checked="checked"';
                echo ' />' . $i . ' </span>';
            }
            ?>
        </span>
    </p>

     <p>
        <label for="rating_dir"><?php _e('Director\'s Professionalism: '); ?></label>
        <span class="commentratingbox">
            <?php for ($i=1; $i <= 5; $i++) {
                echo '<span class="commentrating"><input type="radio" name="rating_dir" id="rating_dir" value="' . $i . '"';
                if ($rating_dir == $i) echo ' checked="checked"';
                echo ' />' . $i . ' </span>';
            }
            ?>
        </span>
    </p>

     <p>
        <label for="rating_prod"><?php _e('Production Quality: '); ?></label>
        <span class="commentratingbox">
            <?php for ($i=1; $i <= 5; $i++) {
                echo '<span class="commentrating"><input type="radio" name="rating_prod" id="rating_prod" value="' . $i . '"';
                if ($rating_prod == $i) echo ' checked="checked"';
                echo ' />' . $i . ' </span>';
            }
            ?>
        </span>
    </p>

     <p>
        <label for="rating_ovr"><?php _e('Overall Pageant Experience: '); ?></label>
        <span class="commentratingbox">
            <?php for ($i=1; $i <= 5; $i++) {
                echo '<span class="commentrating"><input type="radio" name="rating_ovr" id="rating_ovr" value="' . $i . '"';
                if ($rating_ovr == $i) echo ' checked="checked"';
                echo ' />' . $i . ' </span>';
            }
            ?>
        </span>
    </p>
    <?php
}

function extend_comment_edit_metafields($comment_id) {
    if (!isset($_POST['extend_comment_update']) || !wp_verify_nonce($_POST['extend_comment_update'], 'extend_comment_update')) return;

    if ((isset($_POST['rating_org'])) && ($_POST['rating_org'] != '')) :
        $rating_org = $_POST['rating_org'];
        update_comment_meta($comment_id, 'tppdb_review_rating_org', $rating_org);
    else :
        delete_comment_meta($comment_id, 'tppdb_review_rating_org');
    endif;

     if ((isset($_POST['rating_dir'])) && ($_POST['rating_dir'] != '')) :
        $rating_dir = $_POST['rating_dir'];
        update_comment_meta($comment_id, 'tppdb_review_rating_dir', $rating_dir);
    else :
        delete_comment_meta($comment_id, 'tppdb_review_rating_dir');
    endif;

     if ((isset($_POST['rating_prod'])) && ($_POST['rating_prod'] != '')) :
        $rating_prod = $_POST['rating_prod'];
        update_comment_meta($comment_id, 'tppdb_review_rating_prod', $rating_prod);
    else :
        delete_comment_meta($comment_id, 'tppdb_review_rating_prod');
    endif;

     if ((isset($_POST['rating_ovr'])) && ($_POST['rating_ovr'] != '')) :
        $rating_ovr = $_POST['rating_ovr'];
        update_comment_meta($comment_id, 'tppdb_review_rating_ovr', $rating_ovr);
    else :
        delete_comment_meta($comment_id, 'tppdb_review_rating_ovr');
    endif;

    if(isset($_POST['rating_org']) && isset($_POST['rating_dir']) && isset($_POST['rating_prod']) && isset($_POST['rating_ovr'])) {
            $rates = array($_POST['rating_org'], $_POST['rating_dir'], $_POST['rating_prod'], $_POST['rating_ovr']);
            $avg = array_sum($rates) / count($rates);
            update_comment_meta($comment_id, 'tppdb_review_rating_total', $avg);
    }


}
add_action('edit_comment', 'extend_comment_edit_metafields');

add_filter( 'preprocess_comment', 'verify_comment_meta_data' );

function verify_comment_meta_data( $commentdata ) {

   //  wp_die(var_dump($_POST));
    if ( ! isset( $_POST['rating_org'] ) )
        wp_die( __( 'Error: please provide a rating for the Organization of the Pageant.' ) );
     if ( ! isset( $_POST['rating_dir'] ) )
        wp_die( __( 'Error: please provide a rating for the Pageant Director\'s Professionalism.' ) );
     if ( ! isset( $_POST['rating_prod'] ) )
        wp_die( __( 'Error: please provide a rating for the Production Quality.' ) );
     if ( ! isset( $_POST['rating_ovr'] ) )
        wp_die( __( 'Error: please provide a rating for your Overall Pageant Experience.' ) );
    return $commentdata;
}