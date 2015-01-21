<?php
$areas_arr = explode('=', $_POST['data']);

$areas = explode(',', $areas_arr[1]);

$args = array(
    'post_type' => 'tpp_profiles',
    'areas-of-expertise' => $areas,
    'roles' => 'Coach'
);

$coaches = new WP_Query($args);
while ($coaches->have_posts()) : $coaches->the_post();
    $coach_areas = wp_get_post_terms(get_the_ID(), 'areas-of-expertise');
?>
    <div id="post-<?php echo get_the_ID(); ?>" class="panel panel-default">
        <div class="panel-body">
            <h4><a href="<?php echo get_permalink(); ?>" rel="bookmark"><?php echo get_the_title(); ?></a> </h4>

            <div class="row">
                <div class="col-xs-4">
                    <?php if (sizeof($coach_areas) > 0) : ?>
                        <p>Areas of Expertise:<br />
                        <?php
                        foreach ($coach_areas as $index => $area) :                                    if ($index != 0) { echo ', '; }
                        ?>
                        <a href="<?php echo esc_url(get_term_link($area)); ?>"><?php echo $area->name; ?></a>
                        <?php endforeach; ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="col-xs-8">
                    <div class="entry-content"><?php echo get_the_excerpt(); ?></div>
                </div>
            </div>
        </div>
    </div>
<?php
endwhile;
wp_reset_query();
?>