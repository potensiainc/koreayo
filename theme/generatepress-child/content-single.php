<?php
/**
 * Template: Single Post Content
 *
 * @package Koreayo
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php generate_do_microdata('article'); ?>>
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
            // Category Badge
            $categories = get_the_category();
            if (!empty($categories)) :
            ?>
            <div class="entry-category">
                <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="category-badge">
                    <?php echo esc_html($categories[0]->name); ?>
                </a>
            </div>
            <?php endif; ?>

            <?php
            do_action('generate_before_entry_title');

            if (generate_show_title()) {
                $params = generate_get_the_title_parameters();
                the_title($params['before'], $params['after']);
            }
            ?>

            <div class="entry-meta-wrapper">
                <?php
                do_action('generate_after_entry_title');
                ?>
                <span class="reading-time-meta">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <?php echo koreayo_reading_time(); ?> min read
                </span>
            </div>
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

        <div class="entry-content"<?php echo $itemprop; ?>>
            <?php
            the_content();

            wp_link_pages([
                'before' => '<div class="page-links">' . __('Pages:', 'koreayo'),
                'after'  => '</div>',
            ]);
            ?>
        </div>

        <?php
        // Tags
        $tags = get_the_tags();
        if ($tags) :
        ?>
        <div class="entry-tags">
            <span class="tags-label">Tags:</span>
            <?php foreach ($tags as $tag) : ?>
            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag-link">
                <?php echo esc_html($tag->name); ?>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php
        // Author Box
        $author_id = get_the_author_meta('ID');
        $author_bio = get_the_author_meta('description');
        if ($author_bio) :
        ?>
        <div class="author-box">
            <div class="author-avatar">
                <?php echo get_avatar($author_id, 80); ?>
            </div>
            <div class="author-info">
                <span class="author-label">Written by</span>
                <h4 class="author-name"><?php the_author(); ?></h4>
                <p class="author-bio"><?php echo esc_html($author_bio); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <?php
        /**
         * generate_after_entry_content hook.
         */
        do_action('generate_after_entry_content');

        /**
         * generate_after_content hook.
         */
        do_action('generate_after_content');
        ?>
    </div>
</article>

<?php
// Related Posts
$categories = get_the_category();
if (!empty($categories)) :
    $related_posts = new WP_Query([
        'category__in' => wp_list_pluck($categories, 'term_id'),
        'post__not_in' => [get_the_ID()],
        'posts_per_page' => 3,
        'ignore_sticky_posts' => true
    ]);

    if ($related_posts->have_posts()) :
?>
<div class="related-posts-section">
    <h3 class="related-posts-title">Related Guides</h3>
    <div class="related-posts-grid">
        <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
        <article class="related-post-card">
            <?php if (has_post_thumbnail()) : ?>
            <div class="related-post-image">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('koreayo-thumbnail'); ?>
                </a>
            </div>
            <?php endif; ?>
            <div class="related-post-content">
                <h4 class="related-post-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h4>
                <span class="related-post-date"><?php echo get_the_date(); ?></span>
            </div>
        </article>
        <?php endwhile; ?>
    </div>
</div>
<?php
    wp_reset_postdata();
    endif;
endif;
?>

<style>
/* Entry Category Badge */
.entry-category {
    margin-bottom: 1rem;
}

.category-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: var(--koreayo-primary, #2563EB);
    color: #fff !important;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    border-radius: 0.25rem;
    text-decoration: none;
    transition: background 0.15s ease;
}

.category-badge:hover {
    background: var(--koreayo-primary-dark, #1e40af);
}

/* Entry Meta */
.entry-meta-wrapper {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--koreayo-gray-500, #6b7280);
}

.reading-time-meta {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
}

/* Entry Tags */
.entry-tags {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--koreayo-gray-200, #e5e7eb);
}

.tags-label {
    font-weight: 500;
    color: var(--koreayo-gray-600, #4b5563);
}

.tag-link {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: var(--koreayo-gray-100, #f3f4f6);
    color: var(--koreayo-gray-700, #374151);
    font-size: 0.8125rem;
    border-radius: 9999px;
    text-decoration: none;
    transition: all 0.15s ease;
}

.tag-link:hover {
    background: var(--koreayo-primary, #2563EB);
    color: #fff;
}

/* Author Box */
.author-box {
    display: flex;
    gap: 1.25rem;
    margin-top: 2.5rem;
    padding: 1.5rem;
    background: var(--koreayo-gray-50, #f9fafb);
    border-radius: 0.75rem;
}

.author-avatar img {
    width: 80px;
    height: 80px;
    border-radius: 9999px;
    object-fit: cover;
}

.author-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--koreayo-gray-500, #6b7280);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.author-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--koreayo-gray-900, #111827);
    margin: 0.25rem 0 0.5rem;
}

.author-bio {
    font-size: 0.875rem;
    color: var(--koreayo-gray-600, #4b5563);
    line-height: 1.6;
    margin: 0;
}

/* Related Posts */
.related-posts-section {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid var(--koreayo-gray-200, #e5e7eb);
}

.related-posts-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--koreayo-gray-900, #111827);
    margin-bottom: 1.5rem;
}

.related-posts-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.related-post-card {
    background: var(--koreayo-white, #fff);
    border: 1px solid var(--koreayo-gray-200, #e5e7eb);
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.2s ease;
}

.related-post-card:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.related-post-image {
    height: 140px;
    overflow: hidden;
}

.related-post-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.related-post-card:hover .related-post-image img {
    transform: scale(1.05);
}

.related-post-content {
    padding: 1rem;
}

.related-post-title {
    font-size: 0.9375rem;
    font-weight: 600;
    line-height: 1.4;
    margin: 0 0 0.5rem;
}

.related-post-title a {
    color: var(--koreayo-gray-900, #111827);
    text-decoration: none;
}

.related-post-title a:hover {
    color: var(--koreayo-primary, #2563EB);
}

.related-post-date {
    font-size: 0.75rem;
    color: var(--koreayo-gray-500, #6b7280);
}

@media (max-width: 768px) {
    .author-box {
        flex-direction: column;
        text-align: center;
    }

    .author-avatar {
        display: flex;
        justify-content: center;
    }

    .related-posts-grid {
        grid-template-columns: 1fr;
    }
}
</style>
