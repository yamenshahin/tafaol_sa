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

foreach ($filterable_taxonomies as $tax) {
    if (!empty($_GET[$tax])) {
        $tax_query[] = [
            'taxonomy' => $tax,
            'field' => 'slug',
            'terms' => sanitize_text_field(wp_unslash($_GET[$tax])),
        ];
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

<section class="department-section programs-section">

    <?php if ($section_title): ?>
        <header class="section-header">
            <h2 class="section-heading"><?php echo esc_html($section_title); ?></h2>
        </header>
    <?php endif; ?>

    <div class="cpt-grid">
        <?php while ($query->have_posts()):
            $query->the_post(); ?>
            <?php get_template_part('template-parts/cards/card', 'program'); ?>
        <?php endwhile; ?>
    </div>

    <?php
    $more_link = add_query_arg('view', 'program', get_term_link($department));

    foreach ($filterable_taxonomies as $tax) {
        if (!empty($_GET[$tax])) {
            $more_link = add_query_arg($tax, sanitize_text_field(wp_unslash($_GET[$tax])), $more_link);
        }
    }
    ?>

    <div class="section-footer">
        <a href="<?php echo esc_url($more_link); ?>" class="btn btn-view-more">
            <?php esc_html_e('View All Programs', 'your-textdomain'); ?>
        </a>
    </div>

</section>

<?php
wp_reset_postdata();