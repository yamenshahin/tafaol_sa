<?php
/**
 * Flexible Content: Custom Ad Code Section
 * Department → get_sub_field()
 * Homepage  → $args['section']
 */

$department = $args['department'] ?? null;
$section = $args['section'] ?? [];

if ($department instanceof WP_Term) {
    $ad_code = get_sub_field('ad_code', false);
} else {
    $ad_code = $section['ad_code'] ?? '';
}

if (empty($ad_code)) {
    return;
}
?>

<section class="promo-code-section py-8 border-b border-gray-100 last:border-0 my-4">
    <div class="max-w-7xl mx-auto px-6 flex justify-center">
        <div class="w-full max-w-4xl flex justify-center items-center">
            <div class="promo-embed-wrapper text-center w-full overflow-hidden">
                <?php echo $ad_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </div>
    </div>
</section>