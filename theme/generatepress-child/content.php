<?php
/**
 * Template: Post Content in Archive/Index
 *
 * @package Koreayo
 * @version 1.0.1
 */

if (!defined('ABSPATH')) {
    exit;
}

$has_thumbnail = has_post_thumbnail();
$categories = get_the_category();
$category_name = !empty($categories) ? $categories[0]->name : '';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('koreayo-article-card' . ($has_thumbnail ? ' has-thumbnail' : ' no-thumbnail')); ?> <?php generate_do_microdata('article'); ?>>
    <div class="inside-article">
        <?php do_action('generate_before_content'); ?>

        <?php if ($has_thumbnail) : ?>
        <!-- Card with Image -->
        <div class="article-card-image">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('koreayo-featured'); ?>
            </a>
            <?php if ($category_name) : ?>
            <span class="article-card-category"><?php echo esc_html($category_name); ?></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="article-card-content">
            <?php if (!$has_thumbnail && $category_name) : ?>
            <!-- Category badge for no-image cards -->
            <span class="article-card-category-inline"><?php echo esc_html($category_name); ?></span>
            <?php endif; ?>

            <?php if (generate_show_entry_header()) : ?>
            <header <?php generate_do_attr('entry-header'); ?>>
                <?php
                do_action('generate_before_entry_title');

                if (generate_show_title()) {
                    $params = generate_get_the_title_parameters();
                    the_title($params['before'], $params['after']);
                }
                ?>
            </header>
            <?php endif; ?>

            <?php
            $itemprop = '';
            if ('microdata' === generate_get_schema_type()) {
                $itemprop = ' itemprop="text"';
            }

            if (generate_show_excerpt()) :
            ?>
            <div class="entry-summary"<?php echo $itemprop; ?>>
                <?php the_excerpt(); ?>
            </div>
            <?php endif; ?>

            <div class="article-card-footer">
                <div class="article-card-meta">
                    <span class="article-card-date">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <?php echo get_the_date(); ?>
                    </span>
                    <span class="article-card-reading-time">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <?php echo koreayo_reading_time(); ?> min
                    </span>
                </div>
                <a href="<?php the_permalink(); ?>" class="read-more-link">
                    Read More
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
        </div>

        <?php
        do_action('generate_after_entry_content');
        do_action('generate_after_content');
        ?>
    </div>
</article>

<style>
/* Article Card Base */
.koreayo-article-card {
    background: var(--koreayo-white, #fff);
    border: 1px solid var(--koreayo-gray-200, #e5e7eb);
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.2s ease;
}

.koreayo-article-card:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    border-color: var(--koreayo-gray-300, #d1d5db);
    transform: translateY(-2px);
}

.koreayo-article-card .inside-article {
    padding: 0;
    border: none;
}

/* Card with Image */
.article-card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.article-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.koreayo-article-card:hover .article-card-image img {
    transform: scale(1.05);
}

.article-card-category {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    padding: 0.25rem 0.625rem;
    background: var(--koreayo-primary, #2563EB);
    color: #fff;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    border-radius: 0.25rem;
}

/* Card without Image - Clean Design */
.koreayo-article-card.no-thumbnail {
    border-left: 4px solid var(--koreayo-primary, #2563EB);
}

.koreayo-article-card.no-thumbnail .article-card-content {
    padding: 1.5rem;
}

.article-card-category-inline {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    background: var(--koreayo-primary, #2563EB);
    color: #fff;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    border-radius: 0.25rem;
    margin-bottom: 0.75rem;
}

/* Article Card Content */
.article-card-content {
    padding: 1.25rem;
}

.article-card-content .entry-header {
    margin-bottom: 0.5rem;
}

.article-card-content .entry-title {
    font-size: 1.125rem;
    font-weight: 600;
    line-height: 1.4;
    margin: 0;
}

.article-card-content .entry-title a {
    color: var(--koreayo-gray-900, #111827);
    text-decoration: none;
    transition: color 0.15s ease;
}

.article-card-content .entry-title a:hover {
    color: var(--koreayo-primary, #2563EB);
}

/* Summary/Excerpt */
.article-card-content .entry-summary {
    font-size: 0.875rem;
    color: var(--koreayo-gray-600, #4b5563);
    line-height: 1.6;
    margin-bottom: 1rem;
}

.article-card-content .entry-summary p {
    margin: 0;
}

/* Article Card Footer */
.article-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 1rem;
    border-top: 1px solid var(--koreayo-gray-100, #f3f4f6);
}

.article-card-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.75rem;
    color: var(--koreayo-gray-500, #6b7280);
}

.article-card-date,
.article-card-reading-time {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.read-more-link {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--koreayo-primary, #2563EB);
    text-decoration: none;
    transition: gap 0.15s ease;
}

.read-more-link:hover {
    gap: 0.625rem;
}

/* Hide default GeneratePress elements */
.koreayo-article-card .entry-meta,
.koreayo-article-card .post-image {
    display: none !important;
}

/* Responsive */
@media (max-width: 768px) {
    .article-card-image {
        height: 180px;
    }

    .article-card-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
}
</style>
