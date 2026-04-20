<?php
/**
 * Template: Page Content
 *
 * @package Koreayo
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('koreayo-page'); ?> <?php generate_do_microdata('article'); ?>>
    <div class="inside-article">
        <?php
        /**
         * generate_before_content hook.
         */
        do_action('generate_before_content');

        if (generate_show_entry_header()) :
        ?>

        <header <?php generate_do_attr('entry-header'); ?>>
            <?php
            /**
             * generate_before_page_title hook.
             */
            do_action('generate_before_page_title');

            if (generate_show_title()) {
                $params = generate_get_the_title_parameters();
                the_title($params['before'], $params['after']);
            }

            /**
             * generate_after_page_title hook.
             */
            do_action('generate_after_page_title');
            ?>
        </header>

        <?php
        endif;

        /**
         * generate_after_entry_header hook.
         */
        do_action('generate_after_entry_header');

        $itemprop = '';
        if ('microdata' === generate_get_schema_type()) {
            $itemprop = ' itemprop="text"';
        }
        ?>

        <div class="entry-content page-content"<?php echo $itemprop; ?>>
            <?php
            the_content();

            wp_link_pages([
                'before' => '<div class="page-links"><span class="page-links-label">' . __('Pages:', 'koreayo') . '</span>',
                'after'  => '</div>',
            ]);
            ?>
        </div>

        <?php
        /**
         * generate_after_content hook.
         */
        do_action('generate_after_content');
        ?>
    </div>
</article>

<style>
/* Page Styling */
.koreayo-page .inside-article {
    padding: 2.5rem;
}

.koreayo-page .entry-header {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--koreayo-gray-200, #e5e7eb);
}

.koreayo-page .entry-title {
    font-size: 2.25rem;
    font-weight: 700;
    color: var(--koreayo-gray-900, #111827);
    margin: 0;
}

/* Page Content */
.koreayo-page .page-content {
    font-size: 1.0625rem;
    line-height: 1.85;
    color: var(--koreayo-gray-700, #374151);
}

.koreayo-page .page-content h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--koreayo-gray-900, #111827);
    margin-top: 2.5rem;
    margin-bottom: 0.75rem;
    padding-left: 1rem;
    border-left: 4px solid var(--koreayo-primary, #2563EB);
}

.koreayo-page .page-content h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--koreayo-gray-800, #1f2937);
    margin-top: 2rem;
    margin-bottom: 0.5rem;
}

.koreayo-page .page-content p {
    margin-bottom: 1.5rem;
}

.koreayo-page .page-content a {
    color: var(--koreayo-primary, #2563EB);
    text-decoration: underline;
    text-underline-offset: 2px;
}

.koreayo-page .page-content a:hover {
    color: var(--koreayo-primary-dark, #1e40af);
}

/* Page Links (multi-page content) */
.page-links {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--koreayo-gray-200, #e5e7eb);
}

.page-links-label {
    font-weight: 500;
    color: var(--koreayo-gray-600, #4b5563);
}

.page-links a,
.page-links > span:not(.page-links-label) {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.page-links a {
    background: var(--koreayo-white, #fff);
    border: 1px solid var(--koreayo-gray-200, #e5e7eb);
    color: var(--koreayo-gray-700, #374151);
    text-decoration: none;
    transition: all 0.15s ease;
}

.page-links a:hover {
    background: var(--koreayo-primary, #2563EB);
    border-color: var(--koreayo-primary, #2563EB);
    color: #fff;
}

.page-links > span:not(.page-links-label) {
    background: var(--koreayo-primary, #2563EB);
    color: #fff;
}

/* Responsive */
@media (max-width: 768px) {
    .koreayo-page .inside-article {
        padding: 1.5rem;
    }

    .koreayo-page .entry-title {
        font-size: 1.75rem;
    }

    .koreayo-page .page-content {
        font-size: 1rem;
    }
}
</style>
