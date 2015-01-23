<?php
global $wp_query;
$format = get_post_format();

if (false === $format)
    $format = 'standard';

$formats_meta = yt_get_post_formats_meta(get_the_ID());

/**
 *Link format
 */
$share_url = !empty($formats_meta['_format_link_url']) ? $formats_meta['_format_link_url'] : get_permalink(get_the_ID());

//print_r($formats_meta);
$share_url_text = !empty($formats_meta['_format_link_url'])
    ? sprintf('<div class="entry-format-meta margin-bottom-30">%s <a href="%s">#</a></div>',
        $formats_meta['_format_link_url'],
        get_permalink(get_the_ID()))
    : '';

/**
 *Extra class for entry title
 */
$entry_title_class = ' margin-bottom-30';
if ('quote' === $format && $quote_author
    || 'link' === $format && $share_url_text
) {
    $entry_title_class = '';
}


$entry_title = get_the_title(get_the_ID());
if ('link' === $format && $share_url_text) {
    $entry_title = sprintf('<a href="%s" title="%s" target="_blank" rel="external" class="secondary-2-primary">%s</a>', $share_url, get_the_title(get_the_ID()), get_the_title(get_the_ID()));
}

$feature_image = yt_get_options('blog_single_post_featured_image');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php do_action('yt_before_single_post_entry_header'); ?>

    <header class="entry-header tppdb">

        <?php

        do_action('yt_single_post_entry_header_start'); ?>

        <?php
        $fb_url = get_post_meta(get_the_ID(), 'tpp_facebook', true);
        $twitter_url = get_post_meta(get_the_ID(), 'tpp_twitter', true);
        $insta_url = get_post_meta(get_the_ID(), 'tpp_instagram', true);

        $website = get_post_meta(get_the_ID(), 'tpp_website', true);

        ?>

        <?php do_action('yt_single_post_entry_header_end'); ?>
    </header>
    <!-- .entry-header -->

    <?php do_action('yt_before_single_post_entry_content'); ?>

    <div class="row">
        <div class="entry-content col-sm-12">
            <h1 class="entry-title <?php echo $entry_title_class; ?>"><?php echo $entry_title; ?></h1>

            <div class="row">
                <div class="col-sm-8">

                    <?php
                    wp_enqueue_script('tppdb-bio-expander', plugins_url( '/js/tppdb-bio-expander.js', dirname(__FILE__) ), array( 'jquery' ));
                    the_content();
                    ?>

                    <hr/>

                    <?php

                    $pageants = new WP_Query(array(
                        'connected_type' => 'organization',
                        'connected_items' => get_queried_object(),
                        'nopaging' => true,
                    ));

                    $up_votes = 0;
                    $down_votes = 0;

                    $totalPageants = 0;
                    if ($pageants->have_posts()) :
                        $totalPageants = $pageants->found_posts;
                        while ($pageants->have_posts()): $pageants->the_post();
                            if (get_post_meta(get_the_ID(), '_thumbs_rating_up', true)) {
                                $up_votes = $up_votes + get_post_meta(get_the_ID(), '_thumbs_rating_up', true);
                            }

                            if (get_post_meta(get_the_ID(), '_thumbs_rating_down', true)) {
                                $down_votes = $down_votes + get_post_meta(get_the_ID(), '_thumbs_rating_down', true);
                            }
                        endwhile;
                    endif;

                    ?>
                    <div class="overall-hitmiss">
                        <h6 class="post-meta-key">Pageant Rating</h6>

                        <div class="thumbs-rating-container total-aggregate">
                            <?php if ($up_votes >= $down_votes) { ?>
                                <span class="thumbs-rating-up" data-text="HIT"> (<?php echo $up_votes; ?>)</span>
                            <?php } else { ?>
                                <span class="thumbs-rating-down" data-text="MISS"> (<?php echo $down_votes; ?>)</span>
                            <?php } ?>
                        </div>
                        <div class="hit-or-miss">
                            Total Pageants: <?php echo $totalPageants; ?> | Hit votes: <?php echo $up_votes; ?> | Miss
                            votes: <?php echo $down_votes; ?>
                        </div>
                    </div>

                    <hr class="visible-xs" />
                </div>
                <div class="meta-wrapper col-sm-4">
                    <ul class="post-meta">

                        <?php
                        $winner = get_posts(array(
                            'connected_type' => 'organization',
                            'connected_items' => get_queried_object(),
                            'nopaging' => true,
                            'posts_per_page' => 1,
                            'suppress_filters' => false
                        ));

                        foreach ($winner as $post) : setup_postdata($post);

                            p2p_type('winner')->each_connected($winner, array(), 'winner');

                            $winner_id = $post->winner[0]->ID;
                            $winner_name = $post->winner[0]->post_title;

                            break;

                        endforeach;
                        wp_reset_postdata();
                        //echo $winner[0]->post_title;
                        echo '<li>';
                        echo get_the_post_thumbnail($winner_id, array(215, 320)); ?>
                        <span class="post-meta-key">Reigning Titleholder</span>
                        <?php
                        echo '<a href="' . get_permalink($winner_id) . '">' . $winner_name . '</a>';
                        ?>


                        <?php if ($fb_url || $twitter_url || $insta_url) : ?>
                            <ul class="pageant-social-links">
                                <?php if ($fb_url) echo '<li><a href="' . $fb_url . '"><i class="fa fa-facebook-square"></i></a></li>'; ?>
                                <?php if ($twitter_url) echo '<li><a href="' . $twitter_url . '"><i class="fa fa-twitter-square"></i></a></li>'; ?>
                                <?php if ($insta_url) echo '<li><a href="' . $insta_url . '"><i class="fa fa-instagram-square"></i></a></li>'; ?>

                            </ul>
                        <?php endif; ?>
                        <?php
                        $types = wp_get_post_terms(get_the_ID(), 'age-divisions');
                        if (!empty($types)) :
                            ?>
                            <li>
                                <span class="post-meta-key">Age Divisions</span>
                                <ul>
                                    <?php
                                    foreach ($types as $type) {
                                        $link = get_term_link($type);
                                        echo '<li><a href="' . $link . '">' . $type->name . '</a></li>';
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php
                        endif;

                        $stages = wp_get_post_terms(get_the_ID(), 'stages');
                        if (!empty($stages)) :
                            ?>
                            <li>
                                <span class="post-meta-key">Phases of Competition</span>
                                <ul>
                                    <?php
                                    foreach ($stages as $stage) {
                                        $link = get_term_link($stage);
                                        echo '<li><a href="' . $link . '">' . $stage->name . "</a></li>";
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <?php
                        $scoring = get_post_meta(get_the_ID(), 'scoring');
                        if (!empty($scoring)) :
                            ?>
                            <li>
                                <span class="post-meta-key">Scoring</span>
                                <ul>
                                    <?php foreach ($scoring as $score) {
                                        echo '<li>' . $score . '</li>';
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <?php
                        $directors = new WP_Query(array(
                            'connected_type' => 'pageant_directors',
                            'connected_items' => get_queried_object()
                        ));

                        if ($directors->have_posts()):
                            ?>


                            <li>
                                <span class="post-meta-key">Pageant Director</span>
                                <ul>
                                <?php while ($directors->have_posts()) : $directors->the_post(); ?>
                                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                                <?php endwhile; ?>
                                </ul>
                            </li>
                        <?php
                        endif;
                        wp_reset_postdata(); ?>


                    </ul>
                </div>
            </div>

            <hr />

            <?php do_action('yt_single_post_entry_content_start'); ?>
            <?php

            if ('show' == $feature_image && has_post_thumbnail() && !post_password_required()) : ?>
                <div class="entry-thumbnail margin-bottom-30">
                    <?php the_post_thumbnail(array(160, 200)); ?>
                    <?php

                    if ($thumb_excerpt = yt_get_thumbnail_meta(get_post_thumbnail_id(get_the_ID()), 'post_excerpt')) {
                        echo '<div class="entry-caption thumbnail-caption">' . wpautop($thumb_excerpt) . '</div>';
                    }
                    ?>
                </div>
            <?php endif;

            ?>


            <?php do_action('yt_single_post_entry_content_end'); ?>

        </div>
        <!-- .entry-content -->

    </div>
    <!-- .row -->

    <?php if ($pageants->have_posts()) : ?>
    <div id="pageantsByYear" class="row">
        <div class="col-xs-12">
            <h4 style="margin: 0;"><?php the_title(); ?> Pageants by Year</h4>
            <p style="margin: 0;">Read Reviews, see who competed and more...</p>
            <div class="panel-group" id="pageantCollapse">
                <?php
                while ($pageants->have_posts()) : $pageants->the_post();
                $content = get_the_excerpt();
                $link = get_the_permalink();
                $post_stages = wp_get_post_terms(get_the_ID(), 'stages');
                $post_ages = wp_get_post_terms(get_the_ID(), 'age-divisions');
                p2p_type('winner')->each_connected($pageants, array(), 'winner');
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title"><a data-toggle="collapse" data-parent="#pageantCollapse" href="#post-<?php the_id(); ?>"><?php the_title(); ?></a></h5>
                    </div>
                    <div id="post-<?php the_ID(); ?>" class="panel-collapse collapse <?php if ($pageants->current_post == 0) { echo 'in'; } ?>">
                        <div class="panel-body">
                            <div class="col-xs-4">
                                <?php if (sizeof($post_stages) > 0) : ?>
                                    <p>Phases of Competition:<br />
                                        <?php
                                        foreach ($post_stages as $index=>$stage) :
                                            if ($index != 0) { echo ', '; }
                                            ?>
                                            <a href="<?php echo esc_url(get_term_link($stage)); ?>"><?php echo $stage->name; ?></a>
                                        <?php endforeach; ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (sizeof($post_ages) > 0) : ?>
                                    <p>Age Divisions:<br />
                                        <?php
                                        foreach ($post_ages as $index=>$age) :
                                            if ($index != 0) { echo ', '; }
                                            ?>
                                            <a href="<?php echo esc_url(get_term_link($age)); ?>"><?php echo $age->name; ?></a>
                                        <?php endforeach; ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (sizeof($post->winner) > 0) : ?>
                                    <p>Pageant Winner:<br />
                                        <?php foreach ($post->winner as $post) : setup_postdata($post); ?>
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        <?php endforeach; ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="col-xs-8">
                                <div class="entry-content"><?php echo $content; ?></div>
                                <a href="<?php echo $link; ?>">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php do_action('yt_before_single_post_entry_footer'); ?>



    <?php do_action('yt_after_single_post_entry_footer'); ?>

</article><!-- #post-<?php the_ID(); ?>## -->
