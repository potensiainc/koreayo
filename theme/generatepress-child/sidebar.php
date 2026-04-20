<?php
/**
 * Template: Right Sidebar
 *
 * @package Koreayo
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div <?php generate_do_attr('right-sidebar'); ?>>
    <div class="inside-right-sidebar koreayo-sidebar">
        <?php
        /**
         * generate_before_right_sidebar_content hook.
         */
        do_action('generate_before_right_sidebar_content');

        if (is_active_sidebar('sidebar-1')) {
            dynamic_sidebar('sidebar-1');
        } else {
            // Default widgets if no widgets assigned
            ?>
            <!-- Search Widget -->
            <div class="widget koreayo-widget">
                <h3 class="widget-title"><?php esc_html_e('Search', 'koreayo'); ?></h3>
                <?php get_search_form(); ?>
            </div>

            <!-- Categories Widget -->
            <div class="widget koreayo-widget">
                <h3 class="widget-title"><?php esc_html_e('Categories', 'koreayo'); ?></h3>
                <ul class="category-list">
                    <?php
                    $categories = get_categories([
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 10
                    ]);

                    foreach ($categories as $cat) :
                        $is_current = (is_category() && get_queried_object_id() === $cat->term_id);
                    ?>
                    <li>
                        <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" <?php echo $is_current ? 'class="current"' : ''; ?>>
                            <?php echo esc_html($cat->name); ?>
                            <span class="cat-count"><?php echo $cat->count; ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Popular Posts Widget -->
            <div class="widget koreayo-widget">
                <h3 class="widget-title"><?php esc_html_e('Popular Guides', 'koreayo'); ?></h3>
                <ul class="popular-posts-list">
                    <?php
                    $popular_posts = new WP_Query([
                        'posts_per_page' => 5,
                        'orderby' => 'comment_count',
                        'order' => 'DESC',
                        'ignore_sticky_posts' => true
                    ]);

                    if ($popular_posts->have_posts()) :
                        while ($popular_posts->have_posts()) : $popular_posts->the_post();
                    ?>
                    <li class="popular-post-item">
                        <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" class="popular-post-thumb">
                            <?php the_post_thumbnail('thumbnail'); ?>
                        </a>
                        <?php endif; ?>
                        <div class="popular-post-content">
                            <a href="<?php the_permalink(); ?>" class="popular-post-title">
                                <?php the_title(); ?>
                            </a>
                            <span class="popular-post-date"><?php echo get_the_date(); ?></span>
                        </div>
                    </li>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </ul>
            </div>

            <!-- Newsletter Widget -->
            <div class="widget koreayo-widget koreayo-newsletter-widget">
                <h3 class="widget-title"><?php esc_html_e('Stay Updated', 'koreayo'); ?></h3>
                <p class="newsletter-description">Get the latest Korea guides delivered to your inbox.</p>
                <form class="newsletter-form" action="#" method="post">
                    <input type="email" class="newsletter-input" placeholder="<?php esc_attr_e('Your email address', 'koreayo'); ?>" required>
                    <button type="submit" class="newsletter-button"><?php esc_html_e('Subscribe', 'koreayo'); ?></button>
                </form>
            </div>
            <?php
        }

        /**
         * generate_after_right_sidebar_content hook.
         */
        do_action('generate_after_right_sidebar_content');
        ?>
    </div>
</div>

<style>
/* Sidebar */
.koreayo-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Sidebar Widget */
.koreayo-widget {
    padding: 1.5rem;
    background: var(--koreayo-white, #fff);
    border: 1px solid var(--koreayo-gray-200, #e5e7eb);
    border-radius: 0.75rem;
}

.koreayo-widget .widget-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--koreayo-gray-900, #111827);
    margin: 0 0 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--koreayo-primary, #2563EB);
}

/* Category List */
.category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-list li {
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--koreayo-gray-100, #f3f4f6);
}

.category-list li:last-child {
    border-bottom: none;
}

.category-list a {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--koreayo-gray-700, #374151);
    text-decoration: none;
    transition: color 0.15s ease;
}

.category-list a:hover,
.category-list a.current {
    color: var(--koreayo-primary, #2563EB);
}

.cat-count {
    font-size: 0.75rem;
    padding: 0.125rem 0.5rem;
    background: var(--koreayo-gray-100, #f3f4f6);
    color: var(--koreayo-gray-500, #6b7280);
    border-radius: 9999px;
}

/* Popular Posts */
.popular-posts-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.popular-post-item {
    display: flex;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--koreayo-gray-100, #f3f4f6);
}

.popular-post-item:first-child {
    padding-top: 0;
}

.popular-post-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.popular-post-thumb {
    flex-shrink: 0;
    width: 60px;
    height: 60px;
    border-radius: 0.375rem;
    overflow: hidden;
}

.popular-post-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.popular-post-content {
    flex: 1;
    min-width: 0;
}

.popular-post-title {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--koreayo-gray-800, #1f2937);
    text-decoration: none;
    line-height: 1.4;
    margin-bottom: 0.25rem;
    transition: color 0.15s ease;
}

.popular-post-title:hover {
    color: var(--koreayo-primary, #2563EB);
}

.popular-post-date {
    font-size: 0.75rem;
    color: var(--koreayo-gray-500, #6b7280);
}

/* Newsletter Widget */
.koreayo-newsletter-widget {
    background: linear-gradient(135deg, var(--koreayo-primary, #2563EB) 0%, var(--koreayo-primary-dark, #1e40af) 100%);
    color: #fff;
    border: none;
}

.koreayo-newsletter-widget .widget-title {
    color: #fff;
    border-bottom-color: rgba(255, 255, 255, 0.3);
}

.newsletter-description {
    font-size: 0.875rem;
    opacity: 0.9;
    margin: 0 0 1rem;
}

.newsletter-form {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.newsletter-input {
    padding: 0.75rem;
    border: none;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    background: #fff;
    color: var(--koreayo-gray-800, #1f2937);
}

.newsletter-input::placeholder {
    color: var(--koreayo-gray-400, #9ca3af);
}

.newsletter-button {
    padding: 0.75rem;
    background: #fff;
    color: var(--koreayo-primary, #2563EB);
    border: none;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
}

.newsletter-button:hover {
    background: var(--koreayo-gray-100, #f3f4f6);
}

/* Sticky Sidebar for Single Posts */
.single .koreayo-sidebar {
    position: sticky;
    top: 100px;
}
</style>
