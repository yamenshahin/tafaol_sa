<?php
/**
 * Active Filter Header
 */
$department = $args['department'] ?? null;
if (!$department instanceof WP_Term) {
    return;
}

$filterable = [
    'government_entity' => __('Government Entity', 'hello-elementor-child'),
    'private_entity' => __('Private Sector Entity', 'hello-elementor-child'),
    'speaker_influencer' => __('Speaker / Influencer', 'hello-elementor-child'),
    'geographic' => __('Geographic Location', 'hello-elementor-child'),
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

<section class="relative bg-gray-50/50 border-b border-gray-100 py-16 mb-12">
    <div class="max-w-5xl mx-auto px-6 text-center">

        <!-- Subtle Label -->
        <p class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-4">
            <?php echo esc_html($filterable[$active_tax]); ?>
        </p>

        <!-- Elegant Title -->
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 tracking-tight mb-6">
            <?php echo esc_html($active_term->name); ?>
        </h1>

        <?php if ($active_term->description): ?>
            <div class="text-lg text-gray-500 leading-relaxed max-w-2xl mx-auto mb-8">
                <?php echo wp_kses_post(wpautop($active_term->description)); ?>
            </div>
        <?php endif; ?>

        <!-- Minimalist Clear Button -->
        <a href="<?php echo esc_url(get_term_link($department)); ?>"
            class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-full hover:bg-gray-50 hover:text-gray-900 hover:border-gray-300 transition-all duration-300 shadow-sm hover:shadow">
            &larr; <span class="mx-2"><?php esc_html_e('Clear filter', 'hello-elementor-child'); ?></span>
        </a>

    </div>
</section>