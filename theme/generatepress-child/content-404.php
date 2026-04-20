<?php
/**
 * Template: 404 Error Page Content
 *
 * @package Koreayo
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="inside-article koreayo-404">
    <?php do_action('generate_before_content'); ?>

    <div class="error-404-content">
        <!-- Visual Element -->
        <div class="error-404-visual">
            <div class="error-404-number">404</div>
            <div class="error-404-icon">
                <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 8v4"></path>
                    <path d="M12 16h.01"></path>
                </svg>
            </div>
        </div>

        <header <?php generate_do_attr('entry-header'); ?>>
            <h1 class="entry-title" itemprop="headline">
                <?php echo apply_filters('generate_404_title', __('Page Not Found', 'koreayo')); ?>
            </h1>
        </header>

        <?php do_action('generate_after_entry_header'); ?>

        <div class="entry-content" itemprop="text">
            <p class="error-404-description">
                <?php echo apply_filters('generate_404_text', __('The page you\'re looking for doesn\'t exist or has been moved. Try searching for what you need below.', 'koreayo')); ?>
            </p>

            <div class="error-404-search">
                <?php get_search_form(); ?>
            </div>

            <div class="error-404-actions">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="koreayo-btn koreayo-btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Back to Home
                </a>
                <a href="javascript:history.back()" class="koreayo-btn koreayo-btn-outline">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Go Back
                </a>
            </div>

            <?php
            // Popular Posts
            $popular_posts = new WP_Query([
                'posts_per_page' => 4,
                'orderby' => 'comment_count',
                'order' => 'DESC',
                'ignore_sticky_posts' => true
            ]);

            if ($popular_posts->have_posts()) :
            ?>
            <div class="error-404-suggestions">
                <h3>Popular Guides</h3>
                <ul class="suggestion-list">
                    <?php while ($popular_posts->have_posts()) : $popular_posts->the_post(); ?>
                    <li>
                        <a href="<?php the_permalink(); ?>">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            <?php the_title(); ?>
                        </a>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <?php
            wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>

    <?php do_action('generate_after_content'); ?>
</div>

<style>
.koreayo-404 {
    text-align: center;
    padding: 4rem 2rem;
    max-width: 600px;
    margin: 0 auto;
}

.error-404-visual {
    position: relative;
    margin-bottom: 2rem;
}

.error-404-number {
    font-size: 8rem;
    font-weight: 800;
    color: var(--koreayo-primary, #2563EB);
    opacity: 0.1;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    line-height: 1;
}

.error-404-icon {
    position: relative;
    color: var(--koreayo-gray-300, #d1d5db);
}

.error-404-content .entry-title {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.error-404-description {
    font-size: 1.125rem;
    color: var(--koreayo-gray-600, #4b5563);
    margin-bottom: 2rem;
}

.error-404-search {
    max-width: 400px;
    margin: 0 auto 2rem;
}

.error-404-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.koreayo-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.15s ease;
}

.koreayo-btn-primary {
    background: var(--koreayo-primary, #2563EB);
    color: #fff;
}

.koreayo-btn-primary:hover {
    background: var(--koreayo-primary-dark, #1e40af);
    color: #fff;
}

.koreayo-btn-outline {
    background: transparent;
    border: 2px solid var(--koreayo-gray-300, #d1d5db);
    color: var(--koreayo-gray-700, #374151);
}

.koreayo-btn-outline:hover {
    border-color: var(--koreayo-primary, #2563EB);
    color: var(--koreayo-primary, #2563EB);
}

.error-404-suggestions {
    padding-top: 2rem;
    border-top: 1px solid var(--koreayo-gray-200, #e5e7eb);
}

.error-404-suggestions h3 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--koreayo-gray-500, #6b7280);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1rem;
}

.suggestion-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.suggestion-list li a {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    color: var(--koreayo-gray-700, #374151);
    text-decoration: none;
    border-radius: 0.5rem;
    transition: all 0.15s ease;
}

.suggestion-list li a:hover {
    background: var(--koreayo-gray-100, #f3f4f6);
    color: var(--koreayo-primary, #2563EB);
}

@media (max-width: 768px) {
    .error-404-number {
        font-size: 5rem;
    }

    .error-404-actions {
        flex-direction: column;
    }

    .koreayo-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
