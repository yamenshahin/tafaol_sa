<?php
/**
 * Flexible Content: Private Entities Section
 */

$department = $args['department'] ?? null;
$section = $args['section'] ?? [];

if (!empty($section)) {
    $section_title = $section['section_title'] ?? '';
} else {
    $section_title = get_sub_field('section_title');
}

$section_title = $section_title ?: __('Private Sector Entities', 'hello-elementor-child');
$target_taxonomy = 'private_entity';
$query_var = 'private_entity';

$active_terms = [];

if ($department instanceof WP_Term) {
    $active_terms = get_department_intersected_terms((int) $department->term_id, $target_taxonomy);
} else {
    $terms = get_terms([
        'taxonomy' => $target_taxonomy,
        'hide_empty' => true,
    ]);

    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $active_terms[] = [
                'term' => $term,
                'count' => (int) $term->count,
            ];
        }
    }
}

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
            class="flex gap-5 overflow-x-auto pb-6 snap-x snap-mandatory items-stretch [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <?php foreach ($active_terms as $data):
                $term = $data['term'];
                $count = $data['count'];

                if ($department instanceof WP_Term) {
                    $url = add_query_arg($query_var, $term->slug, get_term_link($department));
                } else {
                    $url = get_term_link($term);
                }

                $image = get_field('taxonomy_image', $term);
                ?>
                <a href="<?php echo esc_url($url); ?>"
                    class="group flex-none w-48 snap-start flex flex-col items-center p-6 bg-white border border-gray-100 rounded-2xl hover:border-gray-200 hover:shadow-xl hover:shadow-gray-200/50 hover:-translate-y-1 transition-all duration-300 ease-out">
                    <div
                        class="w-24 h-24 aspect-square mb-5 rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden border border-gray-100 group-hover:border-blue-100 group-hover:bg-white transition-colors duration-300 shadow-sm">
                        <?php if (!empty($image) && is_array($image)): ?>
                            <?php echo wp_get_attachment_image($image['ID'], 'medium', false, [
                                'class' => 'w-full h-full object-contain p-3 group-hover:scale-110 transition-transform duration-500',
                            ]); ?>
                        <?php else: ?>
                            <span class="text-3xl font-bold text-gray-300 uppercase">
                                <?php echo esc_html(mb_substr($term->name, 0, 1)); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h3
                        class="text-sm font-bold text-gray-900 text-center mb-4 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                        <?php echo esc_html($term->name); ?>
                    </h3>
                    <div class="mt-auto">
                        <span
                            class="text-xs font-bold tracking-wide text-gray-500 bg-gray-100 px-3 py-1.5 rounded-full group-hover:bg-blue-50 group-hover:text-blue-700 transition-colors">
                            <?php echo esc_html($count); ?>
                            <?php esc_html_e('Items', 'hello-elementor-child'); ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>