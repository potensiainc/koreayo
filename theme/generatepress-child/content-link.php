<?php
/**
 * Template: Link Post Format Content
 *
 * @package Koreayo
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('koreayo-article-card format-link'); ?> <?php generate_do_microdata('article'); ?>>
    <div class="inside-article">
        <?php
        do_action('generate_before_content');

        if (generate_show_entry_header()) :
        ?>
        <header <?php generate_do_attr('entry-header'); ?>>
            <span class="post-format-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                </svg>
                Link
            </span>
            <?php
            do_action('generate_before_entry_title');

            if (generate_show_title()) {
                $params = generate_get_the_title_parameters();
                the_title($params['before'], $params['after']);
            }

            do_action('generate_after_entry_title');
            ?>
        </header>
        <?php
        endif;

        do_action('generate_after_entry_header');

        $itemprop = '';
        if ('microdata' === generate_get_schema_type()) {
            $itemprop = ' itemprop="text"';
        }

        if (generate_show_excerpt()) :
        ?>
        <div class="entry-summary"<?php echo $itemprop; ?>>
            <?php the_excerpt(); ?>
        </div>
        <?php else : ?>
        <div class="entry-content"<?php echo $itemprop; ?>>
            <?php
            the_content();
            wp_link_pages([
                'before' => '<div class="page-links">' . __('Pages:', 'koreayo'),
                'after'  => '</div>',
            ]);
            ?>
        </div>
        <?php endif; ?>

        <?php
        do_action('generate_after_entry_content');
        do_action('generate_after_content');
        ?>
    </div>
</article>

<style>
/* Link Post Format */
.format-link .inside-article {
    background: linear-gradient(135deg, var(--koreayo-gray-50, #f9fafb) 0%, var(--koreayo-gray-100, #f3f4f6) 100%);
    border-left: 4px solid var(--koreayo-primary, #2563EB);
}

.post-format-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.25rem 0.75rem;
    background: var(--koreayo-primary, #2563EB);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    border-radius: 9999px;
    margin-bottom: 0.75rem;
}

.format-link .entry-title a {
    color: var(--koreayo-primary, #2563EB);
}

.format-link .entry-title a:hover {
    text-decoration: underline;
}
</style>
