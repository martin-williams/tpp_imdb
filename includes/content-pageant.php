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

    <header class="entry-header">

        <?php do_action( 'yt_single_post_entry_header_start' );?>

        <h1 class="entry-title <?php echo $entry_title_class; ?>"><?php echo $entry_title; ?></h1>

        <?php echo 'quote' === $format ? $quote_author : '';?>
        <?php echo 'link' === $format ? $share_url_text : '';?>

        <?php do_action( 'yt_single_post_entry_header_end' );?>
    </header><!-- .entry-header -->

    <?php do_action( 'yt_before_single_post_entry_content' );?>

    <div class="row">
    <div class="entry-content col-md-12">

        <div class="meta-wrapper col-md-4"> <?php the_meta(); ?> </div>

        <?php do_action( 'yt_single_post_entry_content_start' );?>
        <?php
        /*Standard*/
        if( in_array( $format, array( 'standard', 'quote', 'link' ) ) ){

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


        }
        elseif( 'image' === $format ){
            if ( ! post_password_required() && yt_get_the_post_format_image() ) : ?>
                <div class="entry-media margin-bottom-30">
                    <?php echo yt_get_the_post_format_image(); ?>
                </div>
            <?php endif;
        }
        /*Audio*/
        elseif( 'audio' === $format ){
            if ( has_post_thumbnail() && ! post_password_required() ) : ?>
                <div class="entry-thumbnail<?php echo !yt_get_the_post_format_audio() ? ' margin-bottom-30' : ''; ?>">
                    <?php the_post_thumbnail(); ?>
                </div>
            <?php endif;
            if ( yt_get_the_post_format_audio() && !post_password_required() ) : ?>
                <div class="entry-format-media <?php echo has_post_thumbnail() && get_the_post_thumbnail() && ! post_password_required() ? 'with-cover ' : ''; ?>margin-bottom-30">
                    <?php echo yt_get_the_post_format_audio(); ?>
                </div>
            <?php endif;

            /*Gallery*/
        }elseif( 'gallery' === $format ){
            if ( yt_get_the_post_format_gallery() && !post_password_required() ) : ?>
                <div class="entry-format-media margin-bottom-30">
                    <?php echo yt_get_the_post_format_gallery(); ?>
                </div>
            <?php endif;

            /*Video*/
        }elseif( 'video' === $format ){
            if ( yt_get_the_post_format_video() && !post_password_required() ) : ?>
                <div class="entry-format-media margin-bottom-30">
                    <?php echo yt_get_the_post_format_video(); ?>
                </div>
            <?php endif;
        }
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
    $users = get_users( array(
        'connected_type' => 'pageant_competitors',
        'connected_items' => get_queried_object()
    ));
    ?>

    <div id="competitors" class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Pageant Competitors</h4>
        </div>
        <div class="panel-body">
            <ul>
                <?php
                foreach ($users as $user) {
                    echo '<li><a href="#">' . $user -> display_name . '</a></li>';
                }
                ?>
            </ul>
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
