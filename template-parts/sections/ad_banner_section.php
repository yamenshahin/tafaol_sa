<?php
/**
 * Flexible Content: Ad Banner Section
 * Department → get_sub_field()
 * Homepage  → $args['section']
 */

$department = $args['department'] ?? null;
$section = $args['section'] ?? [];

if ($department instanceof WP_Term) {
    // Department page – keep existing working method
    $ad_image = get_sub_field('ad_image');
    $ad_link = get_sub_field('ad_link');
} else {
    // Homepage – use data passed from parent
    $ad_image = $section['ad_image'] ?? null;
    $ad_link = $section['ad_link'] ?? null;
}

$image_id = 0;

if (is_array($ad_image) && !empty($ad_image['ID'])) {
    $image_id = (int) $ad_image['ID'];
} elseif (is_numeric($ad_image)) {
    $image_id = (int) $ad_image;
}

if (!$image_id) {
    return;
}
?>

<section class="promo-banner-section py-8 border-b border-gray-100 last:border-0 my-4">
    <div class="max-w-7xl mx-auto px-6 flex justify-center">
        <div
            class="w-full max-w-4xl overflow-hidden rounded-2xl bg-gray-50 border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md">

            <?php if (!empty($ad_link)): ?>
                <a href="<?php echo esc_url($ad_link); ?>" target="_blank" rel="noopener noreferrer nofollow"
                    class="block group">
                    <?php echo wp_get_attachment_image($image_id, 'full', false, [
                        'class' => 'w-full h-auto object-cover max-h-48 transition-transform duration-500 group-hover:scale-[1.01]',
                    ]); ?>
                </a>
            <?php else: ?>
                <div class="block">
                    <?php echo wp_get_attachment_image($image_id, 'full', false, [
                        'class' => 'w-full h-auto object-cover max-h-48',
                    ]); ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>