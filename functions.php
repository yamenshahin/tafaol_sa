<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

define('HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0');

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles()
{

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);

}
add_action('wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20);

/**
 * Get department/health/?view=xxx
 */
add_filter('query_vars', function ($vars) {
	$vars[] = 'view';
	return $vars;
});

/**
 * Hide the default WordPress taxonomy description field in the admin area.
 */
add_action('admin_head', 'hide_default_taxonomy_description');

function hide_default_taxonomy_description()
{
	// Check if we are on a taxonomy term screen (either adding or editing)
	if (isset($_GET['taxonomy'])) {
		echo '<style>
            /* Hides the description field on both the "Add New" and "Edit" screens */
            .term-description-wrap { 
                display: none !important; 
            }
        </style>';
	}
}