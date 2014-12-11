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
        <footer class="comment-meta">
            <div class="comment-author vcard" itemprop="creator" itemscope itemtype="http://schema.org/Person">
                <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
						<?php printf( '<cite class="fn" itemprop="name">%s</cite>', get_comment_author_link() ); ?>
            </div><!-- .comment-author -->

            <div class="comment-metadata">
                <time datetime="<?php comment_time( 'c' ); ?>" itemprop="dateCreated">
                    <?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'yeahthemes' ), get_comment_date( 'M j, Y' ), get_comment_time() ); ?>
                </time>

            </div><!-- .comment-metadata -->

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