<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">

		<h1 class="entry-title"><?php the_title(); ?></h1>

<?php essays_post_header_metadata(); ?>

	</header><!-- .entry-header -->

	<div class="entry-content">
		
<?php
$release_after=365 * 86400; // days * seconds per day
$post_age = date('U') - get_post_time('U');
if ($post_age > $release_after || current_user_can("access_s2member_level1")) { ?>
	
<?php the_post_thumbnail('medium', array('class' => 'alignleft singlethumb')); ?>		

<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		
		<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>

		<?php if ( get_the_author_meta( 'description' ) && is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries ?>
		<div id="author-info">
			<div id="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyeleven_author_bio_avatar_size', 68 ) ); ?>
			</div><!-- #author-avatar -->
			<div id="author-description">
				<h2><?php printf( esc_attr__( 'About %s', 'twentyeleven' ), get_the_author() ); ?></h2>
				<?php the_author_meta( 'description' ); ?>
				<div id="author-link">
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
						<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'twentyeleven' ), get_the_author() ); ?>
					</a>
				</div><!-- #author-link	-->
			</div><!-- #author-description -->
		</div><!-- #entry-author-info -->
		<?php endif; ?>
	</footer><!-- .entry-meta -->
	<?php } else {
		journal_notice('journal entry');
	} // Closes if-block that checks post age ?>	
	
</article><!-- #post-<?php the_ID(); ?> -->

<?php
$release_after=365 * 86400; // days * seconds per day
$post_age = date('U') - get_post_time('U');
if ($post_age > $release_after || current_user_can("access_s2member_level1")) { ?>
	
<?php essays_post_metadata(); ?>

<?php essays_subscribe_box(); ?>

<?php comments_template( '', true ); ?>

<?php } ?>