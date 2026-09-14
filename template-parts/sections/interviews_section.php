<?php
/**
 * Flexible Content: Interviews Section
 * Works on Department pages and Homepage
 */

$department = $args['department'] ?? null;



$section_title = get_sub_field('section_title');
$posts_limit = get_sub_field('posts_limit') ?: 4;

$tax_query = ['relation' => 'AND'];

if ($department instanceof WP_Term) {
    $tax_query[] = [
        'taxonomy' => 'department',
        'field' => 'term_id',
        'terms' => $department->term_id,
    ];
}

$filterable_taxonomies = [
    'government_entity',
    'private_entity',
    'speaker_influencer',
    'geographic',
    'program_series'
];
$more_link_args = [];

foreach ($filterable_taxonomies as $tax) {
    if (!empty($_GET[$tax])) {
        $term_slug = sanitize_text_field(wp_unslash($_GET[$tax]));
        $tax_query[] = [
            'taxonomy' => $tax,
            'field' => 'slug',
            'terms' => $term_slug,
        ];
        $more_link_args[$tax] = $term_slug;
    }
}

$query = new WP_Query([
    'post_type' => 'interview',
    'posts_per_page' => absint($posts_limit),
    'tax_query' => $tax_query,
    'no_found_rows' => true,
    'ignore_sticky_posts' => true,
]);

if (!$query->have_posts()) {
    return;
}

if ($department instanceof WP_Term) {
    $more_link = add_query_arg(array_merge(['view' => 'interview'], $more_link_args), get_term_link($department));
} else {
    $more_link = get_post_type_archive_link('interview');
}
?>

<section class="department-section interviews-section py-12 border-b border-gray-100 last:border-0">
    <div class="max-w-7xl mx-auto px-6">
        <header class="flex justify-between items-end mb-8">
            <?php if ($section_title): ?>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>
            <a href="<?php echo esc_url($more_link); ?>"
                class="text-sm font-medium text-orange-500 hover:text-orange-700 transition-colors">
                <?php esc_html_e('View All Interviews', 'hello-elementor-child'); ?> &rarr;
            </a>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php while ($query->have_posts()):
                $query->the_post(); ?>
                <?php get_template_part('template-parts/cards/card', 'interview'); ?>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php wp_reset_postdata(); ?>