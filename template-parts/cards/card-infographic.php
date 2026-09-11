<?php
/**
 * Template Part: Minimal Infographic Card
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('minimal-cpt-card infographic-card'); ?>
    style="border: 1px solid #ddd; padding: 1rem; border-radius: 8px; border-top: 4px solid #e74c3c;">

    <?php if (has_post_thumbnail()): ?>
        <a href="<?php the_permalink(); ?>" style="display: block; margin-bottom: 1rem;">
            <?php the_post_thumbnail('medium', ['style' => 'width: 100%; height: auto; border-radius: 4px;']); ?>
        </a>
    <?php endif; ?>

    <h3 style="margin: 0 0 0.5rem 0; font-size: 1.2rem;">
        <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: #e74c3c;">
            <?php the_title(); ?>
        </a>
    </h3>

    <div class="card-excerpt" style="font-size: 0.9rem; color: #555;">
        <?php the_excerpt(); ?>
    </div>

</article>