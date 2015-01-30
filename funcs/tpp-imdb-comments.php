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
            <?php if ($commentrating = get_comment_meta(get_comment_ID(), 'tppdb_review_rating', true)) : ?>
                <div class="comment-rating col-xs-12">
                    <span>
                        <?php
                        for ($i = 0; $i < 5; $i++) {
                            $class = ($i <= $commentrating) ? 'fa fa-star' : 'fa fa-star-o';
                            echo '<i class="' . $class . '"></i>';
                        }
                        ?>
                    </span>
                </div>
            <?php endif; ?>

            <?php if ( '0' == $comment->comment_approved ) : ?>
            <p class="comment-awaiting-moderation clear"><em><?php _e( 'Your comment is awaiting moderation.', 'yeahthemes' ); ?></em></p>
            <?php endif; ?>
        </footer><!-- .comment-meta -->

        <?php do_action('yt_after_comment_meta', $comment);?>

        <div class="comment-content" itemprop="commentText">
            <?php comment_text(); ?>
        </div><!-- .comment-content -->

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
    wp_enqueue_script('tppdb-stars', plugins_url( '/js/stars.js', dirname(__FILE__) ), array( 'jquery' ));

    echo '<p class="comment-form-rating col-xs-12">'.
        '<label>'. __('Rating') . '</label><br />'.
        '<input type="hidden" id="rating" name="rating" value="0" />'.
        '<span>';

    for ($i = 1; $i <= 5; $i++) {
        echo '<i class="fa fa-star-o"></i>';
    }

    echo '</span></p>';
}
add_action('comment_form_logged_in_after', 'additional_fields');
add_action('comment_form_after_fields', 'additional_fields');

function add_rating_metadata($comment_id) {
    if (isset($_POST['rating']) && $_POST['rating'] != '') {
        $rating = $_POST['rating'];
        add_comment_meta($comment_id, 'tppdb_review_rating', $rating);
    }
}
add_action('comment_post', 'add_rating_metadata');

function extend_comment_add_meta_box() {
    add_meta_box('title', __('Comment Metadata - Extend Comment'), 'extend_comment_meta_box', 'comment', 'normal', 'high');
}
add_action('add_meta_box_comment', 'extend_comment_add_meta_box');

function extend_comment_meta_box($comment) {
    $rating = get_comment_meta($comment->comment_ID, 'tppdb_review_rating', true);
    wp_nonce_field('extend_comment_update', 'extend_comment_update', false);
    ?>
    <p>
        <label for="rating"><?php _e('Rating: '); ?></label>
        <span class="commentratingbox">
            <?php for ($i=1; $i <= 5; $i++) {
                echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="' . $i . '"';
                if ($rating == $i) echo ' checked="checked"';
                echo ' />' . $i . ' </span>';
            }
            ?>
        </span>
    </p>
    <?php
}

function extend_comment_edit_metafields($comment_id) {
    if (!isset($_POST['extend_comment_update']) || !wp_verify_nonce($_POST['extend_comment_update'], 'extend_comment_update')) return;

    if ((isset($_POST['rating'])) && ($_POST['rating'] != '')) :
        $rating = $_POST['rating'];
        update_comment_meta($comment_id, 'tppdb_review_rating', $rating);
    else :
        delete_comment_meta($comment_id, 'tppdb_review_rating');
    endif;
}
add_action('edit_comment', 'extend_comment_edit_metafields');