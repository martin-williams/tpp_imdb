<?php
$format = get_post_format();

if( false === $format )
    $format = 'standard';

$formats_meta = yt_get_post_formats_meta( get_the_ID());

/**
 *Quote format
 */
$quote_author = !empty( $formats_meta['_format_quote_source_name'] )  ? '<cite class="entry-format-meta margin-bottom-30">' . $formats_meta['_format_quote_source_name'] . '</cite>' : '';
$quote_author = !empty( $formats_meta['_format_quote_source_url'] ) ? sprintf( '<cite class="entry-format-meta margin-bottom-30"><a href="%s">%s</a></cite>', $formats_meta['_format_quote_source_url'], $formats_meta['_format_quote_source_name'] ) : $quote_author;

/**
 *Link format
 */
$share_url = !empty( $formats_meta['_format_link_url'] ) ? $formats_meta['_format_link_url'] : get_permalink( get_the_ID() );

//print_r($formats_meta);
$share_url_text = !empty( $formats_meta['_format_link_url'] )
    ? sprintf( '<div class="entry-format-meta margin-bottom-30">%s <a href="%s">#</a></div>',
        $formats_meta['_format_link_url'],
        get_permalink( get_the_ID() ) )
    : '';

/**
 *Extra class for entry title
 */
$entry_title_class = ' margin-bottom-30';
if( 'quote' === $format  && $quote_author
    || 'link' === $format  && $share_url_text
){
    $entry_title_class = '';
}


$entry_title = get_the_title( get_the_ID() );
if( 'link' === $format  && $share_url_text  ){
    $entry_title = sprintf('<a href="%s" title="%s" target="_blank" rel="external" class="secondary-2-primary">%s</a>', $share_url, get_the_title( get_the_ID() ), get_the_title( get_the_ID() ) );
}

$feature_image = yt_get_options('blog_single_post_featured_image');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php do_action( 'yt_before_single_post_entry_header' );?>

    <header class="entry-header tppdb">

        <?php

        do_action( 'yt_single_post_entry_header_start' );?>

        <?php echo 'quote' === $format ? $quote_author : '';?>
        <?php echo 'link' === $format ? $share_url_text : '';?>

        <?php do_action( 'yt_single_post_entry_header_end' );?>
    </header><!-- .entry-header -->

    <?php do_action( 'yt_before_single_post_entry_content' );?>

    <?php
    $my_fields = array("Location", "Date");
    $custom_fields = get_post_custom();
    ?>

    <div class="row">
    <div class="entry-content col-sm-12">
        <h1 class="entry-title <?php echo $entry_title_class; ?>"><?php echo $entry_title; ?></h1>
        <div class="row">
            <div class="col-sm-8">

                <h6 class="post-meta-key">Pageant Rating</h6>
                <?php if(tppdb_getPageantRating(get_the_ID())){ ?>
                    <i class="fa fa-star star-rating-main"></i><?php echo tppdb_getPageantRating(get_the_ID());?>/5 Stars
                <?php } else { ?>
                    <i class="fa fa-star star-rating-main"></i> Not Yet Rated
                <?php } ?>

                <hr />
                <?php wp_enqueue_script('tppdb-bio-expander', plugins_url( '/js/tppdb-bio-expander.js', dirname(__FILE__) ), array( 'jquery' )); ?>
                <?php the_content(); ?>

                <hr />
              

                <a id="reviewBtn" class="btn btn-primary" href="#reviews">Read Reviews on <?php echo $entry_title; ?></a>

                <hr class="visible-xs" />
            </div>

            <div class="meta-wrapper col-sm-4">
                <ul class="post-meta">
                    <?php
                    $winners = new WP_Query( array(
                        'connected_type' => 'winner',
                        'connected_items' => get_queried_object()
                    ));
                    ?>

                    <?php if (!empty($winners) && !is_wp_error($winners)) : ?>
                        <?php while ($winners->have_posts() ) : $winners->the_post(); ?>
                            <li>
                                <?php the_post_thumbnail(array(215,320));?>
                                <span class="post-meta-key">Pageant Winner</span>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>

                    <?php
                    // get link to parent system
                    $system = new WP_Query( array(
                        'connected_type' => 'organization',
                        'connected_items' => get_queried_object()
                    ));

                    if (!empty($system) && !is_wp_error($system)) :

                    while ($system->have_posts() ) : $system->the_post();
                    ?>

                    <li>
                        <span class="post-meta-key">Pageant Organization</span>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </li>

                    <?php
                    endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>

                    <?php
                    $terms = wp_get_post_terms( get_the_ID(), 'age-divisions');
                    if (!empty($terms)) :
                    ?>
                        <li>
                            <span class="post-meta-key">Age Divisions</span>
                            <ul>
                            <?php foreach ($terms as $type) : $link = get_term_link($type); ?>
                                <li><a href="<?php echo esc_url($link); ?>"><?php echo $type->name; ?></a></li>
                            <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php
                    endif;
                    ?>

                    <?php
                    $date = get_post_meta($post->ID, "tppdb_pageant_date", true);
                    if ( $date ) {
                            echo '<li><span class="post-meta-key">Date</span>' . $date . '</li>';
                    }
                    ?>

                    <?php
                    $venue = get_post_meta($post->ID, "tppdb_pageant_venue", true);
                    if ( $venue ) {
                            echo '<li><span class="post-meta-key">Venue</span>' . $venue . '</li>';
                    }
                    ?>


                </ul>
            </div>
        </div>

        <?php do_action( 'yt_single_post_entry_content_start' );?>
        <?php

            if ( 'show' == $feature_image && has_post_thumbnail() && ! post_password_required() ) : ?>
                <div class="entry-thumbnail margin-bottom-30">
                    <?php the_post_thumbnail(array(160, 200)); ?>
                    <?php

                    if( $thumb_excerpt = yt_get_thumbnail_meta( get_post_thumbnail_id( get_the_ID() ), 'post_excerpt' ) ){
                        echo '<div class="entry-caption thumbnail-caption">' . wpautop( $thumb_excerpt ) . '</div>';
                    }
                    ?>
                </div>
            <?php endif;

        ?>

        <?php do_action( 'yt_single_post_entry_content_end' );?>

    </div><!-- .entry-content -->

    </div><!-- .row -->

    <hr />

    <?php
        $specialAwards = new WP_Query( array(
            'connected_type' => 'special_awards',
            'connected_items' => get_queried_object()
        ));
    ?>

    <?php if (!empty($winners) && !empty($specialAwards)) : ?>
        <div id="awardWinners" class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Award Winners</h4>
            </div>
            <ul class="list-group">
                <?php while ($winners->have_posts()) : $winners->the_post(); ?>
                    <li class="list-group-item">Pageant Winner: <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php endwhile; ?>
                <?php while ($specialAwards->have_posts()) : $specialAwards->the_post(); ?>
                    <li class="list-group-item"><?php echo p2p_get_meta( get_post()->p2p_id, 'count', true ); ?>: <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>

    <?php
        $competitors = new WP_Query( array(
            'connected_type' => 'pageant_competitors',
            'connected_items' => get_queried_object()
        ));
    ?>

    <div id="competitors" class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Competitors</h4>
        </div>
        <div class="list-group">
            <?php while ($competitors->have_posts() ) : $competitors->the_post(); ?>
                <a class="list-group-item" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            <?php endwhile; ?>
        </div>
    </div>

    <?php wp_reset_postdata(); ?>

    <?php
    $news = new WP_Query( array(
        'connected_type' => 'news_to_pageant',
        'connected_items' => get_queried_object()
    ));
    ?>
    <div id="news" class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title"><?php echo $entry_title; ?> in the news</h4>
        </div>
        <ul class="list-group">
            <?php while ( $news->have_posts() ) : $news->the_post(); ?>
            <li class="list-group-item"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php endwhile; ?>
        </ul>
    </div>
    <?php wp_reset_postdata(); ?>

    <?php
    $attachments = new Attachments( 'gallery_attachments' );
    if ($attachments->exist()) :
        wp_enqueue_script('magnific-popup-js', plugins_url( '/lib/magnific-popup/jquery.magnific-popup.min.js', dirname(__FILE__) ), array( 'jquery' ));
        wp_enqueue_script('tppdb-gallery', plugins_url( '/js/tppdb-gallery.js', dirname(__FILE__) ), array( 'jquery', 'magnific-popup-js' ));
        wp_enqueue_style('magnific-popup-css', plugins_url( '/lib/magnific-popup/magnific-popup.css', dirname(__FILE__) ));
    ?>
    <div id="pageant-gallery" class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Gallery</h4>
        </div>
        <div class="panel-body tppdb-gallery">
            <?php while( $attachment = $attachments->get() ) : ?>
            <div class="col-xs-3">
                <a href="<?php echo $attachments->url(); ?>"><?php echo $attachments->image(); ?></a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php $buzzArticles = get_post_meta(get_the_ID(), 'tpp_pageant_buzz_articles', false); ?>
    <?php if (isset($buzzArticles) && count($buzzArticles) != 0) : ?>
        <div id="buzz-articles" class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Buzz Around The Web</h4>
            </div>
            <div class="list-group">
                <?php foreach ($buzzArticles as $buzz) {
                    $array = explode("|", $buzz);
                    echo '<a class="list-group-item" target="_blank" href="' . $array[1] . '">' . $array[0] . '</a>';
                } ?>
            </div>
        </div>
    <?php endif; ?>

    <div id="reviews" class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Reviews</h4>
        </div>
        <ol class="commentlist">
            <?php
            $reviews = get_comments(array(
                'post_id' => get_the_ID(),
                'status' => 'approve'
            ));

            wp_list_comments(array(
                'per_page' => -1,
                'reverse_top_level' => false,
                'callback' => 'tppdb_comment'
            ), $reviews);
            ?>
        </ol>
        <div class="panel-body">
            <?php
            $args = array(
                'title_reply' => __( 'Write a Review' ),
                'cancel_reply_link' => __( 'Cancel Review' ),
                'label_submit' => __( 'Post Review' ),
                'comment_field' => '<p class="comment-form-comment col-xs-12"><label for="comment">' . _x( 'Review', 'noun' ) . '<span class="required">*</span></label><textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true">' . '</textarea></p>',
                'comment_notes_after' => ''
            );

            comment_form($args);
            ?>
        </div>
    </div>
<?php 
       $permalink = get_permalink(get_the_ID() );

     ?>

    <div class="actions-footer row">
    <div class="col-xs-12">
            <a href="<?php echo get_bloginfo("url");?>/suggest-a-correction/?item=<?php echo $permalink; ?>" style="color: #eee;margin: 0; padding: 0;">Suggest a correction</a>
    </div>

    </div>

    <?php do_action( 'yt_before_single_post_entry_footer' );?>



    <?php do_action( 'yt_after_single_post_entry_footer' );?>

</article><!-- #post-<?php the_ID(); ?>## -->
