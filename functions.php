<?php
/**
 * Twenty Eleven functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyeleven_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 584;

/**
 * Tell WordPress to run twentyeleven_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'twentyeleven_setup' );

if ( ! function_exists( 'twentyeleven_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyeleven_setup() in a child theme, add your own twentyeleven_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, and Post Formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_setup() {

	/* Make Twenty Eleven available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Eleven, use a find and replace
	 * to change 'twentyeleven' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentyeleven', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Load up our theme options page and related code.
	require( dirname( __FILE__ ) . '/inc/theme-options.php' );

	// Grab Twenty Eleven's Ephemera widget.
	require( dirname( __FILE__ ) . '/inc/widgets.php' );

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'twentyeleven' ) );

	// Add support for a variety of post formats
	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );

	// Add support for custom backgrounds
	add_custom_background();

	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	add_theme_support( 'post-thumbnails' );

	// The next four constants set how Twenty Eleven supports custom headers.

	// The default header text color
	define( 'HEADER_TEXTCOLOR', '000' );

	// By leaving empty, we allow for random image rotation.
	define( 'HEADER_IMAGE', '' );

	// The height and width of your custom header.
	// Add a filter to twentyeleven_header_image_width and twentyeleven_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyeleven_header_image_width', 1000 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyeleven_header_image_height', 288 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be the size of the header image that we just defined
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Add Twenty Eleven's custom image sizes
	add_image_size( 'large-feature', HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true ); // Used for large feature (header) images
	add_image_size( 'small-feature', 500, 300 ); // Used for featured posts if a large-feature doesn't exist

	// Turn on random header image rotation by default.
	add_theme_support( 'custom-header', array( 'random-default' => true ) );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See twentyeleven_admin_header_style(), below.
	add_custom_image_header( 'twentyeleven_header_style', 'twentyeleven_admin_header_style', 'twentyeleven_admin_header_image' );

	// ... and thus ends the changeable header business.

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'wheel' => array(
			'url' => '%s/images/headers/wheel.jpg',
			'thumbnail_url' => '%s/images/headers/wheel-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Wheel', 'twentyeleven' )
		),
		'shore' => array(
			'url' => '%s/images/headers/shore.jpg',
			'thumbnail_url' => '%s/images/headers/shore-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Shore', 'twentyeleven' )
		),
		'trolley' => array(
			'url' => '%s/images/headers/trolley.jpg',
			'thumbnail_url' => '%s/images/headers/trolley-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Trolley', 'twentyeleven' )
		),
		'pine-cone' => array(
			'url' => '%s/images/headers/pine-cone.jpg',
			'thumbnail_url' => '%s/images/headers/pine-cone-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Pine Cone', 'twentyeleven' )
		),
		'chessboard' => array(
			'url' => '%s/images/headers/chessboard.jpg',
			'thumbnail_url' => '%s/images/headers/chessboard-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Chessboard', 'twentyeleven' )
		),
		'lanterns' => array(
			'url' => '%s/images/headers/lanterns.jpg',
			'thumbnail_url' => '%s/images/headers/lanterns-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Lanterns', 'twentyeleven' )
		),
		'willow' => array(
			'url' => '%s/images/headers/willow.jpg',
			'thumbnail_url' => '%s/images/headers/willow-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Willow', 'twentyeleven' )
		),
		'hanoi' => array(
			'url' => '%s/images/headers/hanoi.jpg',
			'thumbnail_url' => '%s/images/headers/hanoi-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Hanoi Plant', 'twentyeleven' )
		)
	) );
}
endif; // twentyeleven_setup

if ( ! function_exists( 'twentyeleven_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_header_style() {

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#site-title,
		#site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // twentyeleven_header_style

if ( ! function_exists( 'twentyeleven_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyeleven_setup().
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1,
	#desc {
		font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
	}
	#headimg h1 {
		margin: 0;
	}
	#headimg h1 a {
		font-size: 32px;
		line-height: 36px;
		text-decoration: none;
	}
	#desc {
		font-size: 14px;
		line-height: 23px;
		padding: 0 0 3em;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	#headimg img {
		max-width: 1000px;
		height: auto;
		width: 100%;
	}
	</style>
<?php
}
endif; // twentyeleven_admin_header_style

if ( ! function_exists( 'twentyeleven_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyeleven_setup().
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
		?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // twentyeleven_admin_header_image

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function twentyeleven_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 */
function twentyeleven_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyeleven_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function twentyeleven_auto_excerpt_more( $more ) {
	return ' &hellip;' . twentyeleven_continue_reading_link();
}
add_filter( 'excerpt_more', 'twentyeleven_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function twentyeleven_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= twentyeleven_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyeleven_custom_excerpt_more' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function twentyeleven_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyeleven_page_menu_args' );

/**
 * Register our sidebars and widgetized areas. Also register the default Epherma widget.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_widgets_init() {

	register_widget( 'Twenty_Eleven_Ephemera_Widget' );

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Showcase Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-2',
		'description' => __( 'The sidebar for the optional Showcase Template', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area One', 'twentyeleven' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'twentyeleven' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Three', 'twentyeleven' ),
		'id' => 'sidebar-5',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'twentyeleven_widgets_init' );

/**
 * Display navigation to next/previous pages when applicable
 */
function twentyeleven_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentyeleven' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older ', 'twentyeleven' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></div>
		</nav><!-- #nav-above -->
	<?php endif;
}

/**
 * Return the URL for the first link found in the post content.
 *
 * @since Twenty Eleven 1.0
 * @return string|bool URL or false when no link is present.
 */
function twentyeleven_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function twentyeleven_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-5' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}

if ( ! function_exists( 'twentyeleven_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyeleven_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyeleven' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s %2$s', 'twentyeleven' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a style="font-size:11px; color: #AAA !important; font-family: serif;" href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date */
								sprintf( __( '%1$s', 'twentyeleven' ), get_comment_date() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentyeleven' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'twentyeleven' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
			<div style="clear:both;"></div>
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for twentyeleven_comment()

if ( ! function_exists( 'twentyeleven_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own twentyeleven_posted_on to override in a child theme
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'twentyeleven' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		sprintf( esc_attr__( 'View all posts by %s', 'twentyeleven' ), get_the_author() ),
		esc_html( get_the_author() )
	);
}
endif;

/**
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_body_classes( $classes ) {

	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( is_singular() && ! is_home() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) )
		$classes[] = 'singular';

	return $classes;
}
add_filter( 'body_class', 'twentyeleven_body_classes' );

function essays_post_header_metadata() {
?>
<!-- START POST HEADER METADATA -->

<div style="text-align: center;">
<div class="entry-meta" style="font-size: 13px; line-height: 1.6em;">
<p><em>Published <?php the_time('F jS, Y') ?> by <?php the_author(); ?>
<br>
<?php echo get_post_meta( get_the_ID(), 'ncl_current_location', true ); ?></em></p>
</div>
</div>

<!-- END POST HEADER METADATA -->

<?php }


function essays_post_metadata() {
?>

<!-- START POST METADATA AND SHARING -->

<div style="text-align: center;">
	<span class="entry-meta">
<br>
<em>If you enjoyed this, please share it with others:<br>
<a href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=RT%20@RaamDev%20<?php the_title(); ?>" target="_new">Twitter</a> | <a href="https://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&t=<?php the_title(); ?>" target="_new">Facebook</a> | <a href="https://plusone.google.com/_/+1/confirm?hl=en&url=<?php the_permalink(); ?>" target="_new">Google Plus</a> | <?php if(function_exists('wp_email')) { email_link(); } ?> <!-- <a href="#" target="_new">Send via Email</a>--></em>

<!-- <?php if (get_post_type() == 'post') { ?><br />Filed in <?php the_category(', '); } ?><?php if (has_tag()) { ?>, and tagged <?php the_tags( '', ', ', ''); ?> <?php } ?> -->

</span>
</div>


<!-- END POST METADATA AND SHARING -->

<?php }

function essays_subscribe_box() {
?>

<!-- START ESSAYS SUBSCRIBE BOX -->
<div class="subscribe-essays">

<form method="post" action="http://raamdev.us1.list-manage.com/subscribe/post?u=5daf0f6609de2506882857a28&amp;id=dc1b1538af" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
		
<div class="subscribe-essays-box">
<div class="subscribe-essays-title"><em>Subscribe to receive new thoughts and essays as they're published:</em></div>

	<div class="subscribe-essays-name">Name: <input type="text" name="FNAME" class="text" value="" tabindex="500" onclick="" onfocus="" onblur=""></div>

	<div class="subscribe-essays-email">Email: <input type="text" class="text" name="EMAIL" value="" tabindex="501" onclick="" onfocus=""></div>
	<div style="display:none;"> <input type="hidden" name="group[1129]" value="1" id="mce-group[1129]"> </div>
	<div style="display:none;"> <input type="hidden" name="MERGE3" value="<?php echo 'http://' . $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>" id="MERGE3"> </div>
	<div class="subscribe-essays-submit"><input type="submit" name="subscribe" value="Subscribe" tabindex="503"></div>

<div style="clear: both;"></div>
	</div>
	</form>
</div>
<!-- END ESSAYS SUBSCRIBE BOX -->
<?php }

function cleanr_theme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
    
     <div id="comment-<?php comment_ID(); ?>">
     <div class="commenthead">
      <div class="comment-author vcard">
         <?php echo get_avatar($comment,$size='48',$default='<path_to_url>' ); ?>

         <div style="padding-top:0px; padding-bottom: 5px;"><?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>

      <div class="comment-meta commentmetadata" style="display:inline; font-size:11px;"> - <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s'), get_comment_date()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','') ?>
     
     <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('...awaiting moderation') ?></em>
         <br />
      <?php endif; ?>
      </div>

	</div>
      </div>
      
      <div class="clear"></div>
     
     </div>
     

	<div class="commentbody">
      <?php comment_text() ?>

      <div class="reply">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
	 <div style="clear:both;"></div>
     </div>
     </div>
<?php }


function cleanr_theme_pings($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
    
     <div id="comment-<?php comment_ID(); ?>">     

	<div class="commentbody">
<?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?> <span style="font-size: 12px; color: #AAAAAA;"> <?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?> <?php edit_comment_link(__('(Edit)'),'  ','') ?></span>
     </div>

     </div>
<?php } 


/*
 * Create Custom Post Types
 */
add_action( 'init', 'create_post_type_thoughts' );
add_action( 'init', 'create_post_type_journal' );
add_action( 'init', 'create_post_type_journalk09r4tyf58u35' );
add_action( 'init', 'create_post_type_notes' );
add_action( 'init', 'create_post_type_notesk09r4ty' );

function create_post_type_thoughts() {	
	register_post_type('thoughts', array(	'label' => 'Thoughts','description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'post','hierarchical' => false,'rewrite' => array('slug' => ''),'query_var' => true,'has_archive' => true,'supports' => array('title','editor','trackbacks','comments','revisions','custom-fields',),'taxonomies' => array('post_tag',),'labels' => array (
	  'name' => 'Thoughts',
	  'singular_name' => 'Thought',
	  'menu_name' => 'Thoughts',
	  'add_new' => 'Add Thought',
	  'add_new_item' => 'Add New Thought',
	  'edit' => 'Edit',
	  'edit_item' => 'Edit Thought',
	  'new_item' => 'New Thought',
	  'view' => 'View Thought',
	  'view_item' => 'View Thought',
	  'search_items' => 'Search Thoughts',
	  'not_found' => 'No Thoughts Found',
	  'not_found_in_trash' => 'No Thoughts Found in Trash',
	  'parent' => 'Parent Thought',
	),) );	
}

function create_post_type_journal() {
	register_post_type('journal', array(	'label' => 'Journal','description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'post','hierarchical' => false,'rewrite' => array('slug' => ''),'query_var' => true,'has_archive' => true,'supports' => array('title','editor','trackbacks','comments','revisions','custom-fields',),'taxonomies' => array('post_tag',),'labels' => array (
	  'name' => 'Journal',
	  'singular_name' => 'Journal Entry',
	  'menu_name' => 'Journal',
	  'add_new' => 'Add Journal',
	  'add_new_item' => 'Add New Journal',
	  'edit' => 'Edit',
	  'edit_item' => 'Edit Journal',
	  'new_item' => 'New Journal',
	  'view' => 'View Journal',
	  'view_item' => 'View Journal',
	  'search_items' => 'Search Journal',
	  'not_found' => 'No Journal Entries Found',
	  'not_found_in_trash' => 'No Journal Entries Found in Trash',
	  'parent' => 'Parent Journal Entry',
	),) );
}

/* Used to hack the post_type RSS feed and prevent public from viewing the Journal feed */
function create_post_type_journalk09r4tyf58u35() {
	register_post_type('journalk09r4tyf58u35', array(	'label' => 'Journal_k09r4tyf58u35','description' => '','public' => true,'show_ui' => true,'show_in_menu' => false,'capability_type' => 'post','hierarchical' => false,'rewrite' => array('slug' => ''),'query_var' => true,'has_archive' => true,'supports' => array('title','editor','trackbacks','comments','revisions','custom-fields',),'taxonomies' => array('post_tag',),'labels' => array (
	  'name' => 'Journalk09r4tyf58u35',
	  'singular_name' => 'Journal Entry_k09r4tyf58u35',
	  'menu_name' => 'Journal_k09r4tyf58u35',
	  'add_new' => 'Add Journal_k09r4tyf58u35',
	  'add_new_item' => 'Add New Journal_k09r4tyf58u35',
	  'edit' => 'Edit',
	  'edit_item' => 'Edit Journal_k09r4tyf58u35',
	  'new_item' => 'New Journal_k09r4tyf58u35',
	  'view' => 'View Journal_k09r4tyf58u35',
	  'view_item' => 'View Journal_k09r4tyf58u35',
	  'search_items' => 'Search Journal_k09r4tyf58u35',
	  'not_found' => 'No Journal Entries Found_k09r4tyf58u35',
	  'not_found_in_trash' => 'No Journal_k09r4tyf58u35 Entries Found in Trash',
	  'parent' => 'Parent Journal_k09r4tyf58u35 Entry',
	),) );
}

function create_post_type_notes() {
	register_post_type('notes', array(	'label' => 'Notes','description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'post','hierarchical' => false,'rewrite' => array('slug' => ''),'query_var' => true,'has_archive' => true,'supports' => array('title','editor','trackbacks','comments','revisions','custom-fields',),'taxonomies' => array('post_tag',),'labels' => array (
	  'name' => 'Notes',
	  'singular_name' => 'Notes',
	  'menu_name' => 'Notes',
	  'add_new' => 'Add Note',
	  'add_new_item' => 'Add Note',
	  'edit' => 'Edit',
	  'edit_item' => 'Edit Note',
	  'new_item' => 'New Note',
	  'view' => 'View Note',
	  'view_item' => 'View Note',
	  'search_items' => 'Search Notes',
	  'not_found' => 'No Notes Found',
	  'not_found_in_trash' => 'No Notes Found in Trash',
	  'parent' => 'Parent Note',
	),) );
}

/* Hack to hide Notes RSS feed */
function create_post_type_notesk09r4ty() {
	register_post_type('notesk09r4ty', array(	'label' => 'notesk09r4ty','description' => '','public' => true,'show_ui' => true,'show_in_menu' => false,'capability_type' => 'post','hierarchical' => false,'rewrite' => array('slug' => ''),'query_var' => true,'has_archive' => true,'supports' => array('title','editor','trackbacks','comments','revisions','custom-fields',),'taxonomies' => array('post_tag',),'labels' => array (
	  'name' => 'notesk09r4ty',
	  'singular_name' => 'notesk09r4ty',
	  'menu_name' => 'notesk09r4ty',
	  'add_new' => 'Add notesk09r4ty',
	  'add_new_item' => 'Add New notesk09r4ty',
	  'edit' => 'Edit',
	  'edit_item' => 'Edit notesk09r4ty',
	  'new_item' => 'New notesk09r4ty',
	  'view' => 'View notesk09r4ty',
	  'view_item' => 'View notesk09r4ty',
	  'search_items' => 'Search notesk09r4ty',
	  'not_found' => 'No notesk09r4ty Found',
	  'not_found_in_trash' => 'No notesk09r4ty Found in Trash',
	  'parent' => 'Parent notesk09r4ty',
	),) );
}

/*
 * Show Essays in the default RSS feed; hide Notes and Journal from RSS feeds
 */
function myfeed_request($qv) {
	if (isset($qv['feed']) && !isset($qv['post_type'])) {
		$qv['post_type'] = array('post');
	}
	elseif (isset($qv['feed']) && isset($qv['post_type']) && $qv['post_type'] == "journal") {
		$qv['post_type'] = array('post');
	}
	elseif (isset($qv['feed']) && isset($qv['post_type']) && $qv['post_type'] == "journalk09r4tyf58u35") {
			$qv['post_type'] = "journal";
	}
	elseif (isset($qv['feed']) && isset($qv['post_type']) && $qv['post_type'] == "notes") {
		$qv['post_type'] = array('post');
	}
	elseif (isset($qv['feed']) && isset($qv['post_type']) && $qv['post_type'] == "notesk09r4ty") {
			$qv['post_type'] = "notes";
	}
		
	return $qv;
}
add_filter('request', 'myfeed_request');


/*
 * Redirect the registation form to a specific page after submission
 */
function __my_registration_redirect()
{
    return home_url( '/please-confirm-subscription' );
}
add_filter( 'registration_redirect', '__my_registration_redirect' );


/*
 * Redirect XMLRPC clients (i.e., iPhone app) to post to Thoughts custom post type
 */
/*
function redirect_xmlrpc_to_custom_post_type ($data, $postarr) {
    $my_custom_post_type = 'thoughts';
    if (defined('XMLRPC_REQUEST') || defined('APP_REQUEST')) {
        $data['post_type'] = $my_custom_post_type;
        return $data;
    }
    return $data;
}
add_filter('wp_insert_post_data', 'redirect_xmlrpc_to_custom_post_type', 99, 2);
*/


function my_login_css() {
  echo '<style type="text/css">#login { padding: 15px 0 0; margin: auto; } .login h1 a { padding-bottom: 0px; }</style>';
}
add_action('login_head', 'my_login_css'); 

// SHORTCODE FOR STATIC FILE INCLUDE
function sc_static_html ($atts) {
	
	// Extract Shortcode Parameters/Attributes
    extract( shortcode_atts( array(
    'subdir' => NULL,
    'file' => NULL
    ), $atts ) );
    
    // Set file path
    $path_base = ABSPATH."wp-content/static-files/";    
    $path_file = ($subdir == NULL) ? $path_base.$file : $path_base.$subdir."/".$file;
    
    // Load file or, if absent. throw error
    if (file_exists($path_file)) {
        $file_content = file_get_contents($path_file);        
        return $file_content;
    }
    else {
        trigger_error("'$path_file' file not found", E_USER_WARNING);
        return "FILE NOT FOUND: ".$path_file."
SUBDIR = ".$subdir."
FILE = ".$file."

";
    }
}
add_shortcode('static_html', 'sc_static_html');


/*
 * Used to display age of post and comment(s) link
 * Optionally displays "more" link for custom post types
 */
function post_footer_metadata($post, $a_post_type = "", $a_post_type_label = "", $post_footer_metadata_more_label = FALSE) {
	?>
	<!-- START POST FOOTER METADATA -->
	<div class="post-footer-meta">
	<span class="postmetadata">
	<?php /*echo time_since(abs(strtotime($post->post_date_gmt . " GMT")), time()); ?> ago |*/?> <?php commentCount('comments'); ?> <?php if($post_footer_metadata_more_label) { ?>| <a href="<?php echo get_post_type_archive_link( $a_post_type ); ?>" title="View more <?php echo $a_post_type_label; ?>">Read more <?php echo $a_post_type_label; ?>...</a><?php }?></span>
	</div>	
	<!-- END POST FOOTER METADATA -->	
	<?php
}

function commentCount($type = 'comments'){

	if($type == 'comments'):

		$typeSql = 'comment_type = ""';
		$oneText = '1 comment'; 
		$moreText = '% comments';
		$noneText = 'Comment';

	elseif($type == 'pings'):

		$typeSql = 'comment_type != ""';
		$oneText = 'One pingback/trackback';
		$moreText = '% pingbacks/trackbacks';
		$noneText = 'No pinbacks/trackbacks';

	elseif($type == 'trackbacks'):

		$typeSql = 'comment_type = "trackback"';
		$oneText = 'One trackback';
		$moreText = '% trackbacks';
		$noneText = 'No trackbacks';

	elseif($type == 'pingbacks'):

		$typeSql = 'comment_type = "pingback"';
		$oneText = 'One pingback';
		$moreText = '% pingbacks';
		$noneText = 'No pingbacks';

	endif;

	global $wpdb;

    $result = $wpdb->get_var('
        SELECT
            COUNT(comment_ID)
        FROM
            '.$wpdb->comments.'
        WHERE
            '.$typeSql.' AND
            comment_approved="1" AND
            comment_post_ID= '.get_the_ID()
    );

	if($result == 0):

		echo '<a href="' . get_permalink() . '#respond">' . str_replace('%', $result, $noneText) . '</a>';

	elseif($result == 1): 

		echo '<a href="' . get_permalink() . '#comments">' . str_replace('%', $result, $oneText) . '</a>';

	elseif($result > 1): 

		echo '<a href="' . get_permalink() . '#comments">' . str_replace('%', $result, $moreText) . '</a>';

	endif;

}

function journal_notice($type = "journal entry") {
	?>
						<div id="journal-notice">
										<p>This <?php echo $type; ?> has not been released into the public domain and is currently only available through a subscription to the <a href="http://raamdev.com/about/journal/">Journal</a> or a <a href="/about/journal/#one_time_donation">one-time donation</a>.</p>
	<?php if(is_user_logged_in()) { ?>
	<p>Since you're already logged in, you can <a href="/account/modification/">upgrade now</a> to receive access to this entry.</p>
	<?php } else { ?>
	<p>If you have an active subscription to the Journal, please <a href="https://raamdev.com/wordpress/wp-login.php">login</a> to access this entry (you may need to <a href="https://raamdev.com/wordpress/wp-login.php?action=lostpassword">reset your password</a> first).</p>
	<?php } ?>

						</div>
<?php }

function sharing_buttons() {
?>

<!-- START POST SHARING -->

<div style="width: 400px; margin: 0 auto;">
			<div style="float: right; margin-left: 0.75em;"><a href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=RT%20@RaamDev%20<?php the_title(); ?>" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script></div>
<div style="float: right; margin-right: 120px;"><g:plusone size="medium" annotation="none"></g:plusone></div>
<div align="left" style="padding: 0px; width: 95px; overflow: hidden;"><iframe src="//www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;send=false&amp;layout=button_count&amp;width=200&amp;show_faces=false&amp;action=recommend&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=129928197106327" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:130px; height:21px;" allowTransparency="true"></iframe></div>
</div>

<!-- END POST SHARING -->

<?php }

function release_notice($type = "Journal entries") {
	?>
	<div id="release-notice"><p><em><?php echo $type; ?> will be released into the <a href="http://raamdev.com/income-ethics-framework-ethical-income#public_domain">public domain</a> after one year. If you have an active subscription, please <a href="https://raamdev.com/wordpress/wp-login.php">login</a> to access to the <a href="/about/journal/#archives">archives</a>.</em></p></div>
	<?php
}

function journal_subscribe_boxes() {
	?>
	<div class="" style="text-align: center; margin-top:50px; width: 500px; margin: 0 auto; margin-top: 50px; font-size: 14px; <?php if(is_user_logged_in()) { ?> display: none; <?php } ?>">
		<div style="float:left; width:220px;">
			<a href="/about/journal/"><img id="img" width="220px" src="http://raamdev.com/wordpress/wp-content/uploads/2012/01/about-journal-thumb.jpg"></img></a> <br/><br/>
			<a href="/about/journal/">Read about the Journal</a>
		</div>
		<div style="float:right; width:220px;">
			<a href="/journal-subscribe/"><img id="img" width="220px" src="http://raamdev.com/wordpress/wp-content/uploads/2012/01/journal-checkout-thumb.jpg"></img></a> <br/><br/>
			<a href="/journal-subscribe/">Subscribe to the Journal</a>
		</div>
		<div style="clear:both;"></div>
	</div>
	<?php
}

function notes_description_box() {
	?>
	<div id="notes-description" style="<?php if(is_user_logged_in()) { ?> display: none; <?php } ?>">

				<p>This is my collection of notes on interesting things that cause me to pause and reflect. Access requires a subscription to <a href="http://raamdev.com/journal/">the Journal</a>.</p>
				
							<div style="clear: both;"></div>
	</div>	
	<?php
}

// Filter wp_nav_menu() to add additional links and other output
function new_nav_menu_items($items) {
    $homelink = '<li class="home-nav"><a href="' . home_url( '/' ) . '">' . get_bloginfo('name') . '</a></li><li class="home-nav-separator"><span>»</span></li>';
    $items = $homelink . $items;
		$subscribe_link = '<li class="subscribe"><a href="/#subscribe">subscribe</a> <sup><a href="http://feeds.feedburner.com/RaamDevsWeblog">rss</a></sup></li>';
		$items = $items . $subscribe_link;
    return $items;
}
add_filter( 'wp_nav_menu_items', 'new_nav_menu_items' );