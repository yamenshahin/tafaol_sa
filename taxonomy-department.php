<?php
/**
 * Taxonomy Template: Department
 *
 * Handles:
 * 1. Full multi-section layout (ACF Flexible Content)
 * 2. Isolated CPT view (?view=program|infographic|interview|post)
 */

get_header();

$term = get_queried_object();
$view = get_query_var('view');
$paged = max(1, absint(get_query_var('paged')));

$allowed_views = ['program', 'infographic', 'interview', 'post'];
?>

<main class="department-archive-main">

    <?php if ($view && in_array($view, $allowed_views, true)): ?>

        <?php
        // Isolation mode
        get_template_part('template-parts/department', 'single-cpt', [
            'department' => $term,
            'view' => $view,
            'paged' => $paged,
        ]);
        ?>

    <?php else: ?>

        <?php
        // Multi-section mode (ACF Flexible Content)
        $sections = get_field('department_sections', $term);

        // Fallback to global default
        if (empty($sections)) {
            $sections = get_field('default_department_sections', 'option');
        }

        if (!empty($sections)):
            foreach ($sections as $section):
                get_template_part(
                    'template-parts/sections/' . $section['acf_fc_layout'],
                    null,
                    [
                        'department' => $term,
                        'section' => $section,
                    ]
                );
            endforeach;
        else:
            ?>
            <p><?php esc_html_e('No sections have been configured for this department.', 'your-textdomain'); ?></p>
        <?php endif; ?>

    <?php endif; ?>

</main>

<?php
get_footer();