<?php
/**
 * Active Filter Header
 * Shows title + description of the currently active taxonomy filter
 */

$department = $args['department'] ?? null;
if (!$department instanceof WP_Term) {
    return;
}

$filterable = [
    'government_entity' => __('Government Entity', 'your-textdomain'),
    'private_entity' => __('Private Sector Entity', 'your-textdomain'),
    'speaker_influencer' => __('Speaker / Influencer', 'your-textdomain'),
    'country' => __('Country', 'your-textdomain'),
    'city' => __('City', 'your-textdomain'),
];

$active_tax = null;
$active_term = null;

foreach ($filterable as $tax => $label) {
    if (!empty($_GET[$tax])) {
        $term = get_term_by('slug', sanitize_text_field(wp_unslash($_GET[$tax])), $tax);
        if ($term && !is_wp_error($term)) {
            $active_tax = $tax;
            $active_term = $term;
            break;
        }
    }
}

if (!$active_term) {
    return;
}
?>

<section class="active-filter-header">
    <div class="active-filter-inner">
        <p class="filter-label"><?php echo esc_html($filterable[$active_tax]); ?></p>
        <h1 class="filter-title"><?php echo esc_html($active_term->name); ?></h1>

        <?php if ($active_term->description): ?>
            <div class="filter-description">
                <?php echo wp_kses_post(wpautop($active_term->description)); ?>
            </div>
        <?php endif; ?>

        <p class="filter-clear">
            <a href="<?php echo esc_url(get_term_link($department)); ?>">
                ← <?php esc_html_e('Clear filter & return to department', 'your-textdomain'); ?>
            </a>
        </p>
    </div>
</section>