<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fullstack
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); 

// Display featured event in template
function display_featured_event() {
    $featured_event_query = new WP_Query( array(
        'post_type' => 'event',
        'meta_key' => 'featured_event',
        'meta_value' => 1,
		'posts_per_page' => 4 // Show only 4 featured events
    ) );
    if ( $featured_event_query->have_posts() ) {
        while ( $featured_event_query->have_posts() ) {
            $featured_event_query->the_post();
            // Display featured event using template tags
            the_title();
            the_excerpt();
            the_post_thumbnail();
        }
        wp_reset_postdata();
    }
}


// echo event_search_form(); 
echo do_shortcode( '[eventsearch limit="2"]' ); 


