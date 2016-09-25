<?php
/**
 * This template displays full width pages.
 *
 * @package vantage-cquic
 * @since vantage 1.0
 * @license GPL 2.0
 * 
 * Template Name: New Research Group Page Template
 */

get_header('becerra_research_group'); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
		
		<?php 
		/*
		global $post;
		$parent_title = get_the_title($post->post_parent);
		echo "Printing the group name: " . $parent_title;
		
		
		$slug = $post->post_name;

		# get parent page's slug
		$post_data = get_post($post->post_parent);
		$slug = $post_data->post_name;
		$base_url = get_bloginfo( 'url' );
		
		$parent_title = get_the_title($post->post_parent);
		
		echo " - slug - " . $slug;
		echo " - base url - " . $base_url;
		echo " - parent title - " . $parent_title;
		*/		

		?>
		
			<?php while ( have_posts() ) : the_post(); ?>                
                                                                                                                   
                    
                                                                                     <?php get_template_part( 'content', 'page' ); ?>

				<?php if ( comments_open() || '0' != get_comments_number() ) : ?>
					<?php comments_template( '', true ); ?>
				<?php endif; ?>

			<?php endwhile; // end of the loop. ?>
			


		</div><!-- #content .site-content -->
	</div><!-- #primary .content-area -->

<?php get_footer(); ?>