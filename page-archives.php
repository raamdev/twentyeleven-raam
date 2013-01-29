<?php
/**
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 * Template Name: Archives
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">
				
				<header class="page-header">
					<h1 class="entry-title">Essays and Archives</h1>
				</header>
					<div id="header-line"></div>
<div class="entry-content">
	<div class="hentry archive-title"><h3>50 Most Recent Essays</h3></div>
				<ul>
					<?php query_posts('showposts=50&cat=-547'); ?>
	              <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	                  <li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> <span style="display:none;" id="archive_counts">- <?php echo $post->comment_count ?> comments</span></li>

	              <?php endwhile; endif; ?>	
				</ul>

<div class="hentry archive-title"><h3>Categories</h3>	</div>

				                    <ul>
				                        <?php wp_list_categories('title_li=&hierarchical=0&show_count=0') ?>	
				                    </ul>	

<div class="hentry archive-title"><h3>Monthly Archives</h3></div>
				<div id="monthly-archives-dropdown">
				<select name="archive-dropdown" onChange='document.location.href=this.options[this.selectedIndex].value;'> 
				  <option value=""><?php echo attribute_escape(__('Select Month')); ?></option> 
				  <?php wp_get_archives('type=monthly&format=option&show_post_count=0'); ?> </select>
				</div>

<div class="hentry archive-title"><h3>Tags</h3>
<p style="font-size: 12px;"><em>(Hint: Click one of the categories above to see tags for only that category.)</em></p>
<?php if ( function_exists('wp_tag_cloud') ) : ?>
<?php wp_tag_cloud('smallest=8&largest=22'); ?>
<?php endif; ?>
</div>

</div> <!-- entry-content -->
								
				<?php// the_post(); ?>

				<?php //get_template_part( 'content', 'page' ); ?>

				<?php //comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>
