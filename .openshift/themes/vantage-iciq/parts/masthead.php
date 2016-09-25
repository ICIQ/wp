<?php
/**
 * Part Name: Default Masthead
 */
?>
<header id="masthead" class="site-header" role="banner">

	<div class="hgroup full-container" id="banner">
		<?php
		
		$logo = siteorigin_setting( 'logo_image' );
		$logo = apply_filters('vantage_logo_image_id', $logo);
		
		$image = wp_get_attachment_image_src($logo, 'full');
		$src = $image[0];
		$logo_html = apply_filters('vantage_logo_text', $logo_html);

		?>

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="logo"><img src=<?php echo $src; ?>  class="logo-height-constrain"  width="1080" alt="CQuIC Logo" id="cquic-logo"  /></a>
		

		<?php if( is_active_sidebar('sidebar-header') ) : ?>

			<div id="header-sidebar" <?php if( siteorigin_setting('logo_no_widget_overlay') ) echo 'class="no-logo-overlay"' ?>>
				<?php
				// Display the header area sidebar, and tell mobile navigation that we can use menus in here
				add_filter('siteorigin_mobilenav_is_valid', '__return_true');
				dynamic_sidebar( 'sidebar-header' );
				remove_filter('siteorigin_mobilenav_is_valid', '__return_true');
				?>
			</div>

		<?php else : ?>


		<?php endif; ?>

	</div><!-- .hgroup.full-container -->

	<?php get_template_part( 'parts/menu', apply_filters( 'vantage_menu_type', siteorigin_setting( 'layout_menu' ) ) ); ?>

</header><!-- #masthead .site-header -->