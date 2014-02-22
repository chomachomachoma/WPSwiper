<?php
/*
   Plugin Name: WP Swiper
   Plugin URI: http://wordpress.org/extend/plugins/wpswiper/
   Version: 0.1
   Author: <a href="http://chrischoma.com">Chris Choma</a>
   Description: Display your Wordpress posts in a horizontal slider that is desktop and mobile touch friendly.
   Text Domain: wpswiper
   License: GPLv3
  */

// don't load directly
if (!function_exists('is_admin')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

define( 'WPSWIPER_DEBUG', false);		# never use debug mode on productive systems

// Register styles and scripts.
add_action( 'wp_enqueue_scripts', 'register_wpswiper_styles' );

function register_wpswiper_styles() {
	wp_enqueue_script( 'jquery' );
	
	wp_register_style( 'wp-swiper', plugins_url( 'wp-swiper/css/wp-swiper.css' ) );
	wp_enqueue_style( 'wp-swiper' );
	
	wp_register_script( 'wp-swiper', plugins_url( 'wp-swiper/js/wp-swiper.min.js' ) );
	wp_enqueue_script( 'wp-swiper' );
}

// Register scripts for footer.
add_action( 'wp_footer', 'wpswiper_scripts' );

function wpswiper_scripts() {
?>
	<script>
		var mySwiper = new Swiper('.swiper-container',{
			slidesPerView: <?php echo get_option('article_cnt'); ?>,
			scrollbar: {
				container: '.swiper-scrollbar',
				draggable : true,
				snapOnRelease: true,
				hide: <?php if ( get_option('show_scroll') == '2' ) { echo 'true'; } else { echo 'false'; } ?>,
				
			},
			freeMode: false,
			//freeModeFluid: true
		})
		jQuery('.arrow-left').on('click', function(e){
			e.preventDefault()
			mySwiper.swipePrev()
		})
		jQuery('.arrow-right').on('click', function(e){
			e.preventDefault()
			mySwiper.swipeNext()
		})
	</script>
<?php
}

// create custom plugin settings menu
add_action('admin_menu', 'wpswiper_create_menu');

function wpswiper_create_menu() {

	//create new top-level menu
	add_options_page( 'WP Swiper', 'WP Swiper', 'manage_options', 'wpswiper_settings_page', 'wpswiper_settings_page');

	//call register settings function
	add_action( 'admin_init', 'register_wpswiper_settings' );
}



function register_wpswiper_settings() {
	
	//register settings and defaults
	add_option( 'article_cnt', 4 );
	register_setting( 'wpswiper-settings-group', 'article_cnt', 'intval' );
	
	add_option( 'slider_ht', '500' );
	register_setting( 'wpswiper-settings-group', 'slider_ht', 'intval' );
	
	add_option( 'show_images', '1' );
	register_setting( 'wpswiper-settings-group', 'show_images', 'intval' );
	
	add_option( 'show_titles', '1' );
	register_setting( 'wpswiper-settings-group', 'show_titles', 'intval' );
	
	add_option( 'show_excerpts', '1' );
	register_setting( 'wpswiper-settings-group', 'show_excerpts', 'intval' );
	
	add_option( 'excerpt_chars', '150' );
	register_setting( 'wpswiper-settings-group', 'excerpt_chars', 'intval' );
	
	add_option( 'more_text', 'more' );
	register_setting( 'wpswiper-settings-group', 'more_text', 'sanitize_text_field' );
	
	add_option( 'more_class', 'more-link' );
	register_setting( 'wpswiper-settings-group', 'more_class', 'sanitize_html_class' );
	
	add_option( 'show_arrows', '1' );
	register_setting( 'wpswiper-settings-group', 'show_arrows', 'intval' );
	
	add_option( 'show_scroll', '1' );
	register_setting( 'wpswiper-settings-group', 'show_scroll', 'intval' );
}

function wpswiper_settings_page() {
?>
<div class="wrap">
<h2>WP Swiper Options</h2>

<form method="post" action="options.php">
	<?php settings_fields( 'wpswiper-settings-group' ); ?>
	<?php do_settings_sections( 'wpswiper-settings-group' ); ?>
	<table class="form-table">
		
		<tr valign="top">
			<th scope="row">Number of articles to show at a time</th>
			<td><input type="text" name="article_cnt" value="<?php echo get_option('article_cnt'); ?>" /></td>
		</tr>
		
		<tr valign="top">
			<th scope="row">Slider Height</th>
			<td><input type="text" name="slider_ht" value="<?php echo get_option('slider_ht'); ?>" /> px</td>
		</tr>
		
		<tr valign="top">
			<th scope="row">Show Featured Images</th>
			<td>
				<label for="show_images_0">
					<input type="radio" id="show_images_0" value="0" name="show_images" <?php if ( get_option('show_images') == '0' ) echo 'checked'; ?>> 
					Hide
				</label>
				<label for="show_images_1">
					<input type="radio" id="show_images_1" value="1" name="show_images" <?php if ( get_option('show_images') == '1' ) echo 'checked'; ?>> 
					Show
				</label>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row">Show Titles</th>
			<td>
				<label for="show_titles_0">
					<input type="radio" id="show_titles_0" value="0" name="show_titles" <?php if ( get_option('show_titles') == '0' ) echo 'checked'; ?>> 
					Hide
				</label>
				<label for="show_titles_1">
					<input type="radio" id="show_titles_1" value="1" name="show_titles" <?php if ( get_option('show_titles') == '1' ) echo 'checked'; ?>> 
					Show
				</label>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row">Show Excerpts</th>
			<td>
				<label for="show_excerpts_0">
					<input type="radio" id="show_excerpts_0" value="0" name="show_excerpts" <?php if ( get_option('show_excerpts') == '0' ) echo 'checked'; ?>> 
					Hide
				</label>
				<label for="show_excerpts_1">
					<input type="radio" id="show_excerpts_1" value="1" name="show_excerpts" <?php if ( get_option('show_excerpts') == '1' ) echo 'checked'; ?>> 
					Show
				</label>
			</td>
		</tr>
		 
		<tr valign="top">
			<th scope="row">Characters shown in excerpt<em><br />*Note: last word will NOT be truncated</em></th>
			<td><input type="text" name="excerpt_chars" value="<?php echo get_option('excerpt_chars'); ?>" /></td>
		</tr>
		
		<tr valign="top">
			<th scope="row">Text to display in "more" link of truncated excerpts</th>
			<td><input type="text" name="more_text" value="<?php echo get_option('more_text'); ?>" /></td>
		</tr>
		
		<tr valign="top">
			<th scope="row">CSS class(es) to add to "more" link of truncated excerpts<br /><em>Separate multiple classes with spaces</em></th>
			<td><input type="text" name="more_class" value="<?php echo get_option('more_class'); ?>" /></td>
		</tr>
		
		<tr valign="top">
			<th scope="row">Show Arrow Nav Links</th>
			<td>
				<label for="show_arrows_0">
					<input type="radio" id="show_arrows_0" value="0" name="show_arrows" <?php if ( get_option('show_arrows') == '0' ) echo 'checked'; ?>> 
					Hide
				</label>
				<label for="show_arrows_1">
					<input type="radio" id="show_arrows_1" value="1" name="show_arrows" <?php if ( get_option('show_arrows') == '1' ) echo 'checked'; ?>> 
					Show
				</label>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row">Show Scrollbar</th>
			<td>
				<label for="show_scroll_0">
					<input type="radio" id="show_scroll_0" value="0" name="show_scroll" <?php if ( get_option('show_scroll') == '0' ) echo 'checked'; ?>> 
					Never
				</label>
				<label for="show_scroll_1">
					<input type="radio" id="show_scroll_1" value="1" name="show_scroll" <?php if ( get_option('show_scroll') == '1' ) echo 'checked'; ?>> 
					Always
				</label>
				<label for="show_scroll_2">
					<input type="radio" id="show_scroll_2" value="2" name="show_scroll" <?php if ( get_option('show_scroll') == '2' ) echo 'checked'; ?>> 
					On Swipe
				</label>
			</td>
		</tr>
		
	</table>
	
	<?php submit_button(); ?>

</form>
</div>
<?php 
}

function get_excerpt($count){
	$permalink = get_permalink($post->ID);
	$excerpt = get_the_content();
	$excerpt = strip_tags($excerpt);
	$excerpt = substr($excerpt, 0, $count);
	$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
	$excerpt = $excerpt.'... <a class="'.get_option('more_class').'" href="'.$permalink.'">'.get_option('more_text').'</a>';
	echo $excerpt;
}

function get_img_url(){
	$img_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
	echo $img_url[0];
}

function wpswiper() {
?>

	<section class="swiper-container" style="height: <?php echo get_option('slider_ht'); ?>px;">
		<?php if ( get_option('show_arrows') != '0' ) { ?>
		<a class="arrow-left" href="#"></a> 
		<a class="arrow-right" href="#"></a>
		<?php } ?>
		<?php if ( get_option('show_scroll') != '0' ) { ?>
		<div class="swiper-scrollbar-container">
			<div class="swiper-scrollbar" style="<?php if ( get_option('show_arrows') == '0' ) { echo 'width:100%;left: 0;'; } else { echo 'width:88%;left: 6%;'; } ?>;"></div>
		</div>
		<?php } ?>
		<div class="swiper-wrapper h-feed">
			
			<?php 
			$args = array(
				'post_type' => 'post'
			);
			$query = new WP_Query( $args );
			while ($query->have_posts()) : $query->the_post(); ?>
				
			<article class="swiper-slide h-entry">
				<div class="swiper-content" style="height: <?php echo get_option('slider_ht'); ?>px;">
					<?php if ( !get_post_thumbnail_id($post->ID) == '' ) { ?>
					<div class="feature-img">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
							<img class="u-photo" src="<?php get_img_url(); ?>">
						</a>
					</div>
					<?php } ?>
					<div class="inner-content">
						<div class="title">
							<a class="u-url" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
								<h2 class="p-name"><?php the_title(); ?></h2>
							</a>
						</div>
						<div class="excerpt p-summary">
							<?php get_excerpt(get_option('excerpt_chars')); ?>
						</div>
					</div>
				</div>
			</article>
			
			<?php endwhile; ?>
			
		</div>
	</section>

<?php
}
