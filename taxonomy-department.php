<?php
/**
 * Taxonomy Template: Department
 * Handles:
 * 1. Full multi-section layout
 * 2. Isolated CPT view (?view=program|infographic|interview|post)
 */

get_header();

$department = get_queried_object();
$paged = max(1, absint(get_query_var('paged')));

// Get view parameter reliably
$view = get_query_var('view');
if (empty($view) && isset($_GET['view'])) {
    $view = sanitize_text_field(wp_unslash($_GET['view']));
}

$allowed_views = ['program', 'infographic', 'interview', 'post'];

// Cover image
$cover_data = get_field('department_cover', $department);
$cover_id = 0;

if (is_array($cover_data) && !empty($cover_data['ID'])) {
    $cover_id = $cover_data['ID'];
} elseif (is_numeric($cover_data)) {
    $cover_id = (int) $cover_data;
}
?>

<main class="department-archive-main bg-white pb-24">

    <?php if ($view && in_array($view, $allowed_views, true)): ?>

        <?php
        // =========================================
        // ISOLATION MODE (?view=program, etc.)
        // =========================================
        get_template_part('template-parts/department', 'single-cpt', [
            'department' => $department,
            'view' => $view,
            'paged' => $paged,
        ]);
        ?>

    <?php else: ?>

        <?php
        // =========================================
        // FULL DEPARTMENT LAYOUT
        // =========================================
        ?>

        <!-- Hero Cover Banner -->
        <?php if ($cover_id): ?>
            <section class="relative w-full max-w-7xl mx-auto px-6 mt-6 mb-10">
                <div
                    class="relative w-full h-64 md:h-80 rounded-3xl overflow-hidden shadow-xl shadow-gray-200/50 bg-gray-900 group">

                    <?php echo wp_get_attachment_image($cover_id, 'large', false, [
                        'class' => 'absolute inset-0 w-full h-full object-cover opacity-80 transition-transform duration-1000 group-hover:scale-105 ease-out'
                    ]); ?>

                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/30 to-transparent"></div>

                    <div class="absolute inset-0 flex flex-col justify-end p-6 md:p-10 z-10">
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white tracking-tight drop-shadow-lg">
                            <?php echo esc_html($department->name); ?>
                        </h1>

                        <?php if (!empty($department->description)): ?>
                            <p class="text-gray-200 mt-2 max-w-2xl text-base md:text-lg drop-shadow leading-relaxed line-clamp-2">
                                <?php echo esc_html(wp_strip_all_tags($department->description)); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php
        // Active filter header (only when a filter is active)
        get_template_part('template-parts/sections/active-filter-header', null, [
            'department' => $department,
        ]);
        ?>

        <?php
        // Flexible Content Sections
        if (have_rows('department_sections', $department)):

            while (have_rows('department_sections', $department)):
                the_row();
                $layout = get_row_layout();
                get_template_part('template-parts/sections/' . $layout, null, [
                    'department' => $department,
                ]);
            endwhile;

        else:
            ?>
            <div class="max-w-7xl mx-auto px-6 py-20 text-center">
                <p class="text-xl text-gray-500 font-medium">
                    <?php esc_html_e('No sections have been configured for this department.', 'hello-elementor-child'); ?>
                </p>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</main>

<?php
get_footer();