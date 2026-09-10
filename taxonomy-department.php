<?php
/**
 * Taxonomy Template: Department
 * Handles both the full multi-section layout and the isolated CPT view.
 */

get_header();

$term = get_queried_object();
$view = get_query_var('view');
$paged = max(1, absint(get_query_var('paged')));

// Security whitelist
$allowed_views = ['program', 'infographic', 'interview', 'post'];

echo '<main class="department-archive-main">';

if ($view && in_array($view, $allowed_views, true)) {

    // -------------------------------------------------
    // ISOLATION MODE (?view=program, etc.)
    // -------------------------------------------------
    get_template_part('template-parts/department', 'single-cpt', [
        'department' => $term,
        'view' => $view,
        'paged' => $paged,
    ]);

} else {

    // -------------------------------------------------
    // MAIN MULTI-SECTION VIEW (ACF Flexible Content)
    // -------------------------------------------------
    $sections = get_field('department_sections', $term);

    // Optional fallback to Options Page
    if (empty($sections)) {
        $sections = get_field('default_department_sections', 'option');
    }

    if ($sections) {
        foreach ($sections as $section) {
            get_template_part(
                'template-parts/sections/' . $section['acf_fc_layout'],
                null,
                [
                    'department' => $term,
                    'section' => $section,
                ]
            );
        }
    } else {
        echo '<p>' . esc_html__('No sections have been configured for this department.', 'your-textdomain') . '</p>';
    }
}

echo '</main>';

get_footer();