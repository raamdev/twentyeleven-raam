<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'single-note' ); ?>

				<?php endwhile; // end of the loop. ?>

<?php do_action('erp-show-related-posts', array('title'=>'Related Thoughts, Essays, Journals, and Notes', 'num_to_display'=>12, 'no_rp_text'=>'No Related Posts Found')); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>