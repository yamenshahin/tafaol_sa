<?php
/**
 * Flexible Content: Programs Section
 */
$department = $args['department'] ?? null;
if (!$department instanceof WP_Term) {
    return;
}

$section_title = get_sub_field('section_title');
$posts_limit = get_sub_field('posts_limit') ?: 4;

$tax_query = [
    'relation' => 'AND',
    [
        'taxonomy' => 'department',
        'field' => 'term_id',
        'terms' => $department->term_id,
    ],
];

$filterable_taxonomies = ['government_entity', 'speaker_influencer', 'country', 'city'];

$more_link = add_query_arg('view', 'program', get_term_link($department));

foreach ($filterable_taxonomies as $tax) {
    if (!empty($_GET[$tax])) {
        $term_slug = sanitize_text_field(wp_unslash($_GET[$tax]));
        $tax_query[] = [
            'taxonomy' => $tax,
            'field' => 'slug',
            'terms' => $term_slug,
        ];
        $more_link = add_query_arg($tax, $term_slug, $more_link);
    }
}

$query = new WP_Query([
    'post_type' => 'program',
    'posts_per_page' => absint($posts_limit),
    'tax_query' => $tax_query,
    'no_found_rows' => true,
    'ignore_sticky_posts' => true,
]);

if (!$query->have_posts()) {
    return;
}
?>

<section class="py-12 border-b border-gray-100 last:border-0">
    <div class="max-w-7xl mx-auto px-6">

        <header class="flex justify-between items-end mb-8">
            <?php if ($section_title): ?>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>

            <a href="<?php echo esc_url($more_link); ?>"
                class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                <?php esc_html_e('View All Programs', 'hello-elementor-child'); ?> &rarr;
            </a>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php while ($query->have_posts()):
                $query->the_post(); ?>
                <?php get_template_part('template-parts/cards/card', 'program'); ?>
            <?php endwhile; ?>
        </div>

    </div>
</section>
<?php wp_reset_postdata(); ?>