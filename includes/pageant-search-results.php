<?php
$terms = explode('&', $_POST['data']);

$stages_arr = explode('=', $terms[0]);
$ages_arr = explode('=', $terms[1]);

$stages = explode(',', $stages_arr[1]);
$ages = explode(',', $ages_arr[1]);

$args = array(
    'post_type' => 'pageants',
    'stages' => $stages,
    'age-divisions' => $ages,
    'orderby' => 'title',
    'order'   => 'ASC',
);

$pageants = new WP_Query($args);
while ($pageants->have_posts()) : $pageants->the_post();
    $post_stages = wp_get_post_terms(get_the_ID(), 'stages');
    $post_ages = wp_get_post_terms(get_the_ID(), 'age-divisions');
    ?>

    <div id="post-<?php echo get_the_ID(); ?>" class="panel panel-default">
        <div class="panel-body">
            <h4><a href="<?php echo get_permalink(); ?>" rel="bookmark"><?php echo get_the_title(); ?></a></h4>

            <div class="row">
                <div class="col-xs-4">
                    <?php if (sizeof($post_stages) > 0) : ?>
                        <p>Phases of Competition:<br/>

                            <?php
                            foreach ($post_stages as $index => $stage) :
                                if ($index != 0) {
                                    echo ', ';
                                }
                                ?>
                                <a href="<?php echo esc_url(get_term_link($stage)); ?>"><?php echo $stage->name; ?></a>
                            <?php endforeach; ?>

                        </p>
                    <?php endif; ?>

                    <?php if (sizeof($post_ages) > 0) : ?>
                        <p>Age divisions:<br/>

                            <?php
                            foreach ($post_ages as $index => $age) :
                                if ($index != 0) {
                                    echo ', ';
                                }
                                ?>
                                <a href="<?php echo esc_url(get_term_link($age)); ?>"><?php echo $age->name; ?></a>
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
<?php endwhile; ?>

    <p>Is your pageant not listed? <a href="#">Click to submit your pageant to our database.</a></p>

<?php
wp_reset_query();
?>