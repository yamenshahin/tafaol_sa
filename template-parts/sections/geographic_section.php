<?php
/**
 * Flexible Content: Geographic Section (Filter)
 * Complex Hierarchical: Parents (Countries) -> Children (Cities)
 */

$department = $args['department'] ?? null;
if (!$department instanceof WP_Term) {
    return;
}

$section = $args['section'] ?? [];

if (!empty($section)) {
    $section_title = $section['section_title'] ?? '';
} else {
    $section_title = get_sub_field('section_title');
}

$section_title = $section_title ?: __('Geographic Locations', 'hello-elementor-child');
$target_taxonomy = 'geographic';
$query_var = 'geographic';

// Fetch all intersecting terms
$active_terms = get_department_intersected_terms($department->term_id, $target_taxonomy);

if (empty($active_terms)) {
    return;
}

// -----------------------------------------------------------
// 1. Group terms into Parents (Countries) & Children (Cities)
// -----------------------------------------------------------
$parents = [];
$children_by_parent = [];

foreach ($active_terms as $data) {
    $term = $data['term'];
    if ($term->parent == 0) {
        $parents[$term->term_id] = $data;
    } else {
        $children_by_parent[$term->parent][] = $data;
    }
}

// Ensure parents exist in array if they only had child intersections
foreach ($children_by_parent as $parent_id => $children) {
    if (!isset($parents[$parent_id])) {
        $parent_term = get_term($parent_id, $target_taxonomy);
        if ($parent_term && !is_wp_error($parent_term)) {
            $parents[$parent_id] = [
                'term' => $parent_term,
                'count' => array_sum(array_column($children, 'count'))
            ];
        }
    }
}

// -----------------------------------------------------------
// 2. Pre-Open active city list if URL parameter exists
// -----------------------------------------------------------
$active_parent_id = 0;
if (!empty($_GET[$query_var])) {
    $active_term_obj = get_term_by('slug', sanitize_text_field(wp_unslash($_GET[$query_var])), $target_taxonomy);
    if ($active_term_obj && !is_wp_error($active_term_obj)) {
        if ($active_term_obj->parent == 0 && isset($children_by_parent[$active_term_obj->term_id])) {
            $active_parent_id = $active_term_obj->term_id; // Active country has cities
        } elseif ($active_term_obj->parent != 0) {
            $active_parent_id = $active_term_obj->parent; // Active city reveals siblings
        }
    }
}
?>

<section class="py-12 border-b border-gray-100 last:border-0" id="geographic-filter-section">
    <div class="max-w-7xl mx-auto px-6">

        <header class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                <?php echo esc_html($section_title); ?>
            </h2>
        </header>

        <div class="relative">

            <!-- ========================================== -->
            <!-- MAIN VIEW: COUNTRIES (PARENTS)             -->
            <!-- ========================================== -->
            <div id="geo-parents-view"
                class="geo-view <?php echo $active_parent_id ? 'hidden' : ''; ?> flex gap-5 overflow-x-auto pb-6 snap-x snap-mandatory items-stretch [scrollbar-width:none] [&::-webkit-scrollbar]:hidden transition-opacity duration-300">

                <?php foreach ($parents as $parent_id => $data):
                    $term = $data['term'];
                    $count = $data['count'];
                    $has_children = isset($children_by_parent[$parent_id]);
                    $image = get_field('taxonomy_image', $term);

                    // If it has cities, make it a JS button. If not, make it a standard link.
                    $url = add_query_arg($query_var, $term->slug, get_term_link($department));
                    $tag = $has_children ? 'button' : 'a';
                    $attr = $has_children ? 'type="button" onclick="openCities(' . $parent_id . ')"' : 'href="' . esc_url($url) . '"';
                    ?>

                    <<?php echo $tag; ?>     <?php echo $attr; ?> class="group flex-none w-48 snap-start flex flex-col
                        items-center p-6 bg-white border
                        border-gray-100 rounded-2xl hover:border-gray-200 hover:shadow-xl hover:shadow-gray-200/50
                        hover:-translate-y-1 transition-all duration-300 ease-out text-left focus:outline-none">

                        <div
                            class="w-24 h-24 aspect-square mb-5 rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden border border-gray-100 group-hover:border-blue-100 group-hover:bg-white transition-colors duration-300 shadow-sm relative">
                            <?php if (!empty($image) && is_array($image)): ?>
                                <?php echo wp_get_attachment_image($image['ID'], 'medium', false, ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-500']); ?>
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
                                <?php echo esc_html($count); ?>     <?php esc_html_e('Items', 'hello-elementor-child'); ?>
                            </span>
                        </div>

                    </<?php echo $tag; ?>>

                <?php endforeach; ?>
            </div>

            <!-- ========================================== -->
            <!-- SUB-VIEWS: CITIES (CHILDREN)               -->
            <!-- ========================================== -->
            <?php foreach ($children_by_parent as $parent_id => $children):
                $parent_data = $parents[$parent_id];
                $parent_term = $parent_data['term'];
                $parent_url = add_query_arg($query_var, $parent_term->slug, get_term_link($department));
                $parent_image = get_field('taxonomy_image', $parent_term);
                $is_active_view = ($active_parent_id === $parent_id);
                ?>
                <div id="geo-children-view-<?php echo $parent_id; ?>"
                    class="geo-view <?php echo $is_active_view ? '' : 'hidden'; ?> flex gap-5 overflow-x-auto pb-6 snap-x snap-mandatory items-stretch [scrollbar-width:none] [&::-webkit-scrollbar]:hidden transition-opacity duration-300">

                    <!-- Back to Countries Button -->
                    <button type="button" onclick="showCountries()"
                        class="group flex-none w-32 snap-start flex flex-col items-center justify-center p-4 bg-gray-50 border border-gray-100 rounded-2xl hover:border-gray-200 hover:bg-white hover:shadow-lg transition-all duration-300 focus:outline-none">
                        <div
                            class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-gray-400 group-hover:text-blue-600 group-hover:bg-blue-50 mb-3 shadow-sm transition-colors border border-gray-100">
                            <span class="text-xl">&larr;</span>
                        </div>
                        <span
                            class="text-xs font-bold text-gray-500 group-hover:text-blue-600"><?php esc_html_e('Regions', 'hello-elementor-child'); ?></span>
                    </button>

                    <!-- "ALL" Parent Card -->
                    <a href="<?php echo esc_url($parent_url); ?>"
                        class="group flex-none w-48 snap-start flex flex-col items-center p-6 bg-white border border-gray-100 rounded-2xl hover:border-gray-200 hover:shadow-xl hover:shadow-gray-200/50 hover:-translate-y-1 transition-all duration-300 ease-out relative overflow-hidden">

                        <div class="absolute inset-0 bg-blue-50/50 opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>

                        <div
                            class="w-24 h-24 aspect-square mb-5 rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden border border-gray-200 group-hover:border-blue-200 group-hover:bg-white transition-colors duration-300 shadow-sm relative z-10">
                            <?php if (!empty($parent_image) && is_array($parent_image)): ?>
                                <?php echo wp_get_attachment_image($parent_image['ID'], 'medium', false, ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-80 group-hover:opacity-100']); ?>
                            <?php else: ?>
                                <span
                                    class="text-3xl font-bold text-gray-300 uppercase"><?php echo esc_html(mb_substr($parent_term->name, 0, 1)); ?></span>
                            <?php endif; ?>
                        </div>

                        <h3
                            class="text-sm font-bold text-gray-900 text-center mb-1 line-clamp-2 leading-snug group-hover:text-blue-800 transition-colors relative z-10">
                            <?php echo esc_html(sprintf(__('All %s', 'hello-elementor-child'), $parent_term->name)); ?>
                        </h3>

                        <div class="mt-auto relative z-10 pt-3">
                            <span
                                class="text-xs font-bold tracking-wide text-blue-600 bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-full group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <?php echo esc_html($parent_data['count']); ?>
                                <?php esc_html_e('Total', 'hello-elementor-child'); ?>
                            </span>
                        </div>
                    </a>

                    <!-- Individual City Cards -->
                    <?php foreach ($children as $data):
                        $term = $data['term'];
                        $count = $data['count'];
                        $url = add_query_arg($query_var, $term->slug, get_term_link($department));
                        $image = get_field('taxonomy_image', $term);
                        ?>

                        <a href="<?php echo esc_url($url); ?>"
                            class="group flex-none w-48 snap-start flex flex-col items-center p-6 bg-white border border-gray-100 rounded-2xl hover:border-gray-200 hover:shadow-xl hover:shadow-gray-200/50 hover:-translate-y-1 transition-all duration-300 ease-out">

                            <div
                                class="w-24 h-24 aspect-square mb-5 rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden border border-gray-100 group-hover:border-blue-100 group-hover:bg-white transition-colors duration-300 shadow-sm">
                                <?php if (!empty($image) && is_array($image)): ?>
                                    <?php echo wp_get_attachment_image($image['ID'], 'medium', false, ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-500']); ?>
                                <?php else: ?>
                                    <span
                                        class="text-3xl font-bold text-gray-300 uppercase"><?php echo esc_html(mb_substr($term->name, 0, 1)); ?></span>
                                <?php endif; ?>
                            </div>

                            <h3
                                class="text-sm font-bold text-gray-900 text-center mb-4 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                                <?php echo esc_html($term->name); ?>
                            </h3>

                            <div class="mt-auto">
                                <span
                                    class="text-xs font-bold tracking-wide text-gray-500 bg-gray-100 px-3 py-1.5 rounded-full group-hover:bg-blue-50 group-hover:text-blue-700 transition-colors">
                                    <?php echo esc_html($count); ?>         <?php esc_html_e('Items', 'hello-elementor-child'); ?>
                                </span>
                            </div>

                        </a>
                    <?php endforeach; ?>

                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <!-- Small JS Handler for Smooth View Toggling -->
    <script>
        function openCities(parentId) {
            document.querySelectorAll('.geo-view').forEach(view => {
                view.classList.add('hidden');
            });
            const targetView = document.getElementById('geo-children-view-' + parentId);
            if (targetView) {
                targetView.classList.remove('hidden');
            }
        }

        function showCountries() {
            document.querySelectorAll('.geo-view').forEach(view => {
                view.classList.add('hidden');
            });
            const parentView = document.getElementById('geo-parents-view');
            if (parentView) {
                parentView.classList.remove('hidden');
            }
        }
    </script>
</section>