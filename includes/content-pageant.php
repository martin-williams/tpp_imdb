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

    $header_image = get_post_meta(get_the_ID(), 'wpcf-header-image', true);

        do_action( 'yt_single_post_entry_header_start' );?>

    <div class="tppdb-header-image" style="background-image: url(<?php echo $header_image;?>);">

        <h1 class="entry-title"><?php echo $entry_title; ?></h1>

        </div>
        <h3>
        <?php
        // $years = get_terms( 'years' );
        // if (!empty($years) && !is_wp_error($years)) {
        //     echo '<span>';
        //     foreach ($years as $year) {
        //         echo $year->name;
        //     }
        //     echo '</span>';
        // }
        // $types = get_terms( 'pageant-types');
        // if (!empty($types) && !is_wp_error($types)) {
        //     echo '<span>';
        //     foreach ($types as $type) {
        //         echo $type->name;
        //     }
        //     echo '</span>';
        // }
        ?>
        </h3>

        <?php /*
        $fb_url = get_post_meta(get_the_ID(), 'Facebook', true);
        $twitter_url = get_post_meta(get_the_ID(), 'Twitter', true);
        ?>

        <?php if ($fb_url || $twitter_url) : ?>
        <ul class="pageant-social-links">
            <?php if ($fb_url) echo '<li><a href="' . $fb_url . '"><i class="fa fa-facebook-square"></i></a></li>'; ?>
            <?php if ($twitter_url) echo '<li><a href="' . $twitter_url . '"><i class="fa fa-twitter-square"></i></a></li>'; ?>
        </ul>
        <?php endif; */?>

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
    <div class="entry-content col-md-12">

        <div class="meta-wrapper col-md-4">
            <ul class="post-meta">
                <?php
                $terms = wp_get_post_terms( get_the_ID(), 'pageant-types');
                if (!empty($terms[0]))
                    echo '<li><span class="post-meta-key">Pageant Type</span>' . $terms[0]->name . '</li>';
                ?>

                <?php
                $date = get_post_meta($post->ID, "wpcf-pageant-date", true);
                if ( $date ) {
                        echo '<li><span class="post-meta-key">Date</span>' . date("l, F jS Y", $date) . '</li>';
                }
                ?>

                <?php
                $venue = get_post_meta($post->ID, "wpcf-pageant-venue", true);
                if ( $venue ) {
                        echo '<li><span class="post-meta-key">Venue</span>' . $venue . '</li>';
                }
                ?>

                <?php
                // $competitors = new WP_Query( array(
                //     'connected_type' => 'pageant_competitors',
                //     'connected_items' => get_queried_object()
                // ));
                /*
                <li><span class="post-meta-key">Competitors</span><?php echo $competitors->post_count; ?></li>
                */?>
          
            </ul>
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

        <?php the_content(); ?>

        <?php
        wp_link_pages( array(
            'before' => '<div class="page-links pagination-nav">' . __( 'Pages:', 'yeahthemes' ),
            'after'  => '</div>',
            'link_before' => '<span class="page-numbers">',
            'link_after' => '</span>',
        ) );
        ?>

        <?php do_action( 'yt_single_post_entry_content_end' );?>

    </div><!-- .entry-content -->

    </div><!-- .row -->

    <?php
    $winners = new WP_Query( array(
        'connected_type' => 'winner',
        'connected_items' => get_queried_object()
    ));
    ?>

    <?php if (!empty($winners) && !is_wp_error($winners)) : ?>
    <div id="winners" class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Winner</h4>
        </div>
        <div class="panel-body">
            <ul>
                <?php while ($winners->have_posts() ) : $winners->the_post(); ?>
                <li><a href="<?php the_permalink(); ?>">
                <div class="col-md-2" style="max-height: 50px;overflow: hidden; padding-top: 5px; margin-bottom: 5px;"><?php the_post_thumbnail('thumb');?></div>
                <div class="col-md-10"><?php the_title(); ?></div></a></li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>
    <?php wp_reset_postdata(); 

 $competitors = new WP_Query( array(
                    'connected_type' => 'pageant_competitors',
                    'connected_items' => get_queried_object()
                ));
    ?>



    <div id="competitors" class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Competitors</h4>
        </div>
        <div class="panel-body">
            <ul>
                <?php while ($competitors->have_posts() ) : $competitors->the_post(); ?>
               <li><a href="<?php the_permalink(); ?>">
                <div class="col-md-2" style="max-height: 50px;overflow: hidden; padding-top: 5px; margin-bottom: 5px;"><?php the_post_thumbnail('thumb');?></div>
                <div class="col-md-10"><?php the_title(); ?></div></a></li>
                <?php endwhile; ?>
            </ul>
            </ul>
        </div>
    </div>

    <?php wp_reset_postdata(); ?>




    <div id="reviews" class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Reviews</h4>
        </div>
        <div class="panel-body">
            <ol class="commentlist">
                <?php
                $reviews = get_comments(array(
                    'post_id' => get_the_ID()
                ));

                wp_list_comments(array(
                    'per_page' => -1,
                    'reverse_top_level' => false
                ), $reviews);
                ?>
            </ol>
        </div>
    </div>

    <?php do_action( 'yt_before_single_post_entry_footer' );?>

    <?php

    $tag_list = get_the_tag_list( '', '' );
    if ( $tag_list ) :

        ?>

        <footer class="entry-meta hidden-print">
            <?php do_action( 'yt_single_post_entry_footer_start' );?>

            <?php do_action( 'yt_single_post_entry_footer_end' );?>
        </footer><!-- .entry-meta -->
    <?php endif;?>

    <?php do_action( 'yt_after_single_post_entry_footer' );?>

</article><!-- #post-<?php the_ID(); ?>## -->
