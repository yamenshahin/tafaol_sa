<?php
/**
 * Template Part: Department Social Media Links
 */
$department = $args['department'] ?? null;
if (!$department instanceof WP_Term) {
    return;
}

// Use ACF have_rows to enable sub-field object querying
if (!have_rows('social_links', $department)) {
    return;
}
?>

<section class="py-16 bg-gray-50/50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-6 text-center">

        <span class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-3 block">
            <?php esc_html_e('Stay Connected', 'hello-elementor-child'); ?>
        </span>

        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight mb-4">
            <?php echo esc_html(sprintf(__('Join the %s Community', 'hello-elementor-child'), $department->name)); ?>
        </h2>

        <p class="text-lg text-gray-500 max-w-2xl mx-auto mb-12">
            <?php esc_html_e('Follow us across our digital platforms for the latest updates, programs, and exclusive insights.', 'hello-elementor-child'); ?>
        </p>

        <div class="flex flex-wrap justify-center gap-6">

            <?php
            while (have_rows('social_links', $department)):
                the_row();
                $url = get_sub_field('link');
                $platform_val = get_sub_field('platform') ?: 'fas fa-globe';

                // Dynamically fetch the Label you assigned in ACF (e.g., "X (Twitter)")
                $platform_obj = get_sub_field_object('platform');
                $label = $platform_obj['choices'][$platform_val] ?? 'Website';
                ?>

                <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer"
                    class="group w-44 flex flex-col items-center justify-between p-6 bg-white border border-gray-100 rounded-2xl hover:border-gray-200 hover:shadow-xl hover:shadow-gray-200/50 hover:-translate-y-1 transition-all duration-300 ease-out">

                    <!-- FontAwesome Output -->
                    <div
                        class="w-14 h-14 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors duration-300 mb-4 text-2xl">
                        <i class="<?php echo esc_attr($platform_val); ?>"></i>
                    </div>

                    <!-- Dynamic Label -->
                    <h3 class="text-sm font-bold text-gray-900 mb-2">
                        <?php echo esc_html($label); ?>
                    </h3>

                    <span
                        class="text-xs font-semibold text-gray-400 flex items-center group-hover:text-blue-600 transition-colors">
                        <?php esc_html_e('Visit', 'hello-elementor-child'); ?>
                        <span class="mx-1 transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
                    </span>

                </a>

            <?php endwhile; ?>

        </div>
    </div>
</section>