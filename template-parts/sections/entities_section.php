<?php
/**
 * Flexible Content: Entities Section
 * (Shows related government / private / speaker terms)
 */

$department = $args['department'] ?? null;

if (!$department instanceof WP_Term) {
    return;
}

$section_title = get_sub_field('section_title');
$entity_type = get_sub_field('entity_type') ?: 'government_entity'; // or private_entity / speaker_influencer
$posts_limit = get_sub_field('posts_limit') ?: 6;

$terms = get_terms([
    'taxonomy' => $entity_type,
    'hide_empty' => true,
    'number' => absint($posts_limit),
]);

if (empty($terms) || is_wp_error($terms)) {
    return;
}
?>

<section class="department-section entities-section">

    <?php if ($section_title): ?>
        <header class="section-header">
            <h2 class="section-heading"><?php echo esc_html($section_title); ?></h2>
        </header>
    <?php endif; ?>

    <div class="cpt-grid entities-grid">
        <?php foreach ($terms as $term): ?>
            <article class="entity-card">
                <h3 class="entity-title">
                    <a href="<?php echo esc_url(get_term_link($term)); ?>">
                        <?php echo esc_html($term->name); ?>
                    </a>
                </h3>
                <?php if ($term->description): ?>
                    <p class="entity-excerpt"><?php echo esc_html(wp_trim_words($term->description, 15)); ?></p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>

</section>