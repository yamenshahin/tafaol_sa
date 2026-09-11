<?php
/**
 * Template Part: Image-Only Interview Card (16:9)
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('group relative bg-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-gray-300/50 hover:-translate-y-1 transition-all duration-500'); ?>>

    <a href="<?php the_permalink(); ?>" class="block w-full aspect-[16/9] bg-gray-100">
        <?php if (has_post_thumbnail()): ?>
            <?php the_post_thumbnail('large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out']); ?>
        <?php else: ?>
            <div class="w-full h-full flex items-center justify-center">
                <span class="text-gray-400 text-sm font-medium tracking-wide uppercase">
                    <?php esc_html_e('No Image', 'hello-elementor-child'); ?>
                </span>
            </div>
        <?php endif; ?>
    </a>

</article>