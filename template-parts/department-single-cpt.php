<?php
/**
 * Template Part: Isolated CPT view under a Department
 */

$department = $args['department'] ?? null;
$view = $args['view'] ?? '';
$paged = $args['paged'] ?? 1;

if (!$department instanceof WP_Term || empty($view)) {
    return;
}

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
// Tax query setup
// -------------------------------------------------
$tax_query = [
    'relation' => 'AND',
    [
        'taxonomy' => 'department',
        'field' => 'term_id',
        'terms' => $department->term_id,
    ],
];

// Added 'program_series' here so the new filters work in the isolated view too
$filterable_taxonomies = ['government_entity', 'speaker_influencer', 'private_entity', 'geographic', 'program_series'];

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
// Query execution
// -------------------------------------------------
$isolated_query = new WP_Query([
    'post_type' => $post_type,
    'posts_per_page' => 12,
    'paged' => $paged,
    'post_status' => 'publish',
    'tax_query' => $tax_query,
    'ignore_sticky_posts' => true,
]);
?>

<section class="py-12 view-<?php echo esc_attr($view); ?>">
    <div class="max-w-7xl mx-auto px-6">

        <header class="mb-12 border-b border-gray-100 pb-6">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                <?php echo esc_html($department->name); ?> —
                <span class="text-gray-500"><?php echo esc_html(ucwords(str_replace(['-', '_'], ' ', $view))); ?></span>
            </h1>
        </header>

        <?php if ($isolated_query->have_posts()): ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <?php while ($isolated_query->have_posts()):
                    $isolated_query->the_post(); ?>
                    <?php get_template_part('template-parts/cards/card', $post_type); ?>
                <?php endwhile; ?>
            </div>

            <?php
            // Only show pagination if there is more than 1 page
            if ($isolated_query->max_num_pages > 1):
                ?>
                <nav class="flex justify-center" aria-label="<?php esc_attr_e('Pagination', 'hello-elementor-child'); ?>">
                    <div
                        class="inline-flex items-center gap-2 bg-white px-4 py-2 rounded-full border border-gray-200 shadow-sm">
                        <?php
                        $pagination_args = ['view' => $view];

                        foreach ($filterable_taxonomies as $tax) {
                            if (!empty($_GET[$tax])) {
                                $pagination_args[$tax] = sanitize_text_field(wp_unslash($_GET[$tax]));
                            }
                        }

                        echo paginate_links([
                            'total' => $isolated_query->max_num_pages,
                            'current' => $paged,
                            'prev_text' => __('&laquo; Previous', 'hello-elementor-child'),
                            'next_text' => __('Next &raquo;', 'hello-elementor-child'),
                            'add_args' => $pagination_args,
                        ]);
                        ?>
                    </div>
                </nav>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>

        <?php else: ?>
            <div class="text-center py-16 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                <p class="text-gray-500"><?php esc_html_e('No items found.', 'hello-elementor-child'); ?></p>
            </div>
        <?php endif; ?>

    </div>
</section>