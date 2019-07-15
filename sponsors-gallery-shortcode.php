<?php

/**
* Plugin Name: Sponsors Gallery Shortcode
* Plugin URI:  http://spittoon.co.uk/
* Description: Add Sponsors to your site as Custom Post Types and display them using a shortcode
* Version:     1.0.0
* Author:      Michael Adamson
* Author URI:  http://spittoon.co.uk/
*/


// Add the 'sponsors'gallery' shortcode
add_shortcode( 'sponsors-gallery', 'display_sponsors' );

// Hook into the 'init' action so that the function containing our post type registration is not unnecessarily executed. */
add_action( 'init', 'sponsor_custom_post_type', 0 );

// Trigger the function to check for the shortcode and load the CSS with the wp_enqueue_scripts hook
add_action( 'wp_enqueue_scripts', 'load_scripts_styles_if_shortcode_present' );

/*
* Create a "Sponsor" Custom Post Type
*/
function sponsor_custom_post_type() {

// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => _x( 'Sponsor', 'Post Type General Name', 'twentythirteen' ),
		'singular_name'       => _x( 'Sponsor', 'Post Type Singular Name', 'twentythirteen' ),
		'menu_name'           => __( 'Sponsors', 'twentythirteen' ),
		'parent_item_colon'   => __( 'Parent Sponsor', 'twentythirteen' ),
		'all_items'           => __( 'All Sponsors', 'twentythirteen' ),
		'view_item'           => __( 'View Sponsor', 'twentythirteen' ),
		'add_new_item'        => __( 'Add New Sponsor', 'twentythirteen' ),
		'add_new'             => __( 'Add New', 'twentythirteen' ),
		'edit_item'           => __( 'Edit Sponsor', 'twentythirteen' ),
		'update_item'         => __( 'Update Sponsor', 'twentythirteen' ),
		'search_items'        => __( 'Search Sponsor', 'twentythirteen' ),
		'not_found'           => __( 'Not Found', 'twentythirteen' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'sponsors', 'twentythirteen' ),
		'description'         => __( 'Sponsors', 'twentythirteen' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
		// You can associate this CPT with a taxonomy or custom taxonomy. 
		'taxonomies'          => array( 'sponsors' ),
		/* A hierarchical CPT is like Pages and can have Parent and child items. A non-hierarchical CPT is like Posts. */	
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 7,
		'can_export'          => true,
		'has_archive'         => 'Sponsors',
		'menu_icon'           => 'dashicons-businessman',
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	
	// Registering your Custom Post Type
	register_post_type( 'sponsors', $args );

}

function display_sponsors( $atts ) {

	if ( isset($atts['display-count']) ) {
		$display_count = $atts['display-count'];
	} else {
		$display_count = 6;
	}

	if ( isset($atts['show-title']) && ($atts['show-title'] == 'true') ) {
		$show_title = $atts['show-title'];
	} else {
		$show_title = 'false';
	}

	ob_start();

	$args = array(
		'post_type'      => 'Sponsors', // enter your custom post type
		'orderby'        => 'rand',
		'posts_per_page' => $display_count,  // overrides posts per page in theme settings
	);

	$loop = new WP_Query( $args );

	if( $loop->have_posts() ):

		$i = 0;
				
        echo '<div class="sponsors-container">';
		while( $loop->have_posts() ):
			$loop->the_post();
			global $post;
			?>
            <div class="sponsor-link" style="width:100px;">
		        <a href="<?php echo get_permalink(); ?>" id="<?php echo get_the_title(); ?>" title="<?php echo get_the_title(); ?>">
					<div class="sponsor-logo">
						<?php echo get_the_post_thumbnail(); ?>
					</div>
					<?php
					if ( $show_title == 'true' ) {
					?>
						<div class="sponsor-name">
							<?php echo get_the_title(); ?>
						</div>
					<?php
					}
					?>
	            </a>
			</div>
			<?php
			// if ( ($i+1) % 6 == 0 ) {
				?>
				<!-- <div class="new-sponsor-row"></div> -->
				<?php
			// }
			$i++;
		endwhile;
		echo '</div>';	

		wp_reset_postdata();	
		
	endif;

	return ob_get_clean();
}

function load_scripts_styles_if_shortcode_present() {
	// Get the global post variable
	// global $post;
	// Check the post content contains the shortcode '[sponsors-gallery]'
	// if( has_shortcode( $post->post_content, 'sponsors-gallery') ) {
		// If the post contains the shortcode, enqueue the CSS
		wp_enqueue_style( 'sponsors-gallery', plugin_dir_url( __FILE__ ) . 'style.css' );
		wp_enqueue_script( 'sponsors-gallery', plugin_dir_url( __FILE__ ) . 'sponsor-sizing.js' );
	// }
}