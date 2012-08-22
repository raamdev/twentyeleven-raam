<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<link href='http://fonts.googleapis.com/css?family=Habibi:400italic,400,700' rel='stylesheet' type='text/css'>

<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
<!-- Google Plus One Button -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<!-- Google Plus One Button -->
</head>

<body <?php body_class(); ?>>
<div id="header-nav" class="<?php if(is_home()) { echo 'header-nav-home'; } ?>">
<span style="padding-right: 10px;"><a href="/">Raam Dev</a></span>
<span style="padding-right: 10px;font-size: 12px;font-family: Georgia, serif" ;="">»</span>
<span style="padding-right: 20px;"><a href="/thoughts/">thoughts</a></span>
<span style="padding-right: 20px;"><a href="/archives/">essays</a></span>
<span style="padding-right: 20px;"><a href="/journal/">journal</a></span>
<span style="padding-right: 20px;"><a href="/notes/">notes</a></span>
<span style="padding-right: 20px;"><a href="/contact/">contact</a></span>
<span style="padding-right: 20px;"><a href="/about/">about</a></span>
<span style="padding-right: 0px;"><a href="/#subscribe">subscribe</a> <sup><a href="http://feeds.feedburner.com/RaamDevsWeblog">rss</a></sup></span>
</div>
<?php
// Show development tag in header when running in local development environment
$development_env = array('localhost', '127.0.0.1', 'raamdev.dev');
if(in_array($_SERVER['HTTP_HOST'], $development_env)){ ?> 
	<!-- <div style="width: 100px; margin: 0 auto; text-align: center;"><small>development</small> </div> -->
<?php } ?>

<div id="<?php if(is_home()) { echo 'page-home'; } else { echo 'page'; }?>" class="hfeed">



	<div id="main">