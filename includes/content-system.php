<?php
global $wp_query;
$format = get_post_format();

if( false === $format )
    $format = 'standard';

$formats_meta = yt_get_post_formats_meta( get_the_ID());

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

    // $header_image = get_post_meta(get_the_ID(), 'wpcf-header-image', true);
    
    do_action( 'yt_single_post_entry_header_start' );?>

    <?php /*
    <div class="tppdb-header-image" style="background-image: url(<?php echo $header_image;?>);">

        <h1 class="entry-title"><?php echo $entry_title; ?></h1>
    </div> */

    
    // p2p_type('winner')->each_connected($wp_query, array(), 'winner');?>





    <?php
    $fb_url = get_post_meta(get_the_ID(), 'tpp_facebook', true);
    $twitter_url = get_post_meta(get_the_ID(), 'tpp_twitter', true);
    $insta_url = get_post_meta(get_the_ID(), 'tpp_instagram', true);

    $website = get_post_meta(get_the_ID(), 'tpp_website', true);

    ?>

    <?php do_action( 'yt_single_post_entry_header_end' );?>
</header><!-- .entry-header -->

<?php do_action( 'yt_before_single_post_entry_content' );?>

<div class="row">
    <div class="entry-content col-md-12">
        <div class="col-md-7">
             <h1  class="entry-title <?php echo $entry_title_class; ?>"><?php echo $entry_title; ?></h1>

            <?php the_content(); ?>

            <hr />
           <?php
            $funFacts = get_post_meta( get_the_ID(), 'Fun Fact' );
            $facts = get_post_meta(get_the_ID(), 'tppdb_facts', true);

            if ($facts != "") :
            ?>
            <div class="fun-facts">
                <h6 class="post-meta-key">Fun Facts</h6>
                <?php echo $facts;?>
            </div>
            <hr />

            <?php endif; ?>

            <?php

            $pageants = new WP_Query( array(
                'connected_type' => 'organization',
                'connected_items' => get_queried_object(),
                'nopaging' => true,
            ));

            $up_votes = 0;
            $down_votes = 0;

            $totalPageants = 0; 
            if($pageants->have_posts()) :
                $totalPageants = $pageants->found_posts;
                while($pageants->have_posts()): $pageants->the_post();
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
                    <?php if($up_votes >= $down_votes){ ?> 
                        <span class="thumbs-rating-up" data-text="HIT"> (<?php echo $up_votes;?>)</span>
                    <?php } else { ?>
                         <span class="thumbs-rating-down" data-text="MISS"> (<?php echo $down_votes;?>)</span>
                    <?php } ?>
                </div>
                <div class="hit-or-miss">
                    Total Pageants: <?php echo $totalPageants;?> | Hit votes: <?php echo $up_votes; ?> | Miss votes: <?php echo $down_votes; ?>
                </div>
            </div>


        </div>
        <div class="meta-wrapper col-md-4">
                    <ul class="post-meta">

            <?php 
        $winner = get_posts( array(
          'connected_type' => 'organization',
          'connected_items' => get_queried_object(),
          'nopaging' => true,
          'posts_per_page'   => 1,
          'suppress_filters' => false
        ) );

        foreach ( $winner as $post ) : setup_postdata( $post ); 
       
          p2p_type('winner')->each_connected($winner, array(), 'winner');

          $winner_id = $post->winner[0]->ID;
          $winner_name = $post->winner[0]->post_title;

          break;

        endforeach; 
        wp_reset_postdata();
        //echo $winner[0]->post_title;
        echo '<li>';
        echo get_the_post_thumbnail($winner_id, array(215,320));  ?>
        <span class="post-meta-key">Reigning Titleholder</span>
        <?php 
        echo '<a href="'. get_permalink($winner_id) . '">' . $winner_name . '</a>';
        ?>


                <?php if ($fb_url || $twitter_url || $insta_url) : ?>
                    <ul class="pageant-social-links">
                        <?php if ($fb_url) echo '<li><a href="' . $fb_url . '"><i class="fa fa-facebook-square"></i></a></li>'; ?>
                        <?php if ($twitter_url) echo '<li><a href="' . $twitter_url . '"><i class="fa fa-twitter-square"></i></a></li>'; ?>
                        <?php if ($insta_url) echo '<li><a href="' . $insta_url . '"><i class="fa fa-instagram-square"></i></a></li>'; ?>

                    </ul>
                <?php endif; ?>
                <?php
                $types = wp_get_post_terms( get_the_ID(), 'age-divisions' );
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
                        $link = get_term_link($type);

                        echo '<a href="' . $link . '">' . $type->name . '</a>';
                        $count++;
                    }
                    ?>
                </li>
                <?php
                endif;

                $stages = wp_get_post_terms( get_the_ID(), 'stages' );
                if (!empty($stages)) :
                ?>
                <li>
                    <span class="post-meta-key">Phases of Competition</span>
                    <?php
                    foreach ($stages as $stage) {
                        $link = get_term_link($stage);

                        echo '<a href="' . $link . '">' . $stage->name . "</a><br />";
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

                      <?php
            $directors = new WP_Query( array(
                'connected_type' => 'pageant_directors',
                'connected_items' => get_queried_object()
            ));
            
            if($directors->have_posts()):
            ?>


             <li>
                    <span class="post-meta-key">Pageant Director</span>
          
                    <?php while ($directors->have_posts() ) : $directors->the_post(); ?>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <?php endwhile; ?>

            </li>
            <?php 
            endif;
            wp_reset_postdata(); ?>

                
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
        
    
        <?php do_action( 'yt_single_post_entry_content_end' );?>

    </div><!-- .entry-content -->

</div><!-- .row -->

           
<div id="competitors" class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title"><?php the_title();?> Pageants by Year</h4>
        <p style="margin: 0;">Read Reviews, see who competed and more...</p>
    </div>
    <div class="panel-body">
        <ul>

            <?php



            if($pageants->have_posts()) :
    
                while($pageants->have_posts()): $pageants->the_post();            

                    p2p_type('winner')->each_connected($pageants, array(), 'winner');

            ?>
            <li>
                <div class="col-md-6">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </div>
                <div class="col-md-6" style="text-align: right;">
                    <?php 
                    foreach($post->winner as $post) : setup_postdata($post); ?>
                        Winner: <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <?php break;
                    endforeach;
                    wp_reset_postdata();
                    ?>
                </div>
            </li>
            <?php
            

                endwhile;

            else: 
                  echo  "No connected pageants";
            endif;
            wp_reset_postdata();
            ?>
        </ul>
    </div>
</div>


    <?php
        $news = new WP_Query( array(
            'connected_type' => 'news_to_pageant',
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

    else :  ?>
     <div id="competitors" class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Pageant News</h4>
            </div>
            <div class="panel-body">
                <ul><li>
                <?php echo "No recent news"; ?>
                </li>
                  </ul>
        </div>
    </div>
    <? endif;
    wp_reset_postdata(); ?>

<?php do_action( 'yt_before_single_post_entry_footer' );?>



<?php do_action( 'yt_after_single_post_entry_footer' );?>

</article><!-- #post-<?php the_ID(); ?>## -->
