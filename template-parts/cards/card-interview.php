<?php
/**
 * Template Part: Minimalist Interview Card
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('group flex flex-col bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-2xl hover:shadow-gray-200/50 transition-all duration-500'); ?>>

    <?php if (has_post_thumbnail()): ?>
        <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
            <a href="<?php the_permalink(); ?>" class="block w-full h-full">
                <?php the_post_thumbnail('large', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out']); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="p-6 flex-1 flex flex-col">
        <span class="text-xs font-semibold text-orange-500 tracking-wider uppercase mb-3">
            <?php esc_html_e('Interview', 'hello-elementor-child'); ?>
        </span>

        <h3
            class="text-xl font-bold text-gray-900 mb-3 leading-snug group-hover:text-orange-500 transition-colors duration-300">
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>

        <div class="text-sm text-gray-500 line-clamp-3 mb-6">
            <?php the_excerpt(); ?>
        </div>

        <div class="mt-auto pt-4 border-t border-gray-50">
            <a href="<?php the_permalink(); ?>"
                class="text-sm font-medium text-gray-900 flex items-center group-hover:text-orange-500 transition-colors">
                <?php esc_html_e('View Details', 'hello-elementor-child'); ?>
                <span class="mx-2 transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
            </a>
        </div>
    </div>
</article>