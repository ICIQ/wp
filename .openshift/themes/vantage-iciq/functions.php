<?php

/* WordPress action needed to use the styles of the parent theme*/
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}

//---------------------------------------------------------------------------------------------------//

/* Shorcode function to obfuscate any content*/
function cquic_secret_func( $atts, $content = "" ) {
	$content = strrev("$content");
	//$content = str_replace("(",")", $content);
	//$content = str_replace(")","(", $content);
	$content = strtr ($content, array ('(' => ')', ')' => '('));

	$content = htmlspecialchars($content);
	return "<span style='unicode-bidi:bidi-override; direction: rtl;'> $content</span>";
}

add_shortcode( 'CQuIC secret', 'cquic_secret_func' );

//---------------------------------------------------------------------------------------------------//

/* Shortcode function that returns research interests for a UM profile */
function um_research_interest_field(){

$user = um_profile_id();
$html             = '';


$html .= "<div class='um-field-label'><div class='um-field-label-icon'><i class='um-icon-aperture'></i></div><label>Research Interest Topics</label><div class='um-clear'></div></div><div class='tagchecklist'>";


$taxonomy = get_taxonomy( 'research_interests' );


$terms = wp_get_object_terms( $user, 'research_interests' );

if ( ! empty( $terms ) ) {

	$user_tags        = '';

	foreach($terms as $term) {

		$user_tags[] = $term->name;
		$term_url    = site_url() . '/' . $taxonomy->rewrite['slug'] . '/' . $term->slug;
		$html .= "<div class='tag-hldr'>";
		$html .= '&nbsp;<a href="' . 	$term_url . '" class="term-link">' . $term->name . '</a>';
		$html .= "</div>";

	}
	$user_tags = implode( ',', $user_tags );

} else {

	$html .= "Research interests not given yet.";

}


$html .= "</div>";

return $html;


} 

add_shortcode( 'CQuIc research tags', 'um_research_interest_field' );


//---------------------------------------------------------------------------------------------------//


/* Function for short code to take a user id and return interests */
function userid_research_interest_field($atts){

extract(shortcode_atts(array(
      'user' => 1,
   ), $atts));



// $user = um_profile_id();
$html             = '';


$taxonomy = get_taxonomy( 'research_interests' );


$terms = wp_get_object_terms( $user, 'research_interests' );

if ( ! empty( $terms ) ) {

	$user_tags        = '';

	foreach($terms as $term) {

		$user_tags[] = $term->name;
		$term_url    = site_url() . '/' . $taxonomy->rewrite['slug'] . '/' . $term->slug;
		$html .= "<div class='tag-hldr'>";
		$html .= '&nbsp;<a href="' . 	$term_url . '" class="term-link">' . $term->name . '</a>';
		$html .= "</div>";

	}
	$user_tags = implode( ',', $user_tags );

} else {

	$html .= "Research interests not given yet.";

}


$html .= "</div>";

return $html;
}

add_shortcode('user-research-tags', 'userid_research_interest_field');

//---------------------------------------------------------------------------------------------------//


/**
 * Shortcode for Tags UI in frontend
 */
function cquiq_um_wp_ut_tag_box() {
	$user_id    = um_profile_id();
	// $user_id    = get_current_user_id();
	$taxonomies = get_object_taxonomies( 'user', 'object' );
	wp_nonce_field( 'user-tags', 'user-tags' );
	wp_enqueue_script( 'user_taxonomy_js' );
	if ( empty ( $taxonomies ) ) {
		?>
		<p><?php echo __( 'No taxonomies found', WP_UT_TRANSLATION_DOMAIN ); ?></p><?php
		return;
	}
	if ( ! is_user_logged_in() ) {
		return;
	}
	?>

	<div class='um-profile-body'>
	
	<form name="user-tags" action="" method="post">
	<ul class="form-table user-profile-taxonomy user-taxonomy-wrapper"><?php
		foreach ( $taxonomies as $key => $taxonomy ):
			// Check the current user can assign terms for this taxonomy
			if ( ! current_user_can( $taxonomy->cap->assign_terms ) ) {
				continue;
			}
			$choose_from_text = apply_filters( 'ut_tag_cloud_heading', $taxonomy->labels->choose_from_most_used, $taxonomy );
			// Get all the terms in this taxonomy
			$terms     = wp_get_object_terms( $user_id, $taxonomy->name );
			$num       = 0;
			$html      = '';
			$user_tags = '';
			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					$user_tags[] = $term->name;
					$term_url    = site_url() . '/' . $taxonomy->rewrite['slug'] . '/' . $term->slug;
					$html .= "<div class='tag-hldr'>";
					$html .= '<span><a id="user_tag-' . $taxonomy->name . '-' . $num . '" class="ntdelbutton">x</a></span>&nbsp;<a href="' . $term_url . '" class="term-link">' . $term->name . '</a>';
					$html .= "</div>";
					$num ++;
				}
				$user_tags = implode( ',', $user_tags );
			} ?>
			<li>
			<label for="new-tag-user_tag_<?php echo $taxonomy->name; ?>"><?php _e( "{$taxonomy->labels->singular_name}" ) ?></label>

			<div class="taxonomy-wrapper">
				<input type="text" id="new-tag-user_tag_<?php echo $taxonomy->name; ?>" name="newtag[user_tag]" class="newtag form-input-tip float-left hide-on-blur" size="16" autocomplete="off" value="">
				<input type="button" class="button tagadd float-left" value="Add">

				<p class="howto"><?php _e( 'Separate tags with commas', WP_UT_TRANSLATION_DOMAIN ); ?></p>

				<div class="tagchecklist"><?php echo $html; ?></div>
				<input type="hidden" name="user-tags[<?php echo $taxonomy->name; ?>]" id="user-tags-<?php echo $taxonomy->name; ?>" value="<?php echo $user_tags; ?>"/>
			</div>
			<!--Display Tag cloud for most used terms-->
			<p class="hide-if-no-js tagcloud-container">
				<a href="#titlediv" class="tagcloud-link user-taxonomy" id="link-<?php echo $taxonomy->name; ?>"><?php echo $choose_from_text; ?></a>
			</p>
			</li><?php
		endforeach; ?>
	</ul>
	<?php wp_nonce_field( 'save-user-tags', 'user-tags-nonce' ); ?>
	<input type="submit" name="update-user-tags" class="button tagadd float-left" value="Update User Tags">
	</form>

	</div> <!-- um div -->

	<?php
}






//---------------------------------------------------------------------------------------------------//




add_shortcode('CQuIC tag edit', 'cquiq_um_wp_ut_tag_box');


/* Allows a short code to override the primary menu with another menu */
function print_menu_shortcode($atts, $content = null) {
    extract(shortcode_atts(array( 'name' => null, ), $atts));
    return wp_nav_menu( array( 'menu' => $name, 'echo' => false ) );
}

add_shortcode('menu', 'print_menu_shortcode');


function Get_All_Wordpress_Menus(){
    return get_terms( 'nav_menu', array( 'hide_empty' => true ) ); 
}

add_shortcode('allmenus', 'Get_All_Wordpress_Menus');


// ------------------------------------------------
// The following allows text to be hidden in a post.

function mm_hide_text_func($atts, $content=null) {
   extract(shortcode_atts(array('noatts' => 'Unknown' ), $atts));
   $output = '';
   if (!is_null($content) && current_user_can('administrator')) {
      $output = $content;
   }

   return $output;
}
add_shortcode('mm-hide-text', 'mm_hide_text_func');


// ----------------------------------------------------
// The following adds short code to display bibtex info
function iciq_bib_func( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'bib' => 'ICIQmembers.bib;AuthorLinks.bib',
        'authors' => 'all'
        ), $atts );
        
        

$_GET['bib']=$a['bib'];
if ($a['authors'] == 'all') {
	$_GET['all']=1;
} else {
	$_GET['author']=$a['authors'];
}
$_GET['academic']=1;

/* include_once get_stylesheet_directory().'/bib/bibtexbrowser.php'; */
include( 'bibtexbrowser.php' );

}

add_shortcode('iciq_bib', 'iciq_bib_func');

// ----------------------------------------------------
// The following adds short code to display bibtex info for given groups.
function iciq_bibgroup_func( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'bib' => 'ICIQmembers.bib;AuthorLinks.bib',
        'grouptag' => ''
        ), $atts );
        
        

$_GET['bib']=$a['bib'];
if (empty($a['grouptag'])) {
	$_GET['all']=1;
} else {
	$_GET['search']=$a['grouptag'];
}
$_GET['academic']=1;

/* include_once get_stylesheet_directory().'/bib/bibtexbrowser.php'; */
include( 'bibtexbrowser.php' );

}

add_shortcode('iciq_bibgroup', 'iciq_bibgroup_func');


// The following adds short code to display a bibtex library for publications.
function iciq_biblib_func( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'bib' => 'ICIQmembers.bib;AuthorLinks.bib',
        'authors' => 'all'
        ), $atts );
        // @define('BIBTEXBROWSER_DEFAULT_FRAME','all');
        // @define('BIBTEXBROWSER_EMBEDDED_WRAPPER', 'CustomWrapper');
	//$_GET['menu']=1;        
	$_GET['academic']=1;
	$_GET['bib']=$a['bib'];
	//$_GET['frameset']=1;
	//$_GET['library']=1;
	if ($a['authors'] == 'all') {
		$_GET['all']=1;
	} else {
		$_GET['author']=$a['authors'];
	}
	// Add a search bar.
	echo '
	<form action="/bibtexbrowser.php?" method="get" target="main">
	<div class="sortbox toolbox">
            <div class="search">
            	<input type="text" name="search" class="input_box" id="searchtext"/>
            	<input type="hidden" name="bib" value="ICIQmembers.bib"/>
            	<input type="submit" value="search" class="input_box"/>
            </div>
        </div>
	</form>';
	
	include( 'bibtexbrowser.php' );

	// setDB();

    	// new CustomAuthorsMenu();
    	// new IndependentYearMenu();
    	// new CustomWrapper();
    	
	
	// $content->display();
    	// new Dispatcher();
    	

}

add_shortcode('iciq_biblib', 'iciq_biblib_func');


// The following adds short code to display a frameset of bibtex library for publications.
function iciq_frameset_func( ) {
	include( 'pubframeset.html' );
}
add_shortcode('iciq_frameset', 'iciq_frameset_func');


// The following will change the iCal and Google Calendar buttons in The Event Plugin, and add the Outlook button to export Events.
// Changes the text labels for Google Calendar and iCal buttons on a single event page
remove_action('tribe_events_single_event_after_the_content', array('Tribe__Events__iCal', 'single_event_links'));
add_action('tribe_events_single_event_after_the_content', 'customized_tribe_single_event_links');
 
function customized_tribe_single_event_links()    {
    if (is_single() && post_password_required()) {
        return;
    }
 
    echo '<div class="tribe-events-cal-links">';
    echo '<a class="tribe-events-gcal tribe-events-button" href="' . tribe_get_gcal_link() . '" title="' . __( 'Add to Google Calendar', 'tribe-events-calendar-pro' ) . '">+ Google Calendar </a>';
    echo '<a class="tribe-events-gcal tribe-events-button" href="' . cquic_get_ical_outlook_link() . '">+ iCal to Outlook </a>';
    echo '<a class="tribe-events-ical tribe-events-button" href="' . tribe_get_single_ical_link() . '">+ iCal to Mac/Apple </a>';
    echo '</div><!-- .tribe-events-cal-links -->';
}

function cquic_get_ical_outlook_link() {
                class CQuIC__Events__iCal extends Tribe__Events__iCal{
                    function cquic_ical_outlook_modify( $content ) {
	                $properties  = explode( PHP_EOL, $content );
	                $searchValue = "X-WR-CALNAME";
	                $fl_array    = preg_grep( '/^' . "$searchValue" . '.*/', $properties );
	                $keynum      = key( $fl_array );
	                unset( $properties[ $keynum ] );
	                $content     = implode( "\n", $properties );
	                return $content;
                    }
                }
                add_filter( 'CQuIC__Events__iCal::tribe_ical_properties', 'cquic_ical_outlook_modify', 10, 2 );
		$output = CQuIC__Events__iCal::get_ical_link();
	
		return apply_filters( 'cquic_get_ical_outlook_link', $output );
	}




// The following code adds short code to display the logo.
if(!function_exists('cquic_display_logo')):
/**
 * Display the logo 
 */
function cquic_display_logo(){
	$logo = siteorigin_setting( 'logo_image' );
	$logo = apply_filters('vantage_logo_image_id', $logo);

	if( empty($logo) ) {
		if ( function_exists( 'jetpack_the_site_logo' ) && jetpack_has_site_logo() ) {
			// We'll let Jetpack handle things
			jetpack_the_site_logo();
			return;
		}

		// Just display the site title
		$logo_html = '<h1 class="site-title">'.get_bloginfo( 'name' ).'</h1>';
		$logo_html = apply_filters('vantage_logo_text', $logo_html);
	}
	else {
		// load the logo image
		if(is_array($logo)) {
			list ($src, $height, $width) = $logo;
		}
		else {
			$image = wp_get_attachment_image_src($logo, 'full');
			$src = $image[0];
			$width = '1080px';
		}

		// Add all the logo attributes
		$logo_attributes = apply_filters('vantage_logo_image_attributes', array(
			'src' => $src,
			'class' => siteorigin_setting('logo_in_menu_constrain') ? 'logo-height-constrain' : 'logo-no-height-constrain',
			'width' => round($width),
			'alt' => sprintf( __('%s Logo', 'vantage'), get_bloginfo('name') ),
		) );

		if($logo_attributes['width'] > vantage_get_site_width()) {
			// Don't let the width be more than the site width.
			$logo_attributes['width'] = $width;
		}

		$logo_attributes_str = array();
		if( !empty( $logo_attributes ) ) {
			foreach($logo_attributes as $name => $val) {
				if( empty($val) ) continue;
				$logo_attributes_str[] = $name.'="'.esc_attr($val).'" ';
			}
		}

		$logo_html = apply_filters('vantage_logo_image', '<img '.implode( ' ', $logo_attributes_str ).' />');
	}

	// Echo the image
	echo apply_filters('vantage_logo_html', $logo_html);
}
endif;


?>
