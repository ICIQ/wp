<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package vantage
 * @since vantage 1.0
 * @license GPL 2.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=10" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
        
 <link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_bloginfo('stylesheet_directory');?>/font-awesome.css" />
 <link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_bloginfo('stylesheet_directory');?>/researchstyle.css" />
<link href='https://fonts.googleapis.com/css?family=Exo+2' rel='stylesheet' type='text/css'>
 <!-- <link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_stylesheet_directory().'/bib/bibtexbrowser.css';?>" /> -->

</head>

<body <?php body_class(); ?>>

<?php do_action('vantage_before_page_wrapper') ?>



  <?php
		# current page slug
$slug = $post->post_name;

# get parent page's slug
$post_data = get_post($post->post_parent);
$slug = $post_data->post_name;
$parentid = $post_data ->ID;
$base_url = get_bloginfo( 'url' );

$parent_title = get_the_title($post->post_parent);


$group_url = $base_url . "/" . $slug;

$research_group_name = get_post_meta($parentid, 'research_group_name', true);
$research_group_name_font_size = get_post_meta($parentid, 'research_group_name_font_size', true);
$research_group_subject = get_post_meta($parentid, 'research_group_subject', true);
$research_group_subject_font_size = get_post_meta($parentid, 'research_group_subject_font_size', true);
$research_group_background = get_post_meta($parentid, 'research_group_background', true);
$research_group_has_class = get_post_meta($parentid, 'research_group_has_class', true);

if($research_group_has_class == 'true') {
	$research_group_has_class = true;
} else {
	$research_group_has_class = false;
}


if(empty($research_group_background)) { 
	$research_group_background = 'banner_research.jpg';
}

if (is_numeric($research_group_subject_font_size)){
	$research_group_subject_font_size = 'style="font-size:'.$research_group_subject_font_size.'pt;"';
} else {
	$research_group_subject_font_size = '';
}

if (is_numeric($research_group_name_font_size)){
	$research_group_name_font_size = 'style="font-size:'.$research_group_name_font_size.'pt;"';
} else {
	$research_group_name_font_size = '';
}

?>



<div id="page-wrapper">
    
 <?php  $custom_fields = get_post_custom();  ?>
<!-- HEADER -->

<header id="header-wrapper">
    <div class="research_group_subject">
	<?php echo $research_group_subject; ?>
	</div>
	<div class="research_header">
		<div class="research_group_name">
			<?php echo $research_group_name; ?>
		</div>
	</div>

<div class="research_header_container"> 
<nav role="navigation" class="site-navigation main-navigation primary use-sticky-menu">
	<div class="full-container">
		<div id="search-icon">
			<div id="search-icon-icon">
				<div class="vantage-icon-search"></div>
			</div>
			<form method="get" class="searchform" action="http://cquic.unm.edu/" role="search" style="width: 100%;"><input type="text" class="field" name="s" value="" placeholder="Search">
			</form>
		</div>
		
		<div class="menu-container">
			<ul id="menu-menu-1" class="menu">
				<li id="menu item" class="menu-item"> <a href="http://cquic.unm.edu/">CQuIC</a></li>     
				<li id="menu-item" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item"><a href="<?php echo $group_url; ?>">Group</a></li>
				<li id="menu-item" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item"><a href="<?php echo $group_url; ?>/research-group-members/?research_group_tag=<?php echo $slug; ?>&#038;um_search=1">People</a></li>
				<li id="menu-item" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item"><a href="<?php echo $group_url; ?>/research">Research</a></li>
				<li id="menu-item" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item"><a href="<?php echo $group_url; ?>/publications">Publications</a></li>

		<?php
if($research_group_has_class) {
?>
				<li class="menu-item"><a href="<?php echo $base_url; ?>/courses/#<?php echo $slug; ?>" target="_blank">Classes</a></li>
<?php } ?>
			</ul>
		</div>			
	</div>
</nav>
</div>    
</header>
<!-- END HEADER -->


<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_bloginfo('stylesheet_directory');?>/style.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/bibtexbrowser.css" />
	<div id="main" class="site-main"> 
		<div class="full-container"> 
			<?php do_action( 'vantage_main_top' ); ?>

                    
                    