<?php
/**
 * Flexible Content: Government Entities Section (Filter)
 */

$department = $args['department'] ?? null;
if (!$department instanceof WP_Term) {
    return;
}

$section_title = get_sub_field('section_title') ?: __('Government Entities', 'your-textdomain');
$target_taxonomy = 'government_entity';
$query_var = 'government_entity';

$active_terms = get_department_intersected_terms($department->term_id, $target_taxonomy);

if (empty($active_terms)) {
    return;
}
?>

<section class="department-section entities-section government-entities-section">
    <header class="section-header">
        <h2 class="section-heading"><?php echo esc_html($section_title); ?></h2>
    </header>

    <div class="entities-grid">
        <?php foreach ($active_terms as $data):
            $term = $data['term'];
            $count = $data['count'];
            $url = add_query_arg($query_var, $term->slug, get_term_link($department));
            ?>
            <article class="entity-card">
                <a href="<?php echo esc_url($url); ?>" class="entity-link">
                    <h3 class="entity-title"><?php echo esc_html($term->name); ?></h3>
                    <span class="entity-count">(<?php echo esc_html($count); ?>)</span>
                </a>
            </article>
        <?php endforeach; ?>
    </div>
</section>