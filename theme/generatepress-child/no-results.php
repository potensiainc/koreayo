<?php
/**
 * Template: No Results Found
 *
 * @package Koreayo
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="no-results not-found koreayo-no-results">
    <div class="inside-article">
        <?php do_action('generate_before_content'); ?>

        <div class="no-results-content">
            <div class="no-results-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>

            <header <?php generate_do_attr('entry-header'); ?>>
                <h1 class="entry-title"><?php esc_html_e('Nothing Found', 'koreayo'); ?></h1>
            </header>

            <?php do_action('generate_after_entry_header'); ?>

            <div class="entry-content">
                <?php if (is_home() && current_user_can('publish_posts')) : ?>

                <p><?php
                    printf(
                        wp_kses(
                            __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'koreayo'),
                            ['a' => ['href' => []]]
                        ),
                        esc_url(admin_url('post-new.php'))
                    );
                ?></p>

                <?php elseif (is_search()) : ?>

                <p class="no-results-message">
                    <?php esc_html_e('Sorry, nothing matched your search terms. Please try again with different keywords.', 'koreayo'); ?>
                </p>

                <div class="no-results-search">
                    <?php get_search_form(); ?>
                </div>

                <?php
                // Search suggestions
                $popular_cats = get_categories([
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 6
                ]);

                if (!empty($popular_cats)) :
                ?>
                <div class="search-suggestions">
                    <h3>Popular Categories</h3>
                    <div class="suggestion-tags">
                        <?php foreach ($popular_cats as $cat) : ?>
                        <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="suggestion-tag">
                            <?php echo esc_html($cat->name); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php else : ?>

                <p class="no-results-message">
                    <?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'koreayo'); ?>
                </p>

                <div class="no-results-search">
                    <?php get_search_form(); ?>
                </div>

                <?php endif; ?>

                <div class="no-results-actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="koreayo-btn koreayo-btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>

        <?php do_action('generate_after_content'); ?>
    </div>
</div>

<style>
.koreayo-no-results {
    text-align: center;
    padding: 4rem 2rem;
}

.koreayo-no-results .inside-article {
    max-width: 600px;
    margin: 0 auto;
}

.no-results-icon {
    color: var(--koreayo-gray-300, #d1d5db);
    margin-bottom: 1.5rem;
}

.no-results-content .entry-title {
    font-size: 1.75rem;
    color: var(--koreayo-gray-900, #111827);
    margin-bottom: 1rem;
}

.no-results-message {
    font-size: 1.0625rem;
    color: var(--koreayo-gray-600, #4b5563);
    margin-bottom: 2rem;
}

.no-results-search {
    max-width: 400px;
    margin: 0 auto 2rem;
}

.search-suggestions {
    margin-bottom: 2rem;
}

.search-suggestions h3 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--koreayo-gray-700, #374151);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1rem;
}

.suggestion-tags {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem;
}

.suggestion-tag {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: var(--koreayo-gray-100, #f3f4f6);
    color: var(--koreayo-gray-700, #374151);
    font-size: 0.875rem;
    border-radius: 9999px;
    text-decoration: none;
    transition: all 0.15s ease;
}

.suggestion-tag:hover {
    background: var(--koreayo-primary, #2563EB);
    color: #fff;
}

.no-results-actions {
    margin-top: 1.5rem;
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
</style>
