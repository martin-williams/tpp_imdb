<?php
$options = array();
parse_str($_POST['data'], $options);

$args = array(
    'post_type' => 'tpp_profiles',
    'areas-of-expertise' => $options['areas'],
    'roles' => $options['roles'],
);

$profiles = new WP_Query($args);
if ($profiles->have_posts()) {
    while ($profiles->have_posts()) : $profiles->the_post();
        $profile_areas = wp_get_post_terms(get_the_ID(), 'areas-of-expertise');
        $profile_roles = wp_get_post_terms(get_the_ID(), 'roles');
        ?>
        <div id="post-<?php echo get_the_ID(); ?>" class="panel panel-default">
            <div class="panel-body">
                <h4><a href="<?php echo get_permalink(); ?>" rel="bookmark"><?php echo get_the_title(); ?></a></h4>

                <div class="row">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="col-xs-4">
                            <?php the_post_thumbnail(); ?>
                        </div>
                        <div class="col-xs-8">
                    <?php else : ?>
                        <div class="col-xs-12">
                    <?php endif; ?>
                        <div class="entry-content"><?php echo get_the_excerpt(); ?></div>
                        <?php if (sizeof($profile_areas) > 0) : ?>
                            <p>Areas of Expertise:&nbsp;
                                <?php foreach ($profile_areas as $index => $area) : if ($index != 0) {
                                    echo ', ';
                                } ?>
                                    <a href="<?php echo esc_url(get_term_link($area)); ?>"><?php echo $area->name; ?></a>
                                <?php endforeach; ?>
                            </p>
                        <?php endif; ?>
                        <?php if (sizeof($profile_roles) > 0) : ?>
                            <p>Roles:&nbsp;
                                <?php foreach ($profile_roles as $index => $role) : if ($index != 0) {
                                    echo ', ';
                                } ?>
                                    <a href="<?php echo esc_url(get_term_link($role)); ?>"><?php echo $role->name; ?></a>
                                <?php endforeach; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    endwhile;
    wp_reset_query();
} else {
    echo '<p>Your search returned 0 results. Please try again.</p>';
}
?>