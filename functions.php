<?php
/**
 * Theme functions and definitions.
 */

if (!defined('ABSPATH')) {
	exit;
}

define('HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0');

function hello_elementor_child_scripts_styles()
{
	// 1. Enqueue FontAwesome (Load this first so it's ready for your UI)
	wp_enqueue_style(
		'font-awesome-cdn',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
		[],
		'6.5.1'
	);

	// 2. Enqueue the root style.css (Theme identity & base custom styles)
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_uri(),
		['hello-elementor-theme-style'],
		filemtime(get_stylesheet_directory() . '/style.css')
	);

	// 3. Enqueue the compiled Tailwind utilities
	wp_enqueue_style(
		'hello-elementor-child-tailwind',
		get_stylesheet_directory_uri() . '/assets/css/tailwind.css',
		['hello-elementor-child-style'],
		filemtime(get_stylesheet_directory() . '/assets/css/tailwind.css')
	);
}
add_action('wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20);

/**
 * Register custom query var
 */
add_filter('query_vars', function ($vars) {
	$vars[] = 'view';
	return $vars;
});

/**
 * Hide the default WordPress taxonomy description field
 */
add_action('admin_head', function () {
	if (isset($_GET['taxonomy'])) {
		echo '<style>.term-description-wrap { display: none !important; }</style>';
	}
});

/**
 * Force program, infographic, interview to use /post-type/ID/
 */
add_filter('post_type_link', function ($permalink, $post) {
	$targets = ['program', 'infographic', 'interview'];
	if (in_array($post->post_type, $targets, true)) {
		return home_url($post->post_type . '/' . $post->ID . '/');
	}
	return $permalink;
}, 10, 2);

add_action('init', function () {
	$targets = ['program', 'infographic', 'interview'];
	foreach ($targets as $post_type) {
		add_rewrite_rule(
			'^' . $post_type . '/([0-9]+)/?$',
			'index.php?post_type=' . $post_type . '&p=$matches[1]',
			'top'
		);
	}
});

/**
 * Force standard posts to /news/ID/
 */
add_filter('post_link', function ($permalink, $post) {
	if ('post' === $post->post_type) {
		return home_url('news/' . $post->ID . '/');
	}
	return $permalink;
}, 10, 2);

add_action('init', function () {
	add_rewrite_rule('^news/([0-9]+)/?$', 'index.php?p=$matches[1]', 'top');
});

/**
 * -------------------------------------------------
 * INTERSECTION ENGINE
 * Returns terms of a taxonomy that actually have posts
 * in common with the given department + accurate counts
 * -------------------------------------------------
 */
function get_department_intersected_terms(int $department_id, string $taxonomy, int $limit = 0): array
{
	global $wpdb;

	// 1. Get all post IDs belonging to this department
	$department_posts = $wpdb->get_col($wpdb->prepare("
		SELECT tr.object_id
		FROM {$wpdb->term_relationships} tr
		INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
		WHERE tt.taxonomy = 'department'
		AND tt.term_id = %d
	", $department_id));

	if (empty($department_posts)) {
		return [];
	}

	$post_ids = array_map('intval', $department_posts);
	$placeholders = implode(',', array_fill(0, count($post_ids), '%d'));

	// 2. Find terms of the target taxonomy attached to those posts
	$sql = "
		SELECT tt.term_id, COUNT(tr.object_id) AS post_count
		FROM {$wpdb->term_relationships} tr
		INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
		WHERE tt.taxonomy = %s
		AND tr.object_id IN ($placeholders)
		GROUP BY tt.term_id
		ORDER BY post_count DESC
	";

	$query = $wpdb->prepare($sql, array_merge([$taxonomy], $post_ids));

	if ($limit > 0) {
		$query .= $wpdb->prepare(' LIMIT %d', $limit);
	}

	$results = $wpdb->get_results($query);

	if (empty($results)) {
		return [];
	}

	$output = [];
	foreach ($results as $row) {
		$term = get_term((int) $row->term_id, $taxonomy);
		if ($term && !is_wp_error($term)) {
			$output[] = [
				'term' => $term,
				'count' => (int) $row->post_count,
			];
		}
	}

	return $output;
}