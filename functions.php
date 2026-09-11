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

/**
 * Force program, infographic, and interview CPTs to use post IDs in URLs.
 */
add_filter('post_type_link', 'custom_cpt_id_permalink', 10, 2);

function custom_cpt_id_permalink($permalink, $post)
{
	$target_post_types = ['program', 'infographic', 'interview'];

	if (in_array($post->post_type, $target_post_types, true)) {
		return home_url($post->post_type . '/' . $post->ID . '/');
	}

	return $permalink;
}

add_action('init', 'custom_cpt_id_rewrite_rules');

function custom_cpt_id_rewrite_rules()
{
	$target_post_types = ['program', 'infographic', 'interview'];

	foreach ($target_post_types as $post_type) {
		add_rewrite_rule(
			'^' . $post_type . '/([0-9]+)/?$',
			'index.php?post_type=' . $post_type . '&p=$matches[1]',
			'top'
		);
	}
}

/**
 * Force standard posts to use /news/%post_id%/ 
 * without polluting global permalink structures.
 */
add_filter('post_link', 'custom_news_post_permalink', 10, 2);

function custom_news_post_permalink($permalink, $post)
{
	// Check if the post type is the default 'post'
	if ('post' === $post->post_type) {
		return home_url('news/' . $post->ID . '/');
	}
	return $permalink;
}

add_action('init', 'custom_news_post_rewrite_rules');

function custom_news_post_rewrite_rules()
{
	// Map incoming requests for /news/123/ to the native post ID query
	add_rewrite_rule(
		'^news/([0-9]+)/?$',
		'index.php?p=$matches[1]',
		'top'
	);
}