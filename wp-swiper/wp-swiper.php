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
			slidesPerView: 4,
			scrollbar: {
				container: '.swiper-scrollbar',
				draggable : true,
				hide: false,
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
	//register our settings
	register_setting( 'wpswiper-settings-group', 'new_option_name' );
	register_setting( 'wpswiper-settings-group', 'some_other_option' );
	register_setting( 'wpswiper-settings-group', 'option_etc' );
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
		<th scope="row">Number of articles to show in viewing pane.</th>
		<td><input type="text" name="new_option_name" value="<?php echo get_option('new_option_name'); ?>" /></td>
		</tr>
		 
		<tr valign="top">
		<th scope="row">Number of characters shown in excerpt. (Default 150) <em>*Note: last word will NOT be truncated.</em></th>
		<td><input type="text" name="some_other_option" value="<?php echo get_option('some_other_option'); ?>" /></td>
		</tr>
		
		<tr valign="top">
		<th scope="row">Text to display in "more" link of truncated excerpts.</th>
		<td><input type="text" name="option_etc" value="<?php echo get_option('option_etc'); ?>" /></td>
		</tr>
		
		<tr valign="top">
		<th scope="row">Slider Height</th>
		<td><input type="text" name="option_etc" value="<?php echo get_option('option_etc'); ?>" /> px</td>
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
	$excerpt = $excerpt.'... <a href="'.$permalink.'">more</a>';
	echo $excerpt;
}

function get_img_url(){
	$img_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
	echo $img_url[0];
}

function wpswiper() {
?>

	<div class="swiper-container" style="height: 500px;"><?php //@TODO MAKE HEIGHT DYNAMIC ?>
		<a class="arrow-left" href="#"></a> 
		<a class="arrow-right" href="#"></a>
		<div class="swiper-scrollbar-container">
			<div class="swiper-scrollbar"></div>
		</div>
		<div class="swiper-wrapper">
			
			<?php 
			$args = array(
				'post_type' => 'post'
			);
			$query = new WP_Query( $args );
			while ($query->have_posts()) : $query->the_post(); ?>
				
			<div class="swiper-slide">
				<div class="swiper-content" style="height: 500px;"><?php //@TODO MAKE HEIGHT DYNAMIC ?>
					<?php if ( !get_post_thumbnail_id($post->ID) == '' ) { ?>
					<div class="feature-img">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<img class="" src="<?php get_img_url(); ?>">
						</a>
					</div>
					<?php } ?>
					<div class="inner-content">
						<div class="title">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
								<h2 class="entry-title"><?php the_title(); ?></h2>
							</a>
						</div>
						<div class="excerpt">
							<?php get_excerpt(150); ?>
						</div>
					</div>
				</div>
			</div>
			
			<?php endwhile; ?>
			
		</div>
	</div>

<?php
}
