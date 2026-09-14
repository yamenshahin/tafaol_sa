<?php
/**
 * Flexible Content: Ad Banner Section
 */
$department = $args['department'] ?? null;

$ad_image = get_sub_field('ad_image');
$ad_link = get_sub_field('ad_link');


?>

<section class="py-8 border-b border-gray-100 last:border-0 my-4">
    <div class="max-w-7xl mx-auto px-6 flex justify-center">
        <div
            class="w-full max-w-4xl overflow-hidden rounded-2xl bg-gray-50 border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md">

            <?php if (!empty($ad_link)): ?>
                <a href="<?php echo esc_url($ad_link); ?>" target="_blank" rel="noopener noreferrer nofollow"
                    class="block group">
                <?php else: ?>
                    <div class="block">
                    <?php endif; ?>

                    <?php echo wp_get_attachment_image($ad_image['ID'], 'full', false, [
                        'class' => 'w-full h-auto object-cover max-h-48 transition-transform duration-500 group-hover:scale-[1.01]'
                    ]); ?>

                    <?php if (!empty($ad_link)): ?>
                </a>
            <?php else: ?>
            </div>
        <?php endif; ?>

    </div>
    </div>
</section>