<?php
/**
 * Template Name: Home
 * Template Post Type: page
 *
 * Homepage using the Department Layout Engine.
 */

get_header();

$page_id = get_the_ID();

// Cover image
$cover_data = get_field('department_cover', $page_id);
$cover_id = 0;

if (is_array($cover_data) && !empty($cover_data['ID'])) {
    $cover_id = (int) $cover_data['ID'];
} elseif (is_numeric($cover_data)) {
    $cover_id = (int) $cover_data;
}
?>

<main class="homepage-main bg-white pb-24">

    <?php if ($cover_id): ?>
        <section class="relative w-full max-w-7xl mx-auto px-6 mt-6 mb-10">
            <div
                class="relative w-full h-64 md:h-80 rounded-3xl overflow-hidden shadow-xl shadow-gray-200/50 bg-gray-900 group">
                <?php echo wp_get_attachment_image($cover_id, 'large', false, [
                    'class' => 'absolute inset-0 w-full h-full object-cover opacity-85 transition-transform duration-1000 group-hover:scale-105 ease-out',
                ]); ?>
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/30 to-transparent"></div>
                <div class="absolute inset-0 flex flex-col justify-end p-6 md:p-10 z-10">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white tracking-tight drop-shadow-lg">
                        <?php the_title(); ?>
                    </h1>
                    <?php if (has_excerpt()): ?>
                        <p class="text-gray-200 mt-2 max-w-2xl text-base md:text-lg drop-shadow leading-relaxed line-clamp-2">
                            <?php echo esc_html(get_the_excerpt()); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    $sections = get_field('department_sections', $page_id);

    if (!empty($sections)):
        foreach ($sections as $section):
            $layout = $section['acf_fc_layout'] ?? '';

            if (!$layout) {
                continue;
            }

            get_template_part(
                'template-parts/sections/' . $layout,
                null,
                [
                    'department' => null,
                    'section' => $section,
                ]
            );
        endforeach;
    else:
        ?>
        <div class="max-w-7xl mx-auto px-6 py-20 text-center">
            <p class="text-xl text-gray-500 font-medium">
                <?php esc_html_e('No sections have been configured for the homepage yet.', 'hello-elementor-child'); ?>
            </p>
        </div>
    <?php endif; ?>

</main>

<?php
get_footer();