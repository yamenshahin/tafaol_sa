<?php
/**
 * Flexible Content: Geographic Section (Filter)
 * Only works on Department pages
 */

$department = $args['department'] ?? null;
if (!$department instanceof WP_Term) {
    return;
}

$section_title = get_sub_field('section_title') ?: __('Geographic', 'hello-elementor-child');

$countries = get_department_intersected_terms($department->term_id, 'country');
$cities = get_department_intersected_terms($department->term_id, 'city');

if (empty($countries) && empty($cities)) {
    return;
}
?>

<section class="py-12 border-b border-gray-100 last:border-0">
    <div class="max-w-7xl mx-auto px-6">
        <header class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight"><?php echo esc_html($section_title); ?></h2>
        </header>

        <?php if (!empty($countries)): ?>
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-4">
                <?php esc_html_e('Countries', 'hello-elementor-child'); ?>
            </h3>
            <div
                class="flex gap-5 overflow-x-auto pb-6 snap-x snap-mandatory mb-8 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                <?php foreach ($countries as $data):
                    $term = $data['term'];
                    $count = $data['count'];
                    $url = add_query_arg('country', $term->slug, get_term_link($department));
                    ?>
                    <a href="<?php echo esc_url($url); ?>"
                        class="group flex-none w-44 snap-start flex flex-col items-center justify-between p-6 bg-white border border-gray-100 rounded-2xl hover:border-gray-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <h3 class="text-sm font-medium text-gray-800 text-center mb-3"><?php echo esc_html($term->name); ?>
                        </h3>
                        <span
                            class="text-xs font-semibold text-gray-500 bg-gray-50 px-3 py-1 rounded-full group-hover:bg-gray-100 transition-colors">
                            (<?php echo esc_html($count); ?>)
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($cities)): ?>
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-4">
                <?php esc_html_e('Cities', 'hello-elementor-child'); ?>
            </h3>
            <div
                class="flex gap-5 overflow-x-auto pb-6 snap-x snap-mandatory [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                <?php foreach ($cities as $data):
                    $term = $data['term'];
                    $count = $data['count'];
                    $url = add_query_arg('city', $term->slug, get_term_link($department));
                    ?>
                    <a href="<?php echo esc_url($url); ?>"
                        class="group flex-none w-44 snap-start flex flex-col items-center justify-between p-6 bg-white border border-gray-100 rounded-2xl hover:border-gray-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <h3 class="text-sm font-medium text-gray-800 text-center mb-3"><?php echo esc_html($term->name); ?>
                        </h3>
                        <span
                            class="text-xs font-semibold text-gray-500 bg-gray-50 px-3 py-1 rounded-full group-hover:bg-gray-100 transition-colors">
                            (<?php echo esc_html($count); ?>)
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>