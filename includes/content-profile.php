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

<header class="entry-header">

    <?php echo get_the_post_thumbnail(get_the_ID(), array(215,320)); ?>

    <h1 class="entry-title <?php echo $entry_title_class; ?>"><?php echo $entry_title; ?></h1>

    <?php the_content(); ?>

    <?php
    $dob = get_post_meta(get_the_ID(), 'Date of Birth', true);
    $fb_url = get_post_meta(get_the_ID(), 'Facebook', true);
    $twitter_url = get_post_meta(get_the_ID(), 'Twitter', true);
    $insta_url = get_post_meta(get_the_ID(), 'Instagram', true);
    $website_url = get_post_meta(get_the_ID(), 'Website', true);
    ?>

    <?php if ($dob) : ?>
    <p><span class="post-meta-key">Date of Birth</span><?php echo $dob; ?></p>
    <?php endif; ?>

    <?php if ($fb_url || $twitter_url || $insta_url) : ?>
    <ul class="pageant-social-links">
        <?php if ($fb_url) echo '<li><a href="' . $fb_url . '"><i class="fa fa-facebook-square"></i></a></li>'; ?>
        <?php if ($twitter_url) echo '<li><a href="' . $twitter_url . '"><i class="fa fa-twitter-square"></i></a></li>'; ?>
        <?php if ($insta_url) echo '<li><a href="' . $insta_url . '"><i class="fa fa-instagram"></i></a></li>'; ?>
    </ul>
    <?php
    endif;

    if ($website_url) :
    ?>

    <p><a href="<?php echo $website_url; ?>"><?php echo $website_url; ?></a></p>

    <?php endif; ?>

</header><!-- .entry-header -->

<?php
$pageants_won = new WP_Query( array(
    'connected_type' => 'winner',
    'connected_items' => get_queried_object()
));

if ($pageants_won->have_posts()) :
?>

<div id="pageants-won" class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Pageants Won</h4>
    </div>
    <div class="panel-body">
        <ul>
            <?php while ($pageants_won->have_posts() ) : $pageants_won->the_post(); ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>

<?php
wp_reset_postdata();
endif;
?>

<?php
$directors = new WP_Query( array(
    'connected_type' => 'pageant_directors',
    'connected_items' => get_queried_object()
));

if ($directors->have_posts()) :
?>

<div id="directors" class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Director</h4>
    </div>
    <div class="panel-body">
        <ul>
            <?php while ($directors->have_posts() ) : $directors->the_post(); ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>

<?php
wp_reset_postdata();
endif;
?>

<?php
$competitors = new WP_Query( array(
    'connected_type' => 'pageant_competitors',
    'connected_items' => get_queried_object()
));

if ($competitors->have_posts()) :
?>

<div id="competitors" class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Competed in:</h4>
    </div>
    <div class="panel-body">
        <ul>
            <?php while ($competitors->have_posts() ) : $competitors->the_post(); ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>

<?php
wp_reset_postdata();
endif;
?>
<?php
$news = new WP_Query( array(
    'connected_type' => 'recent_news_profiles',
    'connected_items' => get_queried_object()
));

if ($news->have_posts()) :
?>

<div id="competitors" class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">News</h4>
    </div>
    <div class="panel-body">
        <ul>
            <?php while ($news->have_posts() ) : $news->the_post(); ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>

<?php
wp_reset_postdata();
endif;
?>


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
