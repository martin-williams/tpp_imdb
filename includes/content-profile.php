<?php
$format = get_post_format();

if( false === $format )
    $format = 'standard';

$formats_meta = yt_get_post_formats_meta( get_the_ID());

/**
 *Link format
 */
$share_url = get_permalink( get_the_ID() );

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

    <div class="clearfix"></div>

    <?php
    $dob = get_post_meta(get_the_ID(), 'tppdb_dob', true);
    $city = get_post_meta(get_the_ID(), 'tppdb_city', true);
    $state = get_post_meta(get_the_ID(), 'tppdb_state', true);
    $country = get_post_meta(get_the_ID(), 'tppdb_country', true);

    $fb_url = get_post_meta(get_the_ID(), 'tppdb_facebook', true);
    $twitter_url = get_post_meta(get_the_ID(), 'tppdb_twitter', true);
    $insta_url = get_post_meta(get_the_ID(), 'tppdb_instagram', true);
    $website_url = get_post_meta(get_the_ID(), 'tppdb_website', true);

    $height = get_post_meta(get_the_ID(), 'tppdb_height', true);
    $weight = get_post_meta(get_the_ID(), 'tppdb_weight', true);
    $sign = get_post_meta(get_the_ID(), 'tppdb_zodiac-sign', true);
    $platform = get_post_meta(get_the_ID(), 'tppdb_pageant-platform', true);
    $talent = get_post_meta(get_the_ID(), 'tppdb_talent', true);
    $major = get_post_meta(get_the_ID(), 'tppdb_college-major', true);

    $facts = get_post_meta(get_the_ID(), 'tppdb_interesting_facts', true);
    ?>

        <?php if ($facts != "") :
                        ?>
        <div class="fun-facts">
            <h6 class="post-meta-key">Fun Facts</h6>
            <ul class="facts">
            <?php
                foreach($facts as $fact) :

                    echo "<li>$fact[tppdb_int_facts]</li>";

                endforeach;

            ?>
            </ul>
        </div>
        <hr/>

    <?php endif;  ?>

    <?php if ($fb_url || $twitter_url || $insta_url) : ?>
    <ul class="pageant-social-links">
        <?php if ($fb_url) echo '<li><a href="' . $fb_url . '" target="_blank" rel="nofollow"><i class="fa fa-facebook-square"></i></a></li>'; ?>
        <?php if ($twitter_url) echo '<li><a href="' . $twitter_url . '" target="_blank" rel="nofollow"><i class="fa fa-twitter-square"></i></a></li>'; ?>
        <?php if ($insta_url) echo '<li><a href="' . $insta_url . '" target="_blank" rel="nofollow"><i class="fa fa-instagram"></i></a></li>'; ?>
    </ul>
    <?php
    endif;

    if ($website_url) :
    ?>

    <p><a href="<?php echo $website_url; ?>" target="_blank" rel="nofollow"><?php echo $website_url; ?></a></p>

    <?php endif; ?>

    <ul class="additional-info">
    <?php
    if ($dob) {
        echo '<li><strong>Date of Birth: </strong>' . $dob . '</li>';
    }
    if ($city || $state || $country) {
        echo '<li><strong>Hometown: </strong>';
        if ($city) echo $city . ', ';
        if ($state) echo $state . ', ';
        if ($country) echo $country;
        echo '</li>';
    }
    ?>

    <?php if ($height) echo '<li><strong>Height:</strong> ' . $height . '</li>'; ?>
    <?php if ($weight) echo '<li><strong>Weight:</strong> ' . $weight . '</li>'; ?>
    <?php if ($sign) echo '<li><strong>Zodiac Sign:</strong> ' . $sign . '</li>'; ?>
    <?php if ($platform) echo '<li><strong>Pageant Platform:</strong> ' . $platform . '</li>'; ?>
    <?php if ($talent) echo '<li><strong>Talent:</strong> ' . $talent . '</li>'; ?>
    <?php if ($major) echo '<li><strong>College Major:</strong> ' . $major . '</li>'; ?>
    </ul>

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
wp_enqueue_script('magnific-popup-js', plugins_url('/lib/magnific-popup/jquery.magnific-popup.min.js', dirname(__FILE__) ), array( 'jquery' ));
wp_enqueue_script('tppdb-gallery', plugins_url( '/js/tppdb-gallery.js', dirname(__FILE__) ), array( 'jquery', 'magnific-popup-js' ));
wp_enqueue_style('magnific-popup-css', plugins_url( '/lib/magnific-popup/magnific-popup.css', dirname(__FILE__) ));

$attachments = new Attachments( 'profile_gallery_attachments' );
if ($attachments->exist()) :
?>
    <div id="profile-gallery" class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Gallery</h4>
        </div>
        <div class="panel-body tppdb-gallery">
            <?php while( $attachment = $attachments->get() ) : ?>
            <div class="col-xs-6 col-sm-3">
                <a href="<?php echo $attachments->url(); ?>">
                    <?php echo $attachments->image(); ?>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
<?php endif; ?>

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
