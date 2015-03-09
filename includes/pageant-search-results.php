<?php
$options = array();
parse_str($_POST['data'], $options);

$stages = ($options['stages'] != "") ? explode(',', $options['stages']) : array();
$ages = ($options['ages'] != "") ? explode(',', $options['ages']) : array();

if (count($stages) > 0 && count($ages) > 0) {
    $taxQuery = array(
        'relation' => 'OR',
        array(
            'taxonomy' => 'stages',
            'field' => 'slug',
            'terms' => $stages,
        ),
        array(
            'taxonomy' => 'age-divisions',
            'field' => 'slug',
            'terms' => $ages,
        ),
    );
} elseif (count($stages) > 0 || count($ages) > 0) {
    if (count($stages) > 0) {
        $taxQuery = array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'stages',
                'field' => 'slug',
                'terms' => $stages,
            ),
        );
    } elseif (count($ages) > 0) {
        $taxQuery = array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'age-divisions',
                'field' => 'slug',
                'terms' => $ages,
            ),
        );
    }
} else {
    $taxQuery = array();
}

$args = array(
    'post_type' => 'pageants',
    'post_status' => 'publish',
    'tax_query' => $taxQuery,
    'orderby' => 'title',
    'order' => 'ASC',
);
?>

<?php
$pageants = new WP_Query($args);
while ($pageants->have_posts()) : $pageants->the_post();
    $post_stages = wp_get_post_terms(get_the_ID(), 'stages');
    $post_ages = wp_get_post_terms(get_the_ID(), 'age-divisions');
    ?>

    <div id="post-<?php echo get_the_ID(); ?>" class="panel panel-default">
        <div class="panel-body" style="position: relative;">
            <h4><a href="<?php echo get_permalink(); ?>" rel="bookmark"><?php echo get_the_title(); ?></a></h4>
            <a href="/suggest-a-correction/?item=<?php echo urlencode(get_permalink());?>" style="position: absolute; top: 10px; right: 10px; color:#ccc;font-size: 10px;">Suggest a correction</a>
            <div class="row">

                <div class="col-xs-4">
                    <?php if (sizeof($post_stages) > 0) : ?>
                        <p style="margin: 0;"><strong>Phases of Competition:</strong></p>
                        <ul>
                            <?php foreach ($post_stages as $index => $stage) : ?>
                                <li><a href="<?php echo esc_url(get_term_link($stage)); ?>"><?php echo $stage->name; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if (sizeof($post_ages) > 0) : ?>
                        <p style="margin: 0;"><strong>Age Divisions:</strong></p>
                        <ul>
                            <?php foreach ($post_ages as $age) : ?>
                                <li><a href="<?php echo esc_url(get_term_link($age)); ?>"><?php echo $age->name; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php
                        $avg = tppdb_getPageantRating(get_the_ID());

                        if($avg){
                            echo '<strong>Pageant Rating: </strong>'. $avg . '/5 Stars';
                        } else {
                            echo '<strong>Pageant Rating: </strong>Not yet rated';
                        }

                        echo '<br/><span class="text-primary">';

                        for ($i = 0; $i < 5; $i++) {
                            $class = 'fa-star-o';
                            if ($i < $avg) $class = 'fa-star';
                            echo '<i class="fa ' . $class . '"></i>';
                        }

                        echo '</span>';
                    ?>

                </div>
                <div class="col-xs-8">

                    <div class="entry-content"><?php echo get_the_excerpt(); ?></div>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>

    <p>Is your pageant not listed? <a href="/submit-your-pageant">Click to submit your pageant to our database.</a></p>

<?php
wp_reset_query();
?>