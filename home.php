<?php
/**
 * The home page template file.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 */

get_header(); ?>
</div> <!-- end #main div since we don't use it on the home page -->

<!-- START FLICKR PHP CODE; see https://gist.github.com/3844131 -->
<?php

$flickr_api_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; // Your own API key (get one here: http://www.flickr.com/services/apps/create/apply/)
$flickr_user_id = '89743353@N00'; // Your numeric user ID.
$flickr_album_id = '72157626450096422'; // ID of the photoset

// Get a list of all photos in specified photoset and load the result into an array
$flickr_list_of_photos = "http://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=$flickr_api_key&photoset_id=$flickr_album_id";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $flickr_list_of_photos);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$flickr_list_of_photos_xml = curl_exec($ch);
curl_close($ch);
$flickr_photos = simplexml_load_string($flickr_list_of_photos_xml);

// Get the last item in the array, since it's the newest photo in the set
$flickr_item = rand(0, count($flickr_photos->photoset->photo) - 1);

// Get the data for that photo so that we can build the image URL
$flickr_photo_id = $flickr_photos->photoset->photo[$flickr_item][id]; 
$flickr_secret = $flickr_photos->photoset->photo[$flickr_item][secret];
$flickr_server = $flickr_photos->photoset->photo[$flickr_item][server];
$flickr_farm = $flickr_photos->photoset->photo[$flickr_item][farm];
$flickr_title = $flickr_photos->photoset->photo[$flickr_item][title];

// Build URL to the photo (appending _b to the filename gets the large size)
// See here for more on building URLs: http://www.flickr.com/services/api/misc.urls.html
$flickr_img_url = "http://farm$flickr_farm.static.flickr.com/$flickr_server/" . $flickr_photo_id . "_" . $flickr_secret . ".jpg";
$flickr_img_url_large = "http://farm$flickr_farm.static.flickr.com/$flickr_server/" . $flickr_photo_id . "_" . $flickr_secret . "_b.jpg";

// Get information on the photo, including description
$flickr_photo_info_xml_url = "http://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key=$flickr_api_key&photo_id=$flickr_photo_id";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $flickr_photo_info_xml_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$flickr_photo_info_xml = curl_exec($ch);
curl_close($ch);
$flickr_photo_info = simplexml_load_string($flickr_photo_info_xml);

// Extract the description
$flickr_photo_description = $flickr_photo_info->photo->description;
if ('' == $flickr_photo_description) { $flickr_photo_description = $flickr_photo_info->photo->title; }

?>
<!-- END FLICKR PHP CODE -->

<!-- START OPENING SECTION -->
<div style="margin: 0 auto; width: 35em;">

	<div style="margin-top: 15px; text-align: center; font-size: 13px; font-family: sans-serif; font-weight: 300;">I'm a <a style="border-bottom: 1px dotted #333;" href="#recently-published">writer</a> and I was last seen <?php echo do_shortcode("[ncl-current-location display='date']"); ?> ago in <span class="mapThis" place="<?php echo do_shortcode("[ncl-current-location wikify='false']"); ?>" zoom="2"><?php echo do_shortcode("[ncl-current-location wikify='false']"); ?></span>.</div>

<div style="padding-top: 30px; text-align: center;">	 <!-- MODIFIED -->
	
<!-- Start Author -->

<div class="author-home">

<!-- <a style="border-bottom: 0px;" href="/about"><img border="0" style="padding-bottom: 10px;" src="http://raamdev.com/wordpress/wp-content/uploads/2011/04/2011-03-11-raam-space-shuttle-172x172.png"></a> -->
<!-- <a style="border-bottom: 0px;" href="/about"><img border="0" style="padding-bottom: 10px;" src="http://farm7.static.flickr.com/6001/5940800076_e7fb99c1d0_m.jpg"></a> -->
<!-- <a style="border-bottom: 0px;" href="/about"><img border="0" src="http://raamdev.com/wordpress/wp-content/uploads/2011/07/raam-channel-islands-harbor.png"></a> -->
<!-- <a style="border-bottom: 0px;" href="/about/"><img border="0" src="http://raamdev.com/wordpress/wp-content/uploads/2011/12/2011-12-Raam-Looking-Ocean.jpg"></a> --> 
<!-- <a style="border-bottom: 0px;" href="/about/"><img title="Greeting the sunrise from Australia for the first time" border="0" src="http://raamdev.com/wordpress/wp-content/uploads/2012/05/2012-05-12-Raam-Austraila.jpg"></a> -->
<!-- <a style="border-bottom: 0px;" href="/about/"><img title="Contemplating Life underneath a tree in Australia" border="0" src="http://raamdev.com/wordpress/wp-content/uploads/2012/05/2012-05-20-under-tree-contemplating.jpg"></a> -->
<!-- LOAD RANDOM FLICKR PHOTO; see http://pastie.org/4277961 --> 
<a rel="lightbox[featuredPhoto]" class="cboxModal" style="border-bottom: 0px;" href="<?php echo $flickr_img_url_large; ?>" title="<?php echo $flickr_photo_description; ?>"><img style="max-height: 400px;" title="<?php echo $flickr_photo_description; ?>" border="0" src="<?php echo $flickr_img_url; ?>"></a>
<!-- END LOAD RANDOM FLICKR PHOTO -->

</div>

<!-- End Author -->

</div>
<!-- END OPENING SECTION -->

<!-- Start Thoughts -->

	<?php query_posts($query_string . '&post_type=thoughts&posts_per_page=1'); ?>

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

<div class="thought-entry" style="width: 500px; text-align: center; margin: 0 auto; font-size: 14px; margin-top: 2em;">
		<a style="text-decoration:none; color: #222 !important;" href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
		<?php the_content(); ?>
		</a>

		<?php post_footer_metadata($post, 'thoughts', 'thoughts', true); ?>
</div>

<?php endwhile; ?>
<?php endif; ?>


<!-- End Thoughts -->
</div>  <!-- MODIFIED -->

</div> <!-- END #content -->  <!-- MODIFIED -->

<div style="clear:both;"></div> 

<div class="recently-published" id="recently-published">
	<div class="recently-published-header"></div>
	<div class="recently-published-box-left">
		<h3 class="recently-published-essays"></h3>

<h3 style="font-size:95%; margin-left: 45px;">Personal Reflections</h3>
		<ul>
			<?php query_posts($query_string . '&post_type=post&posts_per_page=5&cat=20'); ?>

			<?php if (have_posts()) : ?>

				<?php while (have_posts()) : the_post(); ?>
					
		<li class="recent-item"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">&#187; &nbsp; <?php echo mb_strimwidth(get_the_title(), 0, 46, '...'); ?></a></li>
		
				<?php endwhile; ?>
				
			<?php endif; ?>
			
		</ul>
		<div class="recently-published-more"><a href="/category/personal-reflections/">More Personal Reflections →</a></div>

<h3 style="font-size:95%; margin-left: 45px;">Writing and Publishing</h3>
		<ul>
			<?php query_posts($query_string . '&post_type=post&posts_per_page=5&cat=859'); ?>

			<?php if (have_posts()) : ?>

				<?php while (have_posts()) : the_post(); ?>
					
		<li class="recent-item"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">&#187; &nbsp; <?php echo mb_strimwidth(get_the_title(), 0, 46, '...'); ?></a></li>
		
				<?php endwhile; ?>
				
			<?php endif; ?>
			
		</ul>
		<div class="recently-published-more"><a href="/category/writing/">More essays on Writing and Publishing →</a></div>

<h3 style="font-size:95%; margin-left: 45px;">Technology and Futurism</h3>
		<ul>
			<?php query_posts($query_string . '&post_type=post&posts_per_page=5&cat=5'); ?>

			<?php if (have_posts()) : ?>

				<?php while (have_posts()) : the_post(); ?>
					
		<li class="recent-item"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">&#187; &nbsp; <?php echo mb_strimwidth(get_the_title(), 0, 43, '...'); ?></a></li>
		
				<?php endwhile; ?>
				
			<?php endif; ?>
			
		</ul>
		<div class="recently-published-more"><a href="/category/technology/">More essays on Technology and Futurism →</a></div>

	</div>
	<div class="recently-published-box-right">
		<h3 class="recently-published-thoughts"></h3>
		<ul>
				<?php query_posts($query_string . '&post_type=thoughts&posts_per_page=10'); ?>

				<?php if (have_posts()) : ?>

					<?php while (have_posts()) : the_post(); ?>

			<li class="recent-item"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">&#187; &nbsp; <?php echo mb_strimwidth(get_the_title(), 0, 46, '...'); ?></a></li>

					<?php endwhile; ?>

				<?php endif; ?>
		</ul>
		<div class="recently-published-more"><a href="/thoughts/">More Thoughts →</a></div>
	</div>

	<div class="recently-published-box-right">

	<h3 class="recently-published-journals"></h3>
		<ul>
				<?php query_posts($query_string . '&post_type=journal&posts_per_page=9'); ?>

				<?php if (have_posts()) : ?>

					<?php while (have_posts()) : the_post(); ?>

			<li class="recent-item"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">&#187; &nbsp; <?php echo mb_strimwidth(get_the_title(), 0, 46, '...'); ?></a></li>

					<?php endwhile; ?>

				<?php endif; ?>
		</ul>
		<div class="recently-published-more"><a href="/journal/">More Journals →</a></div>
	</div>
	<div style="clear: both;"> </div>
	
</div>

<div style="clear:both;"></div>

<div class="subscribe-home" id="subscribe">
	<div class="subscribe-home-header"></div>
	<div class="subscribe-home-email">
		
		<div class="subscribe-essays" style="
		    bottom: -5px;
		    position: absolute;
		    margin-left: 170px;
			color: black;
		">
<!-- Starting MailChimp Code -->
<form method="post" action="http://raamdev.us1.list-manage.com/subscribe/post?u=5daf0f6609de2506882857a28&amp;id=dc1b1538af" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
		<div class="subscribe-essays-box" style="border: none; background: white; border-radius: 0; padding: 0px; ">
		<div class="subscribe-home-email-title">Subscribe to Thoughts and Essays &darr;</div>
		
			<div class="subscribe-essays-name">First Name: <input type="text" name="FNAME" class="text" value="" tabindex="500" onclick="" onfocus="" onblur=""></div>

			<div class="subscribe-essays-email">Email: <input type="text" class="text" name="EMAIL" value="" tabindex="501" onclick="" onfocus=""></div>
			
			<div style="display:none;"> <input type="hidden" name="MERGE3" value="<?php echo 'http://' . $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>" id="MERGE3"> </div>
			<div style="display:none;"> <input type="hidden" name="group[1873]" value="32" id="group[1873]"> </div>
<div style="clear:both"></div>
<div class="mc-field-group">
	<label for="mce-group[1129]">How often would you like to receive new updates? </label>
	<select name="group[1129]" class="REQ_CSS" id="mce-group[1129]" tabindex="502">
	<option value="1" selected="selected">Immediately</option>
<option value="2">Weekly</option>
<option value="4">Monthly</option>
	</select>
<div class="subscribe-home-essay-topics">
Essay topics:
<input type="checkbox" id="group_64" name="group[1989][64]" value="1" checked="yes">&nbsp;<label for="group_64" style="font-style: italic;">Personal Reflections</label>
<input type="checkbox" id="group_128" name="group[1989][128]" value="1" checked="yes">&nbsp;<label for="group_128" style="font-style: italic;">Technology</label>
<input type="checkbox" id="group_256" name="group[1989][256]" value="1" checked="yes">&nbsp;<label for="group_256" style="font-style: italic;">Writing</label><br>
</div>
	<div class="subscribe-essays-submit"><input type="submit" name="subscribe" value="Subscribe" tabindex="503"></div>
</div>

			</div>
			</form>
		</div>
<!-- Ending MailChimp Code -->
		
	</div>
		<div class="subscribe-home-kindle" style="display:none;">
			
			<div class="subscribe-home-kindle-text"><a href="http://www.amazon.com/gp/product/B0051OUR8K/ref=as_li_ss_tl?ie=UTF8&tag=radeswe-20&linkCode=as2&camp=217145&creative=399349&creativeASIN=B0051OUR8K">Subscribe on the Kindle &rarr;</a></div>
		</div>

		<div class="subscribe-home-journal" onclick="window.location='http://raamdev.com/about/journal/';">
			
			<div class="subscribe-home-journal-text"><a href="/about/journal/">Subscribe to my Personal Journal &rarr;</a>
<div style="line-height: 23px; font-size: 14px; margin-top: 20px; font-family: sans-serif; font-weight: 300;">
Discover what inspires my Thoughts and Essays. <br />
Subscribe for $40/year; <a href="http://raamdev.com/about/journal/" style="color: #326281">learn more →</a>
</div></div>
		</div>
<div id="rss" style="
    margin-top: 50px;
    font-size: 14px;
    text-align: center;
"><strong>RSS Feeds:</strong> <a href="http://feeds.feedburner.com/RaamDevsWeblog">Personal Reflections RSS</a> | <a href="http://feeds.feedburner.com/RaamDevWriting">Writing &amp; Publishing RSS</a> | <a href="http://feeds.feedburner.com/RaamDevTechnology">Technology RSS</a></div>
</div>

<div style="clear:both;"></div> 

<div class="say-hello" id="say-hello">
	<div class="say-hello-header"></div>
	
		<div class="say-hello-twitter"><a href="https://twitter.com/raamdev"><img src="http://raamdev.com/wordpress/wp-content/uploads/2011/12/twitter_bird.png" border="0" class="say-hello-twitter-img"></img></a> 
		<div class="say-hello-twitter-follow">
			<a href="https://twitter.com/raamdev" class="twitter-follow-button" data-show-count="false">Follow @RaamDev</a>
			<script src="//platform.twitter.com/widgets.js" type="text/javascript"></script>
			</div>
		</div>
		<div class="say-hello-googleplus"><a href="https://plus.google.com/103678870073436346171"><img src="http://raamdev.com/wordpress/wp-content/uploads/2011/12/googleplus.png" border="0" class="say-hello-googleplus-img"></img></a> 
		</div>		
		<div class="say-hello-facebook"><a href="http://facebook.com/raamdev"><img src="http://raamdev.com/wordpress/wp-content/uploads/2011/12/facebook.png" border="0" class="say-hello-facebook-img"></img></a> 
		</div>
		<div class="say-hello-email"><a href="http://raamdev.com/contact/"><img src="http://raamdev.com/wordpress/wp-content/uploads/2011/12/email.gif" border="0" class="say-hello-email-img"></img></a> 
		</div>

</div>

<div style="clear: both;"> </div>

<div style="margin: 0pt auto; border-top: 1px solid rgb(221, 221, 221); text-transform: uppercase; font-size: 12px; text-align: center; padding: 16px; padding-bottom:0px; width: 850px;">
<div style="width: 815px; margin: 0 auto; text-transform: none !important; margin-bottom: 40px;">
<?php echo do_shortcode("[youtubefeeder]"); ?>
</div>

<div style="clear:both;"></div>

<div style="float: left;">
<h3>Where in the world is Raam?</h3>
<div style="margin-top: 25px;">
<a href="/travels/"><img border="0" src="http://raamdev.com/wordpress/wp-content/uploads/2012/07/travel-map-footer.gif"></a>
</div>
</div>

<div style="float: left; padding-left: 25px;">
<h3>Join the Facebook Community</h3>
<div style="margin-left: 20px;">
<script src="http://static.ak.connect.facebook.com/connect.php/en_US" type="text/javascript"></script>
<script type="text/javascript">FB.init("28129a356cb22db370d905684249d352");</script>
<fb:fan profile_id="407874323168" stream="0" connections="12" logobar="0" width="240" height="270" css="<?php bloginfo('template_url'); ?>/css/facebook.css?2"></fb:fan>
</div>
</div>

<div style="float: left; padding-left: 20px; width: 250px;">
<div class="box2">
                    <div class="spacer flickr">
                        <h3 style="margin-bottom:0px;"><a href="http://www.flickr.com/photos/89743353@N00/" title=""><span style="color:#363636">Latest photos from </span><span style="color:#0063DC">Flick</span><span style="color:#FF0084">r</span><span style="color:#363636"></span></a></h3>
<div style="margin-top: 25px; margin-left: 15px;">
                        <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=9&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=89743353@N00"></script>
</div>
                            </div><!--/spacer -->
                    <div class="fix"></div>
                    <div class="bot"></div>
                </div>

</div>

<div style="clear: both;"></div>

</div>

<!-- start #main div since we didn't use it on the home page; required for footer to work properly -->
<div>
<?php get_footer(); ?>
