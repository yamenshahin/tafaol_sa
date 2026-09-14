<?php
/**
 * Flexible Content: Custom Ad Code Section
 */
$department = $args['department'] ?? null;

$ad_code = get_sub_field('ad_code');


?>

<section class="py-8 border-b border-gray-100 last:border-0 my-4">
    <div class="max-w-7xl mx-auto px-6 flex justify-center">
        <div class="w-full max-w-4xl flex justify-center items-center">
            <div class="ad-embed-wrapper text-center w-full overflow-hidden">
                <?php
                // Unfiltered output so <script>, <ins>, and <iframe> ad tags execute
                echo $ad_code;
                ?>
            </div>
        </div>
    </div>
</section>