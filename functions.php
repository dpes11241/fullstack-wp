<?php
/**
 * fullstack functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package fullstack
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function fullstack_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on fullstack, use a find and replace
		* to change 'fullstack' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'fullstack', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'fullstack' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'fullstack_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'fullstack_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function fullstack_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'fullstack_content_width', 640 );
}
add_action( 'after_setup_theme', 'fullstack_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function fullstack_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'fullstack' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'fullstack' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'fullstack_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function fullstack_scripts() {
	wp_enqueue_style( 'fullstack-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'fullstack-style', 'rtl', 'replace' );

	wp_enqueue_script( 'fullstack-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'fullstack_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}



// Register custom post type for events
function register_event_post_type() {
    $labels = array(
        'name' => 'Events',
        'singular_name' => 'Event',
        'add_new' => 'Add New Event',
        'add_new_item' => 'Add New Event',
        'edit_item' => 'Edit Event',
        'new_item' => 'New Event',
        'view_item' => 'View Event',
        'search_items' => 'Search Events',
        'not_found' => 'No events found',
        'not_found_in_trash' => 'No events found in Trash',
        'parent_item_colon' => 'Parent Event:',
        'menu_name' => 'Events'
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'Custom post type for events',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-calendar-alt',
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );
    register_post_type('event', $args);
}
add_action('init', 'register_event_post_type');


// Register custom field for featured event
function register_featured_event_meta_box() {
    add_meta_box( 'featured_event_meta_box', 'Featured Event', 'display_featured_event_meta_box', 'event', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'register_featured_event_meta_box' );

// Display custom field for featured event
function display_featured_event_meta_box( $event ) {
    $featured_event = get_post_meta( $event->ID, 'featured_event', true );
    ?>
    <input type="checkbox" name="featured_event" value="1" <?php checked( $featured_event, 1 ); ?> />
    <label for="featured_event">Feature this event</label>
    <?php
}

// Save value of custom field for featured event
function save_featured_event_meta( $post_id ) {
    if ( isset( $_POST['featured_event'] ) ) {
        update_post_meta( $post_id, 'featured_event', 1 );
    } else {
        update_post_meta( $post_id, 'featured_event', 0 );
    }
}
add_action( 'save_post', 'save_featured_event_meta' );



// Shortcode to display event search form and results
function event_search_shortcode( $atts ) {
    // Parse shortcode attributes
    $atts = shortcode_atts( array(
        'limit' => 10, // Default value for limit attribute
    ), $atts );
 
    // Create search form
    $form = '<form role="search" method="get" id="event-search-form" action="' . home_url( '/' ) . '" >
        <label class="screen-reader-text" for="s">' . __( 'Search for:' ) . '</label>
        <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'Search events...' ) . '" />
        <input type="hidden" name="post_type" value="event" />
        <input type="submit" id="searchsubmit" value="' . esc_attr__( 'Search' ) . '" />
    </form>';
 
    // Add container for search results
    $form .= '<div id="event-search-results"></div>';
 
    // Enqueue scripts and styles
    wp_enqueue_style( 'event-search-style', get_template_directory_uri() . '/event-search.css' );
    wp_enqueue_script( 'event-search-script', get_template_directory_uri() . '/event-search.js', array( 'jquery' ), '1.0', true );
    wp_localize_script( 'event-search-script', 'event_search_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'limit' => intval( $atts['limit'] ),
    ) );
 
    return $form;
}
add_shortcode( 'eventsearch', 'event_search_shortcode' );



// Handle event search with AJAX
function event_search_ajax() {
    // Check if request is valid
    if ( ! isset( $_POST['s'] ) || ! isset( $_POST['paged'] ) ) {
        wp_die();
    }
 
    // Query events
    $events_query = new WP_Query( array(
        'post_type' => 'event',
        's' => sanitize_text_field( $_POST['s'] ),
        'posts_per_page' => intval( $_POST['limit'] ),
        'paged' => intval( $_POST['paged'] ),
    ) );
 
    // Check if there are events
    if ( $events_query->have_posts() ) {
        while ( $events_query->have_posts() ) : $events_query->the_post ;
        $events_query->the_post(); ?>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php the_excerpt(); ?>
    <?php endwhile;
 
    // Check if there are more pages
    if ( $events_query->max_num_pages > 1 ) : ?>
        <div id="event-search-pagination">
            <?php
            // Previous page link
            if ( $events_query->query['paged'] > 1 ) : ?>
                <a href="#" class="prev" data-paged="<?php echo $events_query->query['paged'] - 1; ?>">Previous</a>
            <?php endif;
 
            // Next page link
            if ( $events_query->max_num_pages > $events_query->query['paged'] ) : ?>
                <a href="#" class="next" data-paged="<?php echo $events_query->query['paged'] + 1; ?>">Next</a>
            <?php endif; ?>
        </div>
    <?php endif;
    wp_reset_postdata();
    }
 
    wp_die();
}
add_action( 'wp_ajax_event_search_ajax', 'event_search_ajax' );
add_action( 'wp_ajax_nopriv_event_search_ajax', 'event_search_ajax' );



// Enqueue scripts and styles
function event_search_enqueue_scripts() {
    wp_enqueue_style( 'event-search-style', get_template_directory_uri() . '/event-search.css' );
	wp_enqueue_script( 'event-search-script', get_template_directory_uri() . '/event-search.js', array( 'jquery' ), '1.0', true );
    wp_localize_script( 'event-search-script', 'event_search_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'event_search_enqueue_scripts' );

// Handle event search pagination with AJAX
function event_search_pagination_ajax() {
    // Check if request is valid
    if ( ! isset( $_POST['s'] ) || ! isset( $_POST['paged'] ) || ! isset( $_POST['limit'] ) ) {
        wp_die();
    }
 
    // Query events
    $events_query = new WP_Query( array(
        'post_type' => 'event',
        's' => sanitize_text_field( $_POST['s'] ),
        'posts_per_page' => intval( $_POST['limit'] ),
        'paged' => intval( $_POST['paged'] ),
    ) );
 
    // Check if there are events
    if ( $events_query->have_posts() ) {
        while ( $events_query->have_posts() ) : $events_query->the_post(); ?>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php the_excerpt(); ?>
        <?php endwhile;
        wp_reset_postdata();
    }
 
    wp_die();
}
add_action( 'wp_ajax_event_search_pagination_ajax', 'event_search_pagination_ajax' );
add_action( 'wp_ajax_nopriv_event_search_pagination_ajax', 'event_search_pagination_ajax' );