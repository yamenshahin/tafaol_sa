<?php
/**
 * Front Page Template
 * Reuses the same Flexible Content field as Departments
 */

get_header();
?>

<main class="homepage-main">

    <?php
    $sections = get_field('department_sections'); // Works on the front page too
    
    if ($sections):
        foreach ($sections as $section):
            get_template_part(
                'template-parts/sections/' . $section['acf_fc_layout'],
                null,
                [
                    'section' => $section,
                    'department' => null, // Important: no department on homepage
                ]
            );
        endforeach;
    else:
        echo '<p>' . esc_html__('No sections configured for the homepage.', 'your-textdomain') . '</p>';
    endif;
    ?>

</main>

<?php
get_footer();