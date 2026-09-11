<?php
/**
 * Flexible Content: Geographic Section (Filter)
 * Shows both countries and cities that have content in this department
 */

$department = $args['department'] ?? null;
if (!$department instanceof WP_Term) {
    return;
}

$section_title = get_sub_field('section_title') ?: __('Geographic', 'your-textdomain');

$countries = get_department_intersected_terms($department->term_id, 'country');
$cities = get_department_intersected_terms($department->term_id, 'city');

if (empty($countries) && empty($cities)) {
    return;
}
?>

<section class="department-section entities-section geographic-section">
    <header class="section-header">
        <h2 class="section-heading"><?php echo esc_html($section_title); ?></h2>
    </header>

    <?php if (!empty($countries)): ?>
        <h3 class="geo-subtitle"><?php esc_html_e('Countries', 'your-textdomain'); ?></h3>
        <div class="entities-grid">
            <?php foreach ($countries as $data):
                $term = $data['term'];
                $count = $data['count'];
                $url = add_query_arg('country', $term->slug, get_term_link($department));
                ?>
                <article class="entity-card">
                    <a href="<?php echo esc_url($url); ?>" class="entity-link">
                        <h3 class="entity-title"><?php echo esc_html($term->name); ?></h3>
                        <span class="entity-count">(<?php echo esc_html($count); ?>)</span>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($cities)): ?>
        <h3 class="geo-subtitle"><?php esc_html_e('Cities', 'your-textdomain'); ?></h3>
        <div class="entities-grid">
            <?php foreach ($cities as $data):
                $term = $data['term'];
                $count = $data['count'];
                $url = add_query_arg('city', $term->slug, get_term_link($department));
                ?>
                <article class="entity-card">
                    <a href="<?php echo esc_url($url); ?>" class="entity-link">
                        <h3 class="entity-title"><?php echo esc_html($term->name); ?></h3>
                        <span class="entity-count">(<?php echo esc_html($count); ?>)</span>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>