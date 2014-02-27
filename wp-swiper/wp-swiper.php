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

define( 'WPSWIPER_DEBUG', false); # never use debug mode on productive systems

// Enable thumbnail (featured image) support
add_theme_support( 'post-thumbnails' );

// Register styles and scripts.
add_action( 'wp_enqueue_scripts', 'register_wpswiper_scripts' );

function register_wpswiper_scripts() {
	wp_enqueue_script( 'jquery' );
	
	wp_register_style( 'wp-swiper', plugins_url( 'wp-swiper/css/wp-swiper.css' ) );
	wp_enqueue_style( 'wp-swiper' );
	
	wp_register_script( 'wp-swiper', plugins_url( 'wp-swiper/js/wp-swiper.min.js' ) );
	wp_enqueue_script( 'wp-swiper' );
?>
	<style>
		.swiper-container,
		.swiper-content {
			height: <?php echo get_option('slider_ht'); ?>px;
		}
		.swiper-slide .swiper-content {
			border:1px solid <?php echo get_option('slide_border_color'); ?>;
			background:<?php echo get_option('slide_background_color'); ?>;
		}
		.swiper-container .arrow-left,
		.swiper-container .arrow-right {
			color:<?php echo get_option('btns_arrows_color'); ?>;
			background: <?php echo get_option('btns_scrollbar_color'); ?>;
		}
		.swiper-scrollbar-drag {
			background:<?php echo get_option('btns_scrollbar_color'); ?>;
		}
		.swiper-scrollbar {
			background:<?php echo get_option('scrollbar_background_color'); ?> !important;
			<?php if ( get_option('show_arrows') == '0' ) { echo 'width:100%;left: 0;'; } else { echo 'width:88%;left: 6%;'; } ?>;
		}
	</style>
<?php
}


// Register scripts for footer.
add_action( 'wp_footer', 'wpswiper_footer_scripts' );

function wpswiper_footer_scripts() {
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

// Register scripts for admin
add_action( 'admin_enqueue_scripts', 'wpswiper_admin_scripts' );

function wpswiper_admin_scripts( $hook_suffix ) {
// first check that $hook_suffix is appropriate for your admin page
wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_script(
	'iris',
	admin_url( 'js/iris.min.js' ),
	array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
	false,
	1
);
wp_register_script( 'wp-swiper-admin', plugins_url( 'wp-swiper/js/wp-swiper-admin.js' ) );
wp_enqueue_script( 'wp-swiper-admin' );
}

// Create settings menu
add_action('admin_menu', 'wpswiper_create_menu');

function wpswiper_create_menu() {

	//create new top-level menu
	add_options_page( 'WP Swiper', 'WP Swiper', 'manage_options', 'wpswiper_settings_page', 'wpswiper_settings_page');

	//call register settings function
	add_action( 'admin_init', 'register_wpswiper_settings' );
}

// Register settings, add default values
function register_wpswiper_settings() {
	
	//register settings and defaults	
	add_option( 'slider_ht', '500' );
	register_setting( 'wpswiper-settings-group', 'slider_ht', 'intval' );
	
	add_option( 'show_images', '1' );
	register_setting( 'wpswiper-settings-group', 'show_images', 'intval' );
	
	add_option( 'show_titles', '1' );
	register_setting( 'wpswiper-settings-group', 'show_titles', 'intval' );
	
	add_option( 'show_meta', '1' );
	register_setting( 'wpswiper-settings-group', 'show_meta', 'intval' );
	
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
	
	add_option( 'slide_background_color', '#F7F7F7' );
	register_setting( 'wpswiper-settings-group', 'slide_background_color', 'sanitize_text_field' );
	
	add_option( 'slide_border_color', '#EEEEEE' );
	register_setting( 'wpswiper-settings-group', 'slide_border_color', 'sanitize_text_field' );
	
	add_option( 'btns_scrollbar_color', '#666666' );
	register_setting( 'wpswiper-settings-group', 'btns_scrollbar_color', 'sanitize_text_field' );
	
	add_option( 'btns_arrows_color', '#FFFFFF' );
	register_setting( 'wpswiper-settings-group', 'btns_arrows_color', 'sanitize_text_field' );
	
	add_option( 'scrollbar_background_color', '#CCCCCC' );
	register_setting( 'wpswiper-settings-group', 'scrollbar_background_color', 'sanitize_text_field' );
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
				<th scope="row">Slider Height</th>
				<td><input type="text" name="slider_ht" value="<?php echo get_option('slider_ht'); ?>" /> px</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Featured Images Visibility</th>
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
				<th scope="row">Titles Visibility</th>
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
				<th scope="row">Metadata Visibility</th>
				<td>
					<label for="show_meta_0">
						<input type="radio" id="show_meta_0" value="0" name="show_meta" <?php if ( get_option('show_meta') == '0' ) echo 'checked'; ?>> 
						Hide
					</label>
					<label for="show_meta_1">
						<input type="radio" id="show_meta_1" value="1" name="show_meta" <?php if ( get_option('show_meta') == '1' ) echo 'checked'; ?>> 
						Show
					</label>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Excerpts Visibility</th>
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
				<th scope="row">Characters shown in excerpt<small><em><br />*Last word will NOT be truncated</em></small></th>
				<td><input type="text" name="excerpt_chars" value="<?php echo get_option('excerpt_chars'); ?>" /></td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Text to display in "more" link of truncated excerpts</th>
				<td><input type="text" name="more_text" value="<?php echo get_option('more_text'); ?>" /></td>
			</tr>
			
			<tr valign="top">
				<th scope="row">CSS class(es) to add to "more" link of truncated excerpts<br /><small><em>*Separate multiple classes with spaces</em></small></th>
				<td><input type="text" name="more_class" value="<?php echo get_option('more_class'); ?>" /></td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Arrow Nav Links Visibility</th>
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
				<th scope="row">Scrollbar Visibility</th>
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
			
			<tr valign="top">
				<th scope="row">Slide background color</th>
				<td><input type="text" name="slide_background_color" id="slide_background_color" class="wp-color-picker" value="<?php echo get_option('slide_background_color'); ?>" /></td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Slide border color</th>
				<td><input type="text" name="slide_border_color" id="slide_border_color" class="wp-color-picker" value="<?php echo get_option('slide_border_color'); ?>" /></td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Nav buttons and scrollbar handle color</th>
				<td><input type="text" name="btns_scrollbar_color" id="btns_scrollbar_color" class="wp-color-picker" value="<?php echo get_option('btns_scrollbar_color'); ?>" /></td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Nav buttons arrows color</th>
				<td><input type="text" name="btns_arrows_color" id="btns_arrows_color" class="wp-color-picker" value="<?php echo get_option('btns_arrows_color'); ?>" /></td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Scrollbar background color</th>
				<td><input type="text" name="scrollbar_background_color" id="scrollbar_background_color" class="wp-color-picker" value="<?php echo get_option('scrollbar_background_color'); ?>" /></td>
			</tr>
			
		</table>
		
		<?php submit_button(); ?>
	
	</form>
</div>
<?php 
}

// Custom excerpt
function get_excerpt($count){
	$permalink = get_permalink($post->ID);
	$excerpt = get_the_content();
	$excerpt = strip_tags($excerpt);
	$excerpt = substr($excerpt, 0, $count);
	$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
	$excerpt = '<div class="p-summary">'.$excerpt.'</div> ... <a class="'.get_option('more_class').'" href="'.$permalink.'">'.get_option('more_text').'</a>';
	echo $excerpt;
}

// Get featured image
function get_img_url(){
	$img_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
	echo $img_url[0];
}

// Enble shortcode, set parameters, loop new query
function wpswiper( $atts ){
	extract( shortcode_atts( array(
		'post_type' => 'post',
		'category' => '',
		'show_posts' => $post_count,
	), $atts ) );
	
	$param_post_type = $post_type;
	$param_category = $category;
	$param_posts_per_page = $show_posts;
	
	$args = array(
		'post_type' => $post_type,
		'taxonomy' => $param_category,
		'posts_per_page' => $param_posts_per_page,
	);
	$query = new WP_Query( $args );
?>

	<section class="swiper-container">
		
		<?php if ( get_option('show_arrows') != '0' ) { ?>
		<a class="arrow-left" href="#"></a> 
		<a class="arrow-right" href="#"></a>
		<?php } ?>
		
		<?php if ( get_option('show_scroll') != '0' ) { ?>
		<div class="swiper-scrollbar-container">
			<div class="swiper-scrollbar"></div>
		</div>
		<?php } ?>
		
		
			
			<?php if ( $query->have_posts() ) { ?>
			
			<div class="swiper-wrapper h-feed">
				
			<?php while ($query->have_posts()) : $query->the_post(); ?>
					
				<article id="post-<?php echo get_the_ID(); ?>" class="swiper-slide h-entry">
					
					<div class="swiper-content">
						
						<?php
							if ( get_option('show_images') != '0' ) {
								if ( !get_post_thumbnail_id($post->ID) == '' ) {
						?>
						
						<div class="feature-img">
							<a class="u-url" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
								<img class="u-photo" src="<?php get_img_url(); ?>">
							</a>
						</div>
						
						<?php
								}
							} 
						?>
						
						<div class="inner-content">
							
							<?php if ( get_option('show_titles') != '0' ) { ?>
							<div class="title">
								<a class="u-url" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
									<h2 class="p-name"><?php the_title(); ?></h2>
								</a>
							</div>
							<?php } ?>
							
							<?php if ( get_option('show_meta') != '0' ) { ?>
							<div class="metadata">
								<time class="dt-published" datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date('M j, Y'); ?></time>
								&nbsp;&#124;&nbsp;
								<span class="p-author h-card"><?php the_author(); ?></span>
							</div>
							<?php } ?>
							
							<?php if ( get_option('show_excerpts') != '0' ) { ?>
							<div class="excerpt">
								<?php get_excerpt(get_option('excerpt_chars')); ?>
							</div>
							<?php } ?>
							
						</div> <!-- .inner-content -->
						
					</div> <!-- .swiper-content -->
					
				</article>
				
			<?php endwhile; ?>
			
			</div> <!-- .swiper-wrapper -->
			
			<?php
			} else {
				echo '<span class="wpslider-noposts">No posts found.</span>';
			}	
			?>
			
	</section>

<?php
}
add_shortcode( 'wpswiper', 'wpswiper' );
