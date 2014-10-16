<?php
/**
 * The Template for displaying all single posts.
 *
 * @package yeahthemes
 */

get_header(); ?>

	<?php yt_before_primary(); ?>
	
	<div id="primary" <?php yt_section_classes( 'content-area', 'primary' );?>>
		
		<?php yt_primary_start(); ?>
		
		<main id="content" <?php yt_section_classes( 'site-content', 'content' );?> role="main" itemprop="mainContentOfPage" itemscope="itemscope" itemtype="http://schema.org/Blog">

		<?php yt_before_loop(); ?>
		
		<?php while ( have_posts() ) : the_post(); ?>
		
			<?php yt_loop_start(); ?>
			
			<?php get_template_part( 'content', 'pageant' ); ?>

			<?php yt_loop_end(); ?>

		<?php endwhile; // end of the loop. ?>

		<?php yt_after_loop(); ?>

		</main><!-- #content -->
	
		<?php yt_primary_end(); ?>
		
	<!-- #primary -->
	
	<?php yt_after_primary(); ?>
	
<?php get_footer(); ?>