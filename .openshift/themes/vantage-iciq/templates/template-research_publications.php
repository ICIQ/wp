<?php
/**
 * This template displays full width pages.
 *
 * @package vantage-iciq
 * @since vantage 1.0
 * @license GPL 2.0
 * 
 * Template Name: Research Publications Page Template
 */

get_header('research_group'); ?>

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
		
			
                     <?php
                               $_GET['bib']= '/ICIQmembers.bib' ;
                               $_GET['all']=1;
                               /* if ($a['authors'] == 'all') {$_GET['all']=1;} 
                                     else {$_GET['author']=$authorlastnames;} */
                               $_GET['academic']=1;
                               include( '/bibtexbrowser.php' ); 
                     ?> 


		</div><!-- #content .site-content -->
	</div><!-- #primary .content-area -->

<?php get_footer(); ?>
