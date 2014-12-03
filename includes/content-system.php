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
    <?php
    $fb_url = get_post_meta(get_the_ID(), 'wpcf-facebook-link', true);
    $twitter_url = get_post_meta(get_the_ID(), 'wpcf-twitter-link', true);
    $website = get_post_meta(get_the_ID(), 'wpcf-website', true);

    ?>


    <?php do_action( 'yt_single_post_entry_header_end' );?>
</header><!-- .entry-header -->

<?php do_action( 'yt_before_single_post_entry_content' );?>

<div class="row">
    <div class="entry-content col-md-12">

        <div class="meta-wrapper col-md-4">
            <ul class="post-meta">

                <?php if ($fb_url || $twitter_url) : ?>
                    <ul class="pageant-social-links">
                        <?php if ($fb_url) echo '<li><a href="' . $fb_url . '"><i class="fa fa-facebook-square"></i></a></li>'; ?>
                        <?php if ($twitter_url) echo '<li><a href="' . $twitter_url . '"><i class="fa fa-twitter-square"></i></a></li>'; ?>
                    </ul>
                <?php endif; ?>
                <?php
                $types = wp_get_post_terms( get_the_ID(), 'pageant-types' );
                if (!empty($types)) :
                ?>
                <li>
                    <span class="post-meta-key">Competition Categories</span>
                    <?php
                    $count = 0;

                    foreach ($types as $type) {
                        if($count > 0){
                            echo ", ";
                        }
                        echo $type->name;
                        $count++;
                    }
                    ?>
                </li>
                <?php
                endif;

                $stages = wp_get_post_terms( get_the_ID(), 'pageant-stages' );
                if (!empty($stages)) :
                ?>
                <li>
                    <span class="post-meta-key">Stages Offered</span>
                    <?php
                    foreach ($stages as $stage) {
                        echo $stage->name;
                    }
                    ?>
                </li>
                <?php endif; ?>

                <?php /*
                $my_fields = array("Address of next pageant", "Next pageant date", "Year of first pageant");
                $custom_fields = get_post_custom();

                foreach ( $custom_fields as $key=>$value ) {
                    if (in_array($key, $my_fields)) : ?>
                        <li>
                            <span class="post-meta-key"><?php echo $key; ?></span><?php echo $value[0]; ?>
                        </li>
                <?php
                endif;
                }
                */ ?>

                <?php
                $scoring = get_post_meta( get_the_ID(), 'scoring');
                if (!empty($scoring)) :
                ?>
                <li>
                    <span class="post-meta-key">Scoring</span>
                    <?php foreach ($scoring as $score) {
                        echo $score . '<br />';
                    }
                    ?>
                </li>
                <?php endif; ?>

                
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


<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Pageant Information</h4>
    </div>
    <div class="panel-body info-body">
        <div class="col-md-6">
            <div class="connect">
                <h6 class="post-meta-key">Connect</h6>
          
                    <?php if ($fb_url || $twitter_url || $website) : ?>
                    <ul class="connect-links">
                        <?php if ($fb_url) echo '<li>Facebook: <a href="' . $fb_url . '">'.$fb_url.'</a></li>'; ?>
                        <?php if ($twitter_url) echo '<li>Twitter: <a href="' . $twitter_url . '">'.$twitter_url.'</a></li>'; ?>
                        <?php if ($website) echo '<li>Website: <a href="' . $website . '">'.$website.'</a></li>'; ?>
                    </ul>
                <?php endif; ?>
            </div>
            
          <?php
            $funFacts = get_post_meta( get_the_ID(), 'Fun Fact' );
            if (!empty($funFacts)) :
            ?>
            <div class="fun-facts">
                <h6 class="post-meta-key">Fun Facts</h6>
                <ul class="fact-items">
                <?php foreach ($funFacts as $fact) {
                    echo '<li>'.$fact.'</li>';
                }
                ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-md-6">

            <?php
            $directors = new WP_Query( array(
                'connected_type' => 'pageant_directors',
                'connected_items' => get_queried_object()
            ));
            ?>
             <div class="current-director">
                <h6 class="post-meta-key">Current Director</h6>
          
            <?php while ($directors->have_posts() ) : $directors->the_post(); ?>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            <?php endwhile; ?>

            </div>
            <?php wp_reset_postdata(); ?>
        </div>

        
    </div>
</div>
           
<div id="competitors" class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title"><?php the_title();?> Pageants by Year</h4>
    </div>
    <div class="panel-body">
        <ul>
            <?php
            p2p_type('winner')->each_connected($post->organization, array(), 'winner');

            foreach($post->organization as $post) : setup_postdata($post);
            ?>
            <li>
                <div class="col-md-6">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </div>
                <div class="col-md-6" style="text-align: right;">
                    <?php
                    foreach($post->winner as $post) :
                        setup_postdata($post);
                    ?>
                    Winner: <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <?php
                    break;
                    endforeach;
                    wp_reset_postdata();
                    ?>
                </div>
            </li>
            <?php
            endforeach;
            wp_reset_postdata();
            ?>
        </ul>
    </div>
</div>


    <?php
        $news = new WP_Query( array(
            'connected_type' => 'recent_news_pageants',
            'connected_items' => get_queried_object()
        ));

        if($news->have_posts()):
    ?>


    <div id="competitors" class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Pageant News</h4>
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

    endif;
    wp_reset_postdata(); ?>

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
