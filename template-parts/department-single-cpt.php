<?php
/**
 * Template Part: Isolated CPT view under a Department
 *
 * Expected $args:
 * - department (WP_Term)
 * - view       (string)  program | infographic | interview | post
 * - paged      (int)
 */

$department = $args['department'] ?? null;
$view = $args['view'] ?? '';
$paged = $args['paged'] ?? 1;

if (!$department instanceof WP_Term || empty($view)) {
    return;
}

// Map view → real post type
$post_type_map = [
    'program' => 'program',
    'infographic' => 'infographic',
    'interview' => 'interview',
    'post' => 'post',
];

$post_type = $post_type_map[$view] ?? null;

if (!$post_type) {
    return;
}

// -------------------------------------------------
// Build tax_query
// -------------------------------------------------
$tax_query = [
    'relation' => 'AND',
    [
        'taxonomy' => 'department',
        'field' => 'term_id',
        'terms' => $department->term_id,
    ],
];

// Persist active filters
$filterable_taxonomies = [
    'government_entity',
    'speaker_influencer',
    'country',
    'city',
];

foreach ($filterable_taxonomies as $tax) {
    if (!empty($_GET[$tax])) {
        $tax_query[] = [
            'taxonomy' => $tax,
            'field' => 'slug',
            'terms' => sanitize_text_field(wp_unslash($_GET[$tax])),
        ];
    }
}

// -------------------------------------------------
// Query
// -------------------------------------------------
$query_args = [
    'post_type' => $post_type,
    'posts_per_page' => 12,
    'paged' => $paged,
    'post_status' => 'publish',
    'tax_query' => $tax_query,
    'ignore_sticky_posts' => true,
];

$isolated_query = new WP_Query($query_args);
?>

<section class="department-single-cpt view-<?php echo esc_attr($view); ?>">

    <header class="section-header">
        <h1>
            <?php echo esc_html($department->name); ?> —
            <?php echo esc_html(ucwords(str_replace('-', ' ', $view))); ?>
        </h1>
    </header>

    <?php if ($isolated_query->have_posts()): ?>

        <div class="cpt-grid">
            <?php while ($isolated_query->have_posts()):
                $isolated_query->the_post(); ?>
                <?php get_template_part('template-parts/cards/card', $post_type); ?>
            <?php endwhile; ?>
        </div>

        <div class="cpt-pagination">
            <?php
            $pagination_args = ['view' => $view];

            // Keep filters in pagination links
            foreach ($filterable_taxonomies as $tax) {
                if (!empty($_GET[$tax])) {
                    $pagination_args[$tax] = sanitize_text_field(wp_unslash($_GET[$tax]));
                }
            }

            echo paginate_links([
                'total' => $isolated_query->max_num_pages,
                'current' => $paged,
                'prev_text' => __('&laquo; Previous', 'your-textdomain'),
                'next_text' => __('Next &raquo;', 'your-textdomain'),
                'add_args' => $pagination_args,
            ]);
            ?>
        </div>

        <?php wp_reset_postdata(); ?>

    <?php else: ?>
        <p><?php esc_html_e('No items found.', 'your-textdomain'); ?></p>
    <?php endif; ?>

</section>