<?php
/**
 * Flexible Content: Speakers & Influencers Section (Filter)
 */
$department = $args['department'] ?? null;
if (!$department instanceof WP_Term) {
    return;
}

$section_title = get_sub_field('section_title') ?: __('Speakers & Influencers', 'hello-elementor-child');
$target_taxonomy = 'speaker_influencer';
$query_var = 'speaker_influencer';

$active_terms = get_department_intersected_terms($department->term_id, $target_taxonomy);

if (empty($active_terms)) {
    return;
}
?>

<section class="py-12 border-b border-gray-100 last:border-0">
    <div class="max-w-7xl mx-auto px-6">

        <header class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                <?php echo esc_html($section_title); ?>
            </h2>
        </header>

        <div
            class="flex gap-5 overflow-x-auto pb-6 snap-x snap-mandatory [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <?php foreach ($active_terms as $data):
                $term = $data['term'];
                $count = $data['count'];
                $url = add_query_arg($query_var, $term->slug, get_term_link($department));

                $image_id = get_field('taxonomy_image', $term);
                $image_html = $image_id ? wp_get_attachment_image($image_id, 'medium', false, ['class' => 'w-16 h-16 object-cover rounded-full opacity-90 group-hover:opacity-100 transition-opacity duration-300']) : '';
                ?>

                <a href="<?php echo esc_url($url); ?>"
                    class="group flex-none w-44 snap-start flex flex-col items-center justify-between p-6 bg-white border border-gray-100 rounded-2xl hover:border-gray-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 ease-out">

                    <?php if ($image_html): ?>
                        <div class="mb-5"><?php echo $image_html; ?></div>
                    <?php endif; ?>

                    <h3 class="text-sm font-medium text-gray-800 text-center mb-3 line-clamp-2">
                        <?php echo esc_html($term->name); ?>
                    </h3>

                    <span
                        class="text-xs font-semibold text-gray-500 bg-gray-50 px-3 py-1 rounded-full group-hover:bg-gray-100 transition-colors">
                        (<?php echo esc_html($count); ?>)
                    </span>

                </a>

            <?php endforeach; ?>
        </div>

    </div>
</section>